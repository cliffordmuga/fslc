{{-- Section label + title + optional intro + optional action (view-all link) --}}
@props([
    'label' => null,
    'title',
    'intro' => null,
    'headingId' => null,
    'bordered' => true,
])

@php
    $id = $headingId ?? 'section-' . \Illuminate\Support\Str::slug(strip_tags($title));
    $wrapperClass = $bordered ? 'mb-8 border-b border-neutral-200 pb-4' : 'mb-8';
    $hasAction = isset($action) && trim((string) $action) !== '';
@endphp

<div {{ $attributes->merge(['class' => $wrapperClass]) }}>
    <div class="{{ $hasAction ? 'flex items-end justify-between gap-4' : '' }}">
        <div class="min-w-0">
            @if ($label)
                <p class="section-label mb-1">{{ $label }}</p>
            @endif
            <h2 id="{{ $id }}" class="text-2xl lg:text-3xl font-black text-neutral-900 tracking-tight {{ $intro ? 'mb-3' : '' }}">
                {{ $title }}
            </h2>
            @if ($intro)
                <p class="text-sm text-neutral-600 max-w-3xl leading-relaxed">
                    {{ $intro }}
                </p>
            @endif
        </div>
        @if ($hasAction)
            <div class="shrink-0 hidden sm:block">
                {{ $action }}
            </div>
        @endif
    </div>
    @if ($hasAction)
        <div class="sm:hidden mt-3">
            {{ $action }}
        </div>
    @endif
</div>
