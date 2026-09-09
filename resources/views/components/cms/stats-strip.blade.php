@props([
    'items' => [],
    'columns' => 4,
    'standalone' => true,
    'compact' => false,
])
@php
    $gridClass = $columns === 3 ? 'md:grid-cols-3' : 'md:grid-cols-4';
    $sectionClass = $compact
        ? 'py-8 lg:py-10 bg-white border-b border-neutral-200'
        : 'py-14 lg:py-20 bg-white border-b border-neutral-100';
    $valueClass = $compact
        ? 'text-2xl sm:text-3xl font-black text-primary-600 mb-1 tracking-tight group-hover:text-primary-700 transition-colors'
        : 'text-3xl sm:text-4xl lg:text-5xl font-black text-primary-600 mb-2 tracking-tight group-hover:text-primary-700 transition-colors';
@endphp
@if ($standalone)
<section class="{{ $sectionClass }}">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
@endif
        <div {{ $attributes->merge(['class' => 'grid ' . $gridClass . ' gap-6 md:gap-8 text-center']) }}>
            @foreach ($items as $item)
                <div class="group">
                    <div class="{{ $valueClass }}">{{ $item['value'] ?? '' }}</div>
                    <p class="text-xs sm:text-sm font-medium text-neutral-600 mx-auto">{{ $item['label'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
@if ($standalone)
    </div>
</section>
@endif
