@props(['data'])

@php
    // Allow either a single array or an array of arrays
    $items = is_array($data) && array_is_list($data) ? $data : [$data];
@endphp

@foreach ($items as $item)
    @if (!empty($item) && is_array($item))
        <script type="application/ld+json">{!! json_encode($item, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
@endforeach
