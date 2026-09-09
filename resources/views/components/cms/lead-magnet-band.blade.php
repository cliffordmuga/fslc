{{-- Full-width lead magnet band — copy from config via lead_magnet() --}}
@props([
    'type' => 'hmis-checklist',
])

@php
    $magnet = lead_magnet($type, 'band');
@endphp

<section {{ $attributes->merge(['class' => 'border-t border-primary-200 bg-primary-50']) }} aria-label="{{ $magnet['title'] }}">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10 lg:py-12">
        <div class="grid lg:grid-cols-[1fr_auto] gap-6 lg:gap-10 items-center border-l-4 border-primary-500 pl-6 lg:pl-8">
            <div>
                <p class="section-label mb-2">{{ $magnet['eyebrow'] }}</p>
                <h2 class="text-xl lg:text-2xl font-black text-neutral-900 tracking-tight mb-2">
                    {{ $magnet['title'] }}
                </h2>
                <p class="text-sm lg:text-base text-neutral-600 leading-relaxed max-w-2xl">
                    {{ $magnet['text'] }}
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ $magnet['url'] }}"
                   class="btn-primary text-sm uppercase tracking-wider"
                   data-cta="lead-magnet-band-{{ $magnet['type'] }}">
                    {{ $magnet['button'] }}
                    <x-icons.arrow-right class="w-4 h-4" />
                </a>
            </div>
        </div>
    </div>
</section>
