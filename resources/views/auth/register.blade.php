{{-- views/auth/register.blade.php --}}
@section('title')
    <title>{{ meta_title('Register') }}</title>
@endsection
@push('meta')
    <meta name="description" content="{{ meta_description('Create an account to manage leads and content.') }}">
@endpush

@php($socialLogin = config('features.social_login_enabled', false))
<x-auth-layout>
    <x-auth.form-shell title="Create account" subtitle="Register to track leads and manage your projects." :max-width="$socialLogin ? 'max-w-4xl' : 'max-w-md'">
        <div class="{{ $socialLogin ? 'grid lg:grid-cols-2 gap-8 lg:gap-10' : '' }}">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                @foreach (['name' => ['text', 'name'], 'email' => ['email', 'username'], 'password' => ['password', 'new-password'], 'password_confirmation' => ['password', 'new-password']] as $field => [$type, $autocomplete])
                    <div class="mb-5">
                        <x-input-label for="{{ $field }}" :value="__($field === 'password_confirmation' ? 'Confirm Password' : ucfirst(str_replace('_', ' ', $field)))" class="mb-2" />
                        <x-text-input id="{{ $field }}" class="input-base" type="{{ $type }}" name="{{ $field }}" :value="$field === 'email' || $field === 'name' ? old($field) : ''" required @if($field === 'name') autofocus @endif autocomplete="{{ $autocomplete }}" />
                        <x-input-error :messages="$errors->get($field)" class="mt-2" />
                    </div>
                @endforeach

                <x-primary-button class="w-full btn-primary min-h-[48px]">{{ __('Register') }}</x-primary-button>
            </form>

            @if ($socialLogin)
                <div class="lg:border-l lg:border-neutral-200 lg:pl-8">
                    <p class="text-sm font-semibold text-neutral-900 mb-2">Quick sign up</p>
                    <p class="text-sm text-neutral-600 mb-4">Create your account instantly.</p>
                    <x-cms.social-register-buttons />
                </div>
            @endif
        </div>

        <p class="mt-6 pt-6 border-t border-neutral-200 text-sm text-neutral-600 text-center">
            Already registered?
            <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">Log in</a>
        </p>
    </x-auth.form-shell>
</x-auth-layout>
