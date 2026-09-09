{{-- views/auth/reset-password.blade.php --}}
@section('title')
    <title>{{ meta_title('Reset Password') }}</title>
@endsection
@push('meta')
    <meta name="description" content="{{ meta_description('Set a new password for your account.') }}">
@endpush

<x-auth-layout>
    <x-auth.form-shell title="Reset password" subtitle="Choose a new password for your account.">
        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-5">
                <x-input-label for="email" :value="__('Email')" class="mb-2" />
                <x-text-input id="email" class="input-base" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-5">
                <x-input-label for="password" :value="__('New Password')" class="mb-2" />
                <x-text-input id="password" class="input-base" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mb-6">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="mb-2" />
                <x-text-input id="password_confirmation" class="input-base" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <x-primary-button class="w-full btn-primary min-h-[48px]">{{ __('Reset Password') }}</x-primary-button>
        </form>
    </x-auth.form-shell>
</x-auth-layout>
