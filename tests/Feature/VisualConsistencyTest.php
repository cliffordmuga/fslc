<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisualConsistencyTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_login_uses_minimal_auth_layout(): void
    {
        $html = $this->get(route('login'))->assertOk()->getContent();

        $this->assertStringContainsString('Back to site', $html);
        $this->assertStringContainsString('card-base', $html);
        $this->assertStringNotContainsString('Chat on WhatsApp', $html);
        $this->assertStringNotContainsString('Get Started', $html);
    }

    public function test_nav_uses_hmis_primary_cta(): void
    {
        $html = $this->get(route('home'))->assertOk()->getContent();

        $this->assertStringContainsString('Request HMIS Demo', $html);
        $this->assertStringNotContainsString('Trusted by 40+ brands', $html);
    }

    public function test_tag_hub_uses_refined_patterns(): void
    {
        $tag = Tag::create(['name' => 'HMIS', 'slug' => 'hmis']);

        Content::create([
            'title' => 'HMIS Tag Test Article',
            'slug' => 'hmis-tag-test-article',
            'type' => 'blog',
            'content' => '<p>HMIS content</p>',
            'excerpt' => 'HMIS',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $this->admin->id,
        ])->tags()->attach($tag);

        $this->get(route('tags.show', 'hmis'))
            ->assertOk()
            ->assertSee('Get HMIS Checklist', false)
            ->assertSee('hero-h-standard', false)
            ->assertDontSee('Get a Free Quote', false);
    }

    public function test_legal_page_uses_hmis_footer_and_prose_custom(): void
    {
        $html = $this->get(route('privacy'))->assertOk()->getContent();

        $this->assertStringContainsString('prose-custom', $html);
        $this->assertStringContainsString('Request HMIS Demo', $html);
        $this->assertStringContainsString('hero-h-standard', $html);
        $this->assertStringNotContainsString('hero-h-home', $html);
    }

    public function test_detail_page_uses_standard_hero_band(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Content::create([
            'title' => 'Hero Uniformity Service',
            'slug' => 'hero-uniformity-service',
            'type' => 'services',
            'content' => '<p>Service body</p>',
            'excerpt' => 'Uniform hero test',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);

        $this->get(route('services.show', 'hero-uniformity-service'))
            ->assertOk()
            ->assertSee('hero-h-standard', false)
            ->assertDontSee('hero-h-home', false);
    }

    public function test_sitemap_uses_sharp_hub_patterns(): void
    {
        $html = $this->get(route('sitemap'))->assertOk()->getContent();

        $this->assertStringContainsString('card-base', $html);
        $this->assertStringContainsString('Request HMIS Demo', $html);
        $this->assertStringNotContainsString('rounded-xl', $html);
    }

    public function test_homepage_hero_uses_home_height_class(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('hero-h-home', false);
    }
}
