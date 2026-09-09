<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Lead;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests for the Lead generation-focused Digital Agency CMS.
 */
class CmsLeadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedSettings();
    }

    protected function seedSettings(): void
    {
        Setting::unguard();
        collect([
            ['key' => 'company_name', 'value' => 'Forefront Solutions', 'type' => 'text', 'category' => 'general'],
            ['key' => 'site_description', 'value' => 'Digital Agency CMS', 'type' => 'text', 'category' => 'seo'],
            ['key' => 'email', 'value' => 'hello@example.com', 'type' => 'text', 'category' => 'general'],
            ['key' => 'phone', 'value' => '+254700000000', 'type' => 'text', 'category' => 'general'],
        ])->each(fn ($s) => Setting::firstOrCreate(['key' => $s['key']], $s));
        Setting::reguard();
    }

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_about_page_loads(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
    }

    public function test_contact_page_loads(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
    }

    public function test_portfolio_page_loads(): void
    {
        $response = $this->get('/portfolio');

        $response->assertStatus(200);
    }

    public function test_services_page_loads(): void
    {
        $response = $this->get('/services');

        $response->assertStatus(200);
    }

    public function test_insights_hub_loads_and_blog_redirects(): void
    {
        $this->get('/insights')->assertStatus(200);
        $this->get('/blog')->assertRedirect('/insights');
    }

    public function test_health_endpoint_returns_ok(): void
    {
        $response = $this->get('/health');

        $response->assertStatus(200);
    }

    public function test_lead_submission_creates_lead(): void
    {
        $response = $this->post('/leads', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+254711222333',
            'message' => 'Interested in your web design services.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'email' => 'jane@example.com',
            'name' => 'Jane Doe',
        ]);
    }

    public function test_lead_submission_via_contact_form(): void
    {
        $response = $this->post('/contact', [
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'message' => 'I would like a quote for a new website.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_lead_validation_requires_name_email_message(): void
    {
        $response = $this->postJson('/leads', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'message']);
    }

    public function test_honeypot_rejects_spam_silently(): void
    {
        $response = $this->post('/leads', [
            'name' => 'Spammer',
            'email' => 'spam@bot.com',
            'message' => 'Buy crypto',
            'website_url' => 'https://spam.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('leads', ['email' => 'spam@bot.com']);
    }

    public function test_portfolio_detail_loads_when_content_exists(): void
    {
        $content = Content::create([
            'title' => 'Sample Project',
            'slug' => 'sample-project',
            'type' => 'portfolio',
            'excerpt' => 'A sample project.',
            'content' => '<p>Project description.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get("/portfolio/{$content->slug}");

        $response->assertStatus(200);
    }

    public function test_blog_detail_loads_when_content_exists(): void
    {
        $content = Content::create([
            'title' => 'Sample Post',
            'slug' => 'sample-post',
            'type' => 'blog',
            'excerpt' => 'A sample blog post.',
            'content' => '<p>Blog post content.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get("/insights/{$content->slug}");

        $response->assertStatus(200);
    }

    public function test_search_page_loads(): void
    {
        $response = $this->get('/search');

        $response->assertStatus(200);
    }

    public function test_sitemap_xml_is_accessible(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
    }

    public function test_privacy_page_loads(): void
    {
        $response = $this->get('/privacy');

        $response->assertStatus(200);
    }

    public function test_terms_page_loads(): void
    {
        $response = $this->get('/terms');

        $response->assertStatus(200);
    }
}
