<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Lead;
use App\Models\PageAnalytic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $days = (int) ($request->date_range ?? 30);
        $days = max(1, min($days, 365)); // safety bounds

        $startDate = now()->subDays($days)->startOfDay();

        $cacheKey = "admin.dashboard:{$days}:" . \App\Services\CacheBuster::current();

        $followUpDays = (int) (\App\Models\Setting::get('lead_follow_up_days', 3));

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($startDate, $days, $followUpDays) {
            $stats = [
                'total_content'       => Content::count(),
                'published_content'   => Content::where('status', 'published')->count(),
                'total_leads'         => Lead::where('created_at', '>=', $startDate)->where('is_spam', false)->count(),
                'new_leads'           => Lead::where('status', 'new')->where('created_at', '>=', $startDate)->where('is_spam', false)->count(),
                'converted_leads'     => Lead::where('status', 'converted')->where('created_at', '>=', $startDate)->count(),
                'total_conversion_value' => (float) Lead::where('status', 'converted')
                    ->where('created_at', '>=', $startDate)
                    ->sum('conversion_value'),
                'total_users'         => User::count(),
            ];

            $recent_leads = Lead::with(['sourceContent:id,title,type,slug'])
                ->where('created_at', '>=', $startDate)
                ->where('is_spam', false)
                ->latest()
                ->take(5)
                ->get();

            $recent_content = Content::with(['creator:id,name'])
                ->where('created_at', '>=', $startDate)
                ->latest()
                ->take(5)
                ->get();

            /**
             * ✅ Top Performing Content (no N+1, no Content::find loop)
             * Use LEFT JOIN so content with analytics but no leads (or vice versa) can still appear.
             * Compute conversion_rate on the fly.
             */
            $topContent = DB::table('page_analytics as pa')
                ->leftJoin('leads as l', function ($join) use ($startDate) {
                    $join->on('l.source_content_id', '=', 'pa.content_id')
                        ->where('l.created_at', '>=', $startDate)
                        ->where('l.is_spam', false);
                })
                ->leftJoin('contents as c', 'c.id', '=', 'pa.content_id')
                ->where('pa.date', '>=', $startDate->toDateString())
                ->selectRaw('
                    pa.content_id,
                    c.title as content_title,
                    c.type  as content_type,
                    SUM(pa.views) as total_views,
                    COUNT(DISTINCT l.id) as total_leads
                ')
                ->groupBy('pa.content_id', 'c.title', 'c.type')
                ->orderByDesc('total_leads')
                ->limit(5)
                ->get()
                ->map(function ($row) {
                    $row->conversion_rate = ($row->total_views > 0)
                        ? round(($row->total_leads / $row->total_views) * 100, 2)
                        : 0.0;
                    return $row;
                });

            $leadSources = Lead::with(['sourceContent:id,title,type,slug'])
                ->where('created_at', '>=', $startDate)
                ->where('is_spam', false)
                ->whereNotNull('source_content_id')
                ->selectRaw('source_content_id, COUNT(*) as lead_count')
                ->groupBy('source_content_id')
                ->orderByDesc('lead_count')
                ->take(5)
                ->get();

            $utmSources = Lead::where('created_at', '>=', $startDate)
                ->where('is_spam', false)
                ->whereNotNull('utm_source')
                ->where('utm_source', '!=', '')
                ->selectRaw('utm_source, COUNT(*) as lead_count')
                ->groupBy('utm_source')
                ->orderByDesc('lead_count')
                ->take(5)
                ->get();

            $inquiryTypes = Lead::where('created_at', '>=', $startDate)
                ->where('is_spam', false)
                ->whereNotNull('inquiry_type')
                ->where('inquiry_type', '!=', '')
                ->selectRaw('inquiry_type, COUNT(*) as lead_count')
                ->groupBy('inquiry_type')
                ->orderByDesc('lead_count')
                ->take(8)
                ->get();

            $highPriorityLeads = Lead::with(['sourceContent:id,title,type,slug'])
                ->highIntent()
                ->byStatus('new')
                ->where('created_at', '>=', $startDate)
                ->latest()
                ->take(5)
                ->get();

            $leadsNeedingFollowUp = Lead::with(['sourceContent:id,title,type,slug'])
                ->needsFollowUp($followUpDays)
                ->latest('created_at')
                ->take(5)
                ->get();

            return compact('stats', 'recent_leads', 'recent_content', 'topContent', 'leadSources', 'utmSources', 'inquiryTypes', 'highPriorityLeads', 'leadsNeedingFollowUp', 'days');
        });

        return view('admin.dashboard', $data);
    }
}
