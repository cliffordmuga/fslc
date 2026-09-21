<?php

namespace Tests\Feature;

use App\Jobs\RegenerateSitemapJob;
use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ContentObserverSitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_unpublishing_content_regenerates_sitemap(): void
    {
        $admin = User::factory()->createOne(['role' => 'admin']);

        $content = Content::create([
            'title' => 'Legacy Service',
            'slug' => 'legacy-service',
            'type' => 'services',
            'content' => '<p>Legacy</p>',
            'excerpt' => 'Legacy',
            'status' => 'published',
            'published_at' => now(),
            'created_by' => $admin->id,
        ]);

        Queue::fake();

        $content->update(['status' => 'draft']);

        Queue::assertPushed(RegenerateSitemapJob::class);
    }

    public function test_saving_unrelated_field_on_draft_content_does_not_regenerate_sitemap(): void
    {
        $admin = User::factory()->createOne(['role' => 'admin']);

        $content = Content::create([
            'title' => 'Draft Only',
            'slug' => 'draft-only',
            'type' => 'services',
            'content' => '<p>Draft</p>',
            'excerpt' => 'Draft',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        Queue::fake();

        $content->update(['excerpt' => 'Updated excerpt']);

        Queue::assertNotPushed(RegenerateSitemapJob::class);
    }
}
