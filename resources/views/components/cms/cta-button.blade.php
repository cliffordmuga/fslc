{{-- resources/views/components/cms/cta-button.blade.php — maps to design-system .btn-* --}}
@props([
    'cta' => null,
    'variant' => 'primary',
    'size' => 'md',
    'url' => null,
    'fullWidth' => false,
    'icon' => null,
])

@php
    $effectiveCta =
        $cta ?:
        (object) [
            'text' => 'Request HMIS Demo',
            'action' => $url ?? route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form',
            'type' => 'link',
            'impressions' => 0,
            'clicks' => 0,
            'conversions' => 0,
            'priority' => 1,
        ];

    $variantClass = match ($variant) {
        'secondary', 'dark' => 'btn-dark',
        'outline' => 'btn-outline',
        'ghost', 'ghost-dark' => 'btn-ghost-dark',
        default => 'btn-primary',
    };

    $sizeClass = match ($size) {
        'sm' => 'text-sm px-5 py-2.5 min-h-[42px]',
        'lg' => 'text-lg px-9 py-4 min-h-[56px]',
        default => 'text-base px-7 py-3.5 min-h-[48px]',
    };

    $buttonClasses = trim(implode(' ', [
        $variantClass,
        $sizeClass,
        $fullWidth ? 'w-full' : '',
        'group',
    ]));

    $actionUrl = $url ?? ($effectiveCta->action ?? route('contact'));
    if (!filled($actionUrl)) {
        $actionUrl = route('contact');
    }
    $finalUrl = generate_utm_url($actionUrl, 'cta_button_' . date('Y'), 'button', 'lead_gen');
    $isExternal = filter_var($finalUrl, FILTER_VALIDATE_URL) && !str_starts_with($finalUrl, url('/'));
@endphp

@if (filter_var($effectiveCta->action ?? $actionUrl, FILTER_VALIDATE_URL) || $effectiveCta->type === 'link')
    <a href="{{ $finalUrl }}" {{ $attributes->merge(['class' => $buttonClasses]) }}
        data-ga-event="{{ $effectiveCta->text ?? 'cta_click' }}" itemprop="url"
        aria-label="{{ $effectiveCta->text ?? 'Click to learn more' }}" @if ($isExternal) rel="noopener noreferrer" @endif>
        @if ($icon)
            <x-heroicon-{{ $icon }} class="w-4 h-4 mr-2 flex-shrink-0" />
        @endif
        <span>{{ $effectiveCta->text }}</span>
    </a>
@else
    <button type="{{ $effectiveCta->type ?? 'button' }}" {{ $attributes->merge(['class' => $buttonClasses]) }}
        data-ga-event="{{ $effectiveCta->text ?? 'cta_click' }}"
        aria-label="{{ $effectiveCta->text ?? 'Click to learn more' }}">
        @if ($icon)
            <x-heroicon-{{ $icon }} class="w-4 h-4 mr-2 flex-shrink-0" />
        @endif
        <span>{{ $effectiveCta->text }}</span>
    </button>
@endif
