@extends('errors.layout')

@section('title', 'Page Not Found')

@section('content')
<div class="text-center max-w-md">
    <p class="text-7xl font-black font-mono text-neutral-200 mb-4 tracking-tighter">404</p>
    <div class="w-12 h-1 bg-primary-500 mx-auto mb-6"></div>
    <h1 class="text-2xl md:text-3xl font-black text-neutral-900 mb-3 tracking-tight">Page Not Found</h1>
    <p class="text-sm text-neutral-600 mb-8 leading-relaxed">
        The page you're looking for doesn't exist or has been moved.
    </p>
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ url('/') }}"
           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 text-white text-sm font-bold uppercase tracking-wider hover:bg-primary-700 transition-colors">
            Go to Home
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
        <a href="{{ url('/contact') }}"
           class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-neutral-300 text-neutral-700 text-sm font-bold uppercase tracking-wider hover:border-neutral-500 transition-colors">
            Contact Us
        </a>
    </div>
</div>
@endsection
