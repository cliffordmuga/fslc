{{-- BC alias — prefer <x-ui.card /> --}}
@props([
    'variant' => 'default',
    'hoverable' => false,
])

<x-ui.card :variant="$variant" :hoverable="$hoverable" {{ $attributes }}>
    @isset($header)
        <x-slot:header>{{ $header }}</x-slot:header>
    @endisset
    {{ $slot }}
    @isset($footer)
        <x-slot:footer>{{ $footer }}</x-slot:footer>
    @endisset
</x-ui.card>
