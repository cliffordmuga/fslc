<?php

namespace Tests\Feature\Middleware;

use App\Models\Content;
use App\Models\PageAnalytic;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TrackPageViewsTest extends TestCase
{
    use RefreshDatabase;

    private Content $content;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        Setting::unguard();
        Setting::updateOrCreate(['key' => 'company_name'], ['value' => 'Forefront Solutions', 'type' => 'text', 'category' => 'general']);
        Setting::reguard();

        $admin = User::factory()->create(['role' => 'admin']);

        $this->content = Content::create([
            'title' => 'HMIS & Digital Health Solutions',
            'slug' => 'hmis-digital-health-solutions-kenya',
            'type' => 'services',
            'content' => '<p>HMIS vendor Kenya.</p>',
            'excerpt' => 'HMIS for Kenya',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);
    }

    private function url(): string
    {
        return route('services.show', $this->content->slug);
    }

    private function views(): int
    {
        return (int) $this->content->analytics()->sum('views');
    }

    private function uniqueVisitors(): int
    {
        return (int) $this->content->analytics()->sum('unique_visitors');
    }

    public function test_records_a_view_for_an_anonymous_visitor(): void
    {
        $this->get($this->url())->assertOk();

        $this->assertSame(1, $this->views());
        $this->assertSame(1, $this->uniqueVisitors());
    }

    public function test_counts_a_view_served_from_the_full_page_cache(): void
    {
        $this->get($this->url())->assertOk()->assertHeader('X-Page-Cache', 'MISS');
        $this->get($this->url())->assertOk()->assertHeader('X-Page-Cache', 'HIT');

        // Both the cache MISS and the cache HIT must be counted.
        $this->assertSame(2, $this->views());
    }

    public function test_counts_a_unique_visitor_once_per_day(): void
    {
        $this->get($this->url())->assertOk();
        $this->get($this->url())->assertOk();

        $this->assertSame(2, $this->views());
        $this->assertSame(1, $this->uniqueVisitors());
    }

    public function test_does_not_track_authenticated_users(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get($this->url())
            ->assertOk();

        $this->assertSame(0, PageAnalytic::count());
    }

    public function test_does_not_track_non_content_routes(): void
    {
        $this->get('/search?q=hmis')->assertOk();

        $this->assertSame(0, PageAnalytic::count());
    }
}
