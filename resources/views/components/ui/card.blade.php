{{-- Design-system card — prefer this over cms.ui.card --}}
@props([
    'variant' => 'default',   // default|elevated|muted
    'padding' => true,
    'hoverable' => false,
])

@php
    $variantClass = match ($variant) {
        'elevated' => 'card-base shadow-md',
        'muted' => 'card-base bg-neutral-50',
        default => 'card-base',
    };
    $hoverClass = $hoverable ? 'hover-lift' : '';
    $padClass = $padding ? '' : '';
@endphp

<div {{ $attributes->merge(['class' => trim("{$variantClass} {$hoverClass}")]) }}>
    @isset($header)
        <div class="px-6 py-4 border-b border-neutral-200">
            {{ $header }}
        </div>
    @endisset

    <div class="{{ $padding ? 'px-6 py-4' : '' }}">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="px-6 py-4 border-t border-neutral-200 bg-neutral-50">
            {{ $footer }}
        </div>
    @endisset
</div>
