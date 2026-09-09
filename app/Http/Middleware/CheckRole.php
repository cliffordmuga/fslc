<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request and check user role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Quick check: if user has any of the allowed roles
        if (!in_array($user->role, $roles, true)) {
            Log::warning('Unauthorized role access attempt', [
                'user_id'       => $user->id,
                'required_roles' => $roles,
                'actual_role'   => $user->role,
                'route'         => $request->route()?->getName(),
                'ip'            => $request->ip(),
            ]);

            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
