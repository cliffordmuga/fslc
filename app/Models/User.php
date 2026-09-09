<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Services\TwoFactorService;
use App\Traits\Cacheable;

/**
 * User Model - Optimized & Complete
 * 
 * FIXED:
 * - Removed HasApiTokens (Laravel Sanctum not installed)
 * - All 2FA methods delegate to TwoFactorService
 * - Added Cacheable trait support
 * - Clean architecture
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $password
 * @property string|null $provider
 * @property string|null $provider_id
 * @property string|null $avatar
 * @property string $role
 * @property string|null $google2fa_secret
 * @property array|null $two_factor_recovery_codes
 * @property \Carbon\Carbon|null $two_factor_verified_at
 * @property \Carbon\Carbon|null $email_verified_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity, Cacheable;

    // ===================================
    // MASS ASSIGNMENT
    // ===================================

    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'avatar',
        'role',
        'google2fa_secret',
        'two_factor_recovery_codes',
        'two_factor_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_verified_at' => 'datetime',
        'two_factor_recovery_codes' => 'array',
        'password' => 'hashed',
    ];

    // ===================================
    // ROLE CONSTANTS & METHODS
    // ===================================

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // ===================================
    // 2FA METHODS (Delegate to Service)
    // ===================================

    /**
     * Check if user has 2FA enabled
     * 
     * @return bool
     */
    public function has2FA(): bool
    {
        return app(TwoFactorService::class)->has2FA($this);
    }

    /**
     * Check if user needs 2FA verification for current session
     * 
     * @return bool
     */
    public function needs2FA(): bool
    {
        return app(TwoFactorService::class)->needs2FAVerification($this);
    }

    /**
     * Check if 2FA is verified in current session
     * 
     * @return bool
     */
    public function is2FAVerified(): bool
    {
        return app(TwoFactorService::class)->isSessionVerified();
    }

    /**
     * Check if user should be prompted for 2FA
     * 
     * @return bool
     */
    public function shouldPromptFor2FA(): bool
    {
        return app(TwoFactorService::class)->shouldPromptFor2FA($this);
    }

    /**
     * Get 2FA status summary
     * 
     * @return array
     */
    public function get2FAStatus(): array
    {
        return app(TwoFactorService::class)->getStatus($this);
    }

    // ===================================
    // RELATIONSHIPS
    // ===================================

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class, 'created_by');
    }

    // ===================================
    // ACTIVITY LOGGING
    // ===================================

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "User {$this->name} ({$this->email}) was {$eventName}");
    }

    // ===================================
    // CACHE SUPPORT (for Cacheable trait)
    // ===================================

    public function getCacheKeys(): array
    {
        return [
            "user_{$this->id}",
            "user_email_{$this->email}",
            "user_2fa_{$this->id}",
            "user_roles_{$this->id}",
        ];
    }

    public function getCachePatterns(): array
    {
        return [
            "user_{$this->id}*",
            "user_email_{$this->email}*",
        ];
    }

    public function getCacheTags(): array
    {
        return [
            'users',
            "user_{$this->id}",
        ];
    }
}
