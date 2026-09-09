{{-- resources/views/components/primary-button.blade.php --}}
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center btn-primary']) }}>
    {{ $slot }}
</button>
