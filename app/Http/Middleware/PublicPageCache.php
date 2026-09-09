<?php

namespace App\Http\Middleware;

use App\Services\CacheBuster;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PublicPageCache
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only cache public GET/HEAD pages
        if (!in_array($request->method(), ['GET', 'HEAD'], true)) {
            return $next($request);
        }

        // Don't cache authenticated admin/editor sessions
        if ($request->user()) {
            return $next($request);
        }

        // Exclusions (admin/auth/search/contact/forms/api/health/etc)
        $path = '/' . ltrim($request->path(), '/');
        $excludedPrefixes = ['/admin', '/login', '/register', '/password', '/email', '/logout', '/api', '/health', '/up', '/telescope'];
        foreach ($excludedPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return $next($request);
            }
        }

        // Exclude known dynamic routes (allow config overrides).
        $excludedExact = ['/search', '/contact', '/leads', '/newsletter/subscribe', '/csp-report'];
        $configuredExcluded = (array) config('public_page_cache.excluded_paths', []);
        foreach ($configuredExcluded as $pattern) {
            if ($this->pathMatches($path, (string) $pattern)) {
                return $next($request);
            }
        }
        if (in_array($path, $excludedExact, true)) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        if (is_string($routeName)) {
            foreach ((array) config('public_page_cache.excluded_route_names', []) as $namePattern) {
                if ($this->routeNameMatches($routeName, (string) $namePattern)) {
                    return $next($request);
                }
            }
        }

        // If request has "nocache=1" allow bypass for debugging
        if ($request->query('nocache') === '1') {
            $response = $next($request);
            return $this->decorate($response, 'BYPASS');
        }

        $key = $this->cacheKey($request);

        $cached = Cache::get($key);
        if (is_array($cached) && isset($cached['html'], $cached['headers'])) {
            $response = new Response($cached['html'], 200, $cached['headers']);
            return $this->decorate($response, 'HIT');
        }

        /** @var Response $response */
        $response = $next($request);

        // Only cache successful HTML responses
        if (!$this->isCacheableResponse($response)) {
            return $this->decorate($response, 'SKIP');
        }

        // Store minimal headers (avoid caching Set-Cookie)
        $headersToCache = [
            'Content-Type' => $response->headers->get('Content-Type'),
        ];

        $ttl = (int) config('public_page_cache.ttl_seconds', 120);

        Cache::put($key, [
            'html' => $response->getContent(),
            'headers' => array_filter($headersToCache),
        ], $ttl);

        return $this->decorate($response, 'MISS');
    }

    private function cacheKey(Request $request): string
    {
        // Normalize URL: path + sorted allow-listed query params to prevent cache-key explosion.
        $path = '/' . ltrim($request->path(), '/');

        $allowedQuery = (array) config('public_page_cache.allowed_query_params', []);
        $query = array_intersect_key($request->query(), array_flip($allowedQuery));
        ksort($query);
        $qs = http_build_query($query);

        // Cache buster invalidates all existing page cache
        $buster = CacheBuster::current();

        // Language/locale can change HTML
        $locale = app()->getLocale();

        // Device bucket: ContentService selects different image variants for mobile vs desktop.
        // Without this slot, the first device to hit a URL "wins" and all subsequent visitors
        // (regardless of device) receive the same cached HTML with the wrong image variants.
        $device = $this->isMobileRequest($request) ? 'm' : 'd';

        return 'page:v1:' . md5($buster . '|' . $locale . '|' . $device . '|' . $path . '?' . $qs);
    }

    private function isMobileRequest(Request $request): bool
    {
        return (bool) preg_match('/mobile|android|iphone|ipad|tablet/i', $request->userAgent() ?? '');
    }

    private function pathMatches(string $path, string $pattern): bool
    {
        if ($pattern === '') {
            return false;
        }

        $pattern = '/' . ltrim($pattern, '/');
        $regex = '#^' . str_replace('\*', '.*', preg_quote($pattern, '#')) . '$#';

        return (bool) preg_match($regex, $path);
    }

    private function routeNameMatches(string $routeName, string $pattern): bool
    {
        if ($pattern === '') {
            return false;
        }

        $regex = '#^' . str_replace('\*', '.*', preg_quote($pattern, '#')) . '$#';

        return (bool) preg_match($regex, $routeName);
    }

    private function isCacheableResponse(Response $response): bool
    {
        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $ct = (string) $response->headers->get('Content-Type', '');
        if (!str_contains($ct, 'text/html')) {
            return false;
        }

        // Never cache responses setting cookies
        if ($response->headers->has('Set-Cookie')) {
            return false;
        }

        // Skip huge responses (prevents cache bloat on shared hosting)
        $maxBytes = (int) config('public_page_cache.max_bytes', 1048576);
        if ($maxBytes > 0 && strlen($response->getContent() ?: '') > $maxBytes) {
            return false;
        }

        return true;
    }

    private function decorate(Response $response, string $status): Response
    {
        $response->headers->set('X-Page-Cache', $status);

        // Optional browser/CDN hint for anonymous cached HTML (Laravel still holds the canonical cache)
        $browserMax = (int) config('public_page_cache.browser_max_age_seconds', 0);
        if ($browserMax > 0 && in_array($status, ['HIT', 'MISS'], true)) {
            $response->headers->set('Cache-Control', 'public, max-age='.$browserMax);
        }

        return $response;
    }
}
