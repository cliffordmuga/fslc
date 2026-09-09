{{-- Public marketing shell — chrome lives in x-layout.* for theme / rebrand swaps --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    @include('components.layout.site-head')
</head>

<body class="flex flex-col min-h-screen bg-white text-neutral-900 antialiased pb-[calc(var(--sticky-bar-h)+env(safe-area-inset-bottom,0px))] lg:pb-0">

    <div id="reading-progress"
         class="fixed top-0 inset-x-0 z-[300] h-[2px] bg-transparent pointer-events-none"
         aria-hidden="true">
        <div id="reading-progress-bar" class="h-full bg-primary-500 w-0"></div>
    </div>

    @if (config('forefront.show_preloader'))
    <div id="preloader" aria-hidden="true"
         class="fixed inset-0 z-[200] flex items-center justify-center bg-white transition-opacity duration-300 motion-reduce:hidden"
         role="presentation">
        <div class="flex flex-col items-center gap-3">
            <div class="w-10 h-10 bg-primary-600 flex items-center justify-center text-white font-black text-xl">
                {{ substr(config('app.name', env('APP_NAME', 'App')), 0, 1) }}
            </div>
            <div class="w-6 h-6 border-2 border-neutral-200 border-t-primary-600 rounded-full animate-spin"></div>
        </div>
    </div>
    @endif

    <a href="#main-content"
       class="sr-only focus:not-sr-only fixed top-2 left-2 z-[100] bg-primary-600 text-white px-4 py-2 text-sm font-semibold">
        Skip to main content
    </a>

    <x-layout.site-nav />

    <main id="main-content" class="flex-1 pt-14 lg:pt-16" role="main">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <x-layout.site-footer />

    <div class="hidden lg:block fixed bottom-6 right-6 z-[100]">
        <x-cms.whatsapp-cta variant="fab" label="Chat on WhatsApp">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </x-cms.whatsapp-cta>
    </div>

    <x-layout.sticky-contact-bar />

    <button id="back-to-top"
            type="button"
            aria-label="Back to top"
            class="fixed bottom-20 right-3 lg:bottom-6 lg:left-6 lg:right-auto z-[90]
                   w-11 h-11 min-w-[44px] min-h-[44px] bg-neutral-900 text-white flex items-center justify-center
                   opacity-0 pointer-events-none transition-opacity duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
        </svg>
    </button>

    <x-cms.flash-messages />

    @stack('scripts')
</body>
</html>
