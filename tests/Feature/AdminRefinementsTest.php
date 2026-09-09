<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Support\AdminUiCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AdminRefinementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect();
    }

    public function test_lead_observer_clears_admin_new_leads_cache(): void
    {
        Cache::put(AdminUiCache::NEW_LEADS_KEY, 99, 3600);
        $this->assertTrue(Cache::has(AdminUiCache::NEW_LEADS_KEY));

        Lead::create([
            'name' => 'Cache Test',
            'email' => 'cache-test@example.com',
            'message' => 'Message',
            'inquiry_type' => 'general',
            'status' => 'new',
            'is_spam' => false,
        ]);

        $this->assertFalse(Cache::has(AdminUiCache::NEW_LEADS_KEY));
    }

    public function test_public_page_cache_sets_cache_control_when_configured(): void
    {
        config(['public_page_cache.browser_max_age_seconds' => 120]);

        $response = $this->get('/');

        $this->assertTrue($response->headers->has('X-Page-Cache'));
        $cc = (string) $response->headers->get('Cache-Control', '');
        $this->assertStringContainsString('max-age=120', $cc);
        $this->assertStringContainsString('public', $cc);
    }
}
