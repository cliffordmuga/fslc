{{-- resources/views/components/danger-button.blade.php --}}
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center btn-danger']) }}>
    {{ $slot }}
</button>
