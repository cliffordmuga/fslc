<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HolisticRefinementTest extends TestCase
{
    use RefreshDatabase;

    /** @param array<string, mixed> $attributes */
    private function createUser(array $attributes = []): User
    {
        return User::factory()->createOne($attributes);
    }

    /** @param array<string, mixed> $attributes */
    private function adminWithTwoFactor(array $attributes = []): User
    {
        return $this->createUser(array_merge([
            'role' => 'admin',
            'email_verified_at' => now(),
            'google2fa_secret' => 'TESTSECRETKEY000',
            'two_factor_recovery_codes' => ['recovery-one'],
        ], $attributes));
    }

    protected function setUp(): void
    {
        parent::setUp();

        Setting::unguard();
        Setting::firstOrCreate(['key' => 'company_name'], ['value' => 'Forefront Solutions', 'type' => 'text', 'category' => 'general']);
        Setting::firstOrCreate(['key' => 'google_analytics_id'], ['value' => 'G-TEST12345', 'type' => 'text', 'category' => 'seo']);
        Setting::reguard();

        $admin = $this->createUser(['role' => 'admin']);

        Content::create([
            'title' => 'HMIS Service',
            'slug' => 'hmis-digital-health-solutions-kenya',
            'type' => 'services',
            'content' => '<p>HMIS</p>',
            'excerpt' => 'HMIS',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);
    }

    public function test_insights_hub_is_primary_url(): void
    {
        $this->get('/insights')->assertOk()->assertSeeText('Insights & Guides');
        $this->get('/blog')->assertRedirect('/insights');
    }

    public function test_blog_post_redirects_to_insights_url(): void
    {
        $admin = $this->createUser(['role' => 'admin']);
        Content::create([
            'title' => 'Test Post',
            'slug' => 'test-insights-post',
            'type' => 'blog',
            'content' => '<p>Body</p>',
            'excerpt' => 'Excerpt',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);

        $this->get('/blog/test-insights-post')->assertRedirect('/insights/test-insights-post');
        $this->get('/insights/test-insights-post')->assertOk();
    }

    public function test_google_analytics_snippet_renders_when_configured(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('G-TEST12345', false)
            ->assertSee('googletagmanager.com/gtag/js', false);
    }

    public function test_contact_page_shows_pillar_aware_headline(): void
    {
        $this->get(route('contact', ['inquiry_type' => 'hmis-demo']))
            ->assertOk()
            ->assertSee('Request Your HMIS Demo', false);
    }

    public function test_service_page_sticky_ux_label(): void
    {
        $this->get(route('services.show', 'hmis-digital-health-solutions-kenya'))
            ->assertOk()
            ->assertSee('Request HMIS Demo', false);
    }

    public function test_lead_magnet_shortcode_renders(): void
    {
        $html = render_cms_content('<p>Intro</p>[lead-magnet type="hmis-checklist"]');
        $this->assertStringContainsString('HMIS Procurement Checklist', $html);
    }

    public function test_tag_hub_intro_from_database(): void
    {
        $tag = Tag::create([
            'name' => 'HMIS',
            'slug' => 'hmis',
            'hub_intro' => 'Custom HMIS hub intro from database.',
            'meta_description' => 'Custom HMIS meta.',
        ]);

        $seo = app(\App\Services\ContentService::class)->getTagSeoData($tag);
        $this->assertStringContainsString('Custom HMIS meta', $seo['description']);
    }

    public function test_admin_cta_show_page_loads(): void
    {
        $admin = $this->adminWithTwoFactor();
        $cta = \App\Models\Cta::create([
            'text' => 'Test CTA',
            'type' => 'primary',
            'action' => '/contact',
            'priority' => 1,
        ]);

        $this->actingAs($admin)
            ->withSession(['2fa_verified' => true])
            ->get(route('admin.ctas.show', $cta))
            ->assertOk()
            ->assertSee('Test CTA', false);
    }

    public function test_homepage_includes_keywords_meta_from_seo_data(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<meta name="keywords"', false)
            ->assertSee('HMIS Kenya', false);
    }

    public function test_homepage_uses_photography_heroes(): void
    {
        $this->assertFileExists(public_path('images/hero-home.jpg'));
        $this->assertStringContainsString('hero-home.jpg', hero_asset(1));
    }

    public function test_homepage_shows_latest_insights_section_when_blog_exists(): void
    {
        $admin = $this->createUser(['role' => 'admin']);
        Content::create([
            'title' => 'HMIS Guide',
            'slug' => 'hmis-test-post',
            'type' => 'blog',
            'content' => '<p>Guide</p>',
            'excerpt' => 'HMIS',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Latest Guides', false)
            ->assertSee('HMIS Checklist', false);
    }

    public function test_homepage_has_no_testimonial_carousel(): void
    {
        \App\Models\Testimonial::create([
            'client_name' => 'Dr. Test',
            'testimonial' => 'Forefront delivered our HMIS on time.',
            'rating' => 5,
            'status' => 'approved',
            'is_featured' => true,
        ]);

        $html = $this->get(route('home'))->getContent();
        $this->assertStringNotContainsString('Client Testimonials Carousel', $html);
    }

    public function test_homepage_styling_refinements(): void
    {
        Setting::unguard();
        Setting::updateOrCreate(['key' => 'founded_year'], ['value' => '2012', 'type' => 'text', 'category' => 'general']);
        Setting::reguard();

        $html = $this->get(route('home'))->assertOk()->getContent();

        $this->assertStringContainsString('btn-ghost-dark', $html);
        $this->assertStringNotContainsString('&amp;amp;', $html);
        $this->assertStringContainsString('HMIS &amp; Digital Transformation Partner', $html);
        $this->assertStringContainsString('HMIS Procurement Checklist', $html);
        $this->assertEquals(0, substr_count($html, 'Ready to start your project?'));
        $this->assertStringContainsString('2012', $html);
        $this->assertStringContainsString('Primary practice', $html);
    }

    public function test_forefront_config_available_without_seeders(): void
    {
        $this->assertNotEmpty(config('forefront.service_lead_config'));
        $this->assertNotEmpty(config('forefront.hero_images'));
    }
}
