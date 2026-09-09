@props([
    'title' => 'How We Bring Your Vision to Life',
    'headingId' => null,
    'ctaUrl' => null,
    'ctaLabel' => null,
    'compact' => false,
    'sectionLabel' => null,
])

@php
    $sectionClass = $compact ? 'py-10 lg:py-14 bg-white border-t border-neutral-200' : 'py-14 lg:py-20 bg-white';
    $headingClass = $compact
        ? 'text-2xl lg:text-3xl font-black text-neutral-900 tracking-tight'
        : 'text-3xl md:text-4xl lg:text-5xl font-extrabold text-center mb-12 lg:mb-14 text-neutral-900 tracking-tight';
    $stepsContainerClass = $compact
        ? 'flex md:grid md:grid-cols-4 gap-4 lg:gap-6 overflow-x-auto snap-x snap-mandatory pb-2 md:pb-0 -mx-6 px-6 md:mx-0 md:px-0 scrollbar-thin'
        : 'grid md:grid-cols-4 gap-6 lg:gap-8';
    $stepCardClass = $compact
        ? 'card-base p-5 lg:p-6 text-center hover-lift group min-w-[78vw] md:min-w-0 snap-start shrink-0 md:shrink'
        : 'card-base p-6 lg:p-8 text-center hover-lift group';
    $stepNumberClass = $compact
        ? 'w-12 h-12 mx-auto mb-4 bg-primary-50 border-2 border-primary-500 flex items-center justify-center text-primary-600 text-xl font-black group-hover:bg-primary-100 transition-colors duration-300'
        : 'w-14 h-14 mx-auto mb-5 bg-gradient-to-br from-primary-50 to-primary-100 rounded-full flex items-center justify-center text-primary-600 text-2xl font-black group-hover:scale-105 transition-transform duration-300';
@endphp

<section {{ $attributes->merge(['class' => $sectionClass]) }}>
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        @if ($compact)
            <div class="mb-8 border-b border-neutral-200 pb-4">
                @if ($sectionLabel)
                    <p class="section-label mb-1">{{ $sectionLabel }}</p>
                @endif
                <h2 @if($headingId) id="{{ $headingId }}" @endif class="{{ $headingClass }}">
                    {{ $title }}
                </h2>
            </div>
        @else
            <h2 @if($headingId) id="{{ $headingId }}" @endif class="{{ $headingClass }}">
                {{ $title }}
            </h2>
        @endif

        <div class="{{ $stepsContainerClass }}">
            @foreach ([['number' => '1', 'title' => 'Discovery', 'description' => 'Free consultation to understand your facility, institution, audience, budget, and timeline.'], ['number' => '2', 'title' => 'Design & Strategy', 'description' => 'We map HMIS modules, portal workflows, brand positioning, or campaign architecture tailored to your goals.'], ['number' => '3', 'title' => 'Development', 'description' => 'Secure delivery of HMIS modules, institutional portals, campaign platforms, and integrations built for Kenyan operations.'], ['number' => '4', 'title' => 'Launch & Growth', 'description' => 'We launch, train your team, optimize performance and SEO, and support long-term adoption and growth.']] as $step)
                <div class="{{ $stepCardClass }}">
                    <div class="{{ $stepNumberClass }}">
                        {{ $step['number'] }}
                    </div>

                    <h3 class="text-lg lg:text-xl font-bold mb-3 text-neutral-900 tracking-tight group-hover:text-primary-600 transition-colors">
                        {{ $step['title'] }}
                    </h3>

                    <p class="text-sm lg:text-base text-neutral-600 leading-relaxed">
                        {{ $step['description'] }}
                    </p>
                </div>
            @endforeach
        </div>

        @if ($ctaUrl)
            <div class="mt-10 lg:mt-12 text-center">
                <a href="{{ $ctaUrl }}"
                    class="btn-primary inline-flex items-center gap-2 px-8 py-4 text-base lg:text-lg font-semibold shadow-medium hover:shadow-elevated hover-lift transition-all duration-500">
                    {{ $ctaLabel ?? 'Start with a Free Consultation' }}
                    <x-icons.arrow-right />
                </a>
            </div>
        @endif
    </div>
</section>
