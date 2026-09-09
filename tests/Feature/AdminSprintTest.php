<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Lead;
use App\Models\Redirect;
use App\Models\User;
use App\Services\CacheBuster;
use App\Support\AdminUiCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AdminSprintTest extends TestCase
{
    use RefreshDatabase;

    /** @param array<string, mixed> $attributes */
    private function createUser(array $attributes = []): User
    {
        return User::factory()->createOne($attributes);
    }

    protected function adminWithTwoFactor(): User
    {
        return $this->createUser([
            'role' => 'admin',
            'email_verified_at' => now(),
            'google2fa_secret' => 'TESTSECRETKEY000',
            'two_factor_recovery_codes' => ['recovery-one', 'recovery-two'],
        ]);
    }

    protected function actingAsAdmin(): self
    {
        $admin = $this->adminWithTwoFactor();
        session(['2fa_verified' => true]);

        return $this->actingAs($admin);
    }

    public function test_leads_export_route_is_not_captured_as_lead_model(): void
    {
        $this->actingAsAdmin()
            ->get(route('admin.leads.export', ['spam_filter' => 'exclude']))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_non_admin_cannot_access_admin_content_store(): void
    {
        $user = $this->createUser(['role' => 'user', 'email_verified_at' => now()]);

        $this->actingAs($user)
            ->post(route('admin.content.store'), [
                'title' => 'Hack',
                'type' => 'page',
                'status' => 'published',
            ])
            ->assertForbidden();
    }

    public function test_blog_slug_change_creates_insights_redirect(): void
    {
        $admin = $this->adminWithTwoFactor();

        $content = Content::create([
            'title' => 'Insights Article',
            'slug' => 'old-insights-slug',
            'type' => 'blog',
            'content' => '<p>Body</p>',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);

        $content->update(['slug' => 'new-insights-slug']);

        $this->assertDatabaseHas('redirects', [
            'old_path' => '/insights/old-insights-slug',
            'new_path' => '/insights/new-insights-slug',
            'status_code' => 301,
        ]);
    }

    public function test_lead_creation_busts_dashboard_cache_token(): void
    {
        $before = CacheBuster::current();

        Lead::create([
            'name' => 'Dashboard Bust',
            'email' => 'bust@example.com',
            'message' => 'Test',
            'inquiry_type' => 'general',
            'status' => 'new',
            'is_spam' => false,
        ]);

        $this->assertNotSame($before, CacheBuster::current());
        $this->assertFalse(Cache::has(AdminUiCache::NEW_LEADS_KEY));
    }

    public function test_analytics_accepts_custom_date_range(): void
    {
        $this->actingAsAdmin()
            ->get(route('admin.analytics.index', [
                'start_date' => '2025-01-01',
                'end_date' => '2025-01-31',
            ]))
            ->assertOk()
            ->assertSee('Analytics Dashboard', false);
    }

    public function test_admin_without_two_factor_redirected_from_dashboard(): void
    {
        $admin = $this->createUser([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('2fa.setup'));
    }

    public function test_two_factor_setup_renders_inline_qr_data_uri(): void
    {
        $admin = $this->createUser([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $html = $this->actingAs($admin)
            ->get(route('2fa.setup'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('data:image/svg+xml;base64,', $html);
        $this->assertStringNotContainsString('otpauth://', $html);
    }

    public function test_signed_content_preview_renders_detail_page(): void
    {
        $admin = $this->adminWithTwoFactor();
        $content = Content::create([
            'title' => 'Draft Preview',
            'slug' => 'draft-preview-article',
            'type' => 'blog',
            'content' => '<p>Draft body</p>',
            'status' => 'draft',
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);

        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'content.preview',
            now()->addHour(),
            ['content' => $content->id]
        );

        $this->get($url)
            ->assertOk()
            ->assertSee('Draft Preview', false);
    }
}
