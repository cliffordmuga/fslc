<?php

namespace Tests\Feature;

use App\Models\Cta;
use App\Models\Testimonial;
use App\Services\CacheBuster;
use App\Services\ContentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheBustObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_cta_save_bumps_content_cache_buster(): void
    {
        $tokenBefore = (int) Cache::get('content:cache_buster', 0);
        $publicBefore = CacheBuster::current();

        Cta::create([
            'text' => 'Request HMIS Demo',
            'type' => 'primary',
            'action' => route('contact'),
            'priority' => 10,
        ]);

        $tokenAfter = (int) Cache::get('content:cache_buster', 0);
        $this->assertGreaterThan($tokenBefore, $tokenAfter);
        $this->assertNotSame($publicBefore, CacheBuster::current());
    }

    public function test_testimonial_save_bumps_content_cache_buster(): void
    {
        $tokenBefore = (int) Cache::get('content:cache_buster', 0);
        $publicBefore = CacheBuster::current();

        Testimonial::factory()->create([
            'client_name' => 'County Hospital',
            'testimonial' => 'Excellent HMIS rollout.',
            'status' => 'approved',
            'is_featured' => true,
        ]);

        $tokenAfter = (int) Cache::get('content:cache_buster', 0);
        $this->assertGreaterThan($tokenBefore, $tokenAfter);
        $this->assertNotSame($publicBefore, CacheBuster::current());
    }

    public function test_cta_update_invalidates_cached_page_cta(): void
    {
        $service = app(ContentService::class);

        $cta = Cta::create([
            'text' => 'Old CTA',
            'type' => 'primary',
            'action' => route('contact'),
            'priority' => 100,
            'content_id' => null,
        ]);

        $first = $service->getCtaForPage('home');
        $this->assertSame('Old CTA', $first?->text);

        $cta->update(['text' => 'New HMIS Demo CTA']);

        $second = $service->getCtaForPage('home');
        $this->assertSame('New HMIS Demo CTA', $second?->text);
    }
}
