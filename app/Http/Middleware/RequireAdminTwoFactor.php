<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\TwoFactorService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireAdminTwoFactor
{
    public function __construct(protected TwoFactorService $twoFactorService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user instanceof User && $user->isAdmin() && ! $this->twoFactorService->has2FA($user)) {
            return redirect()->route('2fa.setup')
                ->with('warning', __('Administrators must enable two-factor authentication.'));
        }

        return $next($request);
    }
}
