<?php

namespace Tests\Unit;

use App\Models\Content;
use App\Models\PageAnalytic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageAnalyticForDayTest extends TestCase
{
    use RefreshDatabase;

    private int $contentId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contentId = Content::create([
            'title' => 'Probe',
            'slug' => 'probe',
            'type' => 'page',
            'status' => 'published',
            'published_at' => now(),
            'created_by' => User::factory()->create()->id,
        ])->id;
    }

    public function test_creates_the_row_once_and_returns_it_on_repeat_calls(): void
    {
        $today = now()->toDateString();

        $a = PageAnalytic::forDay($this->contentId, $today);
        $b = PageAnalytic::forDay($this->contentId, $today);

        $this->assertTrue($a->is($b));
        $this->assertSame(1, PageAnalytic::where('content_id', $this->contentId)->count());
    }

    public function test_repeated_increments_accumulate_on_the_same_row(): void
    {
        $today = now()->toDateString();

        PageAnalytic::forDay($this->contentId, $today)->increment('views');
        PageAnalytic::forDay($this->contentId, $today)->increment('leads_generated');
        PageAnalytic::forDay($this->contentId, $today)->increment('views');

        $row = PageAnalytic::firstWhere('content_id', $this->contentId);

        $this->assertSame(2, (int) $row->views);
        $this->assertSame(1, (int) $row->leads_generated);
    }
}
