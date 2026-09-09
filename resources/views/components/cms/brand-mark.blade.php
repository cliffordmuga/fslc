{{-- resources/views/components/cms/brand-mark.blade.php --}}
@props([
    'size' => 'md', // sm | md | lg
    'showName' => true,
    'nameClass' => 'text-base font-bold text-neutral-900 hidden sm:block tracking-tight',
    'dark' => false,
])

@php
    $logoUrl = site_brand_logo_url();
    $sizes = match ($size) {
        'sm' => 'w-7 h-7',
        'lg' => 'w-10 h-10',
        default => 'w-8 h-8',
    };
    $nameClass = $dark
        ? str_replace('text-neutral-900', 'text-white', $nameClass)
        : $nameClass;
@endphp

@if ($logoUrl)
    <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}" class="{{ $sizes }} object-contain flex-shrink-0" {{ $attributes }}>
@else
    <div {{ $attributes->merge(['class' => "{$sizes} bg-primary-600 flex items-center justify-center text-white font-black text-base flex-shrink-0"]) }}>
        {{ site_brand_initial() }}
    </div>
@endif

@if ($showName)
    <span class="{{ $nameClass }}">{{ config('app.name') }}</span>
@endif
