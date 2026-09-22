<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * Exhaustive HTTP smoke for public + authenticated surfaces.
 * Catches missing views, 500s, broken named routes, and auth gates.
 */
class ExhaustiveHttpSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--force' => true]);
    }

    public function test_public_pages_return_successful_html(): void
    {
        $portfolio = Content::query()->published()->where('type', 'portfolio')->first();
        $service = Content::query()->published()->where('type', 'services')->first();
        $blog = Content::query()->published()->where('type', 'blog')->first();
        $tag = Tag::query()->first();

        $paths = [
            '/',
            '/about',
            '/portfolio',
            '/services',
            '/insights',
            '/blog', // legacy redirect
            '/contact',
            '/mission',
            '/vision',
            '/intro',
            '/privacy',
            '/terms',
            '/search',
            '/search?q=hmis',
            '/sitemap',
            '/sitemap.xml',
            '/health',
            '/login',
            '/register',
            '/forgot-password',
        ];

        if ($portfolio) {
            $paths[] = '/portfolio/'.$portfolio->slug;
        }
        if ($service) {
            $paths[] = '/services/'.$service->slug;
        }
        if ($blog) {
            $paths[] = '/insights/'.$blog->slug;
            $paths[] = '/blog/'.$blog->slug;
        }
        if ($tag) {
            $paths[] = '/tags/'.$tag->slug;
        }

        $failures = [];

        foreach ($paths as $path) {
            $response = $this->get($path);
            $status = $response->getStatusCode();

            // Redirects (301/302) are OK for legacy aliases
            if (in_array($status, [301, 302], true)) {
                continue;
            }

            if ($status >= 400) {
                $failures[] = "{$path} => {$status}";

                continue;
            }

            // HTML pages should not be empty shells (skip binary responses like sitemap.xml)
            $contentType = method_exists($response, 'headers')
                ? (string) $response->headers->get('Content-Type')
                : '';
            if (str_contains($contentType, 'text/html') && method_exists($response, 'getContent')) {
                $html = $response->getContent();
                if ($html === false || trim((string) $html) === '') {
                    $failures[] = "{$path} => empty body";
                }
            }
        }

        $this->assertSame([], $failures, "Public path failures:\n".implode("\n", $failures));
    }

    public function test_admin_pages_require_auth_and_succeed_for_admin(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
            'google2fa_secret' => 'TESTSECRETKEY000',
            'two_factor_recovery_codes' => ['recovery-one', 'recovery-two'],
        ]);

        $guestBlocked = [];
        $adminFailures = [];

        $adminPaths = [
            '/admin/dashboard',
            '/admin/content',
            '/admin/content/create',
            '/admin/leads',
            '/admin/testimonials',
            '/admin/settings',
            '/admin/ctas',
            '/admin/redirects',
            '/admin/analytics',
            '/admin/activity',
            '/dashboard',
            '/profile',
        ];

        foreach ($adminPaths as $path) {
            $guest = $this->get($path);
            if (! in_array($guest->status(), [301, 302, 401, 403], true)) {
                $guestBlocked[] = "{$path} guest => {$guest->status()}";
            }
        }

        $this->actingAs($admin);
        session(['2fa_verified' => true]);

        foreach ($adminPaths as $path) {
            $response = $this->get($path);
            $status = $response->status();

            if (in_array($status, [301, 302], true)) {
                continue;
            }

            if ($status >= 400) {
                $adminFailures[] = "{$path} admin => {$status}";
            }
        }

        $this->assertSame([], $guestBlocked, "Admin paths not gated:\n".implode("\n", $guestBlocked));
        $this->assertSame([], $adminFailures, "Admin path failures:\n".implode("\n", $adminFailures));
    }

    public function test_contact_form_validation_and_success_path(): void
    {
        $fail = $this->post('/contact', []);
        $fail->assertSessionHasErrors();

        $ok = $this->post('/contact', [
            'name' => 'Smoke Tester',
            'email' => 'smoke@example.com',
            'phone' => '+254712345678',
            'message' => 'Please schedule an HMIS demo for our county hospital.',
            'inquiry_type' => 'hmis-demo',
            'website' => '', // honeypot empty
        ]);

        $ok->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'email' => 'smoke@example.com',
            'inquiry_type' => 'hmis-demo',
        ]);
    }

    public function test_blade_view_files_referenced_by_frontend_exist(): void
    {
        $expected = [
            'frontend.index',
            'frontend.about',
            'frontend.portfolio',
            'frontend.services',
            'frontend.blog',
            'frontend.details',
            'frontend.contact',
            'frontend.search',
            'frontend.tag',
            'frontend.legal',
            'sitemap',
            'layouts.guest',
            'layouts.admin',
            'layouts.auth',
        ];

        $missing = [];
        foreach ($expected as $view) {
            if (! view()->exists($view)) {
                $missing[] = $view;
            }
        }

        $this->assertSame([], $missing, 'Missing views: '.implode(', ', $missing));
    }

    public function test_singleton_pages_load_by_type_not_slug(): void
    {
        // Seeders use welcome / our-mission / our-vision — routes must still work.
        $this->get('/intro')->assertOk();
        $this->get('/mission')->assertOk();
        $this->get('/vision')->assertOk();
    }
}
