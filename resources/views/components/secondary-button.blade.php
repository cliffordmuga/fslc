{{-- resources/views/components/secondary-button.blade.php --}}
<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center btn-secondary disabled:opacity-25']) }}>
    {{ $slot }}
</button>
