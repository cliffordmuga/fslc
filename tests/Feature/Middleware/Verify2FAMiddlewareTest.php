<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class Verify2FAMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(['2fa'])->get('/test-secure-area', function () {
            return response()->json(['message' => '2FA Verified']);
        })->name('test.secure.area');

        Route::get('/test-2fa-verify', function () {
            return response()->json(['message' => 'Verify 2FA']);
        })->name('middleware_test_2fa_verify');
    }

    public function test_verified_user_can_access_secure_area()
    {
        $user = User::factory()->create([
            'google2fa_secret' => 'secret',
            'two_factor_recovery_codes' => '["code1","code2"]',
            'two_factor_verified_at' => now(),
        ]);
        session(['2fa_verified' => true]);
        $response = $this->actingAs($user)->get('/test-secure-area');
        $response->assertStatus(200);
        $response->assertJson(['message' => '2FA Verified']);
    }

    public function test_unverified_user_is_redirected_to_2fa_verify()
    {
        $user = User::factory()->create([
            'google2fa_secret' => 'secret',
            'two_factor_recovery_codes' => '["code1","code2"]',
            'two_factor_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get('/test-secure-area');
        $response->assertRedirect(route('2fa.verify'));
    }

    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get('/test-secure-area');
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_access_2fa_verification_route()
    {
        $user = User::factory()->create([
            'google2fa_secret' => 'secret',
            'two_factor_recovery_codes' => '["code1","code2"]',
            'two_factor_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get('/test-2fa-verify');
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Verify 2FA']);
    }
}
