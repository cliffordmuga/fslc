@section('title')
    <title>{{ meta_title('Verify Email') }}</title>
@endsection

<x-auth-layout>
    <x-auth.form-shell title="Verify your email" subtitle="Thanks for signing up. Confirm your email address to continue.">
        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 border-l-4 border-green-500 bg-green-50 p-4 text-sm text-green-900" role="status">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
            @csrf
            <x-primary-button class="w-full btn-primary min-h-[48px]">{{ __('Resend Verification Email') }}</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full btn-secondary min-h-[48px]">{{ __('Log Out') }}</button>
        </form>
    </x-auth.form-shell>
</x-auth-layout>
