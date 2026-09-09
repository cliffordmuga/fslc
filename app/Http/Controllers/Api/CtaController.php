<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cta;
use App\Models\PageAnalytic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CtaController extends Controller
{
    public function click(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cta_id' => ['required', 'exists:ctas,id'],
            'url' => ['nullable', 'url'],
            'referrer' => ['nullable', 'url'],
        ]);

        try {
            $cta = Cta::with('content')->findOrFail($validated['cta_id']);

            // Only track if CTA is global (no content) or linked to published content
            if ($cta->content_id) {
                $content = $cta->content;
                if (!$content || $content->status !== 'published' || ($content->published_at && $content->published_at->isFuture())) {
                    return response()->json(['success' => false, 'message' => 'CTA not available'], 404);
                }
            }

            $cta->increment('clicks');

            // Track CTA clicks on the related content page analytics row.
            if ($cta->content_id) {
                $today = now()->toDateString();

                PageAnalytic::firstOrCreate(
                    ['content_id' => $cta->content_id, 'date' => $today],
                    ['views' => 0, 'unique_visitors' => 0, 'cta_clicks' => 0, 'leads_generated' => 0]
                )->increment('cta_clicks');
            }

            Log::info('CTA clicked', [
                'cta_id' => $cta->id,
                'content_id' => $cta->content_id,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer' => $validated['referrer'] ?? null,
                'url' => $validated['url'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Click tracked successfully',
                'clicks' => $cta->fresh()->clicks,
            ]);
        } catch (\Throwable $e) {
            Log::error('CTA tracking failed', [
                'cta_id' => $validated['cta_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to track click',
            ], 500);
        }
    }
}
