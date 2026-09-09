<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsightsPageRefinementTest extends TestCase
{
    use RefreshDatabase;

    public function test_insights_hub_refinements(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tag = Tag::create(['name' => 'HMIS', 'slug' => 'hmis']);

        Content::create([
            'title' => 'HMIS Guide',
            'slug' => 'hmis-insights-test',
            'type' => 'blog',
            'content' => '<p>Body</p>',
            'excerpt' => 'HMIS',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ])->tags()->attach($tag);

        $html = $this->get(route('insights.index'))->assertOk()->getContent();

        $this->assertStringContainsString('Insights &amp; Guides', $html);
        $this->assertStringContainsString('btn-ghost-dark', $html);
        $this->assertStringContainsString('Search all insights', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
        $this->assertStringContainsString('Showing', $html);
        $this->assertStringContainsString('Request HMIS Demo', $html);
        $this->assertStringContainsString('Get HMIS Checklist', $html);
        $this->assertStringContainsString('HMIS Procurement Checklist', $html);
        $this->assertStringContainsString('Explore our services', $html);
        $this->assertStringContainsString('See case studies', $html);
        $this->assertStringNotContainsString('localStorage', $html);
        $this->assertStringNotContainsString('Ready to start your project?', $html);
        $this->assertStringNotContainsString('All Insights', $html);
    }

    public function test_insights_category_filter_shows_result_context(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tag = Tag::create(['name' => 'HMIS', 'slug' => 'hmis']);

        Content::create([
            'title' => 'HMIS Filter Post',
            'slug' => 'hmis-filter-post',
            'type' => 'blog',
            'content' => '<p>Body</p>',
            'excerpt' => 'HMIS',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ])->tags()->attach($tag);

        $this->get(route('insights.index', ['category' => 'hmis']))
            ->assertOk()
            ->assertSee('HMIS', false)
            ->assertSee('Showing', false);
    }

    public function test_hmis_tag_links_to_tag_hub(): void
    {
        Tag::create(['name' => 'HMIS', 'slug' => 'hmis']);
        $admin = User::factory()->create(['role' => 'admin']);
        Content::create([
            'title' => 'Post',
            'slug' => 'post-with-hmis-tag',
            'type' => 'blog',
            'content' => '<p>Body</p>',
            'excerpt' => 'Excerpt',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ])->tags()->sync([Tag::first()->id]);

        $html = $this->get(route('insights.index'))->getContent();
        $this->assertStringContainsString(route('tags.show', 'hmis'), $html);
    }
}
