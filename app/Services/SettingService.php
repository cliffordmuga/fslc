<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettingService
{
    /**
     * Get a single setting value (cached)
     */
    public function get(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }

    /**
     * Set a setting value with full cache invalidation
     */
    public function set(string $key, $value, string $type = 'text', string $category = 'general'): Setting
    {
        return Setting::set($key, $value, $type, $category);
    }

    /**
     * Get all settings in a category (cached)
     */
    public function getByCategory(string $category): Collection
    {
        return Cache::remember("settings_category_{$category}", now()->addHours(24), function () use ($category) {
            return Setting::byCategory($category)
                ->get()
                ->mapWithKeys(fn($setting) => [$setting->key => $setting->casted_value]);
        });
    }

    /**
     * Get all settings grouped by category (cached)
     */
    public function getAllSettings(): Collection
    {
        return Cache::remember('all_settings', now()->addHours(24), function () {
            return Setting::all()
                ->groupBy('category')
                ->map(fn($settings) => $settings->mapWithKeys(
                    fn($setting) => [$setting->key => $setting->casted_value]
                ));
        });
    }

    /**
     * Clear all setting-related caches
     */
    public function clearCache(?string $key = null, ?string $category = null): void
    {
        if ($key) {
            Cache::forget("setting_{$key}");
        }

        if ($category) {
            Cache::forget("settings_category_{$category}");
        }

        // Full clear fallback
        Cache::forget('all_settings');
        app(ClearCacheService::class)->clearByPattern('setting_*');

        // Tag flushing (Redis/Memcached)
        if (Cache::supportsTags()) {
            Cache::tags(['settings'])->flush();
            if ($category) {
                Cache::tags(["settings_{$category}"])->flush();
            }
        }

        Log::info('Settings cache cleared', [
            'key'      => $key ?? 'all',
            'category' => $category ?? 'all',
        ]);
    }
}
