{{-- views/auth/forgot-password.blade.php --}}
@section('title')
    <title>{{ meta_title('Forgot Password') }}</title>
@endsection
@push('meta')
    <meta name="description" content="{{ meta_description('Reset your password with a secure link.') }}">
@endpush

<x-auth-layout>
    <x-auth.form-shell title="Forgot password" subtitle="Enter your email and we will send a secure reset link.">
        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-6">
                <x-input-label for="email" :value="__('Email')" class="mb-2" />
                <x-text-input id="email" class="input-base" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <x-primary-button class="w-full btn-primary min-h-[48px]">{{ __('Send Password Reset Link') }}</x-primary-button>
        </form>

        <p class="mt-6 pt-6 border-t border-neutral-200 text-sm text-neutral-600 text-center">
            <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">Back to log in</a>
        </p>
    </x-auth.form-shell>
</x-auth-layout>
