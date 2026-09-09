<?php

namespace App\Http\Controllers;

use App\Models\Cta;
use App\Models\Lead;
use App\Models\LeadEvent;
use App\Models\PageAnalytic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        $cacheKey = $this->cacheKey('analytics.index', $startDate, $endDate);

        // ✅ No forced Cache::forget — keep caching effective
        $data = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($startDate, $endDate) {
            $totalViews = PageAnalytic::byDateRange($startDate, $endDate)->sum('views');
            $totalLeads = Lead::whereBetween('created_at', [$startDate, $endDate])->where('is_spam', false)->count();
            $convertedLeads = Lead::where('status', 'converted')->whereBetween('created_at', [$startDate, $endDate])->count();
            $totalRevenue = (float) Lead::where('status', 'converted')->whereBetween('created_at', [$startDate, $endDate])->sum('conversion_value');

            $conversionRate = $this->safeRate($totalLeads, $totalViews);
            $avgConversionRate = $conversionRate;

            $dailyAnalytics = PageAnalytic::byDateRange($startDate, $endDate)
                ->selectRaw('date, SUM(views) as total_views, SUM(leads_generated) as total_leads')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->map(function ($row) {
                    // ✅ computed conversion rate per day
                    $row->conversion_rate = $this->safeRate((int) $row->total_leads, (int) $row->total_views);
                    return $row;
                });

            $pageAnalytics = PageAnalytic::query()
                ->with(['content:id,title,type,slug'])
                ->byDateRange($startDate, $endDate)
                ->selectRaw('content_id, SUM(views) as views, SUM(leads_generated) as leads_generated')
                ->groupBy('content_id')
                ->orderByDesc('views')
                ->get()
                ->map(function ($row) {
                    // ✅ computed conversion rate per content
                    $row->conversion_rate = $this->safeRate((int) $row->leads_generated, (int) $row->views);
                    return $row;
                });

            $topContent = PageAnalytic::query()
                ->with(['content:id,title,type,slug'])
                ->byDateRange($startDate, $endDate)
                ->selectRaw('content_id, SUM(views) as total_views, SUM(leads_generated) as total_leads')
                ->groupBy('content_id')
                ->orderByDesc('total_views')
                ->take(10)
                ->get()
                ->map(function ($row) {
                    $row->conversion_rate = $this->safeRate((int) $row->total_leads, (int) $row->total_views);
                    return $row;
                });

            $leadSources = Lead::query()
                ->with(['sourceContent:id,title,type,slug'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('source_content_id')
                ->selectRaw('source_content_id, COUNT(*) as lead_count')
                ->groupBy('source_content_id')
                ->orderByDesc('lead_count')
                ->take(10)
                ->get();

            $utmSources = Lead::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('utm_source')
                ->where('utm_source', '!=', '')
                ->selectRaw('utm_source, COUNT(*) as lead_count')
                ->groupBy('utm_source')
                ->orderByDesc('lead_count')
                ->take(10)
                ->get();

            $ctaAnalytics = Cta::query()
                ->with(['content:id,title,type,slug'])
                ->select(['id', 'content_id', 'text', 'type', 'clicks', 'conversions'])
                ->orderByDesc('conversions')
                ->orderByDesc('clicks')
                ->take(10)
                ->get();

            // Content that drove converted (paying) leads — high-value insight
            $convertedBySource = \Illuminate\Support\Facades\DB::table('leads')
                ->join('contents', 'contents.id', '=', 'leads.source_content_id')
                ->where('leads.status', 'converted')
                ->whereBetween('leads.created_at', [$startDate, $endDate])
                ->whereNotNull('leads.source_content_id')
                ->selectRaw('leads.source_content_id, contents.title as content_title, contents.type as content_type, COUNT(*) as converted_count, COALESCE(SUM(leads.conversion_value), 0) as total_value')
                ->groupBy('leads.source_content_id', 'contents.title', 'contents.type')
                ->orderByDesc('total_value')
                ->orderByDesc('converted_count')
                ->limit(10)
                ->get();

            $leadEventCounts = LeadEvent::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('event, COUNT(*) as event_count')
                ->groupBy('event')
                ->orderByDesc('event_count')
                ->take(10)
                ->get();

            $leadsByInquiryType = Lead::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('is_spam', false)
                ->selectRaw("COALESCE(NULLIF(inquiry_type, ''), 'general') as inquiry_type, COUNT(*) as lead_count")
                ->groupBy('inquiry_type')
                ->orderByDesc('lead_count')
                ->get();

            return compact(
                'totalViews',
                'totalLeads',
                'convertedLeads',
                'totalRevenue',
                'conversionRate',
                'avgConversionRate',
                'dailyAnalytics',
                'pageAnalytics',
                'topContent',
                'leadSources',
                'utmSources',
                'ctaAnalytics',
                'convertedBySource',
                'leadEventCounts',
                'leadsByInquiryType'
            );
        });

        return view('admin.analytics.index', array_merge($data, [
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]));
    }

    public function content(Request $request): View
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        $cacheKey = $this->cacheKey('analytics.content', $startDate, $endDate);

        $contentAnalytics = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($startDate, $endDate) {
            // ✅ Aggregate in DB (no per-content summing loops)
            $rows = PageAnalytic::query()
                ->with(['content:id,title,type,slug'])
                ->byDateRange($startDate, $endDate)
                ->selectRaw('content_id, SUM(views) as total_views, SUM(leads_generated) as total_leads')
                ->groupBy('content_id')
                ->orderByDesc('total_views')
                ->get()
                ->map(function ($row) {
                    $row->avg_conversion_rate = $this->safeRate((int) $row->total_leads, (int) $row->total_views);
                    return $row;
                });

            return $rows;
        });

        return view('admin.analytics.content', compact('contentAnalytics', 'startDate', 'endDate'));
    }

    // -------------------------
    // Helpers (DRY)
    // -------------------------

    private function resolveDateRange(Request $request): array
    {
        $days = (int) ($request->date_range ?? 30);
        $days = max(1, min($days, 365));

        $start = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->subDays($days)->startOfDay();

        $end = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        if (!$request->start_date && !$request->end_date) {
            $start = now()->subDays($days)->startOfDay();
            $end = now()->endOfDay();
        }

        if ($end->lessThan($start)) {
            [$start, $end] = [$end, $start];
        }

        return [$start, $end];
    }

    private function cacheKey(string $prefix, Carbon $startDate, Carbon $endDate): string
    {
        return sprintf(
            '%s:%s:%s:%s',
            $prefix,
            $startDate->format('Ymd'),
            $endDate->format('Ymd'),
            \App\Services\CacheBuster::current()
        );
    }

    private function safeRate(int $numerator, int $denominator): float
    {
        if ($denominator <= 0) {
            return 0.0;
        }

        return round(($numerator / $denominator) * 100, 2);
    }
}
