<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Services\TwoFactorService;
use Illuminate\Support\Facades\Log;

class Verify2FA
{
    protected TwoFactorService $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Handle an incoming request and enforce 2FA verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If not authenticated → redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // Exempt routes: allow access to 2FA setup, verify, and store
        $exemptRoutes = [
            '2fa.setup',
            '2fa.verify',
            '2fa.store',
            '2fa.enable',
            '2fa.disable',
            'logout',
        ];

        if (in_array($request->route()?->getName(), $exemptRoutes, true)) {
            return $next($request);
        }

        // If 2FA is enabled but not verified in current session
        if ($this->twoFactorService->has2FA($user) && !$this->twoFactorService->isSessionVerified()) {
            Log::info('2FA verification required', [
                'user_id' => $user->id,
                'route'   => $request->route()?->getName(),
            ]);

            return redirect()->route('2fa.verify');
        }

        return $next($request);
    }
}
