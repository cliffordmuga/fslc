{{-- resources/views/components/cms/footer-cta.blade.php --}}
@props([
    'title'          => 'Ready to get started?',
    'subtitle'       => null,
    'primaryUrl'     => null,
    'primaryLabel'   => 'Book a Free Consultation',
    'primaryClass'   => null,
    'secondaryUrl'   => null,
    'secondaryLabel' => null,
    'size'           => 'default',
    'showWhatsapp'   => false,
    'whatsappSource' => null,
    'whatsappLabel'  => 'Chat on WhatsApp',
    'maxWidth'       => 'max-w-5xl',
])

@php
    $btnClass = $primaryClass ?? 'inline-flex items-center gap-2 bg-white text-primary-800 hover:bg-neutral-100 font-semibold px-8 py-4 text-sm min-h-[48px] transition-colors duration-200';
    $secondaryBtnClass = 'inline-flex items-center gap-2 border border-neutral-500 text-white hover:bg-neutral-800 font-semibold px-8 py-4 text-sm min-h-[48px] transition-colors duration-200';
@endphp

<section id="contact"
    {{ $attributes->merge(['class' => 'py-10 lg:py-14 bg-neutral-900 border-t-4 border-primary-500']) }}
    aria-labelledby="footer-cta-heading">

    <div class="{{ $maxWidth }} mx-auto px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

            {{-- Text --}}
            <div>
                <h2 id="footer-cta-heading"
                    class="text-2xl lg:text-3xl font-black text-white tracking-tight leading-tight">
                    {{ $title }}
                </h2>
                @if ($subtitle)
                    <p class="text-sm text-neutral-400 mt-2 max-w-xl leading-relaxed">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            {{-- Actions --}}
            @if (isset($actions) && trim((string) $actions) !== '')
                {{ $actions }}
            @else
                <div class="flex flex-wrap gap-3 lg:flex-shrink-0">
                    @if ($primaryUrl)
                        <a href="{{ $primaryUrl }}" class="{{ $btnClass }}">
                            {{ $primaryLabel }}
                            <x-icons.arrow-right class="w-4 h-4" />
                        </a>
                    @endif
                    @if ($secondaryUrl)
                        <a href="{{ $secondaryUrl }}" class="{{ $secondaryBtnClass }}">
                            {{ $secondaryLabel ?? 'Learn More' }}
                        </a>
                    @endif
                    @if ($showWhatsapp)
                        <x-cms.whatsapp-cta
                            variant="block"
                            :source="$whatsappSource"
                            :label="$whatsappLabel"
                            class="!w-auto" />
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
