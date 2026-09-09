{{-- Inline lead magnet CTA — same config as lead-magnet-band --}}
@props([
    'type' => 'hmis-checklist',
])

@php
    $magnet = lead_magnet($type, 'inline');
@endphp

<aside class="my-8 p-6 lg:p-8 border-l-4 border-primary-500 bg-primary-50/80 border border-primary-100 not-prose" role="complementary" aria-label="{{ $magnet['title'] }}">
    <p class="section-label mb-2">{{ $magnet['eyebrow'] }}</p>
    <h3 class="text-lg font-black text-neutral-900 mb-2 tracking-tight">{{ $magnet['title'] }}</h3>
    <p class="text-sm text-neutral-600 mb-4 leading-relaxed">{{ $magnet['text'] }}</p>
    <a href="{{ $magnet['url'] }}"
       class="btn-primary text-xs uppercase tracking-wider min-h-[40px] px-5 py-2.5"
       data-cta="lead-magnet-{{ $magnet['type'] }}">
        {{ $magnet['button'] }}
        <x-icons.arrow-right class="w-4 h-4" />
    </a>
</aside>
