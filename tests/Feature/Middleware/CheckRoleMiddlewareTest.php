<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CheckRoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(['role:admin'])->get('/test-admin-only', function () {
            return response()->json(['message' => 'Welcome Admin']);
        })->name('test.admin.only');
    }

    public function test_admin_can_access_route()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/test-admin-only');
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Welcome Admin']);
    }

    public function test_user_cannot_access_admin_route()
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/test-admin-only');
        $response->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get('/test-admin-only');
        $response->assertRedirect(route('login'));
    }
}
