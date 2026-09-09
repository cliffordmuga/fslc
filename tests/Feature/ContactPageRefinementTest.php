<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPageRefinementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::unguard();
        Setting::updateOrCreate(['key' => 'company_name'], ['value' => 'Forefront Solutions', 'type' => 'text', 'category' => 'general']);
        Setting::updateOrCreate(['key' => 'phone'], ['value' => '+254700000000', 'type' => 'text', 'category' => 'general']);
        Setting::reguard();
    }

    public function test_contact_page_hub_refinements(): void
    {
        $html = $this->get(route('contact'))->assertOk()->getContent();

        $this->assertStringContainsString('contact-hero.jpg', $html);
        $this->assertStringContainsString('HMIS', $html);
        $this->assertStringContainsString('Which pillar fits your project?', $html);
        $this->assertStringContainsString('Get HMIS Checklist', $html);
        $this->assertStringNotContainsString('Limited spots available', $html);
        $this->assertStringNotContainsString('process-timeline', $html);
        $this->assertStringContainsString('contact-hero.jpg', $html);
    }

    public function test_hmis_demo_contact_shows_pillar_headline(): void
    {
        $this->get(route('contact', ['inquiry_type' => 'hmis-demo']))
            ->assertOk()
            ->assertSee('Request Your HMIS Demo', false)
            ->assertSee('Facility', false)
            ->assertSee('inquiry_type=hmis-demo', false);
    }

    public function test_hmis_checklist_contact_single_step_form(): void
    {
        $html = $this->get(route('contact', ['inquiry_type' => 'hmis-checklist']))
            ->assertOk()
            ->assertSee('Get the HMIS Procurement Checklist', false)
            ->assertSee('Send Checklist', false)
            ->getContent();

        $this->assertStringContainsString('HMIS Procurement Checklist', $html);
        $this->assertStringContainsString('contactFormApp', $html);
        $this->assertMatchesRegularExpression('/singleStep[^,]*true/', $html);
    }

    public function test_contact_form_accepts_hmis_demo_inquiry_type(): void
    {
        $response = $this->postJson(route('contact.store'), [
            'name' => 'Dr. Test User',
            'email' => 'doctor@hospital.ke',
            'message' => 'We need an HMIS demo for our county referral hospital.',
            'inquiry_type' => 'hmis-demo',
            '_form_rendered_at' => time() - 10,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('leads', [
            'email' => 'doctor@hospital.ke',
            'inquiry_type' => 'hmis-demo',
        ]);
    }

    public function test_contact_form_accepts_hmis_checklist_inquiry_type(): void
    {
        $response = $this->postJson(route('contact.store'), [
            'name' => 'Procurement Lead',
            'email' => 'procurement@hospital.ke',
            'inquiry_type' => 'hmis-checklist',
            '_form_rendered_at' => time() - 10,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('leads', [
            'email' => 'procurement@hospital.ke',
            'inquiry_type' => 'hmis-checklist',
        ]);
    }

    public function test_invalid_inquiry_type_normalizes_to_general(): void
    {
        $this->postJson(route('contact.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'message' => 'Hello',
            'inquiry_type' => 'invalid-type',
            '_form_rendered_at' => time() - 10,
        ])->assertOk();

        $this->assertDatabaseHas('leads', [
            'email' => 'test@example.com',
            'inquiry_type' => 'general',
        ]);
    }

    public function test_form_success_shows_inline_confirmation(): void
    {
        $this->get(route('contact', [
            'utm_source' => 'form_success',
            'inquiry_type' => 'hmis-demo',
        ]))
            ->assertOk()
            ->assertSee('demo scheduling call', false);
    }

    public function test_pillar_inquiry_types_are_registered_on_lead_model(): void
    {
        $this->assertArrayHasKey('hmis-demo', Lead::INQUIRY_TYPES);
        $this->assertArrayHasKey('hmis-checklist', Lead::INQUIRY_TYPES);
        $this->assertArrayHasKey('software-quote', Lead::INQUIRY_TYPES);
    }
}
