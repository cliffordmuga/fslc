<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use App\Traits\AssignsDefaultRole;
use App\Traits\RedirectsUsers;

class SocialiteController extends Controller
{
    use AssignsDefaultRole, RedirectsUsers;
    /**
     * Redirect to the OAuth provider.
     */
    public function redirectToProvider(string $provider): RedirectResponse
    {
        if (!config('features.social_login_enabled', false)) {
            abort(404);
        }

        $allowedProviders = ['google', 'facebook', 'apple'];

        if (!in_array($provider, $allowedProviders)) {
            abort(404, 'Unsupported provider');
        }

        return Socialite::driver($provider)->redirect();
    }
    

    /**
     * Handle the OAuth provider callback.
     */
    public function handleProviderCallback(string $provider): RedirectResponse
    {
        if (!config('features.social_login_enabled', false)) {
            abort(404);
        }

        $allowedProviders = ['google', 'facebook', 'apple'];

        if (!in_array($provider, $allowedProviders)) {
            abort(404, 'Unsupported provider');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = User::updateOrCreate(
                [
                    'provider' => $provider,
                    'provider_id' => $socialUser->id,
                ],
                [
                    'name' => $socialUser->name ?? $socialUser->nickname ?? 'Unknown',
                    'email' => $socialUser->email,
                    'avatar' => $socialUser->avatar,
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]
            );
            
            $this->assignDefaultRole($user);
            
            Auth::login($user, true);

            Log::info('Social login successful', [
                'provider' => $provider,
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return $this->redirectAfterLogin($user);

        } catch (\Exception $e) {
            Log::error('Social login failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('login')->withErrors([
                'error' => 'Failed to authenticate with ' . ucfirst($provider),
            ]);
        }
    }
}

