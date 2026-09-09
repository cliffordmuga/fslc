{{-- resources/views/components/cms/content-gallery.blade.php --}}
{{--
Gallery grid with lightbox for content detail pages.

Props:
- content: Content model with gallery images (collection=gallery)
- title: Optional section heading (default: "Gallery")
- class: Additional wrapper classes
--}}

@props([
    'content',
    'title' => 'Gallery',
    'class' => '',
])

@php
    $items = $content->gallery_for_view ?? collect();
    $slides = $items->map(function ($row) use ($content) {
        return [
            'thumb' => $row['thumb_url'],
            'thumbSrcset' => $row['thumb_srcset'],
            'thumbSizes' => $row['thumb_sizes'],
            'thumbW' => $row['thumb_width'],
            'thumbH' => $row['thumb_height'],
            'full' => $row['lightbox_url'],
            'alt' => $row['alt'] !== '' ? $row['alt'] : ($content->title . ' – Gallery image'),
        ];
    })->values()->toArray();
@endphp

@if (count($slides) > 0)
    <section
        class="mt-12 lg:mt-16 {{ $class }}"
        aria-labelledby="gallery-heading"
        x-data="contentGalleryLightbox(@js($slides))">
        <h2 id="gallery-heading" class="text-2xl lg:text-3xl font-extrabold text-neutral-900 mb-6 lg:mb-8 tracking-tight">
            {{ $title }}
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 lg:gap-6">
            @foreach ($items as $index => $row)
                <button
                    type="button"
                    @click="openAt({{ $index }})"
                    class="block relative aspect-[4/3] overflow-hidden bg-neutral-100 group focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                    aria-label="View image {{ $index + 1 }} of {{ $items->count() }}">
                    <img
                        src="{{ $row['thumb_url'] }}"
                        @if (!empty($row['thumb_srcset'])) srcset="{{ $row['thumb_srcset'] }}" sizes="{{ $row['thumb_sizes'] }}" @endif
                        alt="{{ $row['alt'] !== '' ? $row['alt'] : $content->title . ' – Gallery image ' . ($index + 1) }}"
                        loading="lazy"
                        decoding="async"
                        width="{{ $row['thumb_width'] }}"
                        height="{{ $row['thumb_height'] }}"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300" aria-hidden="true"></div>
                </button>
            @endforeach
        </div>

        {{-- Lightbox dialog --}}
        <template x-teleport="body">
            <div
                x-show="isOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-ref="overlay"
                tabindex="-1"
                class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/90 cursor-zoom-out outline-none"
                role="dialog"
                aria-modal="true"
                :aria-label="'Gallery image ' + (currentIndex + 1) + ' of ' + images.length"
                @click.self="close()"
                x-cloak>
                <div class="relative w-full max-w-5xl max-h-[90vh] flex items-center justify-center">
                    <img
                        :src="images[currentIndex]?.full"
                        :alt="images[currentIndex]?.alt || 'Gallery image'"
                        class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl cursor-default"
                        @click.stop />
                </div>

                @if ($items->count() > 1)
                    <button
                        type="button"
                        @click="prev()"
                        class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white/50"
                        aria-label="Previous image">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        @click="next()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white/50"
                        aria-label="Next image">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @endif

                <button
                    type="button"
                    x-ref="lightboxClose"
                    @click="close()"
                    class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white/50"
                    aria-label="Close gallery">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/80 text-sm" x-show="images.length > 1" x-text="(currentIndex + 1) + ' / ' + images.length"></div>
            </div>
        </template>
    </section>
@endif
