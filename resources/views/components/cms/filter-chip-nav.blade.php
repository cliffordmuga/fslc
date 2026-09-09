{{-- Horizontal filter chip navigation (insights, search, tag hubs) --}}
@props([
    'label' => 'Filters',
    'items' => [],
    'resultSummary' => null,
    'sticky' => false,
    'wrap' => false,
])

@php
    $activeClass = 'bg-neutral-900 text-white border-neutral-900';
    $inactiveClass = 'bg-white text-neutral-600 border-neutral-200 hover:border-neutral-400 hover:text-neutral-900';
    $navClass = $wrap
        ? 'flex flex-wrap gap-2'
        : 'flex gap-2 overflow-x-auto snap-x snap-mandatory pb-1 -mx-6 px-6 lg:mx-0 lg:px-0 lg:flex-wrap lg:overflow-visible lg:snap-none';
    $barClass = $sticky
        ? 'sticky top-16 z-40 bg-white/95 backdrop-blur-sm border-b border-neutral-200'
        : 'border-b border-neutral-200 bg-white';
@endphp

<div {{ $attributes->merge(['class' => $barClass]) }}>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
        <div class="@if(isset($prefix)) flex flex-col lg:flex-row lg:items-start gap-4 @endif">
        @if (isset($prefix))
            {{ $prefix }}
        @endif

        <div class="@if(isset($prefix)) flex-1 min-w-0 @endif">
        <nav aria-label="{{ $label }}" class="{{ $navClass }}">
            @foreach ($items as $chip)
                @php
                    $isActive = (bool) ($chip['active'] ?? false);
                    $chipClass = $isActive ? $activeClass : ($chip['class'] ?? $inactiveClass);
                @endphp
                <a href="{{ $chip['url'] }}"
                   @if ($isActive) aria-current="page" @endif
                   @if (! empty($chip['title'])) title="{{ $chip['title'] }}" @endif
                   class="inline-flex items-center px-4 py-2.5 text-xs font-semibold uppercase tracking-wider border transition-colors min-h-[48px] snap-start shrink-0 {{ $chipClass }}">
                    {{ $chip['label'] }}
                </a>
            @endforeach
        </nav>

        @if ($resultSummary)
            <p class="mt-3 text-sm text-neutral-500">{!! $resultSummary !!}</p>
        @endif
        </div>
        </div>

        {{ $slot }}
    </div>
</div>
