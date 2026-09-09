<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * RedirectsUsers Trait
 * 
 * OPTIMIZED: Multi-role support and configuration
 * 
 * Improvements:
 * - Configurable redirect routes
 * - Supports multiple roles
 * - Query parameter preservation
 * - Fallback handling
 * - Logging for debugging
 * 
 * Usage in controllers:
 * ```php
 * use RedirectsUsers;
 * 
 * return $this->redirectAfterLogin($user);
 * // or
 * return $this->redirectAfterLogin($user, route('custom.dashboard'));
 * ```
 */
trait RedirectsUsers
{
    /**
     * Redirect user after login based on role
     * 
     * IMPROVED: Configurable, multi-role, preserves query params
     * 
     * @param User $user
     * @param string|null $default Default redirect if no role match
     * @param bool $preserveQueryParams Preserve query parameters
     * @return RedirectResponse
     */
    public function redirectAfterLogin(
        User $user,
        ?string $default = null,
        bool $preserveQueryParams = true
    ): RedirectResponse {
        // Get redirect URL based on role
        $url = $this->getRedirectUrlForRole($user->role, $default);

        // Preserve query parameters if requested
        if ($preserveQueryParams && request()->hasAny(['utm_source', 'utm_medium', 'utm_campaign', 'ref'])) {
            $url = $this->appendQueryParameters($url);
        }

        // Log redirect for debugging
        Log::info('User redirected after login', [
            'user_id' => $user->id,
            'role' => $user->role,
            'redirect_url' => $url,
            'intended' => session()->has('url.intended'),
        ]);

        // Use intended() if available, otherwise redirect to determined URL
        return redirect()->intended($url);
    }

    /**
     * Get redirect URL based on user role
     * 
     * @param string|null $role
     * @param string|null $default
     * @return string
     */
    protected function getRedirectUrlForRole(?string $role, ?string $default = null): string
    {
        // Get role-specific routes from config
        $roleRoutes = config('auth.role_redirects', [
            User::ROLE_ADMIN => 'admin.dashboard',
            User::ROLE_USER => 'home',
        ]);

        // Get route name for role
        $routeName = $roleRoutes[$role] ?? null;

        // If route exists, use it
        if ($routeName && \Illuminate\Support\Facades\Route::has($routeName)) {
            return route($routeName);
        }

        // Use default if provided
        if ($default) {
            return $default;
        }

        // Fall back to home
        return route('home');
    }

    /**
     * Redirect after registration
     * 
     * NEW METHOD: Separate method for registration redirects
     * 
     * @param User $user
     * @return RedirectResponse
     */
    public function redirectAfterRegistration(User $user): RedirectResponse
    {
        // Get registration-specific redirect URL
        $url = $this->getRegistrationRedirectUrl($user);

        // Add welcome query parameter
        $url = $this->appendQueryParameters($url, ['welcome' => '1']);

        Log::info('User redirected after registration', [
            'user_id' => $user->id,
            'role' => $user->role,
            'redirect_url' => $url,
        ]);

        return redirect($url);
    }

    /**
     * Get registration redirect URL
     * 
     * @param User $user
     * @return string
     */
    protected function getRegistrationRedirectUrl(User $user): string
    {
        // Check for custom registration redirect
        $registrationRoutes = config('auth.registration_redirects', [
            User::ROLE_ADMIN => 'admin.dashboard',
            User::ROLE_USER => 'home',
        ]);

        $routeName = $registrationRoutes[$user->role] ?? 'home';

        if (\Illuminate\Support\Facades\Route::has($routeName)) {
            return route($routeName);
        }

        return route('home');
    }

    /**
     * Redirect after logout
     * 
     * NEW METHOD: Consistent logout redirects
     * 
     * @return RedirectResponse
     */
    public function redirectAfterLogout(): RedirectResponse
    {
        $url = config('auth.logout_redirect', route('home'));

        Log::info('User redirected after logout', [
            'redirect_url' => $url,
        ]);

        return redirect($url);
    }

    /**
     * Redirect after email verification
     * 
     * NEW METHOD: Email verification redirects
     * 
     * @param User $user
     * @return RedirectResponse
     */
    public function redirectAfterVerification(User $user): RedirectResponse
    {
        $url = $this->getRedirectUrlForRole($user->role);
        $url = $this->appendQueryParameters($url, ['verified' => '1']);

        Log::info('User redirected after email verification', [
            'user_id' => $user->id,
            'redirect_url' => $url,
        ]);

        return redirect($url);
    }

    /**
     * Redirect after password reset
     * 
     * NEW METHOD: Password reset redirects
     * 
     * @return RedirectResponse
     */
    public function redirectAfterPasswordReset(): RedirectResponse
    {
        $url = route('login');
        $url = $this->appendQueryParameters($url, ['reset' => '1']);

        Log::info('User redirected after password reset', [
            'redirect_url' => $url,
        ]);

        return redirect($url);
    }

    /**
     * Append query parameters to URL
     * 
     * @param string $url
     * @param array $additionalParams
     * @return string
     */
    protected function appendQueryParameters(string $url, array $additionalParams = []): string
    {
        // Get current query parameters
        $queryParams = request()->query();

        // Add additional parameters
        $queryParams = array_merge($queryParams, $additionalParams);

        // Filter to only keep relevant params
        $relevantParams = [
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_content',
            'utm_term',
            'ref',
            'welcome',
            'verified',
            'reset',
        ];

        $queryParams = array_intersect_key($queryParams, array_flip($relevantParams));

        // Build URL with query parameters
        if (!empty($queryParams)) {
            $separator = parse_url($url, PHP_URL_QUERY) ? '&' : '?';
            $url .= $separator . http_build_query($queryParams);
        }

        return $url;
    }

    /**
     * Get intended URL with fallback
     * 
     * NEW METHOD: Safe intended URL retrieval
     * 
     * @param string $fallback
     * @return string
     */
    protected function getIntendedUrl(string $fallback): string
    {
        $intended = session()->pull('url.intended', $fallback);

        // Ensure intended URL is from our domain (security)
        $parsedIntended = parse_url($intended);
        $parsedApp = parse_url(config('app.url'));

        if (isset($parsedIntended['host']) && $parsedIntended['host'] !== $parsedApp['host']) {
            Log::warning('Intended URL from different domain, using fallback', [
                'intended' => $intended,
                'fallback' => $fallback,
            ]);
            return $fallback;
        }

        return $intended;
    }

    /**
     * Redirect to role-appropriate dashboard
     * 
     * NEW METHOD: Direct dashboard redirect
     * 
     * @param User $user
     * @return RedirectResponse
     */
    public function redirectToDashboard(User $user): RedirectResponse
    {
        return $this->redirectAfterLogin($user, null, false);
    }
}
