{{-- Shared alert / status banner — success|error|warning|info --}}
@props([
    'type' => 'info',
    'title' => null,
    'dismissible' => false,
])

@php
    $styles = match ($type) {
        'success' => [
            'wrap' => 'border-l-4 border-green-500 bg-green-50 text-green-900',
            'title' => 'text-green-900',
            'body' => 'text-green-800',
        ],
        'error', 'danger' => [
            'wrap' => 'border-l-4 border-red-500 bg-red-50 text-red-900',
            'title' => 'text-red-900',
            'body' => 'text-red-800',
        ],
        'warning' => [
            'wrap' => 'border-l-4 border-amber-500 bg-amber-50 text-amber-900',
            'title' => 'text-amber-900',
            'body' => 'text-amber-800',
        ],
        default => [
            'wrap' => 'border-l-4 border-primary-500 bg-primary-50 text-neutral-900',
            'title' => 'text-neutral-900',
            'body' => 'text-neutral-700',
        ],
    };
@endphp

<div
    {{ $attributes->merge([
        'class' => "px-4 py-3 text-sm {$styles['wrap']}",
        'role' => $type === 'error' || $type === 'danger' ? 'alert' : 'status',
    ]) }}
    @if ($type === 'error' || $type === 'danger') aria-live="assertive" @else aria-live="polite" @endif
>
    @if ($title)
        <p class="font-semibold mb-1 {{ $styles['title'] }}">{{ $title }}</p>
    @endif
    <div class="{{ $styles['body'] }}">
        {{ $slot }}
    </div>
</div>
