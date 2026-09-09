@extends('layouts.admin')
@section('title', 'Edit Setting')
@section('content')
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="heading-page">Edit Setting: {{ $setting->key }}</h1>
                <p class="mt-2 text-sm text-neutral-600">Update configuration details</p>
            </div>
            <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center btn-secondary px-4 py-2 text-sm">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Settings
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update', $setting) }}" class="space-y-4 lg:space-y-4 lg:space-y-6" x-data="{ customCategory: '{{ old('category', $setting->category) }}' === 'custom' }">
        @csrf @method('PUT')
        <div class="card-base">
            <div class="px-6 py-4 border-b border-neutral-200">
                <h3 class="text-lg font-medium text-neutral-900">Setting Information</h3>
            </div>
            <div class="px-6 py-4 space-y-4 lg:space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="key" class="block text-sm font-medium text-neutral-700">Key</label>
                        <input type="text" name="key" id="key" value="{{ old('key', $setting->key) }}" required class="mt-1 block w-full input-base" placeholder="setting_key">
                        <p class="mt-1 text-sm text-neutral-500">Use lowercase letters, numbers, and underscores only</p>
                        @error('key') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-neutral-700">Category</label>
                        <select name="category" id="category" @change="if($event.target.value === 'custom') { customCategory = true } else { customCategory = false }" class="mt-1 block w-full input-base">
                            <option value="general" {{ old('category', $setting->category) == 'general' ? 'selected' : '' }}>General</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category', $setting->category) == $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                            @endforeach
                            <option value="custom" {{ old('category', $setting->category) == 'custom' ? 'selected' : '' }}>Custom Category...</option>
                        </select>
                        @error('category') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div x-show="customCategory" x-transition>
                    <label for="custom_category_name" class="block text-sm font-medium text-neutral-700">Custom Category Name</label>
                    <input type="text" name="custom_category_name" id="custom_category_name" value="{{ old('custom_category_name') }}" class="mt-1 block w-full input-base">
                    @error('custom_category_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-neutral-700">Type</label>
                    <select name="type" id="type" class="mt-1 block w-full input-base">
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ old('type', $setting->type) == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                    @error('type') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div id="value-field">
                    @if($setting->type === 'boolean')
                        <label class="flex items-center">
                            <input type="checkbox" name="value" value="1" {{ old('value', $setting->value) ? 'checked' : '' }} class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-neutral-700">Enabled</span>
                        </label>
                    @elseif($setting->type === 'textarea')
                        <label for="value" class="block text-sm font-medium text-neutral-700">Value</label>
                        <textarea name="value" id="value" rows="3" class="mt-1 block w-full input-base">{{ old('value', $setting->value) }}</textarea>
                    @else
                        <label for="value" class="block text-sm font-medium text-neutral-700">Value</label>
                        <input type="{{ $setting->type === 'integer' ? 'number' : 'text' }}" name="value" id="value" value="{{ old('value', $setting->value) }}" class="mt-1 block w-full input-base">
                    @endif
                    @error('value') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-neutral-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full input-base" placeholder="Optional description of what this setting controls">{{ old('description', $setting->description) }}</textarea>
                    @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center btn-secondary px-4 py-2 text-sm">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center btn-primary px-4 py-2 text-sm">
                Update Setting
            </button>
        </div>
    </form>
</div>
@endsection