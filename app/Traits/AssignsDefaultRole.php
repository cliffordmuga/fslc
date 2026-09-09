<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * AssignsDefaultRole Trait
 * 
 * OPTIMIZED: Configurable and validated
 * 
 * Improvements:
 * - Configurable default role
 * - Role validation
 * - Supports custom roles
 * - Better error handling
 * - Flexible role sources
 * 
 * Usage in controllers:
 * ```php
 * use AssignsDefaultRole;
 * 
 * $this->assignDefaultRole($user);
 * // or
 * $this->assignDefaultRole($user, 'admin');
 * ```
 */
trait AssignsDefaultRole
{
    /**
     * Assign default role to user
     * 
     * IMPROVED: Configurable, validated, flexible
     * 
     * @param User $user
     * @param string|null $role Custom role (optional)
     * @return void
     */
    public function assignDefaultRole(User $user, ?string $role = null): void
    {
        // Skip if user already has a role
        if ($user->role) {
            Log::debug('User already has role, skipping assignment', [
                'user_id' => $user->id,
                'existing_role' => $user->role,
            ]);
            return;
        }

        // Determine role to assign
        $roleToAssign = $this->determineRole($user, $role);

        // Validate role
        if (!$this->isValidRole($roleToAssign)) {
            Log::error('Invalid role attempted', [
                'user_id' => $user->id,
                'attempted_role' => $roleToAssign,
                'valid_roles' => $this->getValidRoles(),
            ]);
            
            // Fall back to default role
            $roleToAssign = $this->getDefaultRole();
        }

        // Assign role
        $user->role = $roleToAssign;
        $user->saveQuietly(); // Avoid triggering events

        Log::info('Default role assigned', [
            'user_id' => $user->id,
            'role' => $roleToAssign,
            'provider' => $user->provider ?? 'direct',
            'source' => $role ? 'custom' : 'default',
        ]);
    }

    /**
     * Determine which role to assign
     * 
     * Priority order:
     * 1. Custom role parameter
     * 2. Role from request query
     * 3. Role from config for provider
     * 4. Default role from config
     * 
     * @param User $user
     * @param string|null $customRole
     * @return string
     */
    protected function determineRole(User $user, ?string $customRole = null): string
    {
        // 1. Use custom role if provided
        if ($customRole) {
            return $customRole;
        }

        // 2. Check request query for role (for invite links)
        if (request()->has('role')) {
            $requestRole = request()->query('role');
            if ($this->isValidRole($requestRole)) {
                return $requestRole;
            }
        }

        // 3. Check provider-specific config
        if ($user->provider) {
            $providerRole = config("auth.providers.{$user->provider}.default_role");
            if ($providerRole && $this->isValidRole($providerRole)) {
                return $providerRole;
            }
        }

        // 4. Fall back to default
        return $this->getDefaultRole();
    }

    /**
     * Get default role from config
     * 
     * @return string
     */
    protected function getDefaultRole(): string
    {
        return config('auth.default_role', User::ROLE_USER);
    }

    /**
     * Get valid roles from User model or config
     * 
     * @return array
     */
    protected function getValidRoles(): array
    {
        // Try to get from User model constants
        if (defined('App\Models\User::ROLE_ADMIN')) {
            return [
                User::ROLE_ADMIN,
                User::ROLE_USER,
            ];
        }

        // Fall back to config
        return config('auth.valid_roles', ['admin', 'user']);
    }

    /**
     * Validate if role is allowed
     * 
     * @param string $role
     * @return bool
     */
    protected function isValidRole(string $role): bool
    {
        return in_array($role, $this->getValidRoles());
    }

    /**
     * Assign role with validation and logging
     * 
     * NEW METHOD: More explicit role assignment
     * 
     * @param User $user
     * @param string $role
     * @param bool $force Force assignment even if role exists
     * @return bool Success status
     */
    public function assignRole(User $user, string $role, bool $force = false): bool
    {
        // Check if user already has a role
        if ($user->role && !$force) {
            Log::debug('User already has role', [
                'user_id' => $user->id,
                'existing_role' => $user->role,
                'attempted_role' => $role,
            ]);
            return false;
        }

        // Validate role
        if (!$this->isValidRole($role)) {
            Log::error('Invalid role assignment attempted', [
                'user_id' => $user->id,
                'attempted_role' => $role,
                'valid_roles' => $this->getValidRoles(),
            ]);
            return false;
        }

        // Assign role
        $previousRole = $user->role;
        $user->role = $role;
        $user->saveQuietly();

        Log::info('Role assigned', [
            'user_id' => $user->id,
            'previous_role' => $previousRole,
            'new_role' => $role,
            'forced' => $force,
        ]);

        return true;
    }

    /**
     * Check if user needs role assignment
     * 
     * NEW METHOD: Check before assigning
     * 
     * @param User $user
     * @return bool
     */
    protected function needsRoleAssignment(User $user): bool
    {
        return empty($user->role);
    }
}
