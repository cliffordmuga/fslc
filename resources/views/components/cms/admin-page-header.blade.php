@props([
    'title',
    'description' => null,
    'actions' => null,
])

<header class="mb-6 sm:mb-8 pb-6 border-b border-neutral-200/90">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between sm:gap-6">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-bold text-neutral-900 tracking-tight">{{ $title }}</h1>
            @if ($description)
                <p class="mt-1.5 text-sm text-neutral-600 leading-relaxed max-w-3xl">{{ $description }}</p>
            @endif
        </div>
        @if (isset($actions))
            <div class="flex flex-shrink-0 flex-wrap items-center gap-2 sm:justify-end">{{ $actions }}</div>
        @endif
    </div>
</header>
