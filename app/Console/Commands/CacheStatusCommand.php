<?php

namespace App\Console\Commands;

use App\Services\ContentCacheManager;
use App\Support\ContentCache;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheStatusCommand extends Command
{
    protected $signature = 'cache:status';

    protected $description = 'Show cache driver, content bust token, and deploy cache version (shared hosting safe)';

    public function handle(ContentCacheManager $cacheManager): int
    {
        $driver = config('cache.default');
        $bust = Cache::get('content:cache_buster', 'not set');
        $version = config('app.cache_version', 'v1');
        $supportsTags = in_array($driver, ['redis', 'memcached'], true);

        $this->info('Cache status');
        $this->table(
            ['Setting', 'Value'],
            [
                ['Cache driver', $driver],
                ['APP_CACHE_VERSION', $version],
                ['Content bust token', (string) $bust],
                ['Active bust in keys', (string) $cacheManager->contentCacheBuster()],
                ['Tag invalidation', $supportsTags ? 'enabled' : 'disabled (uses global bust token)'],
                ['Public page cache TTL', (string) config('public_page_cache.ttl', 120) . 's'],
                ['Queue connection', config('queue.default')],
            ]
        );

        if (! $supportsTags) {
            $this->line('');
            $this->comment('On database/file cache stores, fragment caches invalidate via ContentCache::bust() + APP_CACHE_VERSION bump.');
            $this->comment('Run php artisan cache:bust-content after bulk admin edits if observers did not fire.');
        }

        return self::SUCCESS;
    }
}
