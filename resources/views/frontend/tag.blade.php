{{-- resources/views/frontend/tag.blade.php --}}
@extends('layouts.guest')

@push('head')
    @if ($items instanceof \Illuminate\Pagination\LengthAwarePaginator && ($items->currentPage() > 1 || $items->hasMorePages()))
        @if ($items->currentPage() > 1)
            <link rel="prev" href="{{ $items->previousPageUrl() }}">
        @endif
        @if ($items->hasMorePages())
            <link rel="next" href="{{ $items->nextPageUrl() }}">
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
        $tabs = [
            'all' => 'All',
            'blog' => 'Insights',
            'portfolio' => 'Case studies',
            'services' => 'Services',
        ];
        $totalCount = $items instanceof \Illuminate\Pagination\LengthAwarePaginator ? $items->total() : $items->count();
        $pageCount = $items->count();
        $from = $totalCount > 0 && $items instanceof \Illuminate\Pagination\LengthAwarePaginator ? $items->firstItem() : 0;
        $to = $totalCount > 0 && $items instanceof \Illuminate\Pagination\LengthAwarePaginator ? $items->lastItem() : 0;
        $activeTabLabel = $tabs[$type] ?? 'All';
        $tagChips = collect($tabs)->map(fn ($label, $key) => [
            'label' => $label,
            'url' => route('tags.show', $tag->slug) . ($key === 'all' ? '' : '?type=' . $key),
            'active' => $type === $key,
        ])->values()->all();
        $tagSummary = $totalCount > 0
            ? 'Showing ' . $from . '–' . $to . ' of ' . $totalCount . ' ' . \Illuminate\Support\Str::plural('item', $totalCount)
                . ($type !== 'all' ? ' in <span class="font-semibold text-neutral-700">' . e($activeTabLabel) . '</span>' : '')
            : null;
    @endphp

    <x-cms.hub-shell
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Insights', 'url' => route('insights.index')],
            ['label' => 'Tag: ' . $tag->name],
        ]"
        :hero-preload="hero_asset(5)">
        <x-slot:hero>
            <x-cms.hero-section
                theme="dark"
                size="standard"
                :title="$tagIntro ? $tag->name . ' — Content Hub' : $tag->name"
                :subtitle="$tagIntro ?? ('Case studies, services, and insights tagged ' . $tag->name . ' for Kenyan hospitals, NGOs, and institutions.')"
                tagline="Filter by content type below, or browse everything in this topic cluster."
                :image-url="hero_asset(5)"
                :image-srcset="hero_asset_srcset(5)"
                :badges="['HMIS', 'Software', 'Branding', 'Campaigns']"
                :cta-url="hub_cta_url('tag_hero_' . $tag->slug)" />
        </x-slot:hero>

        <x-cms.filter-chip-nav label="Tag content types" :items="$tagChips" :result-summary="$tagSummary" />

    <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="tag-results-heading">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <x-cms.section-heading
                :label="$tag->name"
                heading-id="tag-results-heading"
                :title="$type === 'all' ? 'Everything tagged #' . $tag->name : $activeTabLabel . ' tagged #' . $tag->name" />

            @if ($items->isEmpty())
                <div class="card-base p-8 text-center border-t-4 border-t-primary-500">
                    <p class="section-label mb-2">No matches</p>
                    <h3 class="text-xl font-black text-neutral-900 mb-2">Nothing published in this category yet</h3>
                    <p class="text-sm text-neutral-600 mb-6">
                        We haven&rsquo;t published anything tagged <strong>#{{ $tag->name }}</strong> in this filter yet.
                    </p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="{{ route('tags.show', $tag->slug) }}" class="btn-secondary text-sm px-5 py-2.5 min-h-[48px]">View all types</a>
                        <a href="{{ route('insights.index') }}" class="btn-primary text-sm px-5 py-2.5 min-h-[48px]">Browse insights</a>
                    </div>
                </div>
            @else
                <div class="content-card-grid">
                    @foreach ($items as $item)
                        <x-cms.content-card
                            :item="$item"
                            :type="search_content_card_type($item->type)"
                            style="animation-delay: {{ $loop->index * 0.07 }}s;" />
                    @endforeach
                </div>

                @if ($items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages())
                    <nav class="mt-10 flex justify-center" aria-label="Tag results pagination">
                        {{ $items->appends(['type' => $type !== 'all' ? $type : null])->links('pagination::tailwind') }}
                    </nav>
                @endif
            @endif
        </div>
    </section>

    <x-cms.page-closer
        title="Found something relevant?"
        subtitle="Request an HMIS demo or download the procurement checklist — we respond within 24 hours."
        :primary-url="hub_cta_url('tag_footer_' . $tag->slug)"
        primary-label="Request HMIS Demo"
        :secondary-url="route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'"
        secondary-label="Get HMIS Checklist"
        size="default" />

    </x-cms.hub-shell>

@endsection
