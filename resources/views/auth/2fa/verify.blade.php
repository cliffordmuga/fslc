@section('title')
    <title>{{ meta_title('Two-Factor Authentication') }}</title>
@endsection

<x-auth-layout>
    <x-auth.form-shell title="Two-factor authentication" subtitle="Enter the verification code from your authenticator app.">
        <form method="POST" action="{{ route('2fa.store') }}">
            @csrf
            <div class="mb-6">
                <x-input-label for="code" :value="__('Verification Code')" class="mb-2" />
                <x-text-input id="code" class="input-base" type="text" name="code" required autocomplete="off" autofocus />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>
            <div class="flex items-center justify-between gap-3">
                <a href="{{ route('login') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">{{ __('Back to login') }}</a>
                <x-primary-button class="btn-primary min-h-[48px]">{{ __('Verify') }}</x-primary-button>
            </div>
        </form>
    </x-auth.form-shell>
</x-auth-layout>
