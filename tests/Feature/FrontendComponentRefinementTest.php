<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendComponentRefinementTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_detail_suppresses_hero_cta(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Content::create([
            'title' => 'HMIS Guide Article',
            'slug' => 'hmis-guide-article-refinement',
            'type' => 'blog',
            'content' => '<p>Guide body</p>',
            'excerpt' => 'HMIS guide',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);

        $html = $this->get(route('insights.show', 'hmis-guide-article-refinement'))
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString('btn-ghost-dark', $html);
        $this->assertStringContainsString('Next Step', $html);
    }

    public function test_hub_shell_renders_on_portfolio(): void
    {
        $this->get(route('portfolio.index'))
            ->assertOk()
            ->assertSee('hero-h-standard', false)
            ->assertSee('Case Studies', false);
    }

    public function test_hub_cta_url_helper_builds_contact_link(): void
    {
        $url = hub_cta_url('test_context');
        $this->assertStringContainsString('utm_source=test_context', $url);
        $this->assertStringContainsString('inquiry_type=hmis-demo', $url);
        $this->assertStringContainsString('#contact-form', $url);
    }

    public function test_responsive_image_uses_sharp_corners(): void
    {
        $html = file_get_contents(resource_path('views/components/cms/responsive-image.blade.php'));
        $this->assertStringNotContainsString('rounded-t-lg', $html);
    }

    public function test_standard_heroes_share_homepage_vertical_spacing(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertStringContainsString('.hero-inner', $css);
        $this->assertStringNotContainsString('--hero-standard-h', $css);
        $this->assertStringNotContainsString('max-height: var(--hero-standard', $css);

        $html = $this->get(route('services.index'))->assertOk()->getContent();

        $this->assertStringContainsString('hero-inner', $html);
        $this->assertStringContainsString('hero-h-standard', $html);
    }
}
