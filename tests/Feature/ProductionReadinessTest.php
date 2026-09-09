<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ProductionReadinessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::unguard();
        Setting::firstOrCreate(['key' => 'company_name'], ['value' => 'Forefront Solutions', 'type' => 'text', 'category' => 'general']);
        Setting::reguard();

        $admin = User::factory()->create(['role' => 'admin']);

        Content::create([
            'title' => 'HMIS & Digital Health Solutions',
            'slug' => 'hmis-digital-health-solutions-kenya',
            'type' => 'services',
            'content' => '<p>HMIS vendor Kenya.</p>',
            'excerpt' => 'HMIS for Kenya',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);

        Content::create([
            'title' => 'County HMIS',
            'slug' => 'county-referral-hospital-hmis',
            'type' => 'portfolio',
            'content' => '<p>Case study.</p>',
            'excerpt' => 'HMIS deployment',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);

        Content::create([
            'title' => 'HMIS Procurement Checklist',
            'slug' => 'hmis-procurement-checklist',
            'type' => 'page',
            'content' => '<p>Checklist.</p>',
            'excerpt' => 'Free checklist',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);
    }

    public function test_published_service_content_urls_use_services_show_route(): void
    {
        $content = Content::where('slug', 'hmis-digital-health-solutions-kenya')->firstOrFail();

        $this->assertStringContainsString('/services/', $content->url);
        $this->assertStringContainsString($content->slug, $content->url);
    }

    public function test_published_portfolio_content_urls_use_portfolio_show_route(): void
    {
        $content = Content::where('slug', 'county-referral-hospital-hmis')->firstOrFail();

        $this->assertStringContainsString('/portfolio/', $content->url);
    }

    public function test_service_detail_page_loads_with_lead_config(): void
    {
        $this->get(route('services.show', 'hmis-digital-health-solutions-kenya'))
            ->assertOk()
            ->assertSee('Request HMIS Demo', false);
    }

    public function test_hmis_lead_magnet_page_loads(): void
    {
        $this->get(route('page.show', 'hmis-procurement-checklist'))->assertOk();
    }

    public function test_portfolio_pillar_filter_loads(): void
    {
        $this->get(route('portfolio.index', ['pillar' => 'hmis']))->assertOk();
    }

    public function test_contact_preselects_hmis_inquiry_type(): void
    {
        $this->get(route('contact', ['inquiry_type' => 'hmis-demo']))
            ->assertOk()
            ->assertSee('hmis-demo', false);
    }

    public function test_hero_assets_exist(): void
    {
        $this->assertFileExists(public_path('images/hero-home.jpg'));
        for ($i = 1; $i <= 7; $i++) {
            $mapped = config("forefront.hero_images.{$i}");
            $this->assertTrue(
                ($mapped && file_exists(public_path($mapped)))
                || file_exists(public_path("assets/bg/hero/hero-{$i}.webp"))
                || file_exists(public_path("assets/bg/hero/hero-{$i}.svg")),
                "Hero asset {$i} missing"
            );
        }
    }

    public function test_brand_png_assets_exist(): void
    {
        $this->assertFileExists(public_path('images/default-og-image.png'));
        $this->assertFileExists(public_path('images/logo.png'));
    }

    public function test_insights_index_route_registered(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('insights.index'));
    }

    public function test_content_type_routes_config_matches_registered_routes(): void
    {
        foreach (config('routes.content_types', []) as $type => $routeName) {
            if ($type === 'default') {
                continue;
            }
            $this->assertTrue(Route::has($routeName), "Missing route for content type [{$type}]: {$routeName}");
        }
    }
}
