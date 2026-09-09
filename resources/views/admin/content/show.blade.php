{{-- resources/views/admin/content/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'View Content')

@section('content')
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="heading-page">{{ $content->title }}</h1>
                    <p class="mt-2 text-sm text-neutral-600">{{ ucfirst($content->type) }} • {{ ucfirst($content->status) }}</p>
                </div>
                <div class="space-x-2">
                    <a href="{{ route('admin.content.preview-link', $content) }}" target="_blank" rel="noopener"
                        class="inline-flex items-center px-3 py-2 btn-secondary">
                        Preview
                    </a>
                    <a href="{{ route('admin.content.edit', $content) }}"
                        class="inline-flex items-center px-3 py-2 btn-secondary">
                        Edit
                    </a>
                    <a href="{{ route('admin.content.index') }}"
                        class="inline-flex items-center px-3 py-2 btn-secondary">
                        Back
                    </a>
                </div>
            </div>
        </div>

        <div class="card-base">
            <div class="px-6 py-4 border-b border-neutral-200">
                <h3 class="text-lg font-medium text-neutral-900">Content Details</h3>
            </div>
            <div class="px-6 py-4 space-y-4 lg:space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Title</label>
                        <p class="mt-1 text-sm text-neutral-900">{{ $content->title }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Slug</label>
                        <p class="mt-1 text-sm text-neutral-900">{{ $content->slug }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Type</label>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                            {{ ucfirst($content->type) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Status</label>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $content->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} mt-1">
                            {{ ucfirst($content->status) }}
                        </span>
                    </div>
                    @if ($content->published_at)
                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Published At</label>
                            <p class="mt-1 text-sm text-neutral-900">{{ $content->published_at->format('M d, Y \a\t g:i A') }}
                            </p>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Sort Order</label>
                        <p class="mt-1 text-sm text-neutral-900">{{ $content->sort_order }}</p>
                    </div>
                </div>

                @if ($content->excerpt)
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Excerpt</label>
                        <p class="mt-1 text-sm text-neutral-900">{{ $content->excerpt }}</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-neutral-700">Content</label>
                    <div class="mt-1 prose prose-sm max-w-none">
                        {!! sanitize_rich_html($content->content) !!}
                    </div>
                </div>

                @if (!empty($groupedImages))
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Images</label>
                        <div class="mt-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach ($groupedImages as $uuid => $group)
                                @if ($group['representative'])
                                    <div class="bg-neutral-50 p-2 rounded">
                                        <img src="{{ $group['representative']->url }}"
                                            alt="{{ $group['representative']->alt_text ?? 'Image' }}"
                                            onerror="this.src='{{ asset('images/placeholder.jpg') }}'"
                                            loading="lazy"
                                            class="w-full h-32 object-cover rounded">
                                        <p class="mt-1 text-xs text-neutral-500">{{ $group['representative']->alt_text }}</p>
                                        <p class="mt-1 text-xs text-neutral-400">
                                            Main Variant • {{ ucfirst($group['representative']->collection) }}<br>
                                            <small>{{ count($group['images']) }} total variants</small>
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-neutral-50 p-4 rounded">
                        <p class="text-sm text-neutral-500">No images associated with this content.</p>
                    </div>
                @endif

                @if ($content->seoMetadata)
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">SEO Metadata</label>
                        <div class="mt-1 space-y-2">
                            <p><strong>Meta Title:</strong> {{ $content->seoMetadata->meta_title ?? 'N/A' }}</p>
                            <p><strong>Meta Description:</strong> {{ $content->seoMetadata->meta_description ?? 'N/A' }}
                            </p>
                            <p><strong>Canonical URL:</strong> {{ $content->seoMetadata->canonical_url ?? 'N/A' }}</p>
                            <p><strong>No Index:</strong> {{ $content->seoMetadata->noindex ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
