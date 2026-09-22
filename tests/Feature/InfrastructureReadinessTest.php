<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InfrastructureReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function test_forwarded_proto_is_trusted_so_https_is_detected(): void
    {
        // trustProxies defaults to '*'; behind a proxy Laravel must honour
        // X-Forwarded-Proto or ForceHttps loops and secure cookies break.
        $this->withServerVariables([
            'HTTP_X_FORWARDED_PROTO' => 'https',
            'HTTP_X_FORWARDED_FOR' => '203.0.113.9',
            'REMOTE_ADDR' => '10.0.0.1',
        ])->get('/health');

        $request = $this->app['request'];

        $this->assertTrue($request->isSecure());
        $this->assertSame('203.0.113.9', $request->ip());
    }

    public function test_cache_prune_stale_deletes_only_expired_rows(): void
    {
        Cache::store('database')->put('fresh-key', 'v', now()->addHour());
        DB::table('cache')->insert([
            'key' => config('cache.prefix').'stale-key',
            'value' => serialize('old'),
            'expiration' => now()->subHour()->getTimestamp(),
        ]);

        $this->artisan('cache:prune-stale', ['--store' => 'database'])->assertSuccessful();

        $this->assertSame(0, DB::table('cache')->where('key', 'like', '%stale-key')->count());
        $this->assertSame(1, DB::table('cache')->where('key', 'like', '%fresh-key')->count());
    }

    public function test_cache_prune_stale_is_a_noop_on_non_database_stores(): void
    {
        $this->artisan('cache:prune-stale', ['--store' => 'array'])
            ->expectsOutputToContain('not database-backed')
            ->assertSuccessful();
    }

    public function test_image_optimization_can_be_disabled_by_config(): void
    {
        $this->assertTrue(config('image.optimize')); // default on

        config(['image.optimize' => false]);
        $this->assertFalse(config('image.optimize'));
    }

    public function test_csp_report_tuning_is_read_from_config_not_env(): void
    {
        $this->assertIsInt(config('security.csp_report_sample_percent'));
        $this->assertIsInt(config('security.csp_report_dedupe_seconds'));

        config(['security.csp_report_sample_percent' => 100]);

        $this->postJson('/csp-report', ['csp-report' => ['violated-directive' => 'script-src']])
            ->assertNoContent();
    }
}
