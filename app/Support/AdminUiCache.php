<?php

namespace App\Support;

use App\Models\Content;
use App\Models\Lead;
use App\Models\Setting;
use App\Services\CacheBuster;
use Illuminate\Support\Facades\Cache;

/**
 * Cache keys shared with AppServiceProvider view composers and admin dashboards.
 * Bust these when the underlying data changes so badges/sidebar stay fresh.
 */
final class AdminUiCache
{
    public const NEW_LEADS_KEY = 'admin:new_leads_count';

    public const RECENT_ACTIVITY_KEY = 'admin:recent_activity';

    public const FOLLOW_UP_LEADS_KEY = 'admin:follow_up_leads_count';

    public const HIGH_INTENT_LEADS_KEY = 'admin:high_intent_leads_count';

    public const PUBLISHED_CONTENT_OPTIONS_KEY = 'admin:published_content_options';

    public static function forgetNewLeads(): void
    {
        Cache::forget(self::NEW_LEADS_KEY);
        self::forgetLeadQueues();
    }

    public static function forgetRecentActivity(): void
    {
        Cache::forget(self::RECENT_ACTIVITY_KEY);
    }

    public static function forgetLeadQueues(): void
    {
        Cache::forget(self::FOLLOW_UP_LEADS_KEY);
        Cache::forget(self::HIGH_INTENT_LEADS_KEY);
    }

    public static function forgetPublishedContentOptions(): void
    {
        Cache::forget(self::PUBLISHED_CONTENT_OPTIONS_KEY . ':' . CacheBuster::current());
    }

    public static function forgetDashboardAndAnalytics(): void
    {
        // Keys include CacheBuster token — bumping public cache invalidates admin aggregates too.
        CacheBuster::bump();
    }

    public static function forgetAll(): void
    {
        self::forgetNewLeads();
        self::forgetRecentActivity();
        self::forgetPublishedContentOptions();
    }

    /**
     * Published content dropdown for CTA forms (cached).
     *
     * @return array<int, string>
     */
    public static function publishedContentOptions(): array
    {
        $key = self::PUBLISHED_CONTENT_OPTIONS_KEY . ':' . CacheBuster::current();

        return Cache::remember($key, now()->addMinutes(15), function (): array {
            return Content::query()
                ->published()
                ->orderBy('title')
                ->pluck('title', 'id')
                ->all();
        });
    }

    public static function newLeadsCount(): int
    {
        return (int) Cache::remember(self::NEW_LEADS_KEY, now()->addMinutes(2), function (): int {
            return Lead::query()->where('status', 'new')->where('is_spam', false)->count();
        });
    }

    public static function followUpLeadsCount(): int
    {
        $days = (int) Setting::get('lead_follow_up_days', 3);

        return (int) Cache::remember(self::FOLLOW_UP_LEADS_KEY, now()->addMinutes(2), function () use ($days): int {
            return Lead::query()->needsFollowUp($days)->count();
        });
    }

    public static function highIntentLeadsCount(): int
    {
        return (int) Cache::remember(self::HIGH_INTENT_LEADS_KEY, now()->addMinutes(2), function (): int {
            return Lead::query()->highIntent()->byStatus('new')->where('is_spam', false)->count();
        });
    }
}
