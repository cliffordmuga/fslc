{{-- Shared badge / chip — tone maps to design-system status tokens --}}
@props([
    'tone' => 'neutral',   // brand|neutral|success|warning|danger|dark|light
    'size' => 'sm',        // xs|sm|md
    'uppercase' => true,
])

@php
    $toneClass = match ($tone) {
        'brand' => 'bg-primary-600 text-white border-primary-600',
        'brand-soft' => 'bg-primary-50 text-primary-700 border-primary-200',
        'success' => 'bg-green-50 text-green-800 border-green-200',
        'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
        'danger' => 'bg-red-50 text-red-800 border-red-200',
        'dark' => 'bg-neutral-900/80 text-white border-transparent',
        'light' => 'bg-white text-neutral-900 border-neutral-200',
        'outline' => 'bg-transparent text-neutral-400 border-neutral-600',
        'outline-light' => 'bg-transparent text-neutral-600 border-neutral-300',
        default => 'bg-neutral-100 text-neutral-700 border-neutral-200',
    };

    $sizeClass = match ($size) {
        'xs' => 'px-1.5 py-0.5 text-[10px]',
        'md' => 'px-3 py-1 text-xs',
        default => 'px-2 py-0.5 text-[10px]',
    };

    $caseClass = $uppercase ? 'uppercase tracking-wider font-semibold' : 'font-medium';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center border {$toneClass} {$sizeClass} {$caseClass}"]) }}>
    {{ $slot }}
</span>
