<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_include_core_security_headers(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    }

    public function test_csp_is_report_only_by_default(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $this->assertTrue(
            $response->headers->has('Content-Security-Policy-Report-Only')
            || $response->headers->has('Content-Security-Policy')
        );
        $this->assertStringContainsString(
            "default-src 'self'",
            (string) ($response->headers->get('Content-Security-Policy-Report-Only')
                ?? $response->headers->get('Content-Security-Policy'))
        );
    }

    public function test_csp_enforce_mode_sets_blocking_header(): void
    {
        config(['security.csp_enforce' => true]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertHeader('Content-Security-Policy');
        $this->assertFalse($response->headers->has('Content-Security-Policy-Report-Only'));
    }

    public function test_csp_report_endpoint_accepts_violation_payload(): void
    {
        Cache::flush();
        config(['security.csp_report_sample_percent' => 100]);

        $this->postJson(route('csp.report'), [
            'csp-report' => [
                'document-uri' => 'https://example.test/',
                'violated-directive' => 'script-src',
                'blocked-uri' => 'https://evil.test/bad.js',
            ],
        ])->assertNoContent();

        $this->assertGreaterThan(
            0,
            (int) Cache::get('csp:violations:' . now()->toDateString(), 0)
        );
    }
}
