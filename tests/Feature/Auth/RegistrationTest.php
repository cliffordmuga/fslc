<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect();
        $this->assertStringStartsWith(url('/'), $response->headers->get('Location'));
    }

    /**
     * Regression: an anonymous visitor must not be able to self-assign a
     * privileged role during registration. Previously determineRole() read a
     * `role` request parameter, so POST /register?role=admin created an admin.
     */
    private function assertRegistrationIgnoresRole(string $uri, array $extraPayload): void
    {
        $this->post($uri, array_merge([
            'name' => 'Mallory',
            'email' => 'mallory@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ], $extraPayload));

        $user = User::where('email', 'mallory@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame(User::ROLE_USER, $user->role);
        $this->assertFalse($user->isAdmin());
    }

    public function test_registration_ignores_role_in_query_string(): void
    {
        $this->assertRegistrationIgnoresRole('/register?role=admin', []);
    }

    public function test_registration_ignores_role_in_request_body(): void
    {
        $this->assertRegistrationIgnoresRole('/register', ['role' => 'admin']);
    }

    public function test_registration_ignores_role_array_in_request_body(): void
    {
        $this->assertRegistrationIgnoresRole('/register', ['role' => ['admin']]);
    }
}
