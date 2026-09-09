{{-- resources/views/components/cms/contact-link.blade.php --}}
@props([
    'type', // email|tel|whatsapp
    'value',
    'label' => null,
    'class' => 'flex items-center hover:text-white transition-colors',
    'message' => null, // optional for whatsapp
])

@php
    $encoded = obfuscate_contact((string) $value);

    $label =
        $label ??
        match ($type) {
            'email' => 'Send us an email',
            'tel' => 'Call us',
            'whatsapp' => 'Chat on WhatsApp',
            default => 'Contact',
        };

    $encodedMessage = filled($message) ? obfuscate_contact($message) : null;

    // Build optional attributes safely (no Blade directives)
    $extraAttrs = [];
    if ($type === 'whatsapp' && $encodedMessage) {
        $extraAttrs['data-message'] = $encodedMessage;
    }
@endphp

<span class="protected-contact {{ $class }}" data-type="{{ $type }}" data-value="{{ $encoded }}"
    aria-label="{{ $label }}" role="link" tabindex="0"
    @foreach ($extraAttrs as $k => $v) {{ $k }}="{{ $v }}" @endforeach>
    {{ $slot }}
</span>
