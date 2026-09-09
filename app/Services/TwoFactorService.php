<?php

// app/Services/TwoFactorService.php
namespace App\Services;

use App\Models\User;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

/**
 * TwoFactorService - Complete
 * 
 * ADDED:
 * - needs2FAVerification() method (was missing)
 * - Better documentation
 * - All methods optimized
 */
class TwoFactorService
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    // ===================================
    // CHECK METHODS
    // ===================================

    /**
     * Check if user has 2FA enabled
     * 
     * @param User $user
     * @return bool
     */
    public function has2FA(User $user): bool
    {
        return Cache::remember(
            "user_2fa_enabled_{$user->id}",
            now()->addMinutes(60),
            fn() => !is_null($user->google2fa_secret) && !empty($user->two_factor_recovery_codes)
        );
    }

    /**
     * Check if user needs 2FA verification for current session
     * 
     * NEW: Added method that User model was calling
     * 
     * @param User $user
     * @return bool
     */
    public function needs2FAVerification(User $user): bool
    {
        // User needs 2FA if:
        // 1. They have 2FA enabled
        // 2. Current session is not verified
        return $this->has2FA($user) && !$this->isSessionVerified();
    }

    /**
     * Check if 2FA is verified in current session
     * 
     * @return bool
     */
    public function isSessionVerified(): bool
    {
        return session('2fa_verified', false) === true;
    }

    // ===================================
    // GENERATION METHODS
    // ===================================

    /**
     * Generate new 2FA secret
     * 
     * @param User $user
     * @return string
     */
    public function generateSecret(User $user): string
    {
        $secret = $this->google2fa->generateSecretKey();
        $user->update(['google2fa_secret' => $secret]);

        Log::info('2FA secret generated', ['user_id' => $user->id]);

        return $secret;
    }

    /**
     * Generate an inline QR image src for 2FA setup (data URI — works without storage symlink).
     */
    public function generateQRCodeUrl(User $user): string
    {
        if (! $user->google2fa_secret) {
            $this->generateSecret($user);
            $user->refresh();
        }

        $companyName = config('app.name', env('APP_NAME', 'App'));
        $otpauthUrl = $this->google2fa->getQRCodeUrl($companyName, $user->email, $user->google2fa_secret);

        return $this->qrCodeToDataUri($otpauthUrl);
    }

    /**
     * Render otpauth URL as a base64 SVG data URI for use in <img src="...">.
     */
    private function qrCodeToDataUri(string $otpauthUrl): string
    {
        try {
            $writer = new Writer(
                new ImageRenderer(new RendererStyle(240), new SvgImageBackEnd())
            );
            $svg = $writer->writeString($otpauthUrl);

            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        } catch (\Throwable $e) {
            Log::warning('QR code generation failed', [
                'error' => $e->getMessage(),
            ]);
        }

        if (extension_loaded('imagick')) {
            try {
                $writer = new Writer(
                    new ImageRenderer(new RendererStyle(240), new ImagickImageBackEnd())
                );
                $png = $writer->writeString($otpauthUrl);

                return 'data:image/png;base64,' . base64_encode($png);
            } catch (\Throwable $e) {
                Log::warning('QR PNG fallback failed', ['error' => $e->getMessage()]);
            }
        }

        return '';
    }

    /**
     * Generate recovery codes
     * 
     * @param User $user
     * @param int $count
     * @return array
     */
    public function generateRecoveryCodes(User $user, int $count = 8): array
    {
        $codes = collect(range(1, $count))
            ->map(fn() => Str::upper(Str::random(10)))
            ->toArray();

        $user->update(['two_factor_recovery_codes' => $codes]);

        Log::info('Recovery codes generated', [
            'user_id' => $user->id,
            'count' => $count,
        ]);

        return $codes;
    }

    // ===================================
    // VERIFICATION METHODS
    // ===================================

    /**
     * Verify 2FA code
     * 
     * @param User $user
     * @param string $code
     * @return bool
     */
    public function verifyCode(User $user, string $code): bool
    {
        if (!$user->google2fa_secret) {
            return false;
        }

        $valid = $this->google2fa->verifyKey($user->google2fa_secret, $code, 1); // 1 = 30s window

        Log::info('2FA code verification attempt', [
            'user_id' => $user->id,
            'valid' => $valid,
        ]);

        return $valid;
    }

    /**
     * Verify recovery code
     * 
     * @param User $user
     * @param string $code
     * @return bool
     */
    public function verifyRecoveryCode(User $user, string $code): bool
    {
        if (empty($user->two_factor_recovery_codes)) {
            return false;
        }

        $codes = $user->two_factor_recovery_codes;
        $upperCode = Str::upper($code);

        if (($key = array_search($upperCode, $codes, true)) !== false) {
            unset($codes[$key]);
            $user->update(['two_factor_recovery_codes' => array_values($codes)]);

            Log::info('Recovery code used successfully', [
                'user_id' => $user->id,
                'remaining' => count($codes),
            ]);

            return true;
        }

        return false;
    }

    // ===================================
    // ENABLE/DISABLE METHODS
    // ===================================

    /**
     * Enable 2FA for user
     * 
     * @param User $user
     * @param string $code
     * @return bool
     */
    public function enable2FA(User $user, string $code): bool
    {
        if (!$this->verifyCode($user, $code)) {
            return false;
        }

        if (empty($user->two_factor_recovery_codes)) {
            $this->generateRecoveryCodes($user);
        }

        $user->update(['two_factor_verified_at' => now()]);

        Cache::forget("user_2fa_enabled_{$user->id}");

        Log::info('2FA successfully enabled', ['user_id' => $user->id]);

        return true;
    }

    /**
     * Disable 2FA for user
     * 
     * @param User $user
     * @return void
     */
    public function disable2FA(User $user): void
    {
        $user->update([
            'google2fa_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_verified_at' => null,
        ]);

        // Legacy QR files (older builds wrote to disk)
        foreach (['public', 'local'] as $disk) {
            foreach (['svg', 'png'] as $ext) {
                Storage::disk($disk)->delete("public/qr/{$user->id}-2fa.{$ext}");
                Storage::disk($disk)->delete("qr/{$user->id}-2fa.{$ext}");
            }
        }

        Cache::forget("user_2fa_enabled_{$user->id}");
        session()->forget('2fa_verified');

        Log::info('2FA disabled and cleaned up', ['user_id' => $user->id]);
    }

    // ===================================
    // SESSION METHODS
    // ===================================

    /**
     * Mark 2FA as verified in current session
     * 
     * @return void
     */
    public function markAsVerified(): void
    {
        session([
            '2fa_verified' => true,
            '2fa_verified_at' => now()->timestamp
        ]);
    }

    /**
     * Clear 2FA verification from session
     * 
     * @return void
     */
    public function clearVerification(): void
    {
        session()->forget(['2fa_verified', '2fa_verified_at']);
    }

    // ===================================
    // UTILITY METHODS
    // ===================================

    /**
     * Get all setup data at once
     * 
     * @param User $user
     * @return array
     */
    public function getSetupData(User $user): array
    {
        $secret = $user->google2fa_secret ?? $this->generateSecret($user);
        $qrCodeUrl = $this->generateQRCodeUrl($user);
        $recoveryCodes = $user->two_factor_recovery_codes;

        if (empty($recoveryCodes)) {
            $recoveryCodes = $this->generateRecoveryCodes($user);
        }

        return [
            'secret' => $secret,
            'qr_code_url' => $qrCodeUrl,
            'recovery_codes' => $recoveryCodes,
        ];
    }

    /**
     * Check if user should be prompted for 2FA
     * 
     * NEW: Helper method for middleware
     * 
     * @param User $user
     * @return bool
     */
    public function shouldPromptFor2FA(User $user): bool
    {
        // Don't prompt if:
        // 1. User doesn't have 2FA enabled
        // 2. Session is already verified
        // 3. User just enabled 2FA (grace period)

        if (!$this->has2FA($user)) {
            return false;
        }

        if ($this->isSessionVerified()) {
            return false;
        }

        // Grace period: 5 minutes after enabling 2FA
        if (
            $user->two_factor_verified_at &&
            $user->two_factor_verified_at->gt(now()->subMinutes(5))
        ) {
            return false;
        }

        return true;
    }

    /**
     * Get 2FA status summary for user
     * 
     * NEW: Useful for admin dashboard
     * 
     * @param User $user
     * @return array
     */
    public function getStatus(User $user): array
    {
        return [
            'enabled' => $this->has2FA($user),
            'session_verified' => $this->isSessionVerified(),
            'needs_verification' => $this->needs2FAVerification($user),
            'recovery_codes_count' => count($user->two_factor_recovery_codes ?? []),
            'enabled_at' => $user->two_factor_verified_at,
            'should_prompt' => $this->shouldPromptFor2FA($user),
        ];
    }
}
