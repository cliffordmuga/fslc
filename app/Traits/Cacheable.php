<?php

namespace App\Traits;

use App\Services\ClearCacheService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait Cacheable
{
    protected static string $cacheVersion = 'v1';

    protected static function bootCacheable(): void
    {
        static::saved(fn (Model $model) => static::clearCacheFor($model));
        static::deleted(fn (Model $model) => static::clearCacheFor($model));

        // If you ever add SoftDeletes later:
        // static::restored(fn (Model $model) => static::clearCacheFor($model));
    }

    public static function clearCacheFor(Model $model): void
    {
        try {
            // 1) Clear exact keys (safe on all cache drivers)
            foreach ($model->getCacheKeys() as $key) {
                Cache::forget($key);
            }

            // 2) Clear tags (safe only when supported)
            $tags = $model->getCacheTags();
            if (!empty($tags) && static::supportsCacheTags()) {
                app(ClearCacheService::class)->clearByTags($tags);
            }

            // 3) Clear patterns ONLY when explicitly enabled + Redis
            if (static::supportsPatternClearing()) {
                $patterns = $model->getCachePatterns();
                foreach ($patterns as $pattern) {
                    app(ClearCacheService::class)->clearByPattern($pattern);
                }
            }

            Log::debug('Cache cleared for model', [
                'model' => class_basename($model),
                'id'    => $model->getKey(),
                'keys'  => count($model->getCacheKeys()),
                'tags'  => count($model->getCacheTags()),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to clear cache for model', [
                'model' => class_basename($model),
                'id'    => $model->getKey(),
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected static function supportsCacheTags(): bool
    {
        return in_array(config('cache.default'), ['redis', 'memcached'], true);
    }

    protected static function supportsPatternClearing(): bool
    {
        // Only allow pattern clearing when explicitly enabled
        return config('cache.allow_pattern_clearing', false)
            && config('cache.default') === 'redis';
    }

    /**
     * Standard key prefix for all cache keys used by the model.
     */
    protected function cachePrefix(): string
    {
        return strtolower(class_basename($this)) . ':' . static::$cacheVersion;
    }

    // ---- Override these in models as needed ----

    public function getCacheKeys(): array
    {
        // Safe defaults
        return [
            "{$this->cachePrefix()}:id:{$this->getKey()}",
        ];
    }

    public function getCacheTags(): array
    {
        return [];
    }

    public function getCachePatterns(): array
    {
        // Only used if pattern clearing enabled
        return [];
    }

    /**
     * Optional: call this when pivot relations change (e.g., tags attached/detached).
     */
    public function touchesCache(): void
    {
        static::clearCacheFor($this);
    }
}
