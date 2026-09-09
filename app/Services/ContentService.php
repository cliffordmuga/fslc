<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Cta;
use App\Models\Tag;
use App\Models\Testimonial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class ContentService
{
    private Agent $agent;

    public function __construct(
        protected ContentCacheManager $cacheManager,
        protected ContentSearchService $searchService,
        protected PageSeoService $pageSeo,
    ) {
        $this->agent = new Agent;
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
            'type' => $type,
            'withImages' => $withImages,
            'variant' => $withImages ? $this->imageVariantForDetail() : null,
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
                    'images' => fn ($q) => $q->select(['id', 'image_url', 'alt_text', 'imageable_id', 'variant', 'collection', 'order'])
                        ->where('variant', $variant),
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
                $query->whereHas('tags', fn ($q) => $q->where('slug', $filters['tag']));
            }
            if ($filters['category'] !== '') {
                $query->whereHas('tags', fn ($q) => $q->where('slug', $filters['category']));
            }

            if ($withImages) {
                $variant = $this->imageVariantForList();
                $query->with([
                    'images' => fn ($q) => $q->select(['id', 'image_url', 'alt_text', 'imageable_id', 'variant', 'collection', 'order'])
                        ->where('variant', $variant),
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
                    'images' => fn ($q) => $q
                        ->select(['id', 'image_url', 'alt_text', 'imageable_id', 'variant', 'collection', 'order'])
                        ->where('variant', $variant),
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
                'images' => fn ($q) => $q
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
     * @param  string  $page  One of: 'home', 'about', 'portfolio', 'services', 'blog'
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
        }, ['ctas']);
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
        }, ['ctas']);
    }

    /**
     * Get featured, approved testimonials. Cached for 1 hour.
     */
    public function getFeaturedTestimonials(int $limit = 5): Collection
    {
        $key = $this->makeCacheKey('testimonials_featured', ['limit' => $limit]);

        return $this->remember($key, now()->addHour(), fn () => Testimonial::approved()->featured()->take($limit)->get(), ['testimonials']);
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
                    'images' => fn ($q) => $q
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
        return $this->pageSeo->getSeoData($page, $content);
    }

    /**
     * @param  array<string, mixed>  $seoData
     * @return array<string, mixed>
     */
    public function applyPaginatedHubSeo(array $seoData, string $routeName, int $page): array
    {
        return $this->pageSeo->applyPaginatedHubSeo($seoData, $routeName, $page);
    }

    /**
     * @param  array<string, mixed>  $seoData
     * @return array<string, mixed>
     */
    public function applyFilteredHubSeo(array $seoData, string $routeName): array
    {
        return $this->pageSeo->applyFilteredHubSeo($seoData, $routeName);
    }

    public function getTagSeoData(Tag $tag): array
    {
        return $this->pageSeo->getTagSeoData($tag);
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<int, array<string, mixed>>
     */
    public function getPageSchemas(string $page, array $context = []): array
    {
        return $this->pageSeo->getPageSchemas($page, $context);
    }

    /**
     * Related content for a detail page — cached so every visitor after the first
     * gets the result from cache instead of hitting the database.
     *
     * @param  string  $type  Content type (portfolio, services, blog, page …)
     * @param  int  $excludeId  The current content's id (excluded from results)
     */
    public function getRelatedContent(string $type, int $excludeId, int $limit = 3): Collection
    {
        $variant = $this->imageVariantForList();

        $key = $this->makeCacheKey('related', [
            'type' => $type,
            'excludeId' => $excludeId,
            'limit' => $limit,
            'variant' => $variant,
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
     * @param  string  $type  'all' or a specific content type
     */
    public function getTagPageContent(Tag $tag, string $type = 'all', int $perPage = 12): LengthAwarePaginator
    {
        $variant = $this->imageVariantForList();
        $page = (int) request()->query('page', 1);

        $key = $this->makeCacheKey('tag_page', [
            'tag_id' => $tag->id,
            'type' => $type,
            'variant' => $variant,
            'page' => $page,
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
        }, ['content:tags', "tag_{$tag->id}"]);
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
