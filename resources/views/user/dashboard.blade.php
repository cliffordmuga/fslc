<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">{{ __('Dashboard') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card-base p-6 bg-white">
                <p class="text-neutral-700">Welcome, {{ auth()->user()->name }}.</p>
                <p class="mt-2 text-sm text-neutral-500">Use the menu to manage your profile.</p>
                <a href="{{ route('profile.edit') }}" class="mt-4 inline-flex btn-primary px-4 py-2 text-sm">Edit profile</a>
            </div>
        </div>
    </div>
</x-app-layout>
