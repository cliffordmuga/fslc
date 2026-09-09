<?php

// app/Services/SitemapService.php

namespace App\Services;

use App\Models\Content;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url as SitemapUrl;

/**
 * Sitemap Generation Service - Optimized
 *
 * IMPROVEMENTS:
 * - Safe route checking (prevents errors)
 * - Configurable static pages
 * - Better error handling
 * - SeoMetadata integration
 * - Proper priority calculation
 * - Memory-efficient lazy loading
 *
 * Generates a dynamic sitemap.xml with static pages and all published content.
 * Uses Spatie's laravel-sitemap package.
 */
class SitemapService
{
    protected const CACHE_KEY = 'sitemap_xml';

    protected const CACHE_TTL_MINUTES = 1440; // 24 hours

    /**
     * Generate and save the sitemap.xml file to public/sitemap.xml
     *
     * @return bool Success status
     */
    public function generate(): bool
    {
        try {
            $sitemap = Sitemap::create();

            // 1. Add static high-priority pages
            $this->addStaticPages($sitemap);

            // 2. Add all published content items (portfolio, services, blog, pages, etc.)
            $this->addContentPages($sitemap);

            // 3. Tag hub pages
            $this->addTagPages($sitemap);

            // 4. Write to public/sitemap.xml
            $path = public_path('sitemap.xml');
            $sitemap->writeToFile($path);

            // 4. Cache the last generation timestamp
            Cache::put(self::CACHE_KEY.'_generated_at', now(), now()->addMinutes(self::CACHE_TTL_MINUTES));

            Log::info('Sitemap generated successfully', [
                'path' => $path,
                'content_items' => Content::where('status', 'published')->count(),
                'file_size' => file_exists($path) ? filesize($path) : 0,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to generate sitemap', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Add important static pages with high priority
     *
     * IMPROVED: Safe route checking to prevent errors
     */
    protected function addStaticPages(Sitemap $sitemap): void
    {
        $staticPages = $this->getStaticPages();

        foreach ($staticPages as $page) {
            // Pages may define `url` (absolute) or `route` (named route) — never assume both keys exist
            if (isset($page['url'])) {
                $url = $page['url'];
            } elseif (isset($page['route']) && $this->routeExists($page['route'])) {
                $url = route($page['route']);
            } else {
                Log::warning('Skipping sitemap entry for non-existent route or missing URL', [
                    'route' => $page['route'] ?? 'unknown',
                ]);

                continue;
            }

            $sitemap->add(
                SitemapUrl::create($url)
                    ->setPriority($page['priority'])
                    ->setChangeFrequency($page['changefreq'])
                    ->setLastModificationDate(Carbon::parse($page['lastmod']))
            );
        }
    }

    /**
     * Get static pages configuration
     *
     * NEW: Configurable and safe
     */
    protected function getStaticPages(): array
    {
        return [
            [
                'url' => url('/'), // Home - use URL not route (safer)
                'priority' => 1.0,
                'changefreq' => SitemapUrl::CHANGE_FREQUENCY_DAILY,
                'lastmod' => now(),
            ],
            [
                'route' => 'about', // FIX: Just route name, not route()
                'priority' => 0.9,
                'changefreq' => SitemapUrl::CHANGE_FREQUENCY_MONTHLY,
                'lastmod' => now(),
            ],
            [
                'route' => 'services.index',
                'priority' => 0.9,
                'changefreq' => SitemapUrl::CHANGE_FREQUENCY_WEEKLY,
                'lastmod' => now(),
            ],
            [
                'route' => 'portfolio.index',
                'priority' => 0.9,
                'changefreq' => SitemapUrl::CHANGE_FREQUENCY_WEEKLY,
                'lastmod' => now(),
            ],
            [
                'route' => 'insights.index',
                'priority' => 0.85,
                'changefreq' => SitemapUrl::CHANGE_FREQUENCY_WEEKLY,
                'lastmod' => now(),
            ],
            [
                'route' => 'contact',
                'priority' => 0.8,
                'changefreq' => SitemapUrl::CHANGE_FREQUENCY_MONTHLY,
                'lastmod' => now(),
            ],
            [
                'route' => 'privacy',
                'priority' => 0.4,
                'changefreq' => SitemapUrl::CHANGE_FREQUENCY_YEARLY,
                'lastmod' => now(),
            ],
            [
                'route' => 'terms',
                'priority' => 0.4,
                'changefreq' => SitemapUrl::CHANGE_FREQUENCY_YEARLY,
                'lastmod' => now(),
            ],
            [
                'route' => 'mission',
                'priority' => 0.65,
                'changefreq' => SitemapUrl::CHANGE_FREQUENCY_MONTHLY,
                'lastmod' => now(),
            ],
            [
                'route' => 'vision',
                'priority' => 0.65,
                'changefreq' => SitemapUrl::CHANGE_FREQUENCY_MONTHLY,
                'lastmod' => now(),
            ],
        ];
    }

    /**
     * Add published tag hub URLs.
     */
    protected function addTagPages(Sitemap $sitemap): void
    {
        if (! $this->routeExists('tags.show')) {
            return;
        }

        Tag::query()
            ->orderBy('name')
            ->lazy(100)
            ->each(function (Tag $tag) use ($sitemap) {
                try {
                    $sitemap->add(
                        SitemapUrl::create(route('tags.show', $tag->slug))
                            ->setPriority(0.55)
                            ->setChangeFrequency(SitemapUrl::CHANGE_FREQUENCY_WEEKLY)
                            ->setLastModificationDate($tag->updated_at ?? now())
                    );
                } catch (\Exception $e) {
                    Log::warning('Failed to add tag to sitemap', [
                        'tag_id' => $tag->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            });
    }

    /**
     * Add all published content items dynamically
     *
     * IMPROVED: Better priority logic, SeoMetadata integration
     */
    protected function addContentPages(Sitemap $sitemap): void
    {
        Content::query()
            ->with('seoMetadata') // IMPROVED: Eager load SEO metadata
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->lazy(200) // Process in chunks to avoid memory issues
            ->each(function (Content $content) use ($sitemap) {
                // Skip if noindex is set (IMPROVED)
                if ($content->seoMetadata?->noindex) {
                    return;
                }

                // Get priority (IMPROVED: More granular)
                $priority = $this->calculatePriority($content);

                // Get change frequency based on type
                $changeFreq = $this->getChangeFrequency($content->type);

                // Get last modification date (IMPROVED: Use SEO lastmod if available)
                $lastmod = $content->seoMetadata?->lastmod ?? $content->updated_at;

                try {
                    $sitemap->add(
                        SitemapUrl::create($content->url)
                            ->setPriority($priority)
                            ->setChangeFrequency($changeFreq)
                            ->setLastModificationDate($lastmod)
                    );
                } catch (\Exception $e) {
                    Log::warning('Failed to add content to sitemap', [
                        'content_id' => $content->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            });
    }

    /**
     * Calculate priority based on content type and metrics
     *
     * NEW: Smart priority calculation
     */
    protected function calculatePriority(Content $content): float
    {
        if ($content->type === 'services' && $content->slug === 'hmis-digital-health-solutions-kenya') {
            return 0.95;
        }

        $basePriority = match ($content->type) {
            'portfolio' => 0.9,
            'services' => 0.85,
            'blog' => 0.8,
            'page' => 0.75,
            'about', 'mission', 'vision' => 0.7,
            default => 0.6,
        };

        // Boost priority for high-performing content (OPTIONAL)
        if ($content->relationLoaded('analytics')) {
            $totalViews = $content->analytics->sum('views');
            if ($totalViews > 1000) {
                $basePriority = min(1.0, $basePriority + 0.05);
            }
        }

        return round($basePriority, 2);
    }

    /**
     * Get change frequency based on content type
     *
     * NEW: Type-specific change frequency
     */
    protected function getChangeFrequency(string $type): string
    {
        return match ($type) {
            'blog' => SitemapUrl::CHANGE_FREQUENCY_DAILY,
            'portfolio', 'services' => SitemapUrl::CHANGE_FREQUENCY_WEEKLY,
            'page', 'about', 'mission', 'vision' => SitemapUrl::CHANGE_FREQUENCY_MONTHLY,
            default => SitemapUrl::CHANGE_FREQUENCY_WEEKLY,
        };
    }

    /**
     * Check if route exists
     *
     * NEW: Safe route checking
     */
    protected function routeExists(string $routeName): bool
    {
        return Route::has($routeName);
    }

    /**
     * Get the timestamp of the last successful sitemap generation
     */
    public function getLastGeneratedAt(): ?Carbon
    {
        return Cache::get(self::CACHE_KEY.'_generated_at');
    }

    /**
     * Check if the sitemap is outdated and needs regeneration
     */
    public function needsRegeneration(): bool
    {
        $last = $this->getLastGeneratedAt();

        return ! $last || $last->lt(now()->subMinutes(self::CACHE_TTL_MINUTES));
    }

    /**
     * Force regeneration of sitemap
     *
     * NEW: Clear cache and regenerate
     */
    public function forceRegenerate(): bool
    {
        Cache::forget(self::CACHE_KEY.'_generated_at');

        return $this->generate();
    }

    /**
     * Get sitemap statistics
     *
     * NEW: Useful for admin dashboard
     */
    public function getStats(): array
    {
        $path = public_path('sitemap.xml');

        return [
            'exists' => file_exists($path),
            'size' => file_exists($path) ? filesize($path) : 0,
            'size_human' => file_exists($path) ? $this->formatBytes(filesize($path)) : '0 B',
            'last_generated' => $this->getLastGeneratedAt(),
            'needs_regeneration' => $this->needsRegeneration(),
            'published_content_count' => Content::where('status', 'published')->count(),
        ];
    }

    /**
     * Format bytes to human readable
     *
     * NEW: Helper method
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2).' '.$units[$pow];
    }

    /**
     * Ping search engines about sitemap update
     *
     * NEW: Notify search engines
     */
    public function pingSearchEngines(): bool
    {
        if (! config('seo.ping_search_engines', false)) {
            return true;
        }

        $sitemapUrl = urlencode(url('sitemap.xml'));

        $searchEngines = [
            'bing' => "https://www.bing.com/ping?sitemap={$sitemapUrl}",
        ];

        if (config('seo.ping_google', false)) {
            $searchEngines['google'] = "https://www.google.com/ping?sitemap={$sitemapUrl}";
        }

        $success = true;

        foreach ($searchEngines as $engine => $pingUrl) {
            try {
                Http::timeout(5)->get($pingUrl);

                Log::info("Pinged {$engine} about sitemap update");
            } catch (\Exception $e) {
                Log::warning("Failed to ping {$engine}", [
                    'error' => $e->getMessage(),
                ]);
                $success = false;
            }
        }

        return $success;
    }
}
