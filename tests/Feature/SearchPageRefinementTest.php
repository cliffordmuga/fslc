<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchPageRefinementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::unguard();
        Setting::updateOrCreate(['key' => 'company_name'], ['value' => 'Forefront Solutions', 'type' => 'text', 'category' => 'general']);
        Setting::reguard();

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_empty_search_shows_guided_state(): void
    {
        $html = $this->get(route('search'))
            ->assertOk()
            ->assertSee('What are you looking for?', false)
            ->assertSee('Popular searches', false)
            ->assertSee('HMIS Kenya', false)
            ->assertDontSee('Search Results for', false)
            ->getContent();

        $this->assertStringContainsString('href="' . route('search', ['q' => 'HMIS Kenya']) . '"', $html);
    }

    public function test_search_page_uses_hmis_footer_without_duplicate_whatsapp(): void
    {
        $html = $this->get(route('search', ['q' => 'xyznonexistent999']))
            ->assertOk()
            ->assertSee('Request HMIS Demo', false)
            ->assertSee('Get HMIS Checklist', false)
            ->getContent();

        $this->assertStringNotContainsString('Chat with us on WhatsApp – We\'ll help immediately', $html);
        $this->assertStringNotContainsString('Chat on WhatsApp – Get personalized help', $html);
        $this->assertStringNotContainsString('Get a Free Project Quote', $html);
    }

    public function test_short_query_dev_matches_like_fallback(): void
    {
        Content::create([
            'title' => 'Web Development Best Practices',
            'slug' => 'web-development-best-practices',
            'type' => 'blog',
            'content' => '<p>Guide to web dev in Kenya.</p>',
            'excerpt' => 'Web development guide',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $this->admin->id,
        ]);

        $this->get(route('search', ['q' => 'dev']))
            ->assertOk()
            ->assertSee('Web Development Best Practices', false)
            ->assertSee('<mark', false);
    }

    public function test_search_excludes_internal_content_types(): void
    {
        Content::create([
            'title' => 'Dev Internal FAQ',
            'slug' => 'dev-internal-faq',
            'type' => 'faq_item',
            'content' => '<p>dev content</p>',
            'excerpt' => 'dev faq',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $this->admin->id,
        ]);

        Content::create([
            'title' => 'Custom Dev Services',
            'slug' => 'custom-dev-services',
            'type' => 'services',
            'content' => '<p>dev services</p>',
            'excerpt' => 'dev services',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 2,
            'created_by' => $this->admin->id,
        ]);

        $this->get(route('search', ['q' => 'dev']))
            ->assertOk()
            ->assertSee('Custom Dev Services', false)
            ->assertDontSee('Dev Internal FAQ', false);
    }

    public function test_search_shows_total_count_when_paginated(): void
    {
        $term = 'paginatemarker';

        for ($i = 1; $i <= 16; $i++) {
            Content::create([
                'title' => "Paginate Marker Item {$i}",
                'slug' => "paginate-marker-item-{$i}",
                'type' => 'portfolio',
                'content' => "<p>{$term} content {$i}</p>",
                'excerpt' => "{$term} excerpt",
                'status' => 'published',
                'published_at' => now()->subDays($i),
                'sort_order' => $i,
                'created_by' => $this->admin->id,
            ]);
        }

        $this->get(route('search', ['q' => $term]))
            ->assertOk()
            ->assertSee('Showing 1–15 of 16 results', false)
            ->assertSee('16 matches', false);
    }

    public function test_search_type_filter_limits_results(): void
    {
        $term = 'filtermarker';

        Content::create([
            'title' => 'Filter Marker Portfolio',
            'slug' => 'filter-marker-portfolio',
            'type' => 'portfolio',
            'content' => "<p>{$term}</p>",
            'excerpt' => $term,
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $this->admin->id,
        ]);

        Content::create([
            'title' => 'Filter Marker Insight',
            'slug' => 'filter-marker-insight',
            'type' => 'blog',
            'content' => "<p>{$term}</p>",
            'excerpt' => $term,
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 2,
            'created_by' => $this->admin->id,
        ]);

        $this->get(route('search', ['q' => $term, 'type' => 'blog']))
            ->assertOk()
            ->assertSee('Filter Marker Insight', false)
            ->assertDontSee('Filter Marker Portfolio', false);
    }

    public function test_blog_results_use_insights_hub_label(): void
    {
        Content::create([
            'title' => 'Insights Label Test Post',
            'slug' => 'insights-label-test-post',
            'type' => 'blog',
            'content' => '<p>uniqueinsightslabel content</p>',
            'excerpt' => 'uniqueinsightslabel',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $this->admin->id,
        ]);

        $html = $this->get(route('search', ['q' => 'uniqueinsightslabel']))
            ->assertOk()
            ->assertSee('Insights Label Test Post', false)
            ->getContent();

        $this->assertMatchesRegularExpression('/>\s*Insights\s*</', $html);
    }
}
