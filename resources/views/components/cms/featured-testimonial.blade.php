{{-- Single featured client quote — lightweight alternative to carousel --}}
@props([
    'testimonial' => null,
    'title' => 'Trusted by healthcare and institutional leaders',
    'inline' => false,
])

@php
    $quote = $testimonial;
    $fallback = [
        'body' => 'Forefront understood our HMIS requirements from day one — modules, SHA billing, and training were delivered on schedule for our facility rollout.',
        'name' => 'Hospital IT Director',
        'role' => 'County referral hospital, Kenya',
        'context' => 'HMIS implementation',
    ];
@endphp

<section {{ $attributes->merge(['class' => $inline
    ? 'mt-0 py-0 bg-transparent border-t border-neutral-200'
    : 'py-10 lg:py-12 bg-neutral-50 border-t border-neutral-200']) }} aria-label="Client testimonial">
    <div class="{{ $inline ? 'max-w-none px-0' : 'max-w-4xl mx-auto px-6 lg:px-8' }}">
        @if (! $inline)
            <p class="section-label mb-2 text-center">Testimonial</p>
            <h2 class="text-2xl lg:text-3xl font-black text-neutral-900 tracking-tight text-center mb-6">{{ $title }}</h2>
        @else
            <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-600 mb-2">Client Feedback</p>
            <h2 class="text-xl font-black text-neutral-900 mb-4 tracking-tight">{{ $title }}</h2>
        @endif
        <figure class="card-base p-6 lg:p-8 bg-white" itemscope itemtype="https://schema.org/Review">
            <blockquote class="text-base lg:text-lg text-neutral-700 leading-relaxed mb-5" itemprop="reviewBody">
                <p>&ldquo;{{ $quote ? \Illuminate\Support\Str::limit($quote->testimonial, 320) : $fallback['body'] }}&rdquo;</p>
            </blockquote>
            <figcaption class="flex flex-wrap items-center justify-between gap-3 border-t border-neutral-100 pt-4">
                <cite class="not-italic" itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <span class="block text-sm font-bold text-neutral-900" itemprop="name">
                        {{ $quote?->client_name ?? $fallback['name'] }}
                    </span>
                    <span class="block text-xs text-neutral-500 mt-0.5">
                        {{ $quote?->client_title ?? '' }}
                        @if ($quote?->client_title && $quote?->company) at @endif
                        {{ $quote?->company ?? ($quote ? '' : $fallback['role']) }}
                    </span>
                </cite>
                @if ($quote?->project_context ?? (!$quote && ($fallback['context'] ?? null)))
                    <span class="text-[11px] font-semibold uppercase tracking-wider px-2.5 py-1 bg-primary-50 text-primary-700 border border-primary-100">
                        {{ $quote?->project_context ?? $fallback['context'] }}
                    </span>
                @endif
            </figcaption>
            @if ($quote?->rating)
                <meta itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                <meta itemprop="ratingValue" content="{{ $quote->rating }}">
                <meta itemprop="bestRating" content="5">
            @endif
        </figure>
    </div>
</section>
