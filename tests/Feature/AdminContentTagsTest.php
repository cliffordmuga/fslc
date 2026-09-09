<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentTagsTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): self
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
            'google2fa_secret' => 'TESTSECRETKEY000',
            'two_factor_recovery_codes' => ['a', 'b'],
        ]);
        session(['2fa_verified' => true]);

        return $this->actingAs($admin);
    }

    public function test_create_form_exposes_tag_checkboxes(): void
    {
        $tag = Tag::create(['name' => 'HMIS']);

        $this->actingAsAdmin()
            ->get(route('admin.content.create'))
            ->assertOk()
            ->assertSee('name="tags[]"', false)
            ->assertSee('name="new_tags"', false)
            ->assertSee('HMIS');
    }

    public function test_store_attaches_selected_and_newly_named_tags(): void
    {
        $existing = Tag::create(['name' => 'Laravel']);

        $this->actingAsAdmin()
            ->post(route('admin.content.store'), [
                'title' => 'Tagged Article',
                'type' => 'blog',
                'status' => 'published',
                'tags' => [$existing->id],
                'new_tags' => 'SHA Integration, Digital Health',
            ])
            ->assertRedirect(route('admin.content.index'));

        $content = Content::firstWhere('title', 'Tagged Article');

        $this->assertEqualsCanonicalizing(
            ['Laravel', 'SHA Integration', 'Digital Health'],
            $content->tags->pluck('name')->all(),
        );
        $this->assertDatabaseHas('tags', ['slug' => 'sha-integration']);
    }

    public function test_new_tag_names_are_deduplicated_against_existing_slugs(): void
    {
        Tag::create(['name' => 'HMIS']);

        $this->actingAsAdmin()
            ->post(route('admin.content.store'), [
                'title' => 'Dedupe Article',
                'type' => 'blog',
                'status' => 'published',
                'new_tags' => 'hmis, HMIS, Hmis',
            ])
            ->assertRedirect();

        $this->assertSame(1, Tag::where('slug', 'hmis')->count());
        $this->assertSame(1, Content::firstWhere('title', 'Dedupe Article')->tags()->count());
    }

    public function test_update_replaces_the_tag_set(): void
    {
        $a = Tag::create(['name' => 'Alpha']);
        $b = Tag::create(['name' => 'Beta']);

        $content = Content::create([
            'title' => 'Editable',
            'slug' => 'editable',
            'type' => 'blog',
            'status' => 'published',
            'published_at' => now(),
            'created_by' => User::factory()->create()->id,
        ]);
        $content->tags()->attach($a->id);

        $this->actingAsAdmin()
            ->put(route('admin.content.update', $content), [
                'title' => 'Editable',
                'slug' => 'editable',
                'type' => 'blog',
                'status' => 'published',
                'tags' => [$b->id],
            ])
            ->assertRedirect(route('admin.content.index'));

        $this->assertSame(['Beta'], $content->fresh()->tags->pluck('name')->all());
    }

    public function test_update_with_no_tags_detaches_all(): void
    {
        $tag = Tag::create(['name' => 'Solo']);
        $content = Content::create([
            'title' => 'Clears',
            'slug' => 'clears',
            'type' => 'blog',
            'status' => 'published',
            'published_at' => now(),
            'created_by' => User::factory()->create()->id,
        ]);
        $content->tags()->attach($tag->id);

        $this->actingAsAdmin()
            ->put(route('admin.content.update', $content), [
                'title' => 'Clears',
                'slug' => 'clears',
                'type' => 'blog',
                'status' => 'published',
            ])
            ->assertRedirect();

        $this->assertSame(0, $content->fresh()->tags()->count());
    }
}
