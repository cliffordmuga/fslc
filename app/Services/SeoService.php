<?php

namespace App\Services;

use App\Models\Content;
use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * SeoService - Complete with all helper methods
 * 
 * ADDED:
 * - validateCanonicalUrl() method (was missing)
 * - Better error handling
 * - All methods from optimized version
 */
class SeoService
{
    protected const CACHE_TTL = 720; // 12 hours

    // ===================================
    // CRUD OPERATIONS
    // ===================================

    public function updateMetadata(
        Model $model,
        ?string $title = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $canonicalUrl = null,
        ?string $ogImage = null,
        ?string $ogTitle = null,
        ?string $ogDescription = null,
        bool $noindex = false,
        bool $nofollow = false
    ): SeoMetadata {
        $metadata = $model->seoMetadata ?? new SeoMetadata();

        $metadata->fill([
            'meta_title' => $title ?? $this->generateMetaTitle($model->title ?? $model->name ?? config('app.name')),
            'meta_description' => $description ?? $this->generateMetaDescription($model->excerpt ?? $model->content ?? ''),
            'meta_keywords' => $keywords ?? $this->generateKeywords($model),
            'canonical_url' => $this->validateCanonicalUrl($canonicalUrl ?? $this->generateCanonicalUrl($model)),
            'og_title' => $ogTitle ?? ($title ?? $this->generateMetaTitle($model->title ?? config('app.name'))),
            'og_description' => $ogDescription ?? ($description ?? $this->generateMetaDescription($model->excerpt ?? $model->content ?? '')),
            'og_image' => $ogImage ?? $this->getDefaultOgImage($model),
            'og_type' => $this->getOgType($model),
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $ogTitle ?? ($title ?? $this->generateMetaTitle($model->title ?? config('app.name'))),
            'twitter_description' => $ogDescription ?? ($description ?? $this->generateMetaDescription($model->excerpt ?? $model->content ?? '')),
            'twitter_image' => $ogImage ?? $this->getDefaultOgImage($model),
            'noindex' => $noindex,
            'nofollow' => $nofollow,
            'structured_data' => $this->generateStructuredData($model),
            'lastmod' => now(),
        ]);

        if (!$metadata->exists) {
            $metadata->seoable_type = get_class($model);
            $metadata->seoable_id = $model->id;
        }

        $metadata->save();

        app(ClearCacheService::class)->clearModelCaches($model);

        Log::info('SEO metadata updated', [
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'title' => $metadata->meta_title,
        ]);

        return $metadata;
    }

    public function deleteMetadata(Model $model): bool
    {
        if (!$model->seoMetadata) {
            return false;
        }

        $deleted = $model->seoMetadata->delete();

        if ($deleted) {
            app(ClearCacheService::class)->clearModelCaches($model);

            Log::info('SEO metadata deleted', [
                'model_type' => get_class($model),
                'model_id' => $model->id,
            ]);
        }

        return $deleted;
    }

    public function getSeoData(string $route, $model = null): array
    {
        $appName = config('app.name', env('APP_NAME', 'App'));

        if ($model && method_exists($model, 'seoMetadata') && $model->seoMetadata) {
            return $this->getModelSeoData($model);
        }

        $cacheKey = "seo_default_{$route}";

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($route, $appName) {
            $title = $this->getDefaultTitle($route, $appName);
            $description = $this->getDefaultDescription($route, $appName);

            return [
                'title' => $title,
                'description' => $description,
                'keywords' => '',
                'canonical_url' => $this->validateCanonicalUrl(URL::current()),
                'og_title' => $title,
                'og_description' => $description,
                'og_image' => asset('images/default-og-image.png'),
                'og_type' => 'website',
                'og_url' => $this->validateCanonicalUrl(URL::current()),
                'twitter_card' => 'summary_large_image',
                'twitter_title' => $title,
                'twitter_description' => $description,
                'twitter_image' => asset('images/default-og-image.png'),
                'noindex' => false,
                'nofollow' => false,
                'structured_data' => [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => $title,
                    'url' => $this->validateCanonicalUrl(URL::current()),
                    'description' => $description,
                ],
            ];
        });
    }

    protected function getModelSeoData(Model $model): array
    {
        $metadata = $model->seoMetadata;

        return [
            'title' => $metadata->meta_title ?? $model->title ?? config('app.name'),
            'description' => $metadata->meta_description ?? $this->generateMetaDescription($model->excerpt ?? $model->content ?? ''),
            'keywords' => $metadata->meta_keywords ?? '',
            'canonical_url' => $this->validateCanonicalUrl($metadata->canonical_url ?? $this->generateCanonicalUrl($model)),
            'og_title' => $metadata->og_title ?? $metadata->meta_title,
            'og_description' => $metadata->og_description ?? $metadata->meta_description,
            'og_image' => $metadata->og_image ?? $this->getDefaultOgImage($model),
            'og_type' => $metadata->og_type ?? $this->getOgType($model),
            'og_url' => $this->validateCanonicalUrl($metadata->canonical_url ?? $this->generateCanonicalUrl($model)),
            'twitter_card' => $metadata->twitter_card ?? 'summary_large_image',
            'twitter_title' => $metadata->twitter_title ?? $metadata->og_title,
            'twitter_description' => $metadata->twitter_description ?? $metadata->og_description,
            'twitter_image' => $metadata->twitter_image ?? $metadata->og_image,
            'noindex' => $metadata->noindex ?? false,
            'nofollow' => $metadata->nofollow ?? false,
            'structured_data' => $metadata->structured_data ?? $this->generateStructuredData($model),
        ];
    }

    // ===================================
    // GENERATION HELPERS (SEO Best Practices)
    // ===================================

    /** Ideal: 50-60 chars. Keyword early. Truncate at word boundary. */
    public function generateMetaTitle(string $title): string
    {
        $clean = trim(strip_tags($title));
        if (empty($clean)) {
            return config('app.name');
        }
        $maxLen = 60;
        if (mb_strlen($clean) <= $maxLen) {
            return $clean;
        }
        $truncated = mb_substr($clean, 0, $maxLen);
        $lastSpace = mb_strrpos($truncated, ' ');
        if ($lastSpace !== false && $lastSpace > $maxLen * 0.6) {
            return mb_substr($truncated, 0, $lastSpace);
        }
        return $truncated;
    }

    /** Ideal: 150-160 chars. Unique, compelling, CTA when possible. Word-boundary truncation. */
    public function generateMetaDescription(?string $content): string
    {
        if (empty($content)) {
            return '';
        }
        $clean = html_entity_decode(strip_tags($content), ENT_QUOTES, 'UTF-8');
        $clean = preg_replace('/\s+/', ' ', trim($clean));
        if (empty($clean)) {
            return '';
        }
        $maxLen = 160;
        if (mb_strlen($clean) <= $maxLen) {
            return $clean;
        }
        $truncated = mb_substr($clean, 0, $maxLen);
        $lastSpace = mb_strrpos($truncated, ' ');
        if ($lastSpace !== false && $lastSpace > $maxLen * 0.75) {
            return mb_substr($truncated, 0, $lastSpace);
        }
        return $truncated;
    }

    /** Extract relevant keywords; avoid stuffing. Limit 5-8 terms. */
    public function generateKeywords(Model $model): string
    {
        $text = strip_tags(($model->title ?? '') . ' ' . ($model->excerpt ?? '') . ' ' . Str::limit($model->content ?? '', 500));
        $words = preg_match_all('/\b[a-z]{3,}\b/', strtolower($text), $m) ? $m[0] : [];

        $stopWords = ['the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'a', 'an', 'is', 'it', 'as', 'be', 'by', 'from', 'this', 'that', 'was', 'are', 'were', 'been', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might', 'can'];
        $words = array_values(array_diff($words, $stopWords));
        $words = array_count_values($words);
        arsort($words);
        $words = array_keys(array_slice($words, 0, 8, true));

        return implode(', ', $words);
    }

    public function generateCanonicalUrl(Model $model): string
    {
        if (method_exists($model, 'getUrlAttribute')) {
            return $this->validateCanonicalUrl($model->url);
        }

        return $this->validateCanonicalUrl(URL::current());
    }

    /**
     * Validate and normalize canonical URL
     * 
     * NEW: Added method that helpers.php was calling
     * 
     * @param string|null $url
     * @return string
     */
    public function validateCanonicalUrl(?string $url): string
    {
        if (empty($url)) {
            return rtrim(URL::current(), '/');
        }

        // Remove trailing slash
        $url = rtrim($url, '/');

        // Ensure it's a valid URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            // If not a full URL, try to make it one
            if (str_starts_with($url, '/')) {
                $url = url($url);
            } else {
                // Invalid URL, return current
                return rtrim(URL::current(), '/');
            }
        }

        // Reject stored canonicals from wrong host (e.g. localhost in production DB)
        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        $urlHost = parse_url($url, PHP_URL_HOST);

        if ($appHost && $urlHost) {
            $isLocalhost = in_array($urlHost, ['localhost', '127.0.0.1'], true)
                || str_ends_with($urlHost, '.local')
                || str_ends_with($urlHost, '.test');

            if ($isLocalhost || $urlHost !== $appHost) {
                return rtrim(URL::current(), '/');
            }
        }

        // Ensure HTTPS if in production
        if (app()->environment('production') && str_starts_with($url, 'http://')) {
            $url = str_replace('http://', 'https://', $url);
        }

        return $url;
    }

    protected function getDefaultOgImage(Model $model): string
    {
        if (method_exists($model, 'images') && $model->images()->exists()) {
            $featured = $model->images()->where('collection', 'featured')->first()
                ?? $model->images()->where('variant', 'main')->first()
                ?? $model->images()->first();

            if ($featured) {
                return $featured->url;
            }
        }

        return asset('images/default-og-image.png');
    }

    protected function getOgType(Model $model): string
    {
        if (!isset($model->type)) {
            return 'website';
        }

        return match ($model->type) {
            'blog' => 'article',
            'portfolio' => 'article',
            'service' => 'service',
            default => 'website',
        };
    }

    public function generateStructuredData(Model $model): array
    {
        $appName = config('app.name', env('APP_NAME', 'App'));

        $base = [
            '@context' => 'https://schema.org',
            '@type' => $this->getSchemaType($model),
            'name' => $model->title ?? $appName,
            'url' => $this->validateCanonicalUrl($this->generateCanonicalUrl($model)),
        ];

        if (isset($model->type)) {
            switch ($model->type) {
                case 'blog':
                    $base['@type'] = 'BlogPosting';
                    $base['headline'] = $model->title;
                    if ($model->published_at) {
                        $base['datePublished'] = $model->published_at->toIso8601String();
                    }
                    if ($model->updated_at) {
                        $base['dateModified'] = $model->updated_at->toIso8601String();
                    }
                    $base['author'] = [
                        '@type' => 'Person',
                        'name' => $model->creator?->name ?? $appName,
                    ];
                    $base['publisher'] = [
                        '@type' => 'Organization',
                        'name' => setting('company_name', $appName),
                        'url' => url('/'),
                    ];
                    if ($model->excerpt) {
                        $base['description'] = strip_tags($model->excerpt);
                    }
                    break;

                case 'portfolio':
                    $base['@type'] = 'CreativeWork';
                    $base['creator'] = $model->creator?->name ?? $appName;
                    if ($model->published_at) {
                        $base['dateCreated'] = $model->published_at->toIso8601String();
                    }
                    break;

                case 'services':
                    $base['@type'] = 'Service';
                    $base['serviceType'] = $model->title ?? 'Service';
                    $base['provider'] = [
                        '@type' => 'Organization',
                        'name' => setting('company_name', $appName),
                        'url' => url('/'),
                    ];
                    break;
            }
        }

        $ogImage = $this->getDefaultOgImage($model);
        if ($ogImage) {
            $base['image'] = $ogImage;
        }

        return $base;
    }

    protected function getSchemaType(Model $model): string
    {
        if (!isset($model->type)) {
            return 'WebPage';
        }

        return match ($model->type) {
            'blog' => 'BlogPosting',
            'portfolio' => 'CreativeWork',
            'service' => 'Service',
            default => 'WebPage',
        };
    }

    protected function getDefaultTitle(string $route, string $appName): string
    {
        return match ($route) {
            'home' => "{$appName} | Professional Portfolio",
            'about' => "About Us | {$appName}",
            'services' => "Our Services | {$appName}",
            'portfolio' => "Portfolio Gallery | {$appName}",
            'contact' => "Contact Us | {$appName}",
            default => $appName,
        };
    }

    protected function getDefaultDescription(string $route, string $appName): string
    {
        return match ($route) {
            'home' => "Discover premium portfolio showcases and professional services at {$appName}.",
            'about' => "Learn about our team, mission, and professional expertise at {$appName}.",
            'services' => "Explore our comprehensive range of professional services at {$appName}.",
            'portfolio' => "Browse our curated collection of successful projects and case studies at {$appName}.",
            'contact' => "Get in touch for personalized consultation and project inquiries at {$appName}.",
            default => "Professional portfolio and services showcase at {$appName}.",
        };
    }

    protected function clearMetadataCache(Model $model): void
    {
        app(ClearCacheService::class)->clearModelCaches($model);
        Cache::forget("seo_data_{$model->type}_{$model->slug}");
    }
}
