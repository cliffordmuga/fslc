@props([
    'title' => 'Trusted partners',
    'logos' => [],
    'sectors' => [],
    'certifications' => [],
    'compact' => false,
    'eyebrow' => null,
    'showEyebrow' => null,
])

@php
    $sectionClass = $compact
        ? 'py-8 lg:py-10 bg-neutral-50 border-t border-neutral-200'
        : 'py-14 lg:py-20 bg-white border-b border-neutral-100';
    $titleClass = $compact
        ? 'text-2xl lg:text-3xl font-black tracking-tight text-neutral-900 mb-6'
        : 'text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight text-neutral-900 mb-10';
    $sectorGridClass = $compact
        ? 'grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-3 max-w-3xl mx-auto'
        : 'flex flex-wrap justify-center gap-3 md:gap-4';
    $displayEyebrow = ($showEyebrow ?? ! empty($sectors)) && $compact
        ? ($eyebrow ?: (! empty($sectors) ? 'Clients' : null))
        : null;
@endphp

<section {{ $attributes->merge(['class' => $sectionClass]) }} aria-labelledby="{{ Str::slug($title) }}-heading">
    <div class="max-w-5xl mx-auto px-6 lg:px-8 text-center">
        @if ($displayEyebrow)
            <p class="section-label mb-1">{{ $displayEyebrow }}</p>
        @endif
        <h2 id="{{ Str::slug($title) }}-heading" class="{{ $titleClass }}">
            {{ $title }}
        </h2>
        @if(isset($description))
            {{ $description }}
        @endif
        @if (!empty($sectors))
            <div class="{{ $sectorGridClass }}">
                @foreach ($sectors as $sector)
                    <span class="inline-flex items-center justify-center px-3 py-2 text-[11px] md:text-xs font-semibold uppercase tracking-wider border border-neutral-200 bg-white text-neutral-700">
                        {{ $sector }}
                    </span>
                @endforeach
            </div>
        @elseif (!empty($certifications))
            <div class="flex flex-wrap justify-center gap-2 md:gap-3 max-w-3xl mx-auto">
                @foreach ($certifications as $cert)
                    <span class="inline-flex items-center justify-center px-3 py-2 text-[11px] md:text-xs font-semibold uppercase tracking-wider border border-primary-200 bg-primary-50 text-primary-800">
                        {{ $cert }}
                    </span>
                @endforeach
            </div>
        @else
            <div class="flex flex-wrap justify-center items-center gap-10 md:gap-16 lg:gap-20">
                @foreach ($logos as $logo)
                    <div class="grayscale hover:grayscale-0 opacity-70 hover:opacity-100 transition-all duration-300">
                        <img src="{{ $logo['src'] ?? $logo['url'] ?? '' }}" alt="{{ $logo['alt'] ?? '' }}"
                            class="h-10 md:h-12 object-contain max-w-[120px]" loading="lazy" decoding="async">
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
