{{-- Shared hub page shell: optional hero preload, hero slot, breadcrumb band, optional stats --}}
@props([
    'breadcrumb' => [],
    'heroPreload' => null,
])

@if ($heroPreload)
    @push('head')
        <link rel="preload" href="{{ $heroPreload }}" as="image">
    @endpush
@endif

@if (isset($hero))
    {{ $hero }}
@endif

@if (! empty($breadcrumb))
    <div class="bg-white border-t border-neutral-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <x-cms.breadcrumb :items="$breadcrumb" />
        </div>
    </div>
@endif

@if (isset($stats))
    {{ $stats }}
@endif

{{ $slot }}
