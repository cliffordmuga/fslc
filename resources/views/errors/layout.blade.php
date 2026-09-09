{{-- Minimal error layout – no DB/settings dependency for 500 resilience --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error') – {{ config('app.name', env('APP_NAME', 'App')) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Fallback if Vite fails so the page still renders --}}
    <style>
        :root { --primary: #0ea5e9; }
        body { margin: 0; font-family: ui-sans-serif, system-ui, sans-serif; }
        .min-h-screen { min-height: 100vh; }
        .flex { display: flex; }
        .flex-col { flex-direction: column; }
        .flex-1 { flex: 1 1 0%; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        .bg-neutral-50 { background: #fafafa; }
        .border-b { border-bottom: 1px solid #e5e5e5; }
        .border-t { border-top: 1px solid #e5e5e5; }
        .bg-white { background: #fff; }
        .text-neutral-900 { color: #171717; }
        .text-neutral-500 { color: #737373; }
        .text-primary-600 { color: var(--primary); }
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .py-6 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
        .py-20 { padding-top: 5rem; padding-bottom: 5rem; }
        .max-w-7xl { max-width: 80rem; margin-left: auto; margin-right: auto; }
        .inline-flex { display: inline-flex; }
        .gap-2 { gap: 0.5rem; }
        .font-bold { font-weight: 700; }
        .hover\:underline:hover { text-decoration: underline; }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-neutral-50 font-sans antialiased">

    <header class="border-b border-neutral-200 bg-white h-14 flex items-center">
        <div class="max-w-7xl mx-auto px-6 w-full">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-neutral-900 font-bold hover:text-primary-600 transition-colors">
                <span class="w-8 h-8 bg-primary-600 flex items-center justify-center text-white text-sm font-black">{{ substr(config('app.name', env('APP_NAME', 'App')), 0, 1) }}</span>
                {{ config('app.name', env('APP_NAME', 'App')) }}
            </a>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-6 py-16" role="main">
        @yield('content')
    </main>

    <footer class="border-t border-neutral-200 bg-white py-5 text-center text-xs text-neutral-500">
        <a href="{{ url('/') }}" class="text-primary-600 hover:underline font-semibold">Back to Home</a>
        <span class="mx-2 text-neutral-300">|</span>
        <a href="{{ url('/contact') }}" class="text-primary-600 hover:underline font-semibold">Contact Us</a>
    </footer>

</body>
</html>
