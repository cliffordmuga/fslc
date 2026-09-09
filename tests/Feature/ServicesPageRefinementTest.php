<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesPageRefinementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::unguard();
        Setting::updateOrCreate(['key' => 'founded_year'], ['value' => '2012', 'type' => 'text', 'category' => 'general']);
        Setting::reguard();

        $admin = User::factory()->create(['role' => 'admin']);
        Content::create([
            'title' => 'HMIS & Digital Health Solutions',
            'slug' => 'hmis-digital-health-solutions-kenya',
            'type' => 'services',
            'content' => '<p>HMIS</p>',
            'excerpt' => 'HMIS vendor Kenya',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);
    }

    public function test_services_hub_refinements(): void
    {
        $html = $this->get(route('services.index'))->assertOk()->getContent();

        $this->assertStringContainsString('btn-ghost-dark', $html);
        $this->assertStringContainsString('Primary practice', $html);
        $this->assertStringNotContainsString('Primary revenue practice', $html);
        $this->assertStringContainsString('How we deliver', $html);
        $this->assertStringContainsString('HMIS Procurement Checklist', $html);
        $this->assertStringContainsString('Get HMIS Checklist', $html);
        $this->assertStringContainsString('case studies', $html);
        $this->assertStringContainsString('2012', $html);
        $this->assertStringNotContainsString('Ready to start your project?', $html);
        $this->assertStringNotContainsString('Client Testimonials Carousel', $html);
    }
}
