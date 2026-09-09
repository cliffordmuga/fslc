<?php

namespace Tests\Unit;

use App\Services\CacheBuster;
use App\Services\ContentCacheManager;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ContentCacheManagerTest extends TestCase
{
    public function test_make_cache_key_changes_when_bust_token_bumps(): void
    {
        $manager = app(ContentCacheManager::class);

        $before = $manager->makeCacheKey('demo', ['type' => 'blog']);

        CacheBuster::bump();

        $after = $manager->makeCacheKey('demo', ['type' => 'blog']);

        $this->assertNotSame($before, $after);
    }

    public function test_remember_stores_and_returns_value(): void
    {
        $manager = app(ContentCacheManager::class);
        $key = $manager->makeCacheKey('unit_test', ['n' => 1]);

        $value = $manager->remember($key, 60, fn () => 'cached-payload', ['content:blog']);

        $this->assertSame('cached-payload', $value);
        $this->assertSame('cached-payload', Cache::get($key));
    }
}
