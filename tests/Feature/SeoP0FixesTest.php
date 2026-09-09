<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Services\ContentService;
use App\Services\SitemapService;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SeoP0FixesTest extends TestCase
{
    public function test_sitemap_static_page_routes_are_registered(): void
    {
        $this->assertTrue(Route::has('about'));
        $this->assertTrue(Route::has('services.index'));
        $this->assertTrue(Route::has('portfolio.index'));
        $this->assertTrue(Route::has('insights.index'));
        $this->assertTrue(Route::has('blog.index'));
        $this->assertTrue(Route::has('contact'));
    }

    public function test_home_seo_data_uses_kenya_focused_copy(): void
    {
        $seo = app(ContentService::class)->getSeoData('home');

        $this->assertStringContainsString('HMIS', $seo['title']);
        $this->assertStringContainsString('Kenya', $seo['title']);
        $this->assertStringContainsString('HMIS', $seo['description']);
    }

    public function test_portfolio_and_services_seo_data_are_keyword_rich(): void
    {
        $service = app(ContentService::class);

        $portfolio = $service->getSeoData('portfolio');
        $this->assertStringContainsString('HMIS', $portfolio['title']);

        $services = $service->getSeoData('services');
        $this->assertStringContainsString('HMIS', $services['description']);
    }

    public function test_tag_seo_data_includes_tag_name_and_canonical(): void
    {
        $tag = new Tag(['name' => 'Laravel', 'slug' => 'laravel']);

        $seo = app(ContentService::class)->getTagSeoData($tag);

        $this->assertSame('Laravel — Content Hub', $seo['title']);
        $this->assertStringContainsString('Laravel', $seo['description']);
        $this->assertStringContainsString('/tags/laravel', $seo['canonical_url']);
    }

    public function test_sitemap_static_pages_use_valid_route_names(): void
    {
        $service = app(SitemapService::class);
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('getStaticPages');
        $method->setAccessible(true);
        /** @var array<int, array<string, mixed>> $pages */
        $pages = $method->invoke($service);

        $routes = collect($pages)->pluck('route')->filter()->values();

        foreach ($routes as $routeName) {
            $this->assertTrue(Route::has($routeName), "Missing route: {$routeName}");
        }

        $this->assertContains('portfolio.index', $routes->all());
        $this->assertContains('services.index', $routes->all());
        $this->assertContains('insights.index', $routes->all());
        $this->assertContains('privacy', $routes->all());
        $this->assertContains('terms', $routes->all());
    }

    public function test_forefront_redirect_seeder_uses_insights_paths_for_blog(): void
    {
        $redirects = \Database\Seeders\Support\ForefrontSeederContent::redirects();
        $bestHmis = collect($redirects)->firstWhere('old_path', '/blog/choose-hmis-level-3-5-hospitals-kenya-2026');

        $this->assertNotNull($bestHmis);
        $this->assertStringStartsWith('/insights/', $bestHmis['new_path']);
    }
}
