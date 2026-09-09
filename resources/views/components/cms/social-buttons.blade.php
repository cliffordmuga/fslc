{{-- Social OAuth buttons — mode: login|register --}}
@props([
    'mode' => 'login',
    'providers' => ['google', 'facebook', 'apple'],
    'redirectRoute' => 'socialite.redirect',
])

@if (! config('features.social_login_enabled', false))
    {{-- Social login disabled via SOCIAL_LOGIN_ENABLED --}}
@else
@php
    $headingId = $mode === 'register' ? 'social-register-head' : 'social-login-head';
    $headingText = $mode === 'register' ? 'Register with social accounts' : 'Login with social accounts';
    $utmPrefix = $mode === 'register' ? 'social_register_' : 'social_login_';
@endphp
<div class="mt-6 space-y-3 animate-fade-in" aria-labelledby="{{ $headingId }}">
    <h3 id="{{ $headingId }}" class="sr-only">{{ $headingText }}</h3>

    @foreach ($providers as $provider)
        @php
            $label = ucfirst($provider);
            $icon = match ($provider) {
                'google' => 'bi bi-google',
                'facebook' => 'bi bi-facebook',
                'apple' => 'bi bi-apple',
                default => 'bi bi-person',
            };
            $color = match ($provider) {
                'google' => 'bg-white text-neutral-800 border border-neutral-200 hover:bg-neutral-50 hover:shadow-elevated',
                'facebook' => 'bg-[#1877F2] text-white hover:bg-[#166fe5] hover:shadow-elevated',
                'apple' => 'bg-black text-white hover:bg-neutral-900 hover:shadow-elevated',
                default => 'bg-neutral-700 text-white hover:bg-neutral-800 hover:shadow-elevated',
            };
        @endphp

        <a href="{{ generate_utm_url(route($redirectRoute, $provider), $utmPrefix . $provider . '_2025') }}"
           class="group flex items-center justify-center gap-3 w-full px-6 py-3.5 lg:py-4 font-medium text-sm lg:text-base transition-colors duration-200 {{ $color }} border border-neutral-200 focus:outline-none focus:ring-2 focus:ring-primary-500/40 min-h-[48px]"
           aria-label="Continue with {{ $label }}" itemprop="sameAs">
            <i class="{{ $icon }} text-lg lg:text-xl flex-shrink-0 transition-transform duration-300 group-hover:scale-110" aria-hidden="true"></i>
            <span>Continue with {{ $label }}</span>
        </a>
    @endforeach
</div>
@endif
