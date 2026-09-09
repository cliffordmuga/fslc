<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Redirect to HTTPS in production regardless of whether the
     * server-level (.htaccess) redirect was configured. Defense-in-depth
     * for the manual public/.htaccess edit documented in DEPLOYMENT.md.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('production') && !$request->secure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
