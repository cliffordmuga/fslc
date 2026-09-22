<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Only add headers for “normal” responses.
        // (You can remove this guard if you want them on everything.)
        if (!$response) {
            return $response;
        }

        $isHttps = $request->isSecure();

        // Core hardening headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // HSTS only when HTTPS is definitely enabled
        if ($isHttps) {
            // 6 months + includeSubDomains; add preload only when you're ready.
            $response->headers->set('Strict-Transport-Security', 'max-age=15552000; includeSubDomains');
        }

        // CSP: start in Report-Only mode by default to avoid accidental breakage.
        $csp = $this->buildCsp($request);

        $enforce = (bool) config('security.csp_enforce', false);
        $headerName = $enforce ? 'Content-Security-Policy' : 'Content-Security-Policy-Report-Only';

        // Avoid double-setting if something else already sets it.
        if (!$response->headers->has('Content-Security-Policy') && !$response->headers->has('Content-Security-Policy-Report-Only')) {
            $response->headers->set($headerName, $csp);
        }

        return $response;
    }

    private function buildCsp(Request $request): string
    {
        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "img-src 'self' data: https:",
            "font-src 'self' data: https:",
            "style-src 'self' 'unsafe-inline' https:",
            // 'unsafe-eval' required by Alpine.js (resources/js/app.js), which
            // evaluates x-data/x-on expressions via new Function() internally —
            // switching to @alpinejs/csp instead would need every Blade
            // x-data usage migrated to named Alpine.data() registrations.
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https:",
            "connect-src 'self' https:",
            "frame-src 'self' https://www.google.com https://www.google.com/maps https://maps.google.com",
            "object-src 'none'",
        ];

        // Allow custom CSP source extensions from env for shared-host flexibility.
        $extra = trim((string) config('security.csp_extra_directives', ''));
        if ($extra !== '') {
            $directives[] = $extra;
        }

        // Only upgrade HTTP->HTTPS when production + HTTPS (avoids local dev issues)
        if ($request->isSecure() && app()->environment('production')) {
            $directives[] = "upgrade-insecure-requests";
        }

        // Optional reporting (safe to enable anytime)
        if ((bool) config('security.csp_report_enabled', true)) {
            $directives[] = "report-uri " . route('csp.report');
        }

        return implode('; ', $directives);
    }
}
