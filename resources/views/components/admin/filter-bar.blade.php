@props([
    'action',
    'method' => 'GET',
])

<div class="card-base mb-6">
    <div class="px-6 py-4">
        <form method="{{ $method }}" action="{{ $action }}" class="flex flex-wrap gap-4">
            {{ $slot }}
            <button type="submit" class="btn-primary px-4 py-2 text-sm">Filter</button>
            @if ($clearUrl ?? false)
                <a href="{{ $clearUrl }}" class="btn-secondary px-4 py-2 text-sm">Clear</a>
            @endif
        </form>
    </div>
</div>
