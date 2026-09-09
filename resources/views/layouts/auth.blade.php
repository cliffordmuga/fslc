{{-- resources/views/layouts/auth.blade.php — Minimal sharp auth shell (no marketing chrome) --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    @stack('meta')
    @hasSection('title')
        @yield('title')
    @else
        <title>{{ meta_title('Account') }}</title>
    @endif

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="preload" href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap"
          as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet"></noscript>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>:root { {{ brand_theme_css_vars() }} }</style>
</head>

<body class="min-h-screen bg-neutral-50 text-neutral-900 antialiased flex flex-col">

    <header class="border-b border-neutral-200 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group" aria-label="{{ config('app.name') }} — home">
                <x-cms.brand-mark size="md" />
            </a>
            <a href="{{ route('home') }}" class="text-sm text-neutral-600 hover:text-neutral-900 transition-colors">
                &larr; Back to site
            </a>
        </div>
    </header>

    <main id="main-content" class="flex-1 py-10 lg:py-14 px-6 lg:px-8" role="main">
        {{ $slot }}
    </main>

    <footer class="border-t border-neutral-200 bg-white py-6 text-center text-xs text-neutral-500">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Secure account access.</p>
    </footer>

    <x-cms.flash-messages />
    @stack('scripts')
</body>
</html>
