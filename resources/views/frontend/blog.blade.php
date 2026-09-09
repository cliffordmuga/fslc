{{-- resources/views/frontend/blog.blade.php — Insights hub --}}
@extends('layouts.guest')

@section('seo')
    @foreach ($pageSchemas ?? [] as $schema)
        <x-seo.json-ld :data="$schema" />
    @endforeach
@endsection

@push('head')
    @if ($blogPosts instanceof \Illuminate\Pagination\LengthAwarePaginator && ($blogPosts->currentPage() > 1 || $blogPosts->hasMorePages()))
        @if ($blogPosts->currentPage() > 1)
            <link rel="prev" href="{{ $blogPosts->previousPageUrl() }}">
        @endif
        @if ($blogPosts->hasMorePages())
            <link rel="next" href="{{ $blogPosts->nextPageUrl() }}">
        @endif
    @endif
@endpush

@section('content')

    @php
        $activeCategory = $category ?? '';
        $resultCount = $blogPosts->count();
        $totalCount = $blogPosts instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $blogPosts->total()
            : $resultCount;
        $hubSlugs = $tagHubSlugs ?? ['hmis', 'election-digital-strategy', 'sha-integration', 'digital-health', 'laravel'];
        $categoryChips = collect([['slug' => '', 'name' => 'All', 'hub' => false]])
            ->merge($categories->map(fn ($tag) => ['slug' => $tag->slug, 'name' => $tag->name, 'hub' => in_array($tag->slug, $hubSlugs, true)]))
            ->map(fn ($tag) => [
                'label' => $tag['name'],
                'url' => $tag['slug'] === ''
                    ? route('insights.index')
                    : ($tag['hub']
                        ? route('tags.show', $tag['slug'])
                        : route('insights.index', ['category' => $tag['slug']])),
                'active' => $activeCategory === $tag['slug'],
                'title' => $tag['slug'] === ''
                    ? null
                    : ($tag['hub'] ? 'Browse full ' . $tag['name'] . ' hub' : 'Filter insights by ' . $tag['name']),
            ])
            ->values()
            ->all();
        $insightsSummary = 'Showing ' . $resultCount . ' ' . \Illuminate\Support\Str::plural('article', $resultCount) . ' on this page'
            . ($activeCategory !== '' ? ' in <span class="font-semibold text-neutral-700">' . e($activeCategoryLabel) . '</span>' : '')
            . ($totalCount > $resultCount ? ' · ' . $totalCount . ' total' : '');
    @endphp

    <x-cms.hub-shell
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Insights']]"
        :hero-preload="hero_asset(6)">
        <x-slot:hero>
            <x-cms.hero-section
                theme="dark"
                size="standard"
                title="Insights & Guides"
                :image-url="hero_asset(6)"
                :image-srcset="hero_asset_srcset(6)"
                subtitle="HMIS procurement guides, hospital digitization, election digital strategy, and software development insights for Kenyan hospitals, NGOs, and institutions."
                tagline="Practical HMIS, software, and campaign content for Kenyan institutions."
                :badges="['HMIS', 'Software', 'Branding', 'Campaigns']"
                :cta="$blogCta ?? null"
                :cta-url="hub_cta_url('insights_hero')" />
        </x-slot:hero>

        <x-slot:stats>
            <x-cms.stats-strip
                compact
                :columns="3"
                :items="[
                    ['value' => (string) $totalCount, 'label' => 'Published guides'],
                    ['value' => (string) $categories->count(), 'label' => 'Topics covered'],
                    ['value' => 'HMIS', 'label' => 'Primary content cluster'],
                ]" />
        </x-slot:stats>

        <x-cms.filter-chip-nav label="Insight topics" :items="$categoryChips" :result-summary="$insightsSummary">
            <x-slot:prefix>
                <form action="{{ route('search') }}" method="get" class="relative flex-1 max-w-md mb-4 lg:mb-0 lg:mr-4">
                    <label for="insights-search" class="sr-only">Search insights</label>
                    <input id="insights-search"
                           type="search"
                           name="q"
                           placeholder="Search all insights — press Enter"
                           class="input-base w-full pl-9 pr-4 py-2.5 text-sm min-h-[48px]"
                           autocomplete="off">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </form>
            </x-slot:prefix>
        </x-cms.filter-chip-nav>

    {{-- ── Articles grid ───────────────────────────────────────────────────────── --}}
    <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="insights-heading">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <x-cms.section-heading
                label="Insights"
                heading-id="insights-heading"
                :title="$activeCategory !== '' ? $activeCategoryLabel : 'Latest guides & case studies'"
                intro="Guides on HMIS procurement, SHA integration, Laravel development, and election digital strategy — filter by topic, open a dedicated tag hub, or search the full library." />

            <x-cms.content-grid
                :items="$blogPosts"
                type="blog"
                :show-title="false"
                embedded
                :show-cta-banner="false"
                :show-load-more="false"
                :per-page="9"
                :columns="3"
                highlight-slug="best-hmis-kenya-hospital-evaluation-guide"
                :empty-headline="$activeCategory !== '' ? 'No articles in this topic yet' : null"
                :empty-message="$activeCategory !== '' ? 'Try another topic, browse a tag hub, or view all insights.' : null" />

            @if ($blogPosts instanceof \Illuminate\Pagination\LengthAwarePaginator && $blogPosts->hasPages())
                <div class="mt-10 border-t border-neutral-200 pt-6">
                    {{ $blogPosts->links() }}
                </div>
            @endif

            <div class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm text-neutral-600 border-t border-neutral-200 pt-6">
                <span>Ready to implement what you read?</span>
                <a href="{{ route('services.index') }}" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                    Explore our services
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('portfolio.index') }}" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                    See case studies
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>

    <x-cms.page-closer
        title="Ready to Act on These Insights?"
        subtitle="Request an HMIS demo or download the procurement checklist — we respond within 24 hours."
        :primary-url="hub_cta_url('insights_footer_cta')"
        primary-label="Request HMIS Demo"
        :secondary-url="route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'"
        secondary-label="Get HMIS Checklist" />

    </x-cms.hub-shell>

@endsection
