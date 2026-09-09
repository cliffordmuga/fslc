@section('title')
    <title>{{ meta_title('Set up Two-Factor Authentication') }}</title>
@endsection

<x-auth-layout>
    <x-auth.form-shell title="Set up two-factor authentication" subtitle="Scan the QR code with your authenticator app, then enter the verification code.">
        <form method="POST" action="{{ route('2fa.enable') }}">
            @csrf

            <div class="mb-6 text-center border border-neutral-200 bg-neutral-50 p-4">
                @if ($qrCodeUrl)
                    <img src="{{ $qrCodeUrl }}" alt="2FA QR Code" class="mx-auto max-w-48 h-auto" width="240" height="240">
                @else
                    <p class="text-sm text-amber-800">
                        {{ __('Could not render the QR code. Use the manual secret key below in your authenticator app.') }}
                    </p>
                @endif
            </div>

            <div class="mb-6">
                <p class="text-sm text-neutral-600 mb-2 font-medium">{{ __('Manual secret key') }}</p>
                <code class="block bg-neutral-50 px-4 py-3 text-sm font-mono text-neutral-900 border border-neutral-200 break-all">{{ $secret }}</code>
            </div>

            @if ($recoveryCodes)
                <div class="mb-6 border border-neutral-200 bg-neutral-50 p-4">
                    <h3 class="text-sm font-bold text-neutral-900 mb-2">{{ __('Recovery codes') }}</h3>
                    <ul class="list-disc pl-5 space-y-1 text-sm text-neutral-800">
                        @foreach ($recoveryCodes as $code)
                            <li>{{ $code }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-6">
                <x-input-label for="code" :value="__('Verification Code')" class="mb-2" />
                <x-text-input id="code" class="input-base" type="text" name="code" required autocomplete="off" />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between gap-3">
                <a href="{{ route('dashboard') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">{{ __('Back to dashboard') }}</a>
                <x-primary-button class="btn-primary min-h-[48px]">{{ __('Enable 2FA') }}</x-primary-button>
            </div>
        </form>
    </x-auth.form-shell>
</x-auth-layout>
