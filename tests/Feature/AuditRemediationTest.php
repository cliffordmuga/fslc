<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Tag;
use App\Models\User;
use App\Services\CacheBuster;
use App\Services\ContentService;
use App\Services\SitemapService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuditRemediationTest extends TestCase
{
    use RefreshDatabase;

    /** @param array<string, mixed> $attributes */
    private function createUser(array $attributes = []): User
    {
        return User::factory()->createOne($attributes);
    }

    private function actingAsAdmin(): self
    {
        $admin = $this->createUser([
            'role' => 'admin',
            'email_verified_at' => now(),
            'google2fa_secret' => 'TESTSECRETKEY000',
            'two_factor_recovery_codes' => ['recovery-one', 'recovery-two'],
        ]);
        session(['2fa_verified' => true]);

        return $this->actingAs($admin);
    }

    public function test_robots_txt_blocks_preview_and_admin_paths(): void
    {
        $body = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Disallow: /preview', $body);
        $this->assertStringContainsString('Disallow: /admin', $body);
        $this->assertStringContainsString('Disallow: /2fa', $body);
    }

    public function test_signed_preview_is_noindex(): void
    {
        $admin = $this->createUser(['role' => 'admin']);
        $content = Content::create([
            'title' => 'Draft Preview Post',
            'slug' => 'draft-preview-post',
            'type' => 'blog',
            'content' => '<p>Draft body</p>',
            'excerpt' => 'Draft',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        $url = URL::temporarySignedRoute('content.preview', now()->addHour(), ['content' => $content->id]);

        $this->get($url)
            ->assertOk()
            ->assertSee('noindex', false)
            ->assertSee('nofollow', false);
    }

    public function test_tag_pagination_uses_self_referencing_canonical(): void
    {
        $tag = Tag::create(['name' => 'HMIS', 'slug' => 'hmis']);

        $seo = app(ContentService::class)->getTagSeoData($tag);
        $this->assertStringContainsString('/tags/hmis', $seo['canonical_url']);
        $this->assertFalse($seo['noindex']);

        request()->merge(['page' => 2]);
        $seoPage2 = app(ContentService::class)->getTagSeoData($tag);
        $this->assertStringContainsString('page=2', $seoPage2['canonical_url']);
        $this->assertFalse($seoPage2['noindex']);
    }

    public function test_filtered_hub_views_are_noindex_with_base_canonical(): void
    {
        $service = app(ContentService::class);
        $seo = $service->applyFilteredHubSeo($service->getSeoData('portfolio'), 'portfolio.index');

        $this->assertTrue($seo['noindex']);
        $this->assertSame(route('portfolio.index'), $seo['canonical_url']);
    }

    public function test_sitemap_includes_legal_and_mission_routes(): void
    {
        $reflection = new \ReflectionClass(SitemapService::class);
        $method = $reflection->getMethod('getStaticPages');
        $method->setAccessible(true);
        /** @var array<int, array<string, mixed>> $pages */
        $pages = $method->invoke(app(SitemapService::class));

        $routes = collect($pages)->pluck('route')->filter()->values()->all();

        $this->assertContains('privacy', $routes);
        $this->assertContains('terms', $routes);
        $this->assertContains('mission', $routes);
        $this->assertContains('vision', $routes);
    }

    public function test_admin_layout_emits_noindex_robots(): void
    {
        $this->actingAsAdmin()
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('noindex, nofollow', false);
    }

    public function test_health_endpoint_returns_structured_json(): void
    {
        $response = $this->get(route('health'));

        $response->assertOk()
            ->assertJsonStructure(['ok', 'checks' => ['app', 'db', 'cache'], 'timestamp']);
    }

    public function test_guest_layout_includes_og_site_name(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('og:site_name', false);
    }

    public function test_insights_pagination_uses_self_referencing_canonical(): void
    {
        $service = app(ContentService::class);
        $seo = $service->applyPaginatedHubSeo($service->getSeoData('blog'), 'insights.index', 1);

        $this->assertSame(route('insights.index'), $seo['canonical_url']);
        $this->assertStringNotContainsString('page=', $seo['canonical_url']);

        $seoPage2 = $service->applyPaginatedHubSeo($service->getSeoData('blog'), 'insights.index', 2);
        $this->assertStringContainsString('page=2', $seoPage2['canonical_url']);
    }

    public function test_hub_page_seo_uses_route_canonical_not_query_string(): void
    {
        request()->merge(['utm_source' => 'newsletter']);

        $seo = app(ContentService::class)->getSeoData('blog');

        $this->assertSame(route('insights.index'), $seo['canonical_url']);
        $this->assertStringNotContainsString('utm_source', $seo['canonical_url']);
    }

    public function test_content_save_bumps_public_and_fragment_cache_busters(): void
    {
        $admin = $this->createUser(['role' => 'admin']);
        $publicBefore = CacheBuster::current();
        $fragmentBefore = (int) Cache::get('content:cache_buster', 0);

        Content::create([
            'title' => 'Published Post',
            'slug' => 'published-post',
            'type' => 'blog',
            'content' => '<p>Body</p>',
            'excerpt' => 'Excerpt',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        $this->assertNotSame($publicBefore, CacheBuster::current());
        $this->assertGreaterThan($fragmentBefore, (int) Cache::get('content:cache_buster', 0));
    }

    public function test_sitemap_ping_respects_config_and_targets_bing(): void
    {
        Http::fake();

        config(['seo.ping_search_engines' => false]);
        app(SitemapService::class)->pingSearchEngines();
        Http::assertNothingSent();

        config(['seo.ping_search_engines' => true, 'seo.ping_google' => false]);
        app(SitemapService::class)->pingSearchEngines();

        Http::assertSent(fn ($request) => str_contains($request->url(), 'bing.com/ping'));
        Http::assertSentCount(1);
    }

    public function test_search_sticky_filter_bar_offsets_below_nav(): void
    {
        $this->get(route('search'))
            ->assertOk()
            ->assertSee('sticky top-16 z-40', false);
    }

    public function test_guest_layout_inlines_critical_css_and_defers_full_stylesheet(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('#main-content', false);
        $response->assertSee('rel="preload"', false);
        $response->assertSee('as="style"', false);
    }

    public function test_csp_status_command_reports_mode(): void
    {
        $this->artisan('csp:status')
            ->assertExitCode(0);
    }
}
