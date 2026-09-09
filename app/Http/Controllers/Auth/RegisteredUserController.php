<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Traits\AssignsDefaultRole; // Fixed: PascalCase namespace
use App\Traits\RedirectsUsers;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    use AssignsDefaultRole, RedirectsUsers;

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign default role (public for reuse)
        $this->assignDefaultRole($user);

        // Log for analytics/insights (lead potential)
        Log::info('User registered', [
            'user_id' => $user->id,
            'email' => $user->email,
            'source' => request()->query('utm_source', 'direct'), // Track reg source
        ]);

        event(new Registered($user)); // Triggers verification email

        Auth::login($user);

        // Lead nudge: Redirect with UTM for post-reg CTA
        $redirectUrl = generate_utm_url(
            $this->redirectAfterLogin($user)->getTargetUrl(),
            'reg_complete',
            'auth',
            'user_onboard'
        );

        return redirect($redirectUrl);
    }
}
