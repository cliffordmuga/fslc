<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::firstOrCreate(['key' => 'company_name'], ['value' => 'Test', 'type' => 'text', 'category' => 'general']);
        Setting::firstOrCreate(['key' => 'site_description'], ['value' => 'Test', 'type' => 'text', 'category' => 'seo']);
    }

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
