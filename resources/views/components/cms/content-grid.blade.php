{{-- resources/views/components/cms/content-grid.blade.php --}}
@props([
    'items',
    'title' => null,
    'subtitle' => null,
    'type' => 'portfolio',
    'perPage' => 6,
    'showTitle' => true,
    'embedded' => false,
    'showCtaBanner' => true,
    'showLoadMore' => false,
    'highlightSlug' => null,
    'columns' => 3,
    'emptyHeadline' => null,
    'emptyMessage' => null,
    'globalCta' => null,
    'ctaBannerUrl' => null,
    'ctaBannerText' => 'Request HMIS Demo',
    'ctaBannerHeadline' => null,
])

@php
    $subtitle = $subtitle ?? null;
    $isPaginated = $items instanceof \Illuminate\Pagination\LengthAwarePaginator;
    $displayItems = $isPaginated ? $items : $items->take($perPage ?? 6);
    $wrapperTag = $embedded ? 'div' : 'section';
    $wrapperClass = $embedded
        ? 'py-0 bg-transparent'
        : 'py-14 lg:py-20 bg-neutral-50/50';
    $innerClass = $embedded ? '' : 'max-w-7xl mx-auto px-6 lg:px-8';
    $gridClass = match ((int) $columns) {
        4 => 'content-card-grid content-card-grid-xl',
        default => 'content-card-grid',
    };
    $emptyTitle = $emptyHeadline ?? ('No ' . ($title ? strtolower($title) : ($type === 'blog' ? 'articles' : 'items')) . ' available yet');
    $emptyBody = $emptyMessage ?? "Check back soon — we're always adding new work and insights.";
@endphp

<{{ $wrapperTag }} {{ $attributes->merge(['class' => $wrapperClass]) }} @if($showTitle && !$embedded) aria-labelledby="grid-title-{{ $type }}" @endif>
    <div @if($innerClass) class="{{ $innerClass }}" @endif>
        @if ($showTitle)
            <div class="text-center mb-8 lg:mb-10">
                <h2 id="grid-title-{{ $type }}"
                    class="text-3xl md:text-4xl font-black tracking-tight text-neutral-900 mb-3 lg:mb-4">
                    {{ $title }}
                </h2>
                @if ($subtitle)
                    <p class="text-base lg:text-lg text-neutral-600 max-w-3xl mx-auto font-light leading-relaxed">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        @endif

        <div class="{{ $gridClass }}">
            @forelse ($displayItems as $item)
                <x-cms.content-card
                    :item="$item"
                    :type="$type"
                    :highlighted="$highlightSlug && $item->slug === $highlightSlug"
                    style="animation-delay: {{ $loop->index * 0.07 }}s;" />
            @empty
                <div class="col-span-full py-16 border border-neutral-200 border-t-4 border-t-primary-500 bg-neutral-50 text-center px-8">
                    <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-600 mb-3">Nothing Here Yet</p>
                    <h3 class="text-xl font-black text-neutral-900 tracking-tight mb-2">
                        {{ $emptyTitle }}
                    </h3>
                    <p class="text-sm text-neutral-500 mb-6 max-w-xs mx-auto leading-relaxed">
                        {{ $emptyBody }}
                    </p>
                    @if ($type === 'portfolio' && ($emptyMessage ?? null))
                        <a href="{{ route('portfolio.index') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 border border-neutral-300 text-neutral-700 text-xs font-bold uppercase tracking-wider hover:bg-neutral-50 transition-colors mr-3">
                            View All Case Studies
                        </a>
                    @endif
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white text-xs font-bold uppercase tracking-wider hover:bg-primary-700 transition-colors">
                        Discuss Your Project
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @endforelse
        </div>

        @if ($showCtaBanner)
            @php
                $bannerUrl = $ctaBannerUrl ?? hub_cta_url(($title ? \Illuminate\Support\Str::slug($title) : $type) . '_grid');
                $bannerHeadline = $ctaBannerHeadline ?? (
                    str_contains($ctaBannerText ?? '', 'View All') ? ($type === 'service' ? 'Explore all our services.' : ($type === 'portfolio' ? 'View our full portfolio.' : 'Explore more.')) : 'Ready to start your project?'
                );
            @endphp
            <x-cms.cta-banner :cta="$globalCta ?? null" class="mt-10 lg:mt-12 animate-fade-in"
                :url="$bannerUrl"
                :headline="$bannerHeadline"
                :buttonText="$ctaBannerText" />
        @endif
    </div>
</{{ $wrapperTag }}>

@php
    $gridSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $title ?? ucfirst($type),
        'itemListElement' => $displayItems
            ->map(function ($item, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => [
                        '@type' => 'CreativeWork',
                        'name' => $item->title,
                        'url' => $item->url,
                        'image' => ($item->featured_image ?? $item->featured_image_url ?? null) ?: asset('images/default-og-image.png'),
                    ],
                ];
            })
            ->values()
            ->toArray(),
    ];
@endphp
<x-seo.json-ld :data="$gridSchema" />
