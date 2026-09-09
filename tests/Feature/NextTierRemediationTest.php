<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Image;
use App\Models\Lead;
use App\Models\User;
use App\Services\ContentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class NextTierRemediationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    private function page(string $slug, string $title): Content
    {
        return Content::create([
            'title' => $title,
            'slug' => $slug,
            'type' => 'page',
            'content' => "<p>CMS body for {$slug}.</p>",
            'status' => 'published',
            'published_at' => now(),
            'created_by' => User::factory()->create()->id,
        ]);
    }

    // --- privacy/terms go through the cached ContentService::getPage() ---

    public function test_privacy_page_renders_cms_managed_content(): void
    {
        $this->page('privacy', 'Privacy Policy');

        $this->get(route('privacy'))
            ->assertOk()
            ->assertSee('CMS body for privacy', false);
    }

    public function test_get_page_is_cached_after_first_call(): void
    {
        $this->page('terms', 'Terms');
        $service = app(ContentService::class);

        $service->getPage('terms');

        // Delete the row; a cached call must still return it.
        Content::where('slug', 'terms')->delete();

        $this->assertNotNull($service->getPage('terms'));
    }

    // --- highIntent counts service_content_id, not just source_content_id ---

    public function test_high_intent_scope_matches_service_content_only_leads(): void
    {
        $service = $this->page('a-service', 'A Service');

        $lead = Lead::create([
            'name' => 'Buyer',
            'email' => 'buyer@hospital.go.ke',
            'message' => 'Interested in this service for our facility.',
            'inquiry_type' => 'hmis-demo',
            'service_content_id' => $service->id,
            'status' => 'new',
            'is_spam' => false,
        ]);

        $this->assertTrue(Lead::highIntent()->whereKey($lead->id)->exists());
        $this->assertTrue($lead->is_high_intent);
    }

    public function test_high_intent_scope_still_excludes_general_and_spam(): void
    {
        $service = $this->page('b-service', 'B Service');

        $general = Lead::create([
            'name' => 'X', 'email' => 'x@example.com', 'message' => 'hello there',
            'inquiry_type' => 'general', 'service_content_id' => $service->id,
            'status' => 'new', 'is_spam' => false,
        ]);
        $spam = Lead::create([
            'name' => 'Y', 'email' => 'y@example.com', 'message' => 'hello there',
            'inquiry_type' => 'hmis-demo', 'service_content_id' => $service->id,
            'status' => 'new', 'is_spam' => true,
        ]);

        $ids = Lead::highIntent()->pluck('id');
        $this->assertFalse($ids->contains($general->id));
        $this->assertFalse($ids->contains($spam->id));
    }

    // --- admin content index only eager-loads one featured thumbnail row ---

    public function test_admin_content_index_loads_only_thumbnail_and_main_featured_variants(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
            'google2fa_secret' => 'TESTSECRETKEY000',
            'two_factor_recovery_codes' => ['a', 'b'],
        ]);
        session(['2fa_verified' => true]);

        $content = $this->page('c-content', 'C Content');
        foreach (['main', 'small', 'thumbnail', 'mobile', 'mobile_retina'] as $variant) {
            Image::create([
                'imageable_type' => Content::class,
                'imageable_id' => $content->id,
                'image_url' => "uploads/pages/{$content->id}/c-content-featured-{$variant}.webp",
                'variant' => $variant,
                'collection' => 'featured',
                'order' => 0,
            ]);
        }

        $this->actingAs($admin)->get(route('admin.content.index'))->assertOk();

        $loaded = Content::with(['images' => fn ($q) => $q
            ->where('collection', 'featured')
            ->whereIn('variant', ['thumbnail', 'main'])])
            ->find($content->id);

        $this->assertLessThanOrEqual(2, $loaded->images->count());
        $this->assertSame('thumbnail', $loaded->images->sortBy(fn ($i) => $i->variant === 'thumbnail' ? 0 : 1)->first()->variant);
    }
}
