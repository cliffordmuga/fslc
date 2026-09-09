<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $checks = [
            'app' => true,
            'db' => false,
            'cache' => false,
        ];

        // DB check
        try {
            DB::select('SELECT 1');
            $checks['db'] = true;
        } catch (\Throwable) {
            $checks['db'] = false;
        }

        // Cache check (you asked to keep DB-backed cache for now)
        try {
            $key = 'healthcheck:' . bin2hex(random_bytes(8));
            Cache::put($key, 'ok', 10);
            $checks['cache'] = Cache::get($key) === 'ok';
            Cache::forget($key);
        } catch (\Throwable) {
            $checks['cache'] = false;
        }

        $ok = $checks['app'] && $checks['db'] && $checks['cache'];

        return response()->json([
            'ok' => $ok,
            'checks' => $checks,
            'timestamp' => now()->toIso8601String(),
        ], $ok ? 200 : 503);
    }
}
