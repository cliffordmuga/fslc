<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function __construct(protected TwoFactorService $twoFactorService) {}

    /**
     * Show 2FA setup / management screen
     */
    public function setup(): View
    {
        $user = $this->user();

        if (!$this->twoFactorService->has2FA($user)) {
            $setupData = $this->twoFactorService->getSetupData($user);

            return view('auth.2fa.setup', [
                'secret' => $setupData['secret'],
                'qrCodeUrl' => $setupData['qr_code_url'],
                'recoveryCodes' => $setupData['recovery_codes'],
            ]);
        }

        return view('auth.2fa.manage', [
            'recoveryCodes' => $user->two_factor_recovery_codes ?? [],
        ]);
    }

    /**
     * Enable 2FA after valid code
     */
    public function enable(Request $request): RedirectResponse
    {
        $request->validate(['code' => 'required|string|size:6']);

        $user = $this->user();

        if (!$this->twoFactorService->enable2FA($user, $request->code)) {
            throw ValidationException::withMessages([
                'code' => __('The verification code is invalid.'),
            ]);
        }

        return redirect()
            ->route('dashboard')
            ->with('status', __('Two-factor authentication has been enabled.'));
    }

    /**
     * Disable 2FA (requires password confirmation)
     */
    public function disable(Request $request): RedirectResponse
    {
        $request->validate(['password' => 'required|current_password']);

        $this->twoFactorService->disable2FA($this->user());

        return redirect()
            ->route('dashboard')
            ->with('status', __('Two-factor authentication has been disabled.'));
    }

    /**
     * Show verification screen (called by middleware when needed)
     */
    public function verify(): View
    {
        return view('auth.2fa.verify');
    }

    /**
     * Verify 2FA code or recovery code during login
     */
    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate(['code' => 'required|string|size:6']);

        $user = $this->user();

        $valid = $this->twoFactorService->verifyCode($user, $request->code)
            || $this->twoFactorService->verifyRecoveryCode($user, $request->code);

        if (!$valid) {
            throw ValidationException::withMessages([
                'code' => __('The verification code or recovery code is invalid.'),
            ]);
        }

        $this->twoFactorService->markAsVerified();
        $user->update(['two_factor_verified_at' => now()]);

        return redirect()->intended(route('dashboard'));
    }

    private function user(): User
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }
}
