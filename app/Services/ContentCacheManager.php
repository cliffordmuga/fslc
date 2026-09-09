<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Shared-hosting-safe content fragment cache (database store friendly).
 * Uses a global bust token in cache keys — no Redis tags required.
 */
class ContentCacheManager
{
    public function cacheVersion(): string
    {
        return config('app.cache_version', 'v1');
    }

    public function makeCacheKey(string $prefix, array $params = []): string
    {
        $params['_bust'] = $this->contentCacheBuster();
        ksort($params);

        return "content:{$this->cacheVersion()}:{$prefix}:" . md5(json_encode($params));
    }

    public function contentCacheBuster(): int
    {
        $key = 'content:cache_buster';

        $bust = Cache::get($key);
        if (is_numeric($bust) && (int) $bust > 0) {
            return (int) $bust;
        }

        $initial = 1;
        Cache::forever($key, $initial);

        return $initial;
    }

    public function remember(string $key, \DateTimeInterface|\DateInterval|int $ttl, \Closure $callback, array $tags = []): mixed
    {
        if (! empty($tags) && $this->supportsTags()) {
            return Cache::tags($tags)->remember($key, $ttl, $callback);
        }

        return Cache::remember($key, $ttl, $callback);
    }

    public function supportsTags(): bool
    {
        return in_array(config('cache.default'), ['redis', 'memcached'], true);
    }
}
