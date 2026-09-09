{{-- resources/views/components/auth-form-field.blade.php --}}
@props([
    'id',
    'type' => 'text',
    'name',
    'required' => false,
    'value' => '',
    'placeholder' => '',
    'autocomplete' => '',
    'class' => 'input-base mt-1',
])
<div class="animate-fade-in" :style="`animation-delay: {{ $index ?? 0 } * 0.1 }s`">
    <x-input-label :for="$id" :value="$label ?? ucfirst($name)" />
    <div class="relative">
        <input 
            {{ $attributes->merge(['id' => $id, 'type' => $type, 'name' => $name, 'value' => $value, 'required' => $required, 'autocomplete' => $autocomplete, 'class' => $class, 'placeholder' => $placeholder]) }}
        />
        {{ $slot }} {{-- Slot for custom elements like password toggle --}}
    </div>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>