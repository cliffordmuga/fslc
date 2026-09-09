<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_generate_command_succeeds(): void
    {
        $this->artisan('sitemap:generate')
            ->assertExitCode(0);

        $this->assertFileExists(public_path('sitemap.xml'));
        $this->assertStringContainsString('<?xml', (string) file_get_contents(public_path('sitemap.xml')));
    }
}
