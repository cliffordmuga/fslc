<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminPassword = env('SEED_ADMIN_PASSWORD', Str::password(16));

        User::create([
            'name' => env('SEED_ADMIN_NAME', 'Admin User'),
            'email' => env('SEED_ADMIN_EMAIL', 'admin@example.com'),
            'password' => Hash::make($adminPassword),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => env('SEED_USER_NAME', 'Regular User'),
            'email' => env('SEED_USER_EMAIL', 'user@example.com'),
            'password' => Hash::make(env('SEED_USER_PASSWORD', Str::password(16))),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        if (app()->environment('local') && ! env('SEED_ADMIN_PASSWORD')) {
            $this->command?->warn('Seeded admin: ' . env('SEED_ADMIN_EMAIL', 'admin@example.com'));
            $this->command?->warn('Generated admin password (save it): ' . $adminPassword);
        }
    }
}
