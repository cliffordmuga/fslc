<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        // Redirects should only apply to public page loads
        if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
            return $next($request);
        }

        // Skip safely when DB is missing/unmigrated (shared hosting / local setup gaps)
        try {
            if (! Schema::hasTable('redirects')) {
                return $next($request);
            }
        } catch (\Throwable $e) {
            Log::warning('HandleRedirects skipped: database unavailable', [
                'error' => $e->getMessage(),
            ]);

            return $next($request);
        }

        $path = '/'.ltrim($request->path(), '/');
        $normalized = rtrim($path, '/');
        if ($normalized === '') {
            $normalized = '/';
        }

        // Skip obvious exclusions (avoid interfering with app/system routes)
        if (
            $normalized === '/' ||
            str_starts_with($normalized, '/admin') ||
            str_starts_with($normalized, '/api')
        ) {
            return $next($request);
        }

        $redirect = Cache::remember(
            'redirect:path:'.md5($normalized),
            now()->addHour(),
            fn () => Redirect::query()
                ->where('is_active', true)
                ->where('old_path', $normalized)
                ->first()
        );

        if (! $redirect) {
            return $next($request);
        }

        $target = $this->normalizeTarget((string) $redirect->new_path);

        // Preserve query string
        $qs = $request->getQueryString();
        if ($qs) {
            $target .= (str_contains($target, '?') ? '&' : '?').$qs;
        }

        // Prevent loops: don't redirect to same path (ignoring query string)
        if ($this->normalizePathOnly($target) === $normalized) {
            return $next($request);
        }

        // Validate status
        $status = (int) ($redirect->status_code ?? 301);
        if (! in_array($status, [301, 302, 307, 308], true)) {
            $status = 301;
        }

        // Track hits safely (never block the redirect if tracking fails)
        try {
            $redirect->increment('hits');
            if (Schema::hasColumn('redirects', 'last_hit_at')) {
                $redirect->forceFill(['last_hit_at' => now()])->saveQuietly();
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return redirect($target, $status);
    }

    private function normalizeTarget(string $target): string
    {
        $target = trim($target);

        // Allow absolute URLs (external redirects)
        if (preg_match('#^https?://#i', $target)) {
            return $target;
        }

        // Normalize relative paths
        $t = '/'.ltrim($target, '/');
        $t = rtrim($t, '/');

        return $t === '' ? '/' : $t;
    }

    private function normalizePathOnly(string $target): string
    {
        // If absolute URL, compare only its path
        if (preg_match('#^https?://#i', $target)) {
            $parts = parse_url($target);
            $p = $parts['path'] ?? '/';
            $p = '/'.ltrim($p, '/');
            $p = rtrim($p, '/');

            return $p === '' ? '/' : $p;
        }

        $p = '/'.ltrim($target, '/');
        $p = rtrim($p, '/');

        return $p === '' ? '/' : $p;
    }
}
