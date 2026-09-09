{{-- resources/views/components/cms/breadcrumb.blade.php – Refined breadcrumb navigation --}}
@props([
    'items' => [], // [ ['label' => 'Home', 'url' => '/'], ['label' => 'About'] ] — last item without url = current page
    'separator' => 'chevron', // 'chevron' | 'slash' | 'dot'
    'variant' => 'light', // 'light' | 'dark'
])

@php
    $separatorChar = match ($separator) {
        'slash' => '/',
        'dot' => '·',
        default => null, // chevron uses SVG
    };
    $isDark = $variant === 'dark';
    $linkClass = $isDark
        ? 'truncate py-2 px-2 -mx-2 text-neutral-400 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/50 transition-colors'
        : 'truncate py-2 px-2 -mx-2 rounded text-neutral-600 hover:text-primary-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400/50 focus-visible:ring-offset-1 transition-colors';
    $currentClass = $isDark
        ? 'truncate font-medium text-white py-2 px-2 -mx-2 line-clamp-2 md:truncate md:line-clamp-none'
        : 'truncate font-medium text-neutral-900 py-2 px-2 -mx-2 rounded';
    $sepClass = $isDark ? 'text-neutral-600' : 'text-neutral-400';
@endphp

<nav {{ $attributes->merge(['class' => 'pb-4', 'aria-label' => 'Breadcrumb']) }}>
    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm" itemscope itemtype="https://schema.org/BreadcrumbList">
        @foreach ($items as $index => $item)
            @if ($index > 0)
                <li class="flex items-center shrink-0 {{ $sepClass }} select-none" aria-hidden="true">
                    @if ($separatorChar)
                        <span>{{ $separatorChar }}</span>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    @endif
                </li>
            @endif
            <li class="flex items-center min-w-0 {{ $index > 0 ? 'shrink-0 max-w-[12rem] sm:max-w-none' : '' }}" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                @if (!empty($item['url']))
                    <a href="{{ $item['url'] }}"
                        class="{{ $linkClass }}"
                        itemprop="item">
                        <span itemprop="name">{{ $item['label'] }}</span>
                    </a>
                    <meta itemprop="position" content="{{ $index + 1 }}" />
                @else
                    <span class="{{ $currentClass }}" aria-current="page" itemprop="name">
                        {{ $item['label'] }}
                    </span>
                    <meta itemprop="item" content="{{ url()->current() }}" />
                    <meta itemprop="position" content="{{ $index + 1 }}" />
                @endif
            </li>
        @endforeach
    </ol>
</nav>
