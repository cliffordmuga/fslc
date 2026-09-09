{{-- resources/views/admin/content/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Create Content')

@section('content')
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="heading-page">Create Content</h1>
                    <p class="mt-2 text-sm text-neutral-600">Create a new piece of content for your portfolio</p>
                </div>
                <a href="{{ route('admin.content.index') }}"
                    class="inline-flex items-center btn-secondary px-4 py-2 text-sm">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Content
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.content.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="card-base">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Basic Information</h3>
                </div>
                <div class="px-6 py-4 space-y-4 lg:space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-neutral-700">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="mt-1 block w-full input-base"
                            placeholder="Enter content title">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div x-data="{ slugFromTitle() { const t = document.getElementById('title').value; const s = document.getElementById('slug'); s.value = t.toLowerCase().replace(/[^\w\s-]/g,'').replace(/[\s_-]+/g,'-').replace(/^-+|-+$/g,''); s.dataset.auto = 'true'; } }">
                        <label for="slug" class="block text-sm font-medium text-neutral-700">Slug</label>
                        <div class="mt-1 flex gap-2">
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                class="block flex-1 input-base"
                                placeholder="url-friendly-version">
                            <button type="button" @click="slugFromTitle()"
                                class="btn-secondary px-3 py-2 text-sm whitespace-nowrap"
                                title="Regenerate from title">
                                Regenerate
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-neutral-500">Auto-synced as you type. Use Regenerate to reset from title.</p>
                        @error('slug')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type and Status -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="type" class="block text-sm font-medium text-neutral-700">Type</label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full input-base">
                                <option value="">Select type...</option>
                                @foreach ($types as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-neutral-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full input-base">
                                @foreach ($statuses as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('status', 'draft') == $key ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tags -->
                    @include('admin.content.partials.tags-field', ['selectedTagIds' => []])

                    <!-- Excerpt -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-neutral-700">Excerpt</label>
                        <textarea name="excerpt" id="excerpt" rows="3"
                            class="mt-1 block w-full input-base"
                            placeholder="Brief summary for listings and meta description">{{ old('excerpt') }}</textarea>
                        <p class="mt-1 text-sm text-neutral-500">Leave empty to auto-generate from content.</p>
                        @error('excerpt')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-neutral-700">Content</label>
                        <textarea name="content" id="content" rows="12"
                            class="tinymce-editor mt-1 block w-full input-base"
                            placeholder="Main content goes here...">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Images Section -->
            <div class="card-base">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Images</h3>
                    <p class="text-sm text-neutral-500 mt-0.5">Alt text is auto-generated from the content title on upload. You can edit it later when editing this content.</p>
                </div>
                <div class="px-6 py-4 space-y-4 lg:space-y-6">
                    <!-- Featured Image -->
                    <div>
                        <label for="featured_image" class="block text-sm font-medium text-neutral-700">Featured Image</label>
                        <input type="file" name="featured_image" id="featured_image" accept="image/*"
                            class="mt-1 block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        <p class="mt-1 text-sm text-neutral-500">Upload a featured image for this content</p>
                        @error('featured_image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gallery Images -->
                    <div>
                        <label for="gallery_images" class="block text-sm font-medium text-neutral-700">Gallery Images</label>
                        <input type="file" name="gallery_images[]" id="gallery_images" multiple accept="image/*"
                            class="mt-1 block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        <p class="mt-1 text-sm text-neutral-500">Upload multiple images for a gallery (optional)</p>
                        @error('gallery_images.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SEO Section (Advanced) -->
            <details class="card-base seo-details">
                <summary class="px-6 py-4 border-b border-neutral-200 cursor-pointer list-none flex items-center justify-between hover:bg-neutral-50/50 rounded-t-lg transition-colors">
                    <div>
                        <h3 class="text-lg font-medium text-neutral-900">SEO Settings</h3>
                        <p class="text-sm text-neutral-500 mt-0.5">Leave fields blank to auto-generate from title and content</p>
                    </div>
                    <svg class="details-chevron w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="px-6 py-4 space-y-4 lg:space-y-6">
                    <x-admin.seo-score />
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-neutral-700">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                                maxlength="60" class="mt-1 block w-full input-base"
                                placeholder="Defaults to content title (max 60 chars)">
                            @error('meta_title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="canonical_url" class="block text-sm font-medium text-neutral-700">Canonical URL</label>
                            <input type="url" name="canonical_url" id="canonical_url" value="{{ old('canonical_url') }}"
                                class="mt-1 block w-full input-base"
                                placeholder="Auto-generated if empty">
                            @error('canonical_url')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-neutral-700">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3" maxlength="160"
                            class="mt-1 block w-full input-base"
                            placeholder="Defaults to excerpt (max 160 chars)">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="noindex" id="noindex" value="1"
                            {{ old('noindex') ? 'checked' : '' }}
                            class="rounded border-neutral-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        <label for="noindex" class="ml-2 block text-sm text-neutral-900">No Index (prevent search engines from indexing this page)</label>
                    </div>
                </div>
            </details>

            <!-- Publishing Options -->
            <div class="card-base">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Publishing Options</h3>
                </div>
                <div class="px-6 py-4 space-y-4 lg:space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Published Date -->
                        <div>
                            <label for="published_at" class="block text-sm font-medium text-neutral-700">Publish Date</label>
                            <input type="datetime-local" name="published_at" id="published_at"
                                value="{{ old('published_at') }}"
                                class="mt-1 block w-full input-base">
                            <p class="mt-1 text-sm text-neutral-500">Leave empty to publish immediately when status is
                                published</p>
                            @error('published_at')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-neutral-700">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}"
                                min="0"
                                class="mt-1 block w-full input-base">
                            <p class="mt-1 text-sm text-neutral-500">Higher numbers appear first (0 = default order)</p>
                            @error('sort_order')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.content.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-neutral-300 shadow-sm text-sm font-medium rounded-md text-neutral-700 bg-white hover:bg-neutral-50">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center btn-primary px-4 py-2 text-sm">
                    Create Content
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.getElementById('title').addEventListener('input', function() {
                const title = this.value;
                const slugField = document.getElementById('slug');
                if (!slugField.value || slugField.dataset.auto !== 'false') {
                    const slug = title.toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/[\s_-]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    slugField.value = slug;
                }
            });

            document.getElementById('slug').addEventListener('input', function() {
                this.dataset.auto = 'false';
            });
        </script>
    @endpush
@endsection
