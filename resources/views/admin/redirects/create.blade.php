@extends('layouts.admin')

@section('title', 'Add Redirect')

@section('content')
<div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="heading-page">Add Redirect</h1>
        <p class="mt-2 text-sm text-neutral-600">Create a redirect from an old path to a new destination.</p>
    </div>

    <x-cms.ui.card variant="elevated">
        <form method="POST" action="{{ route('admin.redirects.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-neutral-700">Old Path</label>
                <input name="old_path" value="{{ old('old_path') }}" placeholder="/old-slug"
                       class="mt-1 w-full input-base">
                @error('old_path') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700">New Path or URL</label>
                <input name="new_path" value="{{ old('new_path') }}" placeholder="/new-slug or https://example.com/new"
                       class="mt-1 w-full input-base">
                @error('new_path') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700">Status Code</label>
                    <select name="status_code"
                            class="mt-1 w-full input-base">
                        @foreach ([301,302,307,308] as $code)
                            <option value="{{ $code }}" {{ (int)old('status_code', 301) === $code ? 'selected' : '' }}>{{ $code }}</option>
                        @endforeach
                    </select>
                    @error('status_code') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-2 mt-6">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label class="text-sm text-neutral-700">Active</label>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="btn-primary px-4 py-2 text-sm">
                    Create
                </button>
                <a href="{{ route('admin.redirects.index') }}" class="btn-secondary px-4 py-2 text-sm">Cancel</a>
            </div>
        </form>
    </x-cms.ui.card>
</div>
@endsection
