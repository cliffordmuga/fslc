<?php

namespace App\Models;

use App\Services\CacheBuster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use App\Traits\Cacheable;
use App\Services\ClearCacheService;

/**
 * Setting Model - Optimized
 * 
 * FIXED:
 * - Method name mismatch (clearSettingCaches → clearSettingsCache)
 * - Added cache patterns
 * - Added cache tags
 * - Improved cache clearing
 */
class Setting extends Model
{
    use HasFactory, Cacheable;

    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
        'description',
    ];

    // ===================================
    // SCOPES
    // ===================================

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // ===================================
    // STATIC HELPERS
    // ===================================

    /**
     * Get setting value (cached)
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", now()->addHours(24), function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->casted_value : $default;
        });
    }

    /**
     * Set setting value with full cache clearing
     * 
     * FIXED: Method name corrected to clearSettingsCache (matches service)
     * 
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $category
     * @return self
     */
    public static function set(string $key, $value, string $type = 'text', string $category = 'general'): self
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
                'category' => $category,
            ]
        );

        // FIXED: Correct method name (clearSettingsCache not clearSettingCaches)
        app(ClearCacheService::class)->clearSettingsCache($category);

        // Also clear specific key cache
        Cache::forget("setting_{$key}");
        Cache::forget('all_settings');

        return $setting;
    }

    // ===================================
    // ACCESSORS
    // ===================================

    /**
     * Get casted value based on type
     * 
     * @return mixed
     */
    public function getCastedValueAttribute()
    {
        return match ($this->type) {
            'boolean' => (bool) $this->value,
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'json', 'array' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    // ===================================
    // CACHE METHODS (for Cacheable trait)
    // ===================================

    /**
     * Get cache keys for this setting
     * 
     * @return array
     */
    public function getCacheKeys(): array
    {
        return [
            "setting_{$this->key}",
            "settings_category_{$this->category}",
            'all_settings',
        ];
    }

    /**
     * Get cache patterns for clearing
     * 
     * @return array
     */
    public function getCachePatterns(): array
    {
        return [
            "setting_{$this->key}*",
            "settings_category_{$this->category}*",
        ];
    }

    /**
     * Get cache tags
     * 
     * @return array
     */
    public function getCacheTags(): array
    {
        return [
            'settings',
            "setting_{$this->key}",
            "category_{$this->category}",
        ];
    }

    /**
     * Boot the model
     * 
     * Auto-clear cache when setting is updated or deleted
     */
    protected static function booted(): void
    {
        static::saved(function (Setting $setting) {
            // Clear caches when setting is saved
            Cache::forget("setting_{$setting->key}");
            Cache::forget("settings_category_{$setting->category}");
            Cache::forget('all_settings');
            CacheBuster::bump();
        });

        static::deleted(function (Setting $setting) {
            // Clear caches when setting is deleted
            Cache::forget("setting_{$setting->key}");
            Cache::forget("settings_category_{$setting->category}");
            Cache::forget('all_settings');
            CacheBuster::bump();
        });
    }
}
