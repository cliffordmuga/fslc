<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioPageRefinementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::unguard();
        Setting::updateOrCreate(['key' => 'founded_year'], ['value' => '2012', 'type' => 'text', 'category' => 'general']);
        Setting::reguard();

        $admin = User::factory()->create(['role' => 'admin']);
        foreach (['county-referral-hospital-hmis', 'ngo-community-health-emr', 'government-citizen-services-portal'] as $i => $slug) {
            Content::create([
                'title' => 'Portfolio '.$slug,
                'slug' => $slug,
                'type' => 'portfolio',
                'content' => '<p>Case study</p>',
                'excerpt' => 'Excerpt',
                'status' => 'published',
                'published_at' => now(),
                'sort_order' => $i + 1,
                'created_by' => $admin->id,
            ]);
        }
    }

    public function test_portfolio_hub_refinements(): void
    {
        $html = $this->get(route('portfolio.index'))->assertOk()->getContent();

        $this->assertStringContainsString('Case Studies &amp; Projects', $html);
        $this->assertStringContainsString('btn-ghost-dark', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
        $this->assertStringContainsString('Showing', $html);
        $this->assertStringContainsString('Get HMIS Checklist', $html);
        $this->assertStringNotContainsString('localStorage', $html);
        $this->assertStringNotContainsString('Ready to start your project?', $html);
        $this->assertStringNotContainsString('Client Testimonials Carousel', $html);
    }

    public function test_portfolio_pillar_filter_shows_result_count(): void
    {
        $html = $this->get(route('portfolio.index', ['pillar' => 'hmis']))->assertOk()->getContent();

        $this->assertStringContainsString('HMIS &amp; Digital Health', $html);
        $this->assertStringContainsString('Showing 2 case studies', $html);
    }

    public function test_invalid_pillar_defaults_to_all(): void
    {
        $this->get(route('portfolio.index', ['pillar' => 'invalid']))->assertOk();
    }
}
