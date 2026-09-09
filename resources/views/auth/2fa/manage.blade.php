@section('title')
    <title>{{ meta_title('Manage Two-Factor Authentication') }}</title>
@endsection

<x-auth-layout>
    <x-auth.form-shell title="Two-factor authentication" subtitle="Your account is protected with an authenticator app.">
        <div class="mb-6 border border-green-200 bg-green-50 p-4 text-sm text-green-800">
            {{ __('Two-factor authentication is enabled on your account.') }}
        </div>

        @if (! empty($recoveryCodes))
            <div class="mb-6 border border-neutral-200 bg-neutral-50 p-4">
                <h3 class="text-sm font-bold text-neutral-900 mb-2">{{ __('Recovery codes') }}</h3>
                <p class="text-xs text-neutral-600 mb-3">{{ __('Store these in a safe place. Each code can be used once.') }}</p>
                <ul class="list-disc pl-5 space-y-1 text-sm text-neutral-800 font-mono">
                    @foreach ($recoveryCodes as $code)
                        <li>{{ $code }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('2fa.disable') }}" class="space-y-4" onsubmit="return confirm('{{ __('Disable two-factor authentication?') }}');">
            @csrf
            @method('DELETE')
            <div>
                <x-input-label for="password" :value="__('Confirm password to disable 2FA')" class="mb-2" />
                <x-text-input id="password" class="input-base" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="flex items-center justify-between gap-3">
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">{{ __('Back to dashboard') }}</a>
                <x-danger-button class="btn-secondary min-h-[48px]">{{ __('Disable 2FA') }}</x-danger-button>
            </div>
        </form>
    </x-auth.form-shell>
</x-auth-layout>
