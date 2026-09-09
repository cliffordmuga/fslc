<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RequireAdminTwoFactor;
use App\Http\Middleware\Verify2FA;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\HandleRedirects;
use App\Http\Middleware\ForceHttps;
use App\Http\Middleware\PublicPageCache;
use App\Http\Middleware\TrackPageViews;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // Clear-cache: no session/DB — works when migrations not run (sessions/cache tables missing)
            \Illuminate\Support\Facades\Route::get('/clear-cache', function () {
                $token = (string) request('token', '');
                $envPath = base_path('.env');
                $secret = '';
                if (file_exists($envPath) && preg_match('/^\s*CLEAR_CACHE_TOKEN\s*=\s*(.+)\s*$/m', file_get_contents($envPath), $m)) {
                    $secret = trim(trim($m[1]), "\"'");
                }
                // Refuse the well-known .env.example placeholder so a forgotten
                // rotation step never leaves this endpoint publicly guessable.
                $knownDefaults = ['change-me-to-random-string', 'change-me-in-env', ''];
                if ($token === '' || $secret === '' || in_array($secret, $knownDefaults, true) || ! hash_equals($secret, $token)) {
                    abort(404);
                }
                $ok = [];
                $failed = [];
                foreach (['route:clear', 'config:clear', 'cache:clear', 'view:clear'] as $cmd) {
                    try {
                        \Illuminate\Support\Facades\Artisan::call($cmd);
                        $ok[] = $cmd;
                    } catch (\Throwable $e) {
                        $failed[] = $cmd.': '.$e->getMessage();
                    }
                }
                $msg = 'Cleared: '.implode(', ', $ok);
                if (! empty($failed)) {
                    $msg .= ' | Failed: '.implode('; ', $failed);
                }
                return response($msg, 200);
            })->middleware('throttle:6,1')->name('clear-cache');
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | Trusted Proxies — required on shared/cloud hosting behind load balancers.
        | Without this, Laravel can't detect HTTPS, breaking secure session cookies
        | and causing a login redirect loop on production.
        |
        | Default is '*' (trust any proxy). This is correct here: on cPanel /
        | LiteSpeed the app is only reachable through the host's front end, which
        | sets X-Forwarded-* itself. The old `null` default silently disabled
        | proxy trust once `config:cache` ran (env() then returns null), causing
        | exactly the redirect loop above. Narrow it by setting TRUSTED_PROXIES
        | to a comma-separated IP/CIDR list in .env or via `SetEnv` in .htaccess.
        |--------------------------------------------------------------------------
        */
        $middleware->trustProxies(
            at: env('TRUSTED_PROXIES') ?: '*',
            headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR
                   | \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST
                   | \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT
                   | \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO
                   | \Illuminate\Http\Request::HEADER_X_FORWARDED_AWS_ELB
        );

        /*
        |--------------------------------------------------------------------------
        | Global Web Middleware
        |--------------------------------------------------------------------------
        */

        // Run redirects FIRST
        $middleware->web(prepend: [
            ForceHttps::class,
            HandleRedirects::class,
        ]);

        // Run security headers LAST
        $middleware->web(append: [
            SecurityHeaders::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | FULL PAGE CACHE (IMPORTANT — add this)
        |--------------------------------------------------------------------------
        */

        // Apply page caching globally to web routes
        $middleware->web(append: [
            PublicPageCache::class,
            TrackPageViews::class,
        ]);

        // Browser-generated reports / lightweight tracking endpoints may not carry CSRF cookies.
        $middleware->validateCsrfTokens(except: [
            'csp-report',
            'api/cta/click',
            'lead-events',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Aliases
        |--------------------------------------------------------------------------
        */

        $middleware->alias([
            '2fa' => Verify2FA::class,
            'admin.2fa' => RequireAdminTwoFactor::class,
            'role' => CheckRole::class,
            'page.cache' => PublicPageCache::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
