{{-- resources/views/components/auth/form-shell.blade.php --}}
@props([
    'title',
    'subtitle' => null,
    'maxWidth' => 'max-w-md',
])

<div class="{{ $maxWidth }} mx-auto w-full">
    <div class="mb-8">
        <p class="section-label mb-2">Account</p>
        <h1 class="text-2xl lg:text-3xl font-black text-neutral-900 tracking-tight">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-sm text-neutral-600 mt-2 leading-relaxed">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="card-base bg-white p-6 lg:p-8">
        {{ $slot }}
    </div>
</div>
