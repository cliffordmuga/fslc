{{-- resources/views/frontend/search.blade.php --}}
@extends('layouts.guest')

@push('head')
    @if ($query !== '' && $results instanceof \Illuminate\Pagination\LengthAwarePaginator && ($results->currentPage() > 1 || $results->hasMorePages()))
        @if ($results->currentPage() > 1)
            <link rel="prev" href="{{ $results->previousPageUrl() }}">
        @endif
        @if ($results->hasMorePages())
            <link rel="next" href="{{ $results->nextPageUrl() }}">
        @endif
    @endif
@endpush

@section('seo')
    @foreach ($pageSchemas ?? [] as $schema)
        <x-seo.json-ld :data="$schema" />
    @endforeach
@endsection

@section('content')

    @php
        use Illuminate\Support\Str;
        $hasQuery = $query !== '';
        $totalCount = $hasQuery && $results instanceof \Illuminate\Pagination\LengthAwarePaginator ? $results->total() : 0;
        $pageCount = $hasQuery ? $results->count() : 0;
        $from = $hasQuery && $totalCount > 0 ? $results->firstItem() : 0;
        $to = $hasQuery && $totalCount > 0 ? $results->lastItem() : 0;
        $crumbQuery = Str::limit($query, 40);
        $pillarLinks = config('forefront.contact_pillar_links', []);
        $searchTypeChips = $hasQuery
            ? collect($typeFilters)->map(fn ($label, $typeKey) => [
                'label' => $label,
                'url' => route('search', array_filter(['q' => $query, 'type' => $typeKey !== '' ? $typeKey : null])),
                'active' => ($activeType ?? '') === (string) $typeKey,
            ])->values()->all()
            : [];
        $searchSummary = $hasQuery && $totalCount > 0
            ? 'Showing ' . $from . '–' . $to . ' of ' . $totalCount . ' ' . Str::plural('result', $totalCount)
                . ($activeType !== '' ? ' in <span class="font-semibold text-neutral-700">' . e($activeTypeLabel) . '</span>' : '')
            : null;
    @endphp

    <x-cms.hub-shell
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $hasQuery ? 'Search: ' . $crumbQuery : 'Search'],
        ]"
        :hero-preload="hero_asset(5)">
        <x-slot:hero>
            <x-cms.hero-section
                theme="dark"
                size="standard"
                :title="$hasQuery ? 'Search Results' : 'Search Insights & Portfolio'"
                :subtitle="$hasQuery ? 'for \'' . e($query) . '\'' : 'Find HMIS guides, software case studies, and digital strategy content for Kenyan institutions.'"
                :image-url="hero_asset(5)"
                :image-srcset="hero_asset_srcset(5)"
                tagline="HMIS · Software · Branding · Public Engagement — practical content for Kenyan institutions."
                :badges="['HMIS', 'Software', 'Branding', 'Campaigns']"
                :cta-url="hub_cta_url('search_hero')" />
        </x-slot:hero>

        <x-cms.filter-chip-nav
            label="Search content types"
            :items="$searchTypeChips"
            :result-summary="$searchSummary"
            sticky>
            <x-slot:prefix>
                <form action="{{ route('search') }}" method="GET" class="relative max-w-xl mb-4">
                    <label for="search-query" class="sr-only">Search</label>
                    <input id="search-query"
                           type="search"
                           name="q"
                           value="{{ $query }}"
                           placeholder="Search HMIS, software, case studies…"
                           class="input-base w-full pl-9 pr-4 py-2.5 text-sm min-h-[48px]"
                           aria-label="Search query"
                           autocomplete="off">
                    @if ($activeType !== '')
                        <input type="hidden" name="type" value="{{ $activeType }}">
                    @endif
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </form>
            </x-slot:prefix>
        </x-cms.filter-chip-nav>

    @if (! $hasQuery)
        {{-- Empty query state --}}
        <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="search-start-heading">
            <div class="page-container">
                <x-cms.section-heading
                    label="Search"
                    title="What are you looking for?"
                    intro="Search published case studies, services, and insights — or jump straight to a practice area below."
                    heading-id="search-start-heading" />

                @if (! empty($suggestedQueries))
                    <div class="mb-10">
                        <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500 mb-3">Popular searches</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($suggestedQueries as $suggestion)
                                <a href="{{ route('search', ['q' => $suggestion['q']]) }}"
                                   class="inline-flex items-center px-4 py-2.5 text-sm font-medium border border-neutral-200 bg-white text-neutral-700 hover:border-primary-400 hover:text-primary-700 transition-colors">
                                    {{ $suggestion['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (! empty($pillarLinks))
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500 mb-3">Browse by pillar</p>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            @foreach ($pillarLinks as $pillar)
                                <a href="{{ route('contact', ['inquiry_type' => $pillar['inquiry_type']]) }}#contact-form"
                                   class="card-base p-4 border-t-4 {{ $pillar['accent'] }} group">
                                    <p class="text-xs font-bold text-neutral-900 group-hover:text-primary-600 transition-colors">{{ $pillar['label'] }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-10 flex flex-wrap gap-4 text-sm">
                    <a href="{{ route('portfolio.index') }}" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        See case studies
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('services.index') }}" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        Explore services
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('insights.index') }}" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        Browse insights
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </section>

    @elseif ($results->isEmpty())
        {{-- No results --}}
        <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="search-no-results-heading">
            <div class="max-w-3xl mx-auto px-6 lg:px-8 text-center">
                <x-cms.section-heading
                    label="No matches"
                    :title="'No results for “' . $query . '”'"
                    intro="We publish guides on HMIS procurement, hospital digitization, election digital strategy, and institutional software — try a different term or request a demo."
                    heading-id="search-no-results-heading"
                    class="text-center border-0 pb-0 mb-6 [&>div]:justify-center [&_h2]:mx-auto [&_p]:mx-auto" />

                @if (! empty($suggestedQueries))
                    <p class="text-sm text-neutral-600 mb-3">Try searching for:</p>
                    <div class="flex flex-wrap justify-center gap-2 mb-8">
                        @foreach ($suggestedQueries as $suggestion)
                            <a href="{{ route('search', ['q' => $suggestion['q']]) }}"
                               class="inline-flex items-center px-4 py-2.5 text-sm font-semibold border border-neutral-200 bg-white text-primary-600 hover:border-primary-400 transition-colors">
                                {{ $suggestion['label'] }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="flex flex-wrap justify-center gap-3 mb-8">
                    <x-ui.button :href="generate_utm_url(route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form', 'search_no_results')" variant="primary" class="text-sm uppercase tracking-wider">
                        Request HMIS Demo
                    </x-ui.button>
                    <x-ui.button :href="route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'" variant="secondary" class="text-sm uppercase tracking-wider">
                        Get HMIS Checklist
                    </x-ui.button>
                </div>

                <p class="text-sm text-neutral-500">
                    Or explore our
                    <a href="{{ route('portfolio.index') }}" class="text-primary-600 hover:underline font-medium">case studies</a>
                    and
                    <a href="{{ route('services.index') }}" class="text-primary-600 hover:underline font-medium">services</a>.
                </p>
            </div>
        </section>

    @else
        {{-- Results grid --}}
        <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="search-results-heading">
            <div class="page-container">
                <x-cms.section-heading
                    label="Results"
                    :title="$totalCount . ' ' . Str::plural('match', $totalCount) . ' for “' . $query . '”'"
                    :intro="$activeType !== '' ? 'Filtered to ' . strtolower($activeTypeLabel) . '.' : null"
                    heading-id="search-results-heading" />

                <div class="content-card-grid">
                    @foreach ($results as $result)
                        <x-cms.content-card
                            :item="$result"
                            :type="search_content_card_type($result->type)"
                            :search-query="$query"
                            style="animation-delay: {{ $loop->index * 0.07 }}s;" />
                    @endforeach
                </div>

                @if ($results instanceof \Illuminate\Pagination\LengthAwarePaginator && $results->hasPages())
                    <nav class="mt-10 flex justify-center" aria-label="Search results pagination">
                        {{ $results->appends(array_filter(['q' => $query, 'type' => $activeType ?: null]))->links('pagination::tailwind') }}
                    </nav>
                @endif

                <div class="mt-10 pt-6 border-t border-neutral-100 flex flex-wrap gap-4 text-sm text-neutral-600">
                    <span>Explore more:</span>
                    <a href="{{ route('services.index') }}" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        Our services
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('portfolio.index') }}" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        Case studies
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('insights.index') }}" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        Insights hub
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <x-cms.page-closer
        :title="$hasQuery && ! $results->isEmpty() ? 'Found something close?' : 'Need help finding the right solution?'"
        subtitle="Request an HMIS demo or download the procurement checklist — we respond within 24 hours."
        :primary-url="hub_cta_url('search_footer_cta')"
        primary-label="Request HMIS Demo"
        :secondary-url="route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'"
        secondary-label="Get HMIS Checklist"
        size="default" />

    </x-cms.hub-shell>

@endsection
