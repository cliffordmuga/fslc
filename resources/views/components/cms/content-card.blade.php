{{-- Requires $item with tags relation eager-loaded (ContentService listing/search paths). --}}
@props(['item', 'type' => 'portfolio', 'highlighted' => false, 'compact' => true, 'searchQuery' => null])

@php
    use Illuminate\Support\Str;
    $category    = $item->tags->first()?->name ?? content_hub_label($item->type ?? 'portfolio');
    $tags        = $item->tags ?? collect();
    $wordCount   = str_word_count(strip_tags($item->content ?? ''));
    $readingMins = max(1, (int) round($wordCount / 200));

    $appHost     = parse_url(config('app.url', ''), PHP_URL_HOST) ?? '';
    $linkHost    = $item->url ? (parse_url($item->url, PHP_URL_HOST) ?? '') : '';
    $isExternal  = $linkHost && $appHost && $linkHost !== $appHost;
    $linkRel     = $isExternal ? 'nofollow noopener noreferrer' : null;

    $showTags    = $type === 'blog' && $tags->count() > 0;
    $excerptLimit = $compact ? 88 : 120;
    $excerptLines = $compact ? 'line-clamp-1' : 'line-clamp-2';
    $excerptText = Str::limit($item->excerpt ?? strip_tags($item->content ?? ''), $excerptLimit);
    $titleHtml = $searchQuery ? highlight_search_term($item->title, $searchQuery) : e($item->title);
    $excerptHtml = $searchQuery ? highlight_search_term($excerptText, $searchQuery) : e($excerptText);

    $cardClass = 'card-base group flex flex-col bg-white h-full';
    if ($highlighted) {
        $cardClass .= ' border-t-4 border-t-primary-500 ring-1 ring-primary-100';
    }
@endphp

<article
    @if (!empty($item->slug)) id="{{ $item->slug }}" @endif
    {{ $attributes->merge(['class' => $cardClass]) }}
    itemscope itemtype="https://schema.org/CreativeWork">

    {{-- Thumbnail — 2:1 is shorter than 16:9 while staying scannable --}}
    <div class="relative {{ $compact ? 'aspect-[2/1]' : 'aspect-[16/9]' }} overflow-hidden bg-neutral-100" data-img-wrap>
        <div class="absolute inset-0 bg-neutral-200 animate-pulse" data-skeleton aria-hidden="true"></div>
        <x-cms.responsive-image
            :model="$item"
            collection="featured"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 relative z-10"
            :alt="$item->title . ' image'"
            loading="lazy" />

        <div class="absolute inset-0 bg-neutral-900/0 group-hover:bg-neutral-900/25 transition-colors duration-300"></div>

        <div class="absolute top-2 left-2 z-10 flex flex-col gap-1">
            @if ($highlighted)
                <x-ui.badge tone="brand" size="xs">Primary practice</x-ui.badge>
            @endif
            <x-ui.badge tone="light" size="xs" class="border-l-2 border-l-primary-500">
                {{ $category }}
            </x-ui.badge>
        </div>

        @if ($type === 'blog')
            <div class="absolute bottom-2 right-2 z-10">
                <x-ui.badge tone="dark" size="xs" :uppercase="false" class="font-mono">
                    {{ $readingMins }}m
                </x-ui.badge>
            </div>
        @endif
    </div>

    {{-- Body --}}
    <div class="{{ $compact ? 'p-3.5 sm:p-4' : 'p-5' }} flex flex-col flex-1 min-h-0">
        <h3 class="{{ $compact ? 'text-sm mb-1' : 'text-base mb-2' }} font-bold text-neutral-900 line-clamp-2 leading-snug group-hover:text-primary-600 transition-colors"
            itemprop="name">
            {!! $titleHtml !!}
        </h3>

        <p class="text-xs sm:text-sm text-neutral-500 {{ $compact ? 'mb-2.5' : 'mb-4' }} {{ $excerptLines }} leading-relaxed" itemprop="description">
            {!! $excerptHtml !!}
        </p>

        @if ($showTags)
            <div class="flex flex-wrap gap-1 mb-2.5">
                @foreach ($tags->take($compact ? 2 : 3) as $tag)
                    <x-ui.badge tone="neutral" size="xs" :uppercase="false">{{ $tag->name }}</x-ui.badge>
                @endforeach
                @if ($tags->count() > ($compact ? 2 : 3))
                    <x-ui.badge tone="neutral" size="xs" :uppercase="false" class="text-neutral-400 bg-neutral-50">
                        +{{ $tags->count() - ($compact ? 2 : 3) }}
                    </x-ui.badge>
                @endif
            </div>
        @endif

        <div class="mt-auto flex items-center justify-between gap-2 {{ $compact ? 'pt-2 border-t border-neutral-100' : 'pt-4 border-t border-neutral-100' }}">
            <a href="{{ $item->url }}"
                class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors group/link"
                itemprop="url"
                @if($isExternal) target="_blank" @endif
                @if($linkRel) rel="{{ $linkRel }}" @endif
                aria-label="{{ $type === 'blog' ? 'Read' : 'View' }}: {{ $item->title }}">
                {{ $type === 'blog' ? 'Read' : 'View' }}
                <svg class="w-3.5 h-3.5 group-hover/link:translate-x-0.5 transition-transform duration-200"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            @if ($item->published_at && $type === 'blog')
                <span class="text-[11px] text-neutral-400 font-mono shrink-0">
                    {{ $item->published_at->format('M Y') }}
                </span>
            @endif
        </div>
    </div>
</article>
