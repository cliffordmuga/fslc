{{-- views/auth/login.blade.php --}}
@section('title')
    <title>{{ meta_title('Log In') }}</title>
@endsection
@push('meta')
    <meta name="description" content="{{ meta_description('Securely log in to your account.') }}">
@endpush

@php($socialLogin = config('features.social_login_enabled', false))
<x-auth-layout>
    <x-auth.form-shell title="Log in" subtitle="Access your account to manage leads and content." :max-width="$socialLogin ? 'max-w-4xl' : 'max-w-md'">
        <x-auth-session-status class="mb-6" :status="session('status')" />

        <div class="{{ $socialLogin ? 'grid lg:grid-cols-2 gap-8 lg:gap-10' : '' }}">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-5">
                    <x-input-label for="email" :value="__('Email')" class="mb-2" />
                    <x-text-input id="email" class="input-base" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-5">
                    <x-input-label for="password" :value="__('Password')" class="mb-2" />
                    <x-text-input id="password" class="input-base" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mb-6 flex items-center justify-between flex-wrap gap-3">
                    <label for="remember_me" class="flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="border-neutral-300 text-primary-600 focus:ring-primary-500 h-4 w-4" name="remember">
                        <span class="ml-2 text-sm text-neutral-600">{{ __('Remember me') }}</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-primary-600 hover:text-primary-700 font-medium" href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <x-primary-button class="w-full btn-primary min-h-[48px]">{{ __('Log in') }}</x-primary-button>
            </form>

            @if ($socialLogin)
                <div class="lg:border-l lg:border-neutral-200 lg:pl-8">
                    <p class="text-sm font-semibold text-neutral-900 mb-2">Quick access</p>
                    <p class="text-sm text-neutral-600 mb-4">Sign in with your preferred account.</p>
                    <x-cms.social-login-buttons />
                </div>
            @endif
        </div>

        <p class="mt-6 pt-6 border-t border-neutral-200 text-sm text-neutral-600 text-center">
            New here?
            <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-700">Create account</a>
        </p>
    </x-auth.form-shell>
</x-auth-layout>
