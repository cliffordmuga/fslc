<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CspReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();

        // Sampling: only log a % of reports (default 10%)
        $samplePct = (int) config('security.csp_report_sample_percent', 10);
        $samplePct = max(0, min(100, $samplePct));

        if ($samplePct < 100) {
            $roll = random_int(1, 100);
            if ($roll > $samplePct) {
                return response()->noContent();
            }
        }

        // De-dup identical reports for a short time window
        $hash = hash('sha256', json_encode([
            'ip' => $request->ip(),
            'ua' => (string) $request->userAgent(),
            'p'  => $payload,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $dedupeSeconds = (int) config('security.csp_report_dedupe_seconds', 120);
        $dedupeSeconds = max(10, min(3600, $dedupeSeconds));

        $key = "csp:report:{$hash}";
        if (Cache::has($key)) {
            return response()->noContent();
        }
        Cache::put($key, 1, $dedupeSeconds);

        $dayKey = 'csp:violations:' . now()->toDateString();
        Cache::put($dayKey, (int) Cache::get($dayKey, 0) + 1, now()->addDays(8));

        Log::warning('CSP Violation Report', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'payload' => $payload,
        ]);

        return response()->noContent();
    }
}
