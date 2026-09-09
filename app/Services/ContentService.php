<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Cta;
use App\Models\Tag;
use App\Models\Testimonial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as ConcretePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ContentService
{
    private Agent $agent;

    public function __construct(
        protected ContentCacheManager $cacheManager,
        protected ContentSearchService $searchService,
    ) {
        $this->agent = new Agent();
    }

    /**
     * ✅ Stable version so caches are reusable
     * Bump this when you ship a breaking change to cached payloads.
     */
    protected function cacheVersion(): string
    {
        return $this->cacheManager->cacheVersion();
    }

    protected function makeCacheKey(string $prefix, array $params = []): string
    {
        return $this->cacheManager->makeCacheKey($prefix, $params);
    }

    protected function contentCacheBuster(): int
    {
        return $this->cacheManager->contentCacheBuster();
    }

    protected function remember(string $key, \DateTimeInterface|\DateInterval|int $ttl, \Closure $callback, array $tags = [])
    {
        return $this->cacheManager->remember($key, $ttl, $callback, $tags);
    }

    protected function supportsTags(): bool
    {
        return $this->cacheManager->supportsTags();
    }


    protected function imageVariantForList(): string
    {
        return $this->agent->isMobile() ? 'mobile' : 'thumbnail';
    }

    protected function imageVariantForDetail(): string
    {
        return $this->agent->isMobile() ? 'mobile' : 'main';
    }

    public function getContentByType(string $type, bool $withImages = false): ?Content
    {
        $key = $this->makeCacheKey('type_single', [
            'type'      => $type,
            'withImages' => $withImages,
            'variant'   => $withImages ? $this->imageVariantForDetail() : null,
        ]);

        return $this->remember($key, now()->addHours(12), function () use ($type, $withImages) {
            $query = Content::query()
                ->published()
                ->where('type', $type)
                ->with('seoMetadata')
                ->select(['id', 'title', 'slug', 'type', 'excerpt', 'content', 'published_at', 'sort_order']);

            if ($withImages) {
                $variant = $this->imageVariantForDetail();
                $query->with([
                    'images' => fn($q) =>
                    $q->select(['id', 'image_url', 'alt_text', 'imageable_id', 'variant', 'collection', 'order'])
                        ->where('variant', $variant)
                ]);
            }

            return $query->first();
        }, ["content:{$type}"]);
    }

    public function getPublishedContents(string $type, bool $withImages = false, ?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? ($this->agent->isMobile() ? 6 : 12);

        // Include query filters that affect the list
        $filters = [
            'page' => (int) request()->query('page', 1),
            'tag' => (string) request()->query('tag', ''),
            'category' => (string) request()->query('category', ''),
            'q' => (string) request()->query('q', ''), // if you ever reuse for search-like lists
        ];

        $key = $this->makeCacheKey('type_list', [
            'type' => $type,
            'withImages' => $withImages,
            'perPage' => $perPage,
            'variant' => $withImages ? $this->imageVariantForList() : null,
            'filters' => $filters,
        ]);

        return $this->remember($key, now()->addHours(6), function () use ($type, $withImages, $perPage, $filters) {
            $query = Content::query()
                ->published()
                ->where('type', $type)
                ->with('seoMetadata')
                ->ordered()
                ->select(['id', 'title', 'slug', 'type', 'excerpt', 'published_at', 'sort_order', 'created_by']);

            // Apply filters if present (safe defaults)
            if ($filters['tag'] !== '') {
                $query->whereHas('tags', fn($q) => $q->where('slug', $filters['tag']));
            }
            if ($filters['category'] !== '') {
                $query->whereHas('tags', fn($q) => $q->where('slug', $filters['category']));
            }

            if ($withImages) {
                $variant = $this->imageVariantForList();
                $query->with([
                    'images' => fn($q) =>
                    $q->select(['id', 'image_url', 'alt_text', 'imageable_id', 'variant', 'collection', 'order'])
                        ->where('variant', $variant)
                ]);
            }

            return $query->paginate($perPage)->withQueryString();
        }, ["content:list:{$type}"]);
    }

    public function getPublishedContentsCollection(string $type, bool $withImages = false, ?int $limit = null)
    {
        $variant = $withImages ? $this->imageVariantForList() : null;

        $key = $this->makeCacheKey('type_collection', [
            'type' => $type,
            'withImages' => $withImages,
            'variant' => $variant,
            'limit' => $limit,
        ]);

        return $this->remember($key, now()->addHours(6), function () use ($type, $withImages, $variant, $limit) {
            $query = Content::query()
                ->published()
                ->where('type', $type)
                ->with('seoMetadata')
                ->ordered()
                ->select(['id', 'title', 'slug', 'type', 'excerpt', 'published_at', 'sort_order', 'created_by']);

            if ($withImages) {
                $query->with([
                    'images' => fn($q) => $q
                        ->select(['id', 'image_url', 'alt_text', 'imageable_id', 'variant', 'collection', 'order'])
                        ->where('variant', $variant)
                ]);
            }

            if ($limit) {
                $query->limit($limit);
            }

            return $query->get();
        }, ["content:collection:{$type}"]);
    }

    /**
     * Portfolio items filtered by pillar — pillar is part of the cache key.
     */
    public function getPortfolioByPillar(string $pillar = 'all', bool $withImages = false): Collection
    {
        $pillars = config('forefront.portfolio_pillars', []);
        $variant = $withImages ? $this->imageVariantForList() : null;

        $key = $this->makeCacheKey('portfolio_pillar', [
            'pillar' => $pillar,
            'withImages' => $withImages,
            'variant' => $variant,
        ]);

        return $this->remember($key, now()->addHours(6), function () use ($pillar, $pillars, $withImages, $variant) {
            $query = Content::query()
                ->published()
                ->where('type', 'portfolio')
                ->with('seoMetadata')
                ->ordered()
                ->select(['id', 'title', 'slug', 'type', 'excerpt', 'published_at', 'sort_order', 'created_by']);

            if ($pillar !== 'all' && isset($pillars[$pillar]['slugs']) && ! empty($pillars[$pillar]['slugs'])) {
                $query->whereIn('slug', $pillars[$pillar]['slugs']);
            }

            if ($withImages) {
                $query->with([
                    'images' => fn ($q) => $q
                        ->select(['id', 'image_url', 'alt_text', 'imageable_id', 'variant', 'collection', 'order'])
                        ->where('variant', $variant),
                ]);
            }

            return $query->get();
        }, ['content:collection:portfolio', "content:pillar:{$pillar}"]);
    }

    /**
     * Total published portfolio count (all pillars) — cached separately for filter views.
     */
    public function getPortfolioPublishedCount(): int
    {
        $key = $this->makeCacheKey('portfolio_count_all', []);

        return (int) $this->remember($key, now()->addHours(6), function () {
            return Content::query()
                ->published()
                ->where('type', 'portfolio')
                ->count();
        }, ['content:collection:portfolio']);
    }


    public function getFeaturedContent(string $type, int $limit = 6, array $with = [])
    {
        $variant = $this->imageVariantForList();

        $key = $this->makeCacheKey('featured', [
            'type' => $type,
            'limit' => $limit,
            'variant' => $variant,
            'with' => $with,
        ]);

        return $this->remember($key, now()->addHours(3), function () use ($type, $limit, $variant, $with) {
            $relations = array_merge($with, [
                'images' => fn($q) => $q
                    ->where('collection', 'featured')
                    ->where('variant', $variant)
                    ->ordered(),
            ]);

            return Content::published()
                ->ofType($type)
                ->with($relations)
                ->ordered()
                ->take($limit)
                ->get();
        }, ["content:featured:{$type}"]);
    }

    /**
     * Get the best CTA for a given page (global or content-type-specific).
     * Cached for 1 hour (works with database cache on shared hosting).
     *
     * @param string $page One of: 'home', 'about', 'portfolio', 'services', 'blog'
     * @return \App\Models\Cta|null
     */
    public function getCtaForPage(string $page): ?Cta
    {
        $contentTypes = match ($page) {
            'home' => [],
            'about' => ['about', 'intro'],
            'portfolio' => ['portfolio'],
            'services' => ['services'],
            'blog' => ['blog'],
            default => [],
        };

        $key = $this->makeCacheKey('cta_page', ['page' => $page, 'types' => $contentTypes]);

        return $this->remember($key, now()->addHour(), function () use ($contentTypes) {
            $query = Cta::query()->ordered();

            if (empty($contentTypes)) {
                return $query->whereNull('content_id')->first();
            }

            return $query->where(function ($q) use ($contentTypes) {
                $q->whereNull('content_id')
                    ->orWhereHas('content', fn ($sub) => $sub->whereIn('type', $contentTypes));
            })->first();
        });
    }

    /**
     * Cached CTA for the contact page (global or contact page content).
     */
    public function getContactPageCta(): ?Cta
    {
        $key = $this->makeCacheKey('cta_contact', []);

        return $this->remember($key, now()->addHour(), function () {
            return Cta::query()
                ->ordered()
                ->where(function ($q) {
                    $q->whereNull('content_id')
                        ->orWhereHas('content', fn ($sub) => $sub
                            ->whereIn('type', ['page', 'intro'])
                            ->where('slug', 'contact'));
                })
                ->first();
        });
    }

    /**
     * Get featured, approved testimonials. Cached for 1 hour.
     */
    public function getFeaturedTestimonials(int $limit = 5): Collection
    {
        $key = $this->makeCacheKey('testimonials_featured', ['limit' => $limit]);

        return $this->remember($key, now()->addHour(), fn () =>
            Testimonial::approved()->featured()->take($limit)->get()
        );
    }

    /**
     * Get tags used by published content of a given type. Cached (invalidated when Content/Tag changes).
     */
    public function getTagsForType(string $type): Collection
    {
        $key = $this->makeCacheKey('tags_for_type', ['type' => $type]);

        return $this->remember($key, now()->addMinutes(30), function () use ($type) {
            return Tag::query()
                ->whereHas('contents', fn ($q) => $q->published()->ofType($type))
                ->orderBy('name')
                ->get();
        }, ["content:{$type}"]);
    }

    /**
     * Get timeline items for the About page. CMS-driven; falls back to defaults if empty.
     * Content mapping: excerpt=year, title=title, content=description.
     */
    public function getTimelineItems(): array
    {
        $key = $this->makeCacheKey('about_timeline');

        return $this->remember($key, now()->addMinutes(30), function () {
            $items = Content::published()
                ->where('type', 'timeline_item')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(['id', 'title', 'excerpt', 'content', 'sort_order']);

            if ($items->isEmpty()) {
                return [
                    ['year' => 2015, 'title' => 'Founded in Nairobi', 'description' => 'Forefront Solutions established to deliver HMIS and custom software for Kenyan healthcare and public institutions.'],
                    ['year' => 2017, 'title' => 'First HMIS Go-Live', 'description' => 'Completed inaugural hospital management system deployment for a Nairobi medical centre.'],
                    ['year' => 2020, 'title' => 'Digital Health Practice', 'description' => 'Expanded EMR and community health modules for NGO and county health programmes.'],
                    ['year' => 2022, 'title' => 'Public Engagement Vertical', 'description' => 'Launched campaign and stakeholder engagement services for political and advocacy clients.'],
                    ['year' => 2025, 'title' => 'Four-Pillar Platform', 'description' => 'Unified HMIS, software development, digital communications, and public engagement under one consultancy.'],
                ];
            }

            return $items->map(fn ($c) => [
                'year' => (int) (trim($c->excerpt ?? '') ?: date('Y')),
                'title' => $c->title ?? '',
                'description' => trim(strip_tags($c->content ?? '')) ?: '',
            ])->toArray();
        }, ['content:timeline_item']);
    }

    /**
     * Get FAQ items for the About page. CMS-driven; falls back to defaults if empty.
     * Content mapping: title=question, content=answer.
     */
    public function getFaqItems(): array
    {
        $key = $this->makeCacheKey('about_faq');

        return $this->remember($key, now()->addMinutes(30), function () {
            $items = Content::published()
                ->where('type', 'faq_item')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(['id', 'title', 'content', 'sort_order']);

            if ($items->isEmpty()) {
                return [
                    ['question' => 'How long does a typical project take?', 'answer' => 'Most projects are completed in 4–12 weeks, depending on scope.'],
                    ['question' => 'Do you offer maintenance?', 'answer' => 'Yes, we provide ongoing support packages.'],
                ];
            }

            return $items->map(fn ($c) => [
                'question' => $c->title ?? '',
                'answer' => trim(strip_tags($c->content ?? '')) ?: '',
            ])->toArray();
        }, ['content:faq_item']);
    }

    public function getContentWithRelations(string $type, string $slug): Content
    {
        $variant = $this->imageVariantForDetail();

        $key = $this->makeCacheKey('detail', [
            'type' => $type,
            'slug' => $slug,
            'variant' => $variant,
            'img_profile' => 'gallery_tsm_v1',
        ]);

        return $this->remember($key, now()->addMinutes(30), function () use ($type, $slug, $variant) {
            $content = Content::query()
                ->with([
                    'tags',
                    'ctas',
                    'seoMetadata',
                    'testimonials',
                    'creator',
                    'images' => fn($q) => $q
                        ->select(['id', 'image_url', 'alt_text', 'imageable_id', 'imageable_type', 'variant', 'collection', 'order'])
                        ->where(function ($w) use ($variant) {
                            $w->where(function ($q) use ($variant) {
                                $q->where('collection', '!=', 'gallery')
                                    ->where('variant', $variant);
                            })->orWhere(function ($q) {
                                $q->where('collection', 'gallery')
                                    ->whereIn('variant', ['thumbnail', 'small', 'main']);
                            });
                        })
                        ->orderBy('order')
                        ->orderBy('id'),
                ])
                ->published()
                ->where('type', $type)
                ->where('slug', $slug)
                ->firstOrFail();

            if (config('app.debug')) {
                Log::debug("Content cache miss: {$type}/{$slug}", ['variant' => $variant]);
            }

            return $content;
        }, ["content:{$type}"]);
    }


    public function getSeoData(string $page, ?Content $content = null): array
    {
        // If content is provided, let Seoable trait be the source of truth
        if ($content) {
            return [
                'title'           => $content->getSeoTitle(),
                'description'     => $content->getSeoDescription(),
                'keywords'        => $content->seoMetadata?->meta_keywords ?? 'digital agency Kenya, web development, Laravel, East Africa',
                'og_title'        => $content->seoMetadata?->og_title ?? $content->getSeoTitle(),
                'og_description'  => $content->seoMetadata?->og_description ?? $content->getSeoDescription(),
                'og_image'        => $content->getOgImage(),
                'canonical_url'   => $content->getCanonicalUrl(),
                'noindex'         => (bool) ($content->seoMetadata?->noindex ?? false),
                'nofollow'        => (bool) ($content->seoMetadata?->nofollow ?? false),
                'structured_data' => $content->getStructuredData(),
                'preload_image'   => $content->getOgImage(),
            ];
        }

        // Page-level defaults (do NOT pre-append site name; meta_title() in the layout will do it)
        $page = trim((string) $page);

        $defaultTitle = $this->getPageSeoTitle($page);
        $defaultDescription = $this->getPageSeoDescription($page);

        $keywordMap = [
            'home'     => 'HMIS Kenya, hospital management system Kenya, web development company Kenya, election digital strategy Kenya',
            'about'    => 'HMIS vendor Kenya, digital transformation Nairobi, Forefront Solutions, healthcare technology Kenya',
            'portfolio'=> 'HMIS case studies Kenya, hospital EMR projects, campaign websites Kenya, software development Kenya',
            'services' => 'HMIS Kenya, EMR Kenya, Laravel developers Kenya, branding agency Kenya, political campaign website Kenya',
            'blog'     => 'HMIS Kenya, SHA integration, election digital strategy, M-Pesa integration, SEO Kenya',
            'contact'  => 'HMIS demo Kenya, software consultation Nairobi, campaign strategy Kenya, Forefront Solutions contact',
            'privacy'  => 'privacy policy Kenya, data protection healthcare IT',
            'terms'    => 'terms of service, IT consultancy Kenya',
        ];
        $keywords = $keywordMap[$page] ?? 'portfolio, services, digital agency, Kenya';

        return [
            'title'           => $defaultTitle, // meta_title() in Blade will append site name
            'description'     => $defaultDescription,
            'keywords'        => $keywords,
            'og_title'        => $defaultTitle ? $defaultTitle : config('app.name'),
            'og_description'  => $defaultDescription,
            'og_image'        => asset('images/default-og-image.png'),
            'canonical_url'   => $this->pageCanonicalUrl($page),
            'noindex'         => false,
            'structured_data' => [
                '@context' => 'https://schema.org',
                '@type'    => 'WebPage',
                'name'     => $defaultTitle ? ($defaultTitle . ' | ' . config('app.name')) : config('app.name'),
                'url'      => $this->pageCanonicalUrl($page),
            ],
        ];
    }

    /**
     * Canonical URL for hub/list pages without query filters.
     */
    protected function pageCanonicalUrl(string $page): string
    {
        $routeMap = [
            'home'      => 'home',
            'about'     => 'about',
            'portfolio' => 'portfolio.index',
            'services'  => 'services.index',
            'blog'      => 'insights.index',
            'contact'   => 'contact',
        ];

        if (isset($routeMap[$page])) {
            return route($routeMap[$page]);
        }

        return canonical_url();
    }

    /**
     * SEO for paginated hub views — self-referencing canonical per page.
     *
     * @param  array<string, mixed>  $seoData
     * @return array<string, mixed>
     */
    public function applyPaginatedHubSeo(array $seoData, string $routeName, int $page): array
    {
        $seoData['canonical_url'] = $page > 1
            ? route($routeName, ['page' => $page])
            : route($routeName);

        return $seoData;
    }

    /**
     * SEO for filtered hub views (?pillar=, ?category=) — canonical to base hub, noindex.
     *
     * @param  array<string, mixed>  $seoData
     * @return array<string, mixed>
     */
    public function applyFilteredHubSeo(array $seoData, string $routeName): array
    {
        $seoData['canonical_url'] = route($routeName);
        $seoData['noindex'] = true;

        return $seoData;
    }

    /**
     * SEO metadata for tag archive pages.
     */
    public function getTagSeoData(Tag $tag): array
    {
        $company = setting('company_name', config('app.name'));
        $title = "{$tag->name} — Content Hub";
        $description = $tag->meta_description
            ?: ($tag->hub_intro ?: (config("forefront.tag_hub_intros.{$tag->slug}") ?? "Browse {$tag->name} insights, services, and case studies from {$company}."));
        $description = meta_description($description);

        $page = max(1, (int) request()->query('page', 1));
        $typeFilter = (string) request()->query('type', 'all');
        $hasTypeFilter = $typeFilter !== '' && $typeFilter !== 'all';

        $canonical = $hasTypeFilter
            ? route('tags.show', $tag->slug)
            : ($page > 1
                ? route('tags.show', ['slug' => $tag->slug, 'page' => $page])
                : route('tags.show', $tag->slug));

        return [
            'title'           => $title,
            'description'     => $description,
            'og_title'        => $title,
            'og_description'  => $description,
            'og_image'        => cdn_asset('images/default-og-image.png'),
            'canonical_url'   => $canonical,
            'noindex'         => $hasTypeFilter,
        ];
    }

    protected function getPageSeoTitle(string $page): ?string
    {
        return match ($page) {
            'home'      => 'HMIS & Digital Health Kenya | Software, Branding & Public Engagement',
            'about'     => 'About Forefront Solutions — Healthcare Technology Partner in Kenya',
            'portfolio' => 'Case Studies — HMIS, Software, Campaign & SACCO Projects in Kenya',
            'services'  => 'Core Services — HMIS, Software, Branding & Political Engagement',
            'blog'      => 'Insights — HMIS, Elections, Software & Digital Marketing in Kenya',
            'contact'   => 'Contact Forefront Solutions | HMIS Demo, Software & Campaign Consultation',
            'privacy'   => 'Privacy Policy',
            'terms'     => 'Terms & Conditions',
            'sitemap'   => 'Sitemap',
            default     => mb_strlen($page) ? ucfirst($page) : null,
        };
    }

    protected function getPageSeoDescription(string $page): string
    {
        $foundedYear = setting('founded_year', '2015');

        return match ($page) {
            'home' => 'Forefront Solutions is a Kenyan digital transformation company specializing in HMIS and healthcare technology, custom software development, digital communications, and public engagement platforms.',
            'about' => "Since {$foundedYear}, Forefront Solutions has focused on four pillars — HMIS & digital health, custom software, branding & marketing, and political & public engagement — for hospitals, government, NGOs, and campaigns in Kenya.",
            'portfolio' => 'Explore Forefront case studies: county HMIS deployments, NGO EMR programmes, government portals, M-Pesa e-commerce, campaign digital hubs, and SACCO member portals.',
            'services' => 'Four focused service pillars: HMIS & digital health, custom software & web development, digital strategy & branding, and political & public engagement — built for Kenyan institutions.',
            'blog' => 'HMIS procurement, SHA integration, election digital strategy, Laravel development, M-Pesa integration, and SEO guides for Kenyan healthcare, government, and campaign teams.',
            'contact' => 'Request an HMIS demo, software quote, or campaign strategy session with Forefront Solutions Kenya. We respond within 24 hours.',
            'privacy' => 'How Forefront Solutions (K) Ltd collects, uses, and protects personal information submitted through our website and client portals.',
            'terms' => 'Terms governing use of the Forefront Solutions website and professional technology, HMIS, and communications services in Kenya.',
            'sitemap' => 'Browse Forefront Solutions — HMIS, services, portfolio, insights, and contact pages.',
            default => setting('site_description', 'HMIS, digital health, web development, and strategic communications — Kenya.'),
        };
    }

    /**
     * Get JSON-LD schema(s) for a page. Returns array of schema arrays (some pages have multiple).
     *
     * @param string $page home|about|services|portfolio|blog|contact|search
     * @param array $context Page-specific data (e.g. $about, $services, $faqItems, $query)
     * @return array<array>
     */
    public function getPageSchemas(string $page, array $context = []): array
    {
        $schemas = [];

        switch ($page) {
            case 'home':
                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebSite',
                    'name' => setting('company_name'),
                    'url' => url('/'),
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => ['@type' => 'EntryPoint', 'urlTemplate' => url('/search?q={search_term_string}')],
                        'query-input' => 'required name=search_term_string',
                    ],
                    'description' => setting('site_description', 'HMIS and digital transformation partner in Kenya.'),
                ];

                $portfolioItems = $context['portfolioItems'] ?? collect();
                if ($portfolioItems->isNotEmpty()) {
                    $schemas[] = [
                        '@context' => 'https://schema.org',
                        '@type' => 'ItemList',
                        'name' => 'Featured Portfolio — ' . setting('company_name'),
                        'itemListElement' => $portfolioItems->take(6)->values()->map(fn ($item, $index) => [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'name' => $item->title,
                            'url' => $item->url ?? url('/'),
                        ])->all(),
                    ];
                }

                $services = $context['services'] ?? collect();
                if ($services->isNotEmpty()) {
                    $schemas[] = [
                        '@context' => 'https://schema.org',
                        '@type' => 'ItemList',
                        'name' => 'Core Services — ' . setting('company_name'),
                        'itemListElement' => $services->take(4)->values()->map(fn ($item, $index) => [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'name' => $item->title,
                            'url' => $item->url ?? url('/'),
                        ])->all(),
                    ];
                }
                break;

            case 'about':
                $about = $context['about'] ?? null;
                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => setting('company_name'),
                    'description' => ($about?->excerpt ?? setting('site_description', 'HMIS vendor and digital transformation partner in Kenya.')),
                    'url' => route('about'),
                    'foundingDate' => setting('founded_year', '2015'),
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => setting('address_street', '123 Business Ave, Suite 100'),
                        'addressLocality' => setting('address_city', 'Nairobi'),
                        'addressRegion' => setting('address_state', 'Nairobi'),
                        'postalCode' => setting('address_zip', '00100'),
                        'addressCountry' => 'KE',
                    ],
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'contactType' => 'Customer Service',
                        'url' => route('contact') . '#contact-form',
                        'areaServed' => 'KE',
                        'availableLanguage' => ['English', 'Swahili'],
                    ],
                ];
                $faqItems = $context['faqItems'] ?? [];
                if (!empty($faqItems) && count($faqItems) > 0) {
                    $schemas[] = [
                        '@context' => 'https://schema.org',
                        '@type' => 'FAQPage',
                        'mainEntity' => array_map(function ($faq) {
                            $answer = html_entity_decode(strip_tags((string) ($faq['answer'] ?? '')), ENT_QUOTES, 'UTF-8');
                            $answer = trim(preg_replace('/\s+/', ' ', $answer) ?? '');

                            return [
                                '@type' => 'Question',
                                'name' => strip_tags((string) ($faq['question'] ?? '')),
                                'acceptedAnswer' => [
                                    '@type' => 'Answer',
                                    'text' => Str::limit($answer, 5000, ''),
                                ],
                            ];
                        }, $faqItems),
                    ];
                }
                break;

            case 'services':
                $services = $context['services'] ?? collect();
                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Service',
                    'name' => setting('company_name') . ' — Core Services',
                    'serviceType' => 'Healthcare Technology and Digital Transformation',
                    'provider' => $this->organizationSchema(),
                    'areaServed' => ['KE', 'East Africa'],
                    'knowsAbout' => ['HMIS', 'Hospital Management System', 'Software Development', 'Digital Marketing', 'Election Digital Strategy'],
                    'offers' => $services->map(fn ($s) => [
                        '@type' => 'Offer',
                        'itemOffered' => ['@type' => 'Service', 'name' => $s->title, 'description' => generate_excerpt($s->excerpt)],
                        'price' => $s->price ? 'KES ' . number_format($s->price) : 'Custom Quote',
                        'priceCurrency' => 'KES',
                    ])->values()->toArray(),
                ];
                break;

            case 'portfolio':
                $portfolioItems = $context['portfolioItems'] ?? collect();
                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'ItemList',
                    'name' => 'Portfolio',
                    'itemListElement' => $portfolioItems->take(6)->map(function ($item, $index) {
                        $featuredUrl = $item->featured_image ?? $item->featured_image_url ?? asset('images/default-og-image.png');
                        return [
                            '@type' => 'CreativeWork',
                            'name' => $item->title,
                            'image' => $featuredUrl,
                            'url' => $item->url,
                            'description' => generate_excerpt($item->excerpt),
                            'position' => $index + 1,
                        ];
                    })->values()->toArray(),
                ];
                break;

            case 'blog':
                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Blog',
                    'name' => 'Insights — ' . setting('company_name'),
                    'description' => 'HMIS guides, election digital strategy, software development, and digital marketing insights for Kenyan institutions.',
                    'url' => route('insights.index'),
                ];
                break;

            case 'contact':
                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'ContactPage',
                    'name' => 'Contact ' . setting('company_name'),
                    'url' => route('contact'),
                    'description' => 'Request an HMIS demo, software quote, brand consultation, or campaign strategy session in Kenya.',
                    'mainEntity' => $this->organizationSchema(),
                ];
                break;

            case 'search':
                $query = $context['query'] ?? '';
                $results = $context['results'] ?? null;
                $searchSchema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'SearchResultsPage',
                    'name' => $query === '' ? 'Site Search' : "Search Results for '{$query}'",
                    'url' => request()->fullUrl(),
                    'searchTerms' => $query,
                    'about' => [
                        '@type' => 'Organization',
                        'name' => setting('company_name'),
                        'description' => 'HMIS vendor and digital transformation partner in Kenya.',
                    ],
                ];

                if ($results && method_exists($results, 'count') && $results->count() > 0) {
                    $items = method_exists($results, 'getCollection')
                        ? $results->getCollection()
                        : collect($results);

                    $searchSchema['mainEntity'] = [
                        '@type' => 'ItemList',
                        'numberOfItems' => method_exists($results, 'total') ? $results->total() : $results->count(),
                        'itemListElement' => $items->take(10)->values()->map(fn ($item, $index) => [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'name' => $item->title,
                            'url' => $item->url ?? url('/'),
                        ])->all(),
                    ];
                }

                $schemas[] = $searchSchema;
                break;

            case 'tag':
                $tag = $context['tag'] ?? null;
                if ($tag) {
                    $intro = $tag->hub_intro ?: (config("forefront.tag_hub_intros.{$tag->slug}") ?? null);
                    $schemas[] = [
                        '@context' => 'https://schema.org',
                        '@type' => 'CollectionPage',
                        'name' => "{$tag->name} — Content Hub",
                        'url' => route('tags.show', $tag->slug),
                        'description' => $intro ?? "Browse content tagged with {$tag->name}.",
                    ];
                }
                break;

            case 'details':
                $content = $context['content'] ?? null;
                if (!$content) {
                    break;
                }
                $schemaType = match ($content->type) {
                    'portfolio' => 'CreativeWork',
                    'blog' => 'BlogPosting',
                    'services' => $content->slug === 'hmis-digital-health-solutions-kenya' ? 'MedicalBusiness' : 'Service',
                    default => 'Article',
                };
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => $schemaType,
                    'headline' => $content->title,
                    'name' => $content->title,
                    'description' => generate_excerpt($content->excerpt ?? $content->content),
                    'datePublished' => $content->published_at?->toIso8601String(),
                    'inLanguage' => 'en-KE',
                    'author' => [
                        '@type' => 'Organization',
                        'name' => setting('company_name'),
                    ],
                    'image' => $content->featured_image_url ?? asset('images/og/company.svg'),
                    'url' => $content->url ?? request()->url(),
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => setting('company_name'),
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => asset('images/og/company.svg'),
                        ],
                    ],
                    'areaServed' => ['KE', 'East Africa'],
                ];

                if ($content->type === 'services' && $content->slug === 'hmis-digital-health-solutions-kenya') {
                    $schema['medicalSpecialty'] = 'Hospital Information Systems';
                    $schema['category'] = 'Hospital Management System';
                }

                $schemas[] = $schema;
                break;
        }

        return $schemas;
    }

    private function organizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => setting('company_name'),
            'url' => url('/'),
            'foundingDate' => setting('founded_year', '2015'),
            'description' => setting('site_description'),
            'knowsAbout' => ['HMIS', 'Software Development', 'Digital Marketing', 'Election Digital Strategy'],
            'address' => $this->postalAddressSchema(),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'telephone' => setting('phone'),
                'email' => setting('email'),
                'url' => route('contact') . '#contact-form',
                'areaServed' => 'KE',
                'availableLanguage' => ['English', 'Swahili'],
            ],
        ];
    }

    private function localBusinessSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => setting('company_name'),
            'url' => url('/'),
            'image' => asset('images/og/company.svg'),
            'telephone' => setting('phone'),
            'email' => setting('email'),
            'address' => $this->postalAddressSchema(),
            'areaServed' => 'Kenya',
        ];
    }

    private function postalAddressSchema(): array
    {
        return [
            '@type' => 'PostalAddress',
            'streetAddress' => setting('address_street', 'The Place Plaza, 5th Floor, Off Church Road'),
            'addressLocality' => setting('address_city', 'Nairobi'),
            'addressRegion' => setting('address_state', 'Nairobi County'),
            'postalCode' => setting('address_zip', '00100'),
            'addressCountry' => 'KE',
        ];
    }

    /**
     * Related content for a detail page — cached so every visitor after the first
     * gets the result from cache instead of hitting the database.
     *
     * @param string $type  Content type (portfolio, services, blog, page …)
     * @param int    $excludeId  The current content's id (excluded from results)
     * @param int    $limit
     */
    public function getRelatedContent(string $type, int $excludeId, int $limit = 3): Collection
    {
        $variant = $this->imageVariantForList();

        $key = $this->makeCacheKey('related', [
            'type'      => $type,
            'excludeId' => $excludeId,
            'limit'     => $limit,
            'variant'   => $variant,
        ]);

        return $this->remember($key, now()->addMinutes(30), function () use ($type, $excludeId, $variant, $limit) {
            return Content::published()
                ->ofType($type)
                ->where('id', '!=', $excludeId)
                ->with([
                    'seoMetadata',
                    'images' => fn ($q) => $q
                        ->select(['id', 'image_url', 'alt_text', 'imageable_id', 'variant', 'collection', 'order'])
                        ->where('variant', $variant),
                ])
                ->ordered()
                ->limit($limit)
                ->get();
        }, ["content:{$type}"]);
    }

    /**
     * Cached, paginated content list for a tag page.
     * Scopes images to a single variant (same as listing pages) to avoid the N+1
     * that the old direct ->with(['images']) caused.
     *
     * @param Tag    $tag
     * @param string $type 'all' or a specific content type
     * @param int    $perPage
     */
    public function getTagPageContent(Tag $tag, string $type = 'all', int $perPage = 12): LengthAwarePaginator
    {
        $variant = $this->imageVariantForList();
        $page    = (int) request()->query('page', 1);

        $key = $this->makeCacheKey('tag_page', [
            'tag_id'  => $tag->id,
            'type'    => $type,
            'variant' => $variant,
            'page'    => $page,
        ]);

        return $this->remember($key, now()->addMinutes(15), function () use ($tag, $type, $variant, $perPage) {
            $query = Content::published()
                ->whereHas('tags', fn ($q) => $q->where('tags.id', $tag->id))
                ->with([
                    'seoMetadata',
                    'tags',
                    'images' => fn ($q) => $q
                        ->select(['id', 'image_url', 'alt_text', 'imageable_id', 'variant', 'collection', 'order'])
                        ->where('variant', $variant),
                ])
                ->ordered();

            if ($type !== 'all') {
                $query->where('type', $type);
            }

            return $query->paginate($perPage)->withQueryString();
        });
    }

    public function getContactPageContent(): ?Content
    {
        $key = $this->makeCacheKey('contact_page', []);

        return $this->remember($key, now()->addHours(12), function () {
            return Content::query()
                ->published()
                ->where(function ($q) {
                    $q->where('slug', 'contact')->orWhere('slug', 'intro');
                })
                ->whereIn('type', ['page', 'intro'])
                ->with('seoMetadata')
                ->first();
        }, ['content:contact']);
    }

    public function getPublishedServicesList(): Collection
    {
        $key = $this->makeCacheKey('services_list_contact', []);

        return $this->remember($key, now()->addHours(6), function () {
            return Content::query()
                ->published()
                ->where('type', 'services')
                ->orderBy('sort_order')
                ->get(['id', 'title', 'slug']);
        }, ['content:list:services']);
    }

    public function searchPublishedContents(string $term, int $perPage = 15, ?string $type = null): LengthAwarePaginator
    {
        return $this->searchService->searchPublishedContents($term, $perPage, $type);
    }
}
