{{-- resources/views/components/cms/hero-section.blade.php --}}
@props([
    'title'             => setting('company_name', config('app.name', env('APP_NAME', 'App'))),
    'subtitle'          => 'Explore our portfolio, services, and insights.',
    'tagline'           => null,
    'cta'               => null,
    'image'             => null,
    'imageUrl'          => null,
    'imageSrcset'       => null,
    'imageSizes'        => '100vw',
    'size'              => 'standard',   // 'home' (homepage only) | 'standard' (all other pages)
    'gradient'          => 'to-br',
    'urgency'           => false,
    'metrics'           => null,
    'badges'            => [],
    'backgroundType'    => 'grid',
    'ctaUrl'            => null,
    'ctaLabel'          => 'Request HMIS Demo',
    'overlay'           => null,
    'theme'             => 'dark',
    'showScrollIndicator' => false,
    'showSecondaryCta'  => true,
    'suppressCta'       => false,
])

@php
    $headingId   = 'hero-' . uniqid();
    $subtitleId  = 'hero-sub-' . uniqid();
    $isLight     = $theme === 'light';
    $isHome      = $size === 'home';

    $heightClass = $isHome ? 'hero-h-home' : 'hero-h-standard';

    $bgClass = $isLight ? 'bg-neutral-50' : 'bg-neutral-900';

    $headingClass = $isLight ? 'text-neutral-900' : 'text-white';
    $subtitleClass = $isLight ? 'text-neutral-600' : 'text-neutral-300';
    $taglineClass  = $isLight ? 'text-neutral-500' : 'text-neutral-400';

    $headingSizeClass = $isHome
        ? 'text-3xl sm:text-4xl lg:text-5xl xl:text-6xl'
        : 'text-3xl sm:text-4xl lg:text-5xl';

    $hasImage  = $image || $imageUrl;
    $imageSrc  = $image ? $image->url('main') : $imageUrl;
    $isDecorativeHero = $hasImage && ! $image?->alt_text;
    $imageAlt  = $isDecorativeHero ? '' : ($image?->alt_text ?? '');
    $srcset    = $imageSrcset ?? null;
    $demoUrl   = hub_cta_url('hero_cta');
    $hasActions = ! $suppressCta && ($cta || $ctaUrl) && ! $isLight;
@endphp

<section
    {{ $attributes->merge(['class' => "relative {$heightClass} flex items-center overflow-hidden {$bgClass}"]) }}
    role="region"
    aria-labelledby="{{ $headingId }}"
>
    @if ($hasImage && $imageSrc)
        <div class="absolute inset-0">
            <img src="{{ $imageSrc }}"
                 @if($srcset) srcset="{{ $srcset }}" sizes="{{ $imageSizes }}" @endif
                 alt="{{ $imageAlt }}"
                 @if($isDecorativeHero) aria-hidden="true" @endif
                 width="1920" height="1080"
                 class="w-full h-full object-cover {{ $isLight ? 'opacity-20' : 'opacity-[0.38]' }}"
                 loading="eager" fetchpriority="high" decoding="async">
            <div class="absolute inset-0 {{ $isLight ? 'bg-gradient-to-b from-white/70 via-white/30 to-neutral-50/80' : 'bg-gradient-to-b from-neutral-900/45 via-neutral-900/25 to-neutral-900/65' }}"></div>
        </div>
    @endif

    @if (!$isLight)
        <div class="absolute inset-0 bg-hero-grid opacity-[0.05] pointer-events-none" aria-hidden="true"></div>
    @endif

    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary-500" aria-hidden="true"></div>

    <div class="relative z-10 page-container hero-inner">

        @if (!empty($badges))
            <div class="flex flex-wrap gap-2 mb-4" role="list">
                @foreach ($badges as $badge)
                    <x-ui.badge
                        :tone="$isLight ? 'outline-light' : 'outline'"
                        size="sm"
                        class="font-mono tracking-widest"
                        role="listitem">
                        {{ $badge }}
                    </x-ui.badge>
                @endforeach
            </div>
        @endif

        <h1 id="{{ $headingId }}"
            class="{{ $headingSizeClass }} font-black leading-[1.05] tracking-tight mb-3 {{ $headingClass }}"
            itemprop="name">
            {{ $title }}
        </h1>

        @if ($subtitle)
            <p id="{{ $subtitleId }}"
                class="text-base sm:text-lg lg:text-xl max-w-2xl leading-relaxed mb-1 {{ $subtitleClass }}"
                itemprop="description">
                {{ $subtitle }}
            </p>
        @endif

        @if ($tagline)
            <p class="text-sm mt-2 max-w-xl {{ $taglineClass }} font-medium">
                {{ $tagline }}
            </p>
        @endif

        @if ($slot->isNotEmpty())
            <div class="mt-5">{{ $slot }}</div>
        @elseif ($hasActions)
            <div class="flex flex-wrap gap-3 mt-6 items-center">
                @if ($cta)
                    <x-cms.cta-button
                        :cta="$cta"
                        variant="primary"
                        :url="$ctaUrl ?? $demoUrl"
                        class="text-sm font-semibold"
                    />
                @else
                    <x-ui.button :href="$ctaUrl ?? $demoUrl" variant="primary" class="text-sm font-semibold">
                        {{ $ctaLabel }}
                        <x-icons.arrow-right class="w-4 h-4" />
                    </x-ui.button>
                @endif

                @if ($showSecondaryCta)
                    <x-ui.button
                        :href="hub_cta_url('hero_secondary', '', route('portfolio.index'))"
                        :variant="$isLight ? 'secondary' : 'ghost-dark'"
                        class="text-sm px-6"
                        aria-label="View our work">
                        View Our Work
                    </x-ui.button>
                @endif

                @if ($urgency)
                    <x-ui.badge tone="danger" size="md" :uppercase="false">
                        Limited availability
                    </x-ui.badge>
                @endif
            </div>
        @endif

        @if ($isHome && $metrics)
            <div class="mt-7 pt-5 border-t {{ $isLight ? 'border-neutral-200' : 'border-neutral-700' }}">
                <div class="flex flex-wrap gap-8">
                    @foreach ($metrics as $metric)
                        <div>
                            <div class="text-2xl lg:text-3xl font-black font-mono {{ $isLight ? 'text-neutral-900' : 'text-white' }}">
                                {{ $metric['value'] }}
                            </div>
                            <div class="text-xs uppercase tracking-wider font-medium mt-0.5 {{ $isLight ? 'text-neutral-500' : 'text-neutral-400' }}">
                                {{ $metric['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
