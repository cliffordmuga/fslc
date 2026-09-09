<?php

use App\Models\Setting;
use App\Services\SeoService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

if (!function_exists('setting')) {
    /**
     * Get a setting value by key with caching
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        try {
            return Setting::get($key, $default);
        } catch (\Exception $e) {
            // Log error and return default if database is unavailable
            Log::warning("Setting '{$key}' could not be retrieved: " . $e->getMessage());
            return $default;
        }
    }
}

if (!function_exists('settings')) {
    /**
     * Get multiple settings at once
     *
     * @param array $keys
     * @return array
     */
    function settings(array $keys): array
    {
        $cacheKey = 'settings_batch_' . md5(implode(',', $keys));
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($keys) {
            $settings = [];
            foreach ($keys as $key) {
                $settings[$key] = setting($key);
            }
            return $settings;
        });
    }
}

if (!function_exists('canonical_url')) {
    /**
     * Generate canonical URL for the current page
     *
     * @param string|null $staticRoute
     * @param mixed $model
     * @return string
     */
    function canonical_url($staticRoute = null, $model = null): string
    {
        try {
            $seoService = app(SeoService::class);
            if ($staticRoute && is_string($staticRoute)) {
                if (Route::has($staticRoute)) {
                    return $seoService->validateCanonicalUrl(route($staticRoute));
                }
                // Fallback if route doesn't exist
                return $seoService->validateCanonicalUrl(url('/'));
            }
            if ($model) {
                if ($model instanceof \App\Models\Content && method_exists($model, 'getUrlAttribute')) {
                    return $seoService->validateCanonicalUrl($model->url);
                }
                // Handle other model types if needed
                if (method_exists($model, 'getCanonicalUrlAttribute')) {
                    return $seoService->validateCanonicalUrl($model->canonical_url);
                }
            }
            // Default to current URL or home
            return $seoService->validateCanonicalUrl(request()->url() ?: url('/'));
        } catch (\Exception $e) {
            Log::warning('Canonical URL generation failed: ' . $e->getMessage());
            return url('/');
        }
    }
}

if (!function_exists('theme_asset')) {
    /**
     * Generate URL for theme-specific assets
     *
     * @param string $path
     * @param string|null $theme
     * @return string
     */
    function theme_asset(string $path, ?string $theme = null): string
    {
        // Sanitize path to prevent directory traversal
        $path = ltrim(str_replace(['../', '.\\'], '', $path), '/');
        if (empty($path)) {
            return asset('themes/default/');
        }
        $theme = $theme ?: setting('active_theme', 'default');
        // Validate theme name (alphanumeric, hyphens, underscores only)
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $theme)) {
            $theme = 'default';
        }
        $assetPath = "themes/{$theme}/{$path}";
        // Check if file exists in public directory
        if (file_exists(public_path($assetPath))) {
            return asset($assetPath);
        }
        // Fallback to default theme
        $defaultPath = "themes/default/{$path}";
        return asset($defaultPath);
    }
}

if (!function_exists('truncate_html')) {
    /**
     * Truncate HTML content while preserving word boundaries
     *
     * @param string $html
     * @param int $length
     * @param string $ending
     * @return string
     */
    function truncate_html(string $html, int $length = 160, string $ending = '...'): string
    {
        if (empty($html)) {
            return '';
        }
        // Strip tags and decode HTML entities
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8');
        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', trim($text));
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        // Truncate at word boundary
        $truncated = mb_substr($text, 0, $length);
        $lastSpace = mb_strrpos($truncated, ' ');
        if ($lastSpace !== false && $lastSpace > ($length * 0.75)) {
            $truncated = mb_substr($truncated, 0, $lastSpace);
        }
        return $truncated . $ending;
    }
}

if (!function_exists('format_bytes')) {
    /**
     * Format bytes into human readable format
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function format_bytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('sanitize_rich_html')) {
    /**
     * Minimal rich-text sanitizer for CMS output.
     * Keeps common formatting tags and strips script/style/event handler vectors.
     */
    function sanitize_rich_html(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // Remove high-risk elements entirely.
        $html = preg_replace('#<(script|style|iframe|object|embed|link|meta)[^>]*>.*?</\1>#is', '', $html) ?? '';

        // Remove inline event handlers (onclick, onerror, etc.) and dangerous URLs.
        $html = preg_replace('/\son\w+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '', $html) ?? '';
        $html = preg_replace('/\s(href|src)\s*=\s*([\'"])\s*(javascript|data|vbscript):[^\'"]*\2/i', '', $html) ?? '';

        // Keep typical CMS formatting tags only.
        $allowed = '<p><br><hr><strong><b><em><i><u><s><blockquote><pre><code><ul><ol><li><h1><h2><h3><h4><h5><h6><a><img><table><thead><tbody><tr><th><td><figure><figcaption><span><div>';

        return strip_tags($html, $allowed);
    }
}

if (!function_exists('time_ago')) {
    /**
     * Get human readable time difference
     *
     * @param \Carbon\Carbon|string $datetime
     * @return string
     */
    function time_ago($datetime): string
    {
        if (is_string($datetime)) {
            $datetime = \Carbon\Carbon::parse($datetime);
        }
        if (!$datetime instanceof \Carbon\Carbon) {
            return 'Unknown';
        }
        return $datetime->diffForHumans();
    }
}

if (!function_exists('meta_title')) {
    /**
     * Generate optimized meta title
     *
     * @param string|null $title
     * @param string|null $siteName
     * @param int $maxTotal
     * @return string
     */
    function meta_title(?string $title = null, ?string $siteName = null, int $maxTotal = 60): string
{
    $siteName = trim($siteName ?: setting('company_name', config('app.name', env('APP_NAME', 'App'))));

    // Normalize incoming title
    $title = $title !== null ? trim(preg_replace('/\s+/', ' ', strip_tags($title))) : '';

    // Remove trailing separators like " |", " -", " —"
    $title = rtrim($title, " \t\n\r\0\x0B|-–—");

    // If empty or effectively just the site name, return site name only
    if ($title === '' || mb_strtolower($title) === mb_strtolower($siteName)) {
        return $siteName;
    }

    // If the title already contains the site name, avoid appending again
    if (mb_stripos($title, $siteName) !== false) {
        // Still enforce overall max length (word-safe)
        if (mb_strlen($title) > $maxTotal) {
            $title = mb_substr($title, 0, $maxTotal);
            $cut = mb_strrpos($title, ' ');
            if ($cut !== false && $cut > (int)($maxTotal * 0.7)) {
                $title = mb_substr($title, 0, $cut);
            }
            $title = rtrim($title, " \t\n\r\0\x0B|-–—") . '…';
        }
        return $title;
    }

    // Build final title with separator
    $sep = ' | ';
    $maxTitleLen = max(10, $maxTotal - mb_strlen($siteName) - mb_strlen($sep));

    // Truncate page title portion safely (word boundary + ellipsis)
    if (mb_strlen($title) > $maxTitleLen) {
        $truncated = mb_substr($title, 0, $maxTitleLen);
        $cut = mb_strrpos($truncated, ' ');
        if ($cut !== false && $cut > (int)($maxTitleLen * 0.7)) {
            $truncated = mb_substr($truncated, 0, $cut);
        }
        $title = rtrim($truncated, " \t\n\r\0\x0B|-–—") . '…';
    }

    return $title . $sep . $siteName;
}

}

if (!function_exists('meta_description')) {
    /**
     * Generate optimized meta description
     *
     * @param string|null $description
     * @param int $maxLength
     * @return string
     */
    function meta_description(?string $description = null, int $maxLength = 160): string
    {
        if (empty($description)) {
            return setting('site_description', 'Professional portfolio and services showcase.');
        }
        $description = strip_tags($description);
        $description = preg_replace('/\s+/', ' ', trim($description));
        if (strlen($description) <= $maxLength) {
            return $description;
        }
        $truncated = substr($description, 0, $maxLength);
        $lastSpace = strrpos($truncated, ' ');
        if ($lastSpace !== false && $lastSpace > ($maxLength * 0.75)) {
            $truncated = substr($truncated, 0, $lastSpace);
        }
        return $truncated . '...';
    }
}

if (!function_exists('meta_keywords')) {
    /**
     * Sanitize optional meta keywords (legacy SEO; omit when empty).
     */
    function meta_keywords(?string $keywords, int $maxLength = 255): ?string
    {
        if ($keywords === null || trim($keywords) === '') {
            return null;
        }

        $keywords = strip_tags($keywords);
        $keywords = preg_replace('/\s+/', ' ', trim($keywords));

        if ($keywords === '') {
            return null;
        }

        if (strlen($keywords) <= $maxLength) {
            return $keywords;
        }

        $truncated = substr($keywords, 0, $maxLength);
        $lastComma = strrpos($truncated, ',');

        if ($lastComma !== false && $lastComma > (int) ($maxLength * 0.6)) {
            return rtrim(substr($truncated, 0, $lastComma), ' ,');
        }

        return rtrim($truncated, ' ,');
    }
}

if (!function_exists('is_current_route')) {
    /**
     * Check if current route matches given route name or pattern
     *
     * @param string|array $routes
     * @return bool
     */
    function is_current_route($routes): bool
    {
        if (!is_array($routes)) {
            $routes = [$routes];
        }
        $currentRoute = request()->route();
        if (!$currentRoute) {
            return false;
        }
        $currentRouteName = $currentRoute->getName();
        foreach ($routes as $route) {
            if (str_contains($route, '*')) {
                // Pattern matching
                if (Str::is($route, $currentRouteName)) {
                    return true;
                }
            } else {
                // Exact match
                if ($route === $currentRouteName) {
                    return true;
                }
            }
        }
        return false;
    }
}

if (!function_exists('generate_excerpt')) {
    /**
     * Generate excerpt from content
     *
     * @param string|null $content
     * @param int $length
     * @return string
     */
    function generate_excerpt(?string $content, int $length = 160): string
    {
        if (empty($content)) {
            return '';
        }
        $excerpt = html_entity_decode(strip_tags($content), ENT_QUOTES, 'UTF-8');
        $excerpt = preg_replace('/\s+/', ' ', trim($excerpt));
        $excerpt = preg_replace('/^(Welcome to|Introduction|Overview|About)\s+/i', '', $excerpt);
        return truncate_html($excerpt, $length);
    }
}

if (!function_exists('defaultSeoData')) {
    /**
     * Generate fallback SEO metadata for views without explicit SEO data
     *
     * @return array
     */
    function defaultSeoData(): array
    {
        return [
            'title' => config('app.name'),
            'description' => 'Welcome to ' . config('app.name') . '. Discover our services, portfolio, and insights.',
            'og_title' => config('app.name'),
            'og_description' => 'Explore what we offer at ' . config('app.name'),
            'og_image' => cdn_asset('images/default-og-image.png'),
            'canonical_url' => canonical_url(),
            'noindex' => false,
            'structured_data' => [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => config('app.name'),
                'url' => canonical_url(),
            ],
        ];
    }
}

if (!function_exists('generate_utm_url')) {
    /**
     * Generate URL with UTM parameters for lead tracking.
     *
     * @param string $baseUrl Base route or URL
     * @param string $source UTM source (e.g., 'hero_cta')
     * @param string $medium UTM medium (default: 'cta')
     * @param string $campaign UTM campaign (default: 'lead_gen')
     * @return string URL with query params
     */
    function generate_utm_url(string $baseUrl, string $source, string $medium = 'cta', string $campaign = 'lead_gen'): string
    {
        $utmParams = [
            'utm_source'   => $source,
            'utm_medium'   => $medium,
            'utm_campaign' => $campaign . '_' . date('Y-m-d'),
        ];

        // Fragment (#anchor) must always come after the query string.
        // Strip it out first, append UTM params, then re-attach it.
        // Without this: /contact#contact-form?utm_source=… (broken)
        // With this:    /contact?utm_source=…#contact-form   (correct)
        $fragment = '';
        if (($hashPos = strpos($baseUrl, '#')) !== false) {
            $fragment = substr($baseUrl, $hashPos);
            $baseUrl  = substr($baseUrl, 0, $hashPos);
        }

        $separator = str_ends_with($baseUrl, '?') ? '' : (str_contains($baseUrl, '?') ? '&' : '?');

        return $baseUrl . $separator . http_build_query($utmParams) . $fragment;
    }
}

if (!function_exists('hub_cta_url')) {
    /**
     * Standard HMIS demo / contact CTA URL with UTM attribution for hub pages.
     *
     * @param string $context UTM source key (e.g. portfolio_hero, services_footer_cta)
     * @param string|null $inquiryType Pass empty string for non-contact base URLs
     * @param string|null $baseUrl Override base (e.g. route('portfolio.index'))
     */
    function hub_cta_url(string $context, ?string $inquiryType = 'hmis-demo', ?string $baseUrl = null): string
    {
        if ($baseUrl === null) {
            $baseUrl = route('contact', array_filter([
                'inquiry_type' => ($inquiryType !== null && $inquiryType !== '') ? $inquiryType : null,
            ])) . '#contact-form';
        }

        return generate_utm_url($baseUrl, $context);
    }
}

if (!function_exists('breadcrumb_schema')) {
    /**
     * Build BreadcrumbList schema from an array of items:
     * [
     *   ['name' => 'Home', 'url' => url('/')],
     *   ['name' => 'Portfolio', 'url' => route('portfolio')],
     *   ['name' => $content->title, 'url' => url()->current()],
     * ]
     */
    function breadcrumb_schema(array $items): array
    {
        $list = [];
        $pos = 1;

        foreach ($items as $it) {
            if (empty($it['name']) || empty($it['url'])) continue;

            $list[] = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'item' => [
                    '@id' => (string) $it['url'],
                    'name' => (string) $it['name'],
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $list,
        ];
    }
}

if (!function_exists('organization_schema')) {
    function organization_schema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => setting('company_name', config('app.name')),
            'url' => url('/'),
            'logo' => cdn_asset('images/logo.png'),
            'sameAs' => collect([
                setting('facebook_url'),
                setting('twitter_url'),
                setting('linkedin_url'),
                setting('instagram_url'),
            ])->filter()->values()->toArray(),
        ];
    }
}

if (!function_exists('image_srcset')) {
    /**
     * Build a srcset string from an array like:
     * [
     *   ['url' => '/storage/x-480.webp', 'w' => 480],
     *   ['url' => '/storage/x-768.webp', 'w' => 768],
     * ]
     */
    function image_srcset(?array $variants): ?string
    {
        if (empty($variants) || !is_array($variants)) return null;

        $parts = [];
        foreach ($variants as $v) {
            if (!is_array($v)) continue;
            $url = $v['url'] ?? null;
            $w = $v['w'] ?? null;

            if ($url && $w) {
                $parts[] = "{$url} {$w}w";
            }
        }

        return count($parts) ? implode(', ', $parts) : null;
    }
}

if (!function_exists('safe_image_alt')) {
    function safe_image_alt(?string $alt, string $fallback): string
    {
        $alt = trim((string)$alt);
        return $alt !== '' ? $alt : $fallback;
    }
}

if (!function_exists('obfuscate_contact')) {
    /**
     * Obfuscate email/phone for protected-contact elements.
     * Uses base64(strrev(value)) so simple scrapers that atob() get reversed text.
     * JS must reverse after decode.
     *
     * @param string $value Raw email or phone
     * @return string Obfuscated value for data-value attr
     */
    function obfuscate_contact(string $value): string
    {
        return base64_encode(strrev((string) $value));
    }
}

if (!function_exists('cdn_asset')) {
    /**
     * Serve static assets via optional CDN / ASSET_URL prefix.
     */
    function cdn_asset(string $path): string
    {
        $path = ltrim($path, '/');
        $base = config('filesystems.cdn_url') ?: config('app.asset_url');

        return $base ? rtrim($base, '/') . '/' . $path : asset($path);
    }
}

if (!function_exists('upload_asset')) {
    /**
     * Public URL for CMS uploads under /uploads (CDN-aware, subdirectory-safe via asset()).
     */
    function upload_asset(string $path): string
    {
        $path = ltrim($path, '/');

        if (str_starts_with($path, 'images/')) {
            return cdn_asset($path);
        }

        $cdn = config('filesystems.cdn_url');
        if ($cdn) {
            return rtrim($cdn, '/') . '/uploads/' . $path;
        }

        return asset('uploads/' . $path);
    }
}

if (!function_exists('lead_ux_for_request')) {
    /**
     * Pillar-aware sticky bar + contact headline config for the current request.
     *
     * @return array{contact_headline:string,sticky_label:string,sticky_utm:string,whatsapp_message:string}
     */
    function lead_ux_for_request(?string $inquiryType = null): array
    {
        $inquiryType = $inquiryType ?? (string) request()->query('inquiry_type', '');

        if ($inquiryType === '' && request()->routeIs('services.show')) {
            $slug = (string) request()->route('slug');
            $inquiryType = config("forefront.service_lead_config.{$slug}.inquiry_type", '');
        }

        if ($inquiryType === '' && request()->routeIs('portfolio.show')) {
            $slug = (string) request()->route('slug');
            $hmisSlugs = config('forefront.portfolio_pillars.hmis.slugs', []);
            $inquiryType = in_array($slug, $hmisSlugs, true) || str_contains($slug, 'hmis')
                ? 'hmis-demo'
                : 'portfolio';
        }

        if ($inquiryType === '' && request()->routeIs('insights.show', 'blog.show')) {
            $inquiryType = 'hmis-demo';
        }

        return config("forefront.inquiry_ux.{$inquiryType}")
            ?? config('forefront.inquiry_ux.default', [
                'contact_headline' => 'Tell Us Your Vision',
                'sticky_label' => 'Get a Free Quote',
                'sticky_utm' => 'sticky_bar_cta',
                'whatsapp_message' => 'Hi, I\'d like to get a free quote.',
            ]);
    }
}

if (!function_exists('render_cms_content')) {
    /**
     * Sanitize CMS HTML and expand lead-magnet shortcodes.
     */
    function render_cms_content(?string $html): string
    {
        $html = sanitize_rich_html($html ?? '');

        return preg_replace_callback(
            '/\[lead-magnet(?:\s+type=["\']([^"\']+)["\'])?\s*\]/i',
            function (array $matches): string {
                $type = $matches[1] ?? 'hmis-checklist';

                return view('components.cms.lead-magnet-cta', ['type' => $type])->render();
            },
            $html
        ) ?? $html;
    }
}

if (!function_exists('hero_asset')) {
    /**
     * Hero background — prefers configured JPG photography, then WebP, then SVG.
     */
    function hero_asset(int $variant = 1): string
    {
        $variant = max(1, min(7, $variant));
        $mapped = config("forefront.hero_images.{$variant}");

        if ($mapped && file_exists(public_path($mapped))) {
            return cdn_asset($mapped);
        }

        $webp = "assets/bg/hero/hero-{$variant}.webp";
        if (file_exists(public_path($webp))) {
            return cdn_asset($webp);
        }

        $svg = "assets/bg/hero/hero-{$variant}.svg";
        if (file_exists(public_path($svg))) {
            return cdn_asset($svg);
        }

        return cdn_asset('images/og/company.svg');
    }
}

if (!function_exists('hero_asset_srcset')) {
    function hero_asset_srcset(int $variant = 1): ?string
    {
        $variant = max(1, min(7, $variant));
        $mapped = config("forefront.hero_images.{$variant}");

        if ($mapped && file_exists(public_path($mapped))) {
            return null;
        }

        $sizes = [640, 1280, 1920];
        $parts = [];

        foreach ($sizes as $w) {
            $suffix = $w === 1920 ? '' : "-{$w}";
            $file = "assets/bg/hero/hero-{$variant}{$suffix}.webp";
            if (file_exists(public_path($file))) {
                $parts[] = cdn_asset($file) . " {$w}w";
            }
        }

        return count($parts) ? implode(', ', $parts) : null;
    }
}

if (!function_exists('content_hub_label')) {
    /** Human-readable hub name for a content type (matches hub page titles). */
    function content_hub_label(string $type): string
    {
        return match ($type) {
            'portfolio' => 'Portfolio',
            'services'  => 'Services',
            'blog'      => 'Insights',
            default     => ucfirst($type),
        };
    }
}

if (!function_exists('content_is_hmis_related')) {
    /** Whether detail content is HMIS / digital-health themed (tags, slug, or pillar). */
    function content_is_hmis_related(\App\Models\Content $content): bool
    {
        if ($content->slug === 'hmis-digital-health-solutions-kenya') {
            return true;
        }

        $hmisSlugs = array_merge(
            config('forefront.portfolio_pillars.hmis.slugs', []),
            ['hmis-digital-health-solutions-kenya']
        );

        if (in_array($content->slug, $hmisSlugs, true) || str_contains($content->slug, 'hmis')) {
            return true;
        }

        if ($content->relationLoaded('tags') && $content->tags?->isNotEmpty()) {
            $hmisTags = ['hmis', 'emr', 'digital-health', 'sha-integration'];
            if ($content->tags->pluck('slug')->intersect($hmisTags)->isNotEmpty()) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('content_inquiry_type_for_detail')) {
    /** Primary contact inquiry_type for a detail page. */
    function content_inquiry_type_for_detail(\App\Models\Content $content, string $type, ?array $leadConfig = null): string
    {
        if ($type === 'services' && ! empty($leadConfig['inquiry_type'])) {
            return (string) $leadConfig['inquiry_type'];
        }

        if (content_is_hmis_related($content)) {
            return 'hmis-demo';
        }

        return match ($type) {
            'portfolio' => 'portfolio',
            'blog'      => 'hmis-demo',
            default     => 'general',
        };
    }
}

if (!function_exists('content_detail_metrics')) {
    /**
     * @return list<array{value:string,label:string}>|null
     */
    function content_detail_metrics(\App\Models\Content $content): ?array
    {
        if (! in_array($content->type, ['portfolio', 'services'], true)) {
            return null;
        }

        $metrics = config("forefront.content_detail_metrics.{$content->slug}");

        return is_array($metrics) && $metrics !== [] ? $metrics : null;
    }
}

if (!function_exists('content_reading_time_minutes')) {
    function content_reading_time_minutes(?string $html): int
    {
        $words = str_word_count(trim(strip_tags($html ?? '')));

        return max(1, (int) ceil($words / 200));
    }
}

if (!function_exists('content_article_headings')) {
    /**
     * @return list<array{id:string,text:string}>
     */
    function content_article_headings(?string $html): array
    {
        preg_match_all('/<h2[^>]*>(.*?)<\/h2>/is', $html ?? '', $matches);
        $headings = [];

        foreach ($matches[1] ?? [] as $i => $raw) {
            $text = trim(strip_tags($raw));
            if ($text === '') {
                continue;
            }
            $headings[] = ['id' => 'section-' . ($i + 1), 'text' => $text];
        }

        return $headings;
    }
}

if (!function_exists('render_cms_article')) {
    /** CMS body with heading anchor IDs for in-page TOC links. */
    function render_cms_article(?string $html): string
    {
        $html = render_cms_content($html);
        $index = 0;

        return preg_replace_callback(
            '/<h2([^>]*)>(.*?)<\/h2>/is',
            static function (array $m) use (&$index): string {
                $index++;
                if (preg_match('/\sid\s*=/', $m[1])) {
                    return $m[0];
                }

                return '<h2' . $m[1] . ' id="section-' . $index . '">' . $m[2] . '</h2>';
            },
            $html
        ) ?? $html;
    }
}

if (!function_exists('content_detail_hero_variant')) {
    function content_detail_hero_variant(string $type): int
    {
        return match ($type) {
            'portfolio' => 3,
            'services'  => 4,
            'blog'      => 6,
            default     => 2,
        };
    }
}

if (!function_exists('contact_form_ux')) {
    /**
     * Contact form copy and behaviour for a pillar inquiry type.
     *
     * @return array{
     *     description:string,
     *     message_label:string,
     *     message_placeholder:string,
     *     submit_label:string,
     *     single_step:bool,
     *     require_service:bool,
     *     show_upload:bool,
     *     default_message:?string,
     *     success_message:string
     * }
     */
    function contact_form_ux(string $inquiryType = 'general'): array
    {
        $defaults = config('forefront.contact_form.default', []);
        $specific = config("forefront.contact_form.{$inquiryType}", []);

        return array_merge($defaults, is_array($specific) ? $specific : []);
    }
}

if (!function_exists('normalize_inquiry_type')) {
    /** Ensure inquiry_type is a known Lead value, else general. */
    function normalize_inquiry_type(?string $inquiryType): string
    {
        $inquiryType = (string) ($inquiryType ?? 'general');

        return array_key_exists($inquiryType, \App\Models\Lead::INQUIRY_TYPES)
            ? $inquiryType
            : 'general';
    }
}

if (!function_exists('highlight_search_term')) {
    /** Highlight matched search term in plain text (safe HTML output). */
    function highlight_search_term(?string $text, string $term): string
    {
        $escaped = e($text ?? '');
        $term = trim($term);

        if ($term === '' || mb_strlen($term) < 2) {
            return $escaped;
        }

        return preg_replace(
            '/(' . preg_quote($term, '/') . ')/iu',
            '<mark class="bg-primary-100 text-neutral-900 px-0.5 not-italic font-medium">$1</mark>',
            $escaped
        ) ?? $escaped;
    }
}

if (!function_exists('search_content_card_type')) {
    function search_content_card_type(string $contentType): string
    {
        return match ($contentType) {
            'blog' => 'blog',
            'services' => 'service',
            default => 'portfolio',
        };
    }
}

if (!function_exists('site_brand_logo_url')) {
    /** Public logo asset URL, or null if only letter fallback is available. */
    function site_brand_logo_url(): ?string
    {
        foreach (['images/logo.png', 'images/logo.svg'] as $path) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        return null;
    }
}

if (!function_exists('site_brand_initial')) {
    function site_brand_initial(): string
    {
        return mb_strtoupper(mb_substr(config('app.name', 'F'), 0, 1));
    }
}

if (!function_exists('nav_primary_cta_url')) {
    function nav_primary_cta_url(): string
    {
        $cfg = config('forefront.nav_primary_cta', []);
        $path = $cfg['url'] ?? route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form';
        $utm = $cfg['utm'] ?? 'nav_primary_cta';
        $url = str_starts_with($path, 'http') ? $path : url($path);

        return generate_utm_url($url, $utm);
    }
}

if (!function_exists('should_show_sticky_contact_bar')) {
    function should_show_sticky_contact_bar(): bool
    {
        if (request()->routeIs(
            'login', 'register', 'password.*', 'verification.*',
            'password.confirm', '2fa.*', 'contact', 'contact.store'
        )) {
            return false;
        }

        return true;
    }
}

if (! function_exists('lead_magnet')) {
    /**
     * Resolve lead-magnet copy for band / inline CTAs from config.
     *
     * @return array{eyebrow: string, title: string, text: string, button: string, url: string, type: string}
     */
    function lead_magnet(string $type = 'hmis-checklist', string $surface = 'band'): array
    {
        $magnets = config('forefront.lead_magnets', []);
        $config = $magnets[$type] ?? $magnets['default'] ?? [];
        $inquiry = $config['inquiry_type'] ?? 'hmis-demo';
        $utmKey = $surface === 'inline' ? 'utm_inline' : 'utm_band';
        $utm = $config[$utmKey] ?? 'lead_magnet';

        return [
            'type' => $type,
            'eyebrow' => $config['eyebrow'] ?? 'Free Resource',
            'title' => $config['title'] ?? 'Get Started',
            'text' => $config['text'] ?? '',
            'button' => $config['button'] ?? 'Contact Us',
            'url' => generate_utm_url(
                route('contact', ['inquiry_type' => $inquiry]) . '#contact-form',
                $utm
            ),
        ];
    }
}

if (! function_exists('brand_theme_css_vars')) {
    /**
     * CSS custom properties for runtime brand theming (CMS primary_color).
     */
    function brand_theme_css_vars(?string $hex = null): string
    {
        $hex = $hex ?: (string) setting('primary_color', '#0ea5e9');
        $hex = ltrim(trim($hex), '#');
        if (! preg_match('/^[0-9A-Fa-f]{6}$/', $hex)) {
            $hex = '0ea5e9';
        }
        $brand = '#' . strtolower($hex);

        // Simple darken for hover / strong (no GD dependency)
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $darken = static fn (int $c, float $factor): string => sprintf('%02x', max(0, min(255, (int) round($c * $factor))));
        $hover = '#' . $darken($r, 0.85) . $darken($g, 0.85) . $darken($b, 0.85);
        $strong = '#' . $darken($r, 0.7) . $darken($g, 0.7) . $darken($b, 0.7);
        $muted = sprintf('#%02x%02x%02x', min(255, (int) round($r + (255 - $r) * 0.85)), min(255, (int) round($g + (255 - $g) * 0.85)), min(255, (int) round($b + (255 - $b) * 0.85)));

        return implode(' ', [
            "--color-brand: {$brand};",
            "--color-brand-hover: {$hover};",
            "--color-brand-strong: {$strong};",
            "--color-brand-muted: {$muted};",
            "--primary: {$brand};",
            "--primary-hover: {$hover};",
            "--color-primary-500: {$brand};",
            "--color-primary-600: {$hover};",
            "--color-primary-700: {$strong};",
            "--color-primary-100: {$muted};",
        ]);
    }
}


