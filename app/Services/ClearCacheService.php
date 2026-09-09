<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class ClearCacheService
{
    /**
     * Clear all caches related to a model instance
     */
    public function clearModelCaches(Model $model): void
    {
        if (!method_exists($model, 'getCacheKeys')) {
            Log::warning('Model does not implement getCacheKeys', [
                'model' => class_basename($model),
                'id'    => $model->id ?? 'N/A',
            ]);
            return;
        }

        $keys = $model->getCacheKeys();
        $cleared = 0;

        foreach ($keys as $key) {
            if (Cache::forget($key)) {
                $cleared++;
            }
        }

        // Also clear patterns (driver-aware)
        foreach (($model->getCachePatterns() ?? []) as $pattern) {
            $this->clearByPattern($pattern);
        }

        // Clear tags (only if supported)
        $tags = $model->getCacheTags() ?? [];
        $this->clearByTags($tags);

        Log::info('Model caches cleared', [
            'model'        => class_basename($model),
            'id'           => $model->id ?? 'N/A',
            'keys_cleared' => $cleared,
            'patterns'     => count($model->getCachePatterns() ?? []),
            'tags'         => count($tags),
        ]);
    }

    /**
     * Clear cache by tags (only supported by redis/memcached stores)
     */
    public function clearByTags(array $tags): void
    {
        $tags = array_values(array_filter($tags)); // remove empty/null

        if (empty($tags) || !$this->supportsCacheTags()) {
            return;
        }

        Cache::tags($tags)->flush();

        Log::debug('Cache tags flushed', [
            'tags' => $tags,
            'driver' => config('cache.default'),
        ]);
    }

    /**
     * Clear cache by pattern (driver-aware)
     */
    public function clearByPattern(string $pattern): int
    {
        $driver = config('cache.default');
        $cleared = 0;

        try {
            $cleared = match ($driver) {
                'redis'     => $this->clearRedisPattern($pattern),
                'file'      => $this->clearFilePattern($pattern),
                'database'  => $this->clearDatabasePattern($pattern),
                default     => $this->clearFallback($pattern),
            };
        } catch (\Exception $e) {
            Log::error('Cache pattern clear failed', [
                'pattern' => $pattern,
                'driver'  => $driver,
                'error'   => $e->getMessage(),
            ]);
        }

        Log::debug('Cache pattern cleared', [
            'pattern' => $pattern,
            'driver'  => $driver,
            'cleared' => $cleared,
        ]);

        return $cleared;
    }

    /**
     * Clear content-related caches (optimized for Content model).
     * On database/file stores, pattern clears may miss content:v1:* keys — always bust the global token.
     */
    public function clearContentCache(?string $type = null): void
    {
        \App\Support\ContentCache::bust();

        $patterns = [
            'content:v1:*',
            'content_*',
            'sitemap_data',
            'content_types',
        ];

        if ($type) {
            $patterns = array_merge($patterns, [
                "content_{$type}_*",
                "content_detail_{$type}_*",
                "content_featured_{$type}_*",
            ]);
        }

        foreach ($patterns as $pattern) {
            $this->clearByPattern($pattern);
        }

        if ($type) {
            $this->clearByTags(["content_{$type}", 'content_list']);
        }
    }

    /**
     * Clear all settings caches
     */
    public function clearSettingsCache(?string $category = null): void
    {
        if ($category) {
            $this->clearByPattern("settings_category_{$category}_*");
        } else {
            $this->clearByPattern('setting_*');
            Cache::forget('all_settings');
        }
    }

    /**
     * Clear everything (use sparingly)
     */
    public function clearAll(): void
    {
        Cache::flush();
        Log::info('All caches cleared');
    }

    // ── Driver-specific implementations ─────────────────────────────────────

    protected function clearRedisPattern(string $pattern): int
    {
        $prefix = config('cache.prefix', 'laravel_cache:');
        $fullPattern = $prefix . str_replace('*', '*', $pattern);

        $redis = Redis::connection(config('cache.stores.redis.connection', 'default'));

        $keys = [];
        $cursor = 0;

        do {
            $result = $redis->scan($cursor, 'MATCH', $fullPattern, 'COUNT', 500);
            if ($result === false) {
                break;
            }
            [$cursor, $found] = $result;
            $keys = array_merge($keys, $found);
        } while ($cursor !== 0);

        if (empty($keys)) {
            return 0;
        }

        return $redis->del($keys);
    }

    protected function clearFilePattern(string $pattern): int
    {
        $path = config('cache.stores.file.path');
        if (!$path || !is_dir($path)) {
            return 0;
        }

        $regex = '/^' . str_replace(['*', '/'], ['.*', '\/'], preg_quote($pattern, '/')) . '$/';
        $cleared = 0;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && preg_match($regex, $file->getFilename())) {
                if (@unlink($file->getPathname())) {
                    $cleared++;
                }
            }
        }

        return $cleared;
    }

    protected function clearDatabasePattern(string $pattern): int
    {
        $table = config('cache.stores.database.table', 'cache');
        $connection = config('cache.stores.database.connection');

        $likePattern = str_replace('*', '%', $pattern);
        $prefix = config('cache.prefix', '');

        return DB::connection($connection)
            ->table($table)
            ->where('key', 'like', $prefix . $likePattern)
            ->delete();
    }

    protected function clearFallback(string $pattern): int
    {
        Log::warning('Cache driver does not support pattern clearing - flushing all', [
            'driver'  => config('cache.default'),
            'pattern' => $pattern,
        ]);

        Cache::flush();
        return -1; // Indicates full flush
    }

    protected function supportsCacheTags(): bool
    {
        return in_array(config('cache.default'), ['redis', 'memcached'], true);
    }
}
