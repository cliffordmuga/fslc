<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DetailsPageRefinementTest extends TestCase
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

    public function test_service_detail_uses_hmis_footer_and_no_carousel(): void
    {
        Content::create([
            'title' => 'HMIS & Digital Health Solutions',
            'slug' => 'hmis-digital-health-solutions-kenya',
            'type' => 'services',
            'content' => '<p>HMIS vendor Kenya.</p>',
            'excerpt' => 'HMIS for Kenya',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $this->admin->id,
        ]);

        $html = $this->get(route('services.show', 'hmis-digital-health-solutions-kenya'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('Request HMIS Demo', $html);
        $this->assertStringContainsString('Get HMIS Checklist', $html);
        $this->assertStringContainsString('Download HMIS Checklist', $html);
        $this->assertStringContainsString('prose-custom', $html);
        $this->assertStringContainsString('services-hero.jpg', $html);
        $this->assertStringNotContainsString('testimonial-carousel', $html);
        $this->assertStringNotContainsString('Client Testimonials Carousel', $html);
        $this->assertStringContainsString('12+', $html);
        $this->assertStringContainsString('HMIS modules', $html);
    }

    public function test_portfolio_hmis_detail_uses_hmis_demo_inquiry(): void
    {
        Content::create([
            'title' => 'County Referral Hospital HMIS Deployment',
            'slug' => 'county-referral-hospital-hmis',
            'type' => 'portfolio',
            'content' => '<p>Case study body.</p>',
            'excerpt' => 'HMIS deployment',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $this->admin->id,
        ]);

        $html = $this->get(route('portfolio.show', 'county-referral-hospital-hmis'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('Request HMIS Demo', $html);
        $this->assertStringContainsString('inquiry_type=hmis-demo', $html);
        $this->assertStringContainsString('Get HMIS Checklist', $html);
        $this->assertStringContainsString('Daily OPD visits', $html);
        $this->assertStringNotContainsString('details-process-heading', $html);
        $this->assertStringContainsString('Explore our services', $html);
        $this->assertStringContainsString('Read insights', $html);
    }

    public function test_blog_detail_shows_reading_time_and_insights_labels(): void
    {
        $tag = Tag::create(['name' => 'HMIS', 'slug' => 'hmis']);
        $post = Content::create([
            'title' => 'HMIS Procurement Guide for Kenya Hospitals',
            'slug' => 'hmis-procurement-guide-test',
            'type' => 'blog',
            'content' => '<h2>Section One</h2><p>' . str_repeat('word ', 400) . '</p>'
                . '<h2>Section Two</h2><p>More content.</p>'
                . '<h2>Section Three</h2><p>Final section.</p>',
            'excerpt' => 'Procurement guide',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $this->admin->id,
        ]);
        $post->tags()->attach($tag->id);

        $html = $this->get(route('insights.show', 'hmis-procurement-guide-test'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('min read', $html);
        $this->assertStringContainsString('In this article', $html);
        $this->assertStringContainsString('prose-custom', $html);
        $this->assertStringContainsString('blog-hero.jpg', $html);
        $this->assertStringContainsString('"name":"Insights"', $html);
        $this->assertStringNotContainsString('"name":"Blog"', $html);
        $this->assertStringContainsString('HMIS Procurement Checklist', $html);
    }

    public function test_attached_testimonial_renders_without_featured_filter(): void
    {
        $service = Content::create([
            'title' => 'Custom Software Development',
            'slug' => 'custom-software-web-development-kenya',
            'type' => 'services',
            'content' => '<p>Software services.</p>',
            'excerpt' => 'Software',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $this->admin->id,
        ]);

        $testimonial = Testimonial::create([
            'client_name' => 'Jane Doe',
            'testimonial' => 'Forefront delivered on time and within budget for our portal project.',
            'status' => 'approved',
            'is_featured' => false,
            'rating' => 5,
        ]);

        $service->testimonials()->attach($testimonial->id, ['sort_order' => 1]);

        $html = $this->get(route('services.show', 'custom-software-web-development-kenya'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('Jane Doe', $html);
        $this->assertStringContainsString('What Clients Say', $html);
        $this->assertStringNotContainsString('testimonial-carousel', $html);
    }

    public function test_hero_uses_photography_not_duplicate_featured_in_banner(): void
    {
        Content::create([
            'title' => 'HMIS Service',
            'slug' => 'hmis-digital-health-solutions-kenya',
            'type' => 'services',
            'content' => '<p>Body</p>',
            'excerpt' => 'Excerpt',
            'status' => 'published',
            'published_at' => now(),
            'sort_order' => 1,
            'created_by' => $this->admin->id,
        ]);

        $html = $this->get(route('services.show', 'hmis-digital-health-solutions-kenya'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('opacity-[0.38]', $html);
        $this->assertStringContainsString('services-hero.jpg', $html);
    }
}
