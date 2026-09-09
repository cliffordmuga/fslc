{{-- Shared labelled form control wrapper --}}
@props([
    'label' => null,
    'name' => null,
    'required' => false,
    'help' => null,
    'error' => null,
])

@php
    $fieldId = $attributes->get('id') ?? ($name ? str_replace(['[', ']'], ['-', ''], $name) : 'field-' . uniqid());
    $errorId = $fieldId . '-error';
    $helpId = $fieldId . '-help';
    $describedBy = collect([
        $error ? $errorId : null,
        $help ? $helpId : null,
    ])->filter()->implode(' ');
@endphp

<div {{ $attributes->whereDoesntStartWith('id')->merge(['class' => 'space-y-1.5']) }}>
    @if ($label)
        <label for="{{ $fieldId }}" class="block text-sm font-medium text-neutral-700">
            {{ $label }}@if ($required) <span class="text-red-600" aria-hidden="true">*</span>@endif
        </label>
    @endif

    <div @if ($describedBy) data-describedby="{{ $describedBy }}" @endif>
        {{ $slot }}
    </div>

    @if ($help)
        <p id="{{ $helpId }}" class="text-xs text-neutral-500">{{ $help }}</p>
    @endif

    @if ($error)
        <p id="{{ $errorId }}" class="text-red-600 text-xs" role="alert">{{ $error }}</p>
    @endif
</div>
