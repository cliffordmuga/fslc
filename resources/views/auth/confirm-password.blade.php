@section('title')
    <title>{{ meta_title('Confirm Password') }}</title>
@endsection

<x-auth-layout>
    <x-auth.form-shell title="Confirm password" subtitle="Please confirm your password before continuing.">
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="mb-6">
                <x-input-label for="password" :value="__('Password')" class="mb-2" />
                <x-text-input id="password" class="input-base" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <x-primary-button class="w-full btn-primary min-h-[48px]">{{ __('Confirm') }}</x-primary-button>
        </form>
    </x-auth.form-shell>
</x-auth-layout>
