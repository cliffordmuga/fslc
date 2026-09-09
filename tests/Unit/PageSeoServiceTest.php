<?php

namespace Tests\Unit;

use App\Services\PageSeoService;
use Tests\TestCase;

class PageSeoServiceTest extends TestCase
{
    public function test_home_seo_data_uses_kenya_focused_copy(): void
    {
        $seo = app(PageSeoService::class)->getSeoData('home');

        $this->assertStringContainsString('HMIS', $seo['title']);
        $this->assertStringContainsString('Kenya', $seo['title']);
        $this->assertStringContainsString('HMIS', $seo['description']);
    }

    public function test_home_schema_includes_website_type(): void
    {
        $schemas = app(PageSeoService::class)->getPageSchemas('home');

        $this->assertNotEmpty($schemas);
        $this->assertSame('WebSite', $schemas[0]['@type']);
    }
}
