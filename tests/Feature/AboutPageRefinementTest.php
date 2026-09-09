<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutPageRefinementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::unguard();
        Setting::updateOrCreate(['key' => 'founded_year'], ['value' => '2012', 'type' => 'text', 'category' => 'general']);
        Setting::updateOrCreate(['key' => 'company_name'], ['value' => 'Forefront Solutions (K) Ltd', 'type' => 'text', 'category' => 'general']);
        Setting::reguard();
    }

    public function test_about_page_refinements(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Content::create([
            'title' => 'HMIS Service',
            'slug' => 'hmis-digital-health-solutions-kenya',
            'type' => 'services',
            'content' => '<p>HMIS</p>',
            'excerpt' => 'HMIS',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);

        Testimonial::create([
            'client_name' => 'Dr. About',
            'testimonial' => 'Forefront delivered our HMIS on time.',
            'rating' => 5,
            'status' => 'approved',
            'is_featured' => true,
        ]);

        $html = $this->get(route('about'))->assertOk()->getContent();

        $this->assertStringContainsString('Healthcare Technology Partner in Kenya', $html);
        $this->assertStringContainsString('hero-h-standard', $html);
        $this->assertStringContainsString('utm_source=about_hero', $html);
        $this->assertStringContainsString('btn-ghost-dark', $html);
        $this->assertStringContainsString('Who we are', $html);
        $this->assertStringContainsString('MOH-Aligned HMIS', $html);
        $this->assertStringContainsString('Kenya Data Protection Act', $html);
        $this->assertStringContainsString('Request HMIS Demo', $html);
        $this->assertStringContainsString('Get HMIS Checklist', $html);
        $this->assertStringNotContainsString('Client Testimonials Carousel', $html);
        $this->assertStringNotContainsString('laravel-certified.png', $html);
        $this->assertStringContainsString('2012', $html);
        $this->assertStringContainsString('Four core service pillars', $html);
    }
}
