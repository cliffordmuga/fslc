{{-- resources/views/admin/content/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Content - ' . $content->title)

@section('content')
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="heading-page">Edit Content</h1>
                    <p class="mt-2 text-sm text-neutral-600">{{ $content->title }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ $content->url }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center btn-primary px-4 py-2 text-sm">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        View Live
                    </a>
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
        </div>

        <form method="POST" action="{{ route('admin.content.update', $content) }}" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Basic Information Section (unchanged) --}}
            <div class="card-base">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Basic Information</h3>
                </div>
                <div class="px-6 py-4 space-y-4 lg:space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-neutral-700">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $content->title) }}"
                            required
                            class="mt-1 block w-full input-base">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ slugFromTitle() { const t = document.getElementById('title').value; document.getElementById('slug').value = t.toLowerCase().replace(/[^\w\s-]/g,'').replace(/[\s_-]+/g,'-').replace(/^-+|-+$/g,''); } }">
                        <label for="slug" class="block text-sm font-medium text-neutral-700">Slug</label>
                        <div class="mt-1 flex gap-2">
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $content->slug) }}"
                                class="block flex-1 input-base" placeholder="url-friendly-version">
                            <button type="button" @click="slugFromTitle()"
                                class="btn-secondary px-3 py-2 text-sm whitespace-nowrap"
                                title="Regenerate from title">
                                Regenerate
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-neutral-500">URL path. Leave empty or use Regenerate to auto-generate from title.</p>
                        @error('slug')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="type" class="block text-sm font-medium text-neutral-700">Type</label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full input-base">
                                @foreach ($types as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('type', $content->type) == $key ? 'selected' : '' }}>{{ $label }}
                                    </option>
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
                                        {{ old('status', $content->status) == $key ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-neutral-700">Excerpt</label>
                        <textarea name="excerpt" id="excerpt" rows="3"
                            class="mt-1 block w-full input-base"
                            placeholder="Brief summary for listings and meta description">{{ old('excerpt', $content->excerpt) }}</textarea>
                        <p class="mt-1 text-sm text-neutral-500">Leave empty to auto-generate from content.</p>
                        @error('excerpt')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-neutral-700">Content</label>
                        <textarea name="content" id="content" rows="12"
                            class="tinymce-editor mt-1 block w-full input-base">{{ old('content', $content->content) }}</textarea>
                        @error('content')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Current Images Section --}}
            @if (!empty($groupedImages))
                <div class="card-base">
                    <div class="px-6 py-4 border-b border-neutral-200">
                        <h3 class="text-lg font-medium text-neutral-900">Current Images</h3>
                        <p class="text-sm text-neutral-600 mt-1">Alt text is auto-generated from the content title on upload. Customize for better accessibility and SEO. Select groups to delete (removes all variants).</p>
                    </div>
                    <div class="px-6 py-4 space-y-6">
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                            @foreach ($groupedImages as $uuid => $group)
                                @if ($group['representative'])
                                    <div class="relative group space-y-2">
                                        {{-- Checkbox: Value = UUID (group key) --}}
                                        <div class="absolute top-2 left-2 z-10">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="images_to_delete[]"
                                                    value="{{ $uuid }}" id="image-checkbox-{{ $loop->index }}"
                                                    class="sr-only peer">
                                                <div
                                                    class="w-5 h-5 bg-white peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded peer-checked:bg-blue-600 peer-checked:after:absolute peer-checked:after:inset-0 peer-checked:after:bg-white peer-checked:after:bg-opacity-0 peer-checked:after:border-2 peer-checked:after:border-white peer-checked:after:rounded-sm after:border-neutral-300 after:border-2 after:rounded-sm after:absolute after:inset-0 after:bg-white after:bg-opacity-0">
                                                </div>
                                            </label>
                                        </div>
                                        {{-- Representative (main) Image --}}
                                        <img src="{{ $group['representative']->url }}"
                                            alt="{{ $group['representative']->alt_text ?? 'Image' }}"
                                            onerror="this.src='{{ asset('images/placeholder.jpg') }}'"
                                            loading="lazy"
                                            class="w-full h-32 object-cover rounded-lg border-2 border-neutral-200 hover:border-blue-300 transition-colors">
                                        {{-- Alt text: prominent field for SEO and accessibility --}}
                                        <div>
                                            <label for="image_alt_{{ $loop->index }}" class="block text-sm font-medium text-neutral-700">
                                                <span class="text-amber-600" title="Important for SEO and screen readers">Alt text</span>
                                            </label>
                                            <input type="text" name="image_alt[{{ $uuid }}]" id="image_alt_{{ $loop->index }}"
                                                value="{{ old("image_alt.{$uuid}", $group['representative']->alt_text ?? '') }}"
                                                placeholder="Describe this image (improves accessibility)"
                                                maxlength="255"
                                                class="mt-1 block w-full input-base text-sm">
                                        </div>
                                        {{-- Hover: Collection & Variant Count --}}
                                        <div
                                            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="text-white text-xs font-medium px-2 py-1 bg-blue-600 rounded">
                                                {{ $group['representative']->collection }} • {{ count($group['images']) }} variants
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @error('images_to_delete')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('image_alt.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @else
                <div class="card-base p-6">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-3 text-sm font-medium text-neutral-900">No Images</h3>
                        <p class="mt-4 text-sm text-neutral-500">Add images using the "Update Images" section below.</p>
                    </div>
                </div>
            @endif

            {{-- Update Images --}}
            <div class="card-base">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Update Images</h3>
                    <p class="text-sm text-neutral-500 mt-0.5">New uploads get alt text auto-generated from the content title. Edit in the section above after saving.</p>
                </div>
                <div class="px-6 py-4 space-y-4 lg:space-y-6">
                    <div>
                        <label for="featured_image" class="block text-sm font-medium text-neutral-700">Replace Featured
                            Image</label>
                        <input type="file" name="featured_image" id="featured_image" accept="image/*"
                            class="mt-1 block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        @error('featured_image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="gallery_images" class="block text-sm font-medium text-neutral-700">Add Gallery
                            Images</label>
                        <input type="file" name="gallery_images[]" id="gallery_images" multiple accept="image/*"
                            class="mt-1 block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        @error('gallery_images.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SEO Settings (Advanced) --}}
            <details class="card-base seo-details" {{ (optional($content->seoMetadata)->meta_title || optional($content->seoMetadata)->meta_description) ? 'open' : '' }}>
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
                    <x-admin.seo-score :content="$content" />
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-neutral-700">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title"
                                value="{{ old('meta_title', $content->seoMetadata->meta_title ?? '') }}" maxlength="60"
                                class="mt-1 block w-full input-base"
                                placeholder="Defaults to content title (max 60 chars)">
                            @error('meta_title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="canonical_url" class="block text-sm font-medium text-neutral-700">Canonical URL</label>
                            <input type="url" name="canonical_url" id="canonical_url"
                                value="{{ old('canonical_url', $content->seoMetadata->canonical_url ?? '') }}"
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
                            placeholder="Defaults to excerpt (max 160 chars)">{{ old('meta_description', $content->seoMetadata->meta_description ?? '') }}</textarea>
                        @error('meta_description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="noindex" id="noindex" value="1"
                            {{ old('noindex', $content->seoMetadata->noindex ?? false) ? 'checked' : '' }}
                            class="rounded border-neutral-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        <label for="noindex" class="ml-2 block text-sm text-neutral-900">No Index (prevent search engines from indexing this page)</label>
                    </div>
                </div>
            </details>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.content.index') }}"
                    class="inline-flex items-center btn-secondary px-4 py-2 text-sm">
                    Cancel </a>
                <button type="submit" class="inline-flex items-center btn-primary px-4 py-2 text-sm">
                    Update Content </button>
            </div>
        </form>
    </div>
@endsection
