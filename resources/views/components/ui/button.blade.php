{{-- Token-aware button / link — variants map to .btn-* design-system classes --}}
@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
    'size' => 'md',
])

@php
    $variantClass = match ($variant) {
        'secondary' => 'btn-secondary',
        'outline' => 'btn-outline',
        'dark' => 'btn-dark',
        'ghost-dark', 'ghost' => 'btn-ghost-dark',
        'danger' => 'btn-danger',
        default => 'btn-primary',
    };
    $sizeClass = match ($size) {
        'sm' => 'text-xs px-5 py-2.5 min-h-0',
        'lg' => 'text-sm px-8 py-4',
        default => '',
    };
    $classes = trim("{$variantClass} {$sizeClass}");
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
