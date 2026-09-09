{{-- resources/views/components/cms/cta-banner.blade.php --}}
@props([
    'cta' => null,
    'url' => null,
    'headline' => 'Ready to digitize your institution?',
    'buttonText' => 'Request HMIS Demo',
    'sharp' => true,
])

@php
    $targetUrl = $cta
        ? generate_utm_url($cta->action, 'banner_cta')
        : ($url ?? generate_utm_url(route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form', 'grid_cta'));
@endphp

<section
    {{ $attributes->merge(['class' => 'bg-primary-50 border border-primary-200 border-l-4 border-l-primary-500 py-8 lg:py-10']) }}
    aria-labelledby="cta-banner-heading">

    <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
        <h2 id="cta-banner-heading" class="text-xl lg:text-2xl font-black text-neutral-900 mb-4 tracking-tight">
            {{ $cta?->text ?? $headline }}
        </h2>
        <a href="{{ $targetUrl }}"
            class="btn-primary inline-flex items-center gap-2 px-8 py-3.5 text-sm font-bold uppercase tracking-wider min-h-[48px]"
            aria-label="{{ $buttonText }}">
            {{ $buttonText }}
            <x-icons.arrow-right class="w-4 h-4" />
        </a>
    </div>
</section>
