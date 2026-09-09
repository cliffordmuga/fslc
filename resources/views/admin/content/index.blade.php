{{-- resources/views/admin/content/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Content Management')
@section('header', 'Content')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <x-cms.admin-page-header title="Content Management" description="Manage your portfolio, services, and pages">
        <x-slot name="actions">
            <a href="{{ route('admin.content.create') }}" class="inline-flex items-center btn-primary px-4 py-2 text-sm">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Content
            </a>
        </x-slot>
    </x-cms.admin-page-header>

    <!-- Filters -->
    <div class="card-base mb-6">
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('admin.content.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-0">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search content..."
                           class="input-base">
                </div>
                <div class="min-w-0">
                    <select name="type" class="input-base">
                        <option value="">All Types</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-0">
                    <select name="status" class="input-base">
                        <option value="">All Status</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'type', 'status']))
                    <a href="{{ route('admin.content.index') }}" class="btn-secondary px-4 py-2 text-sm">
                        Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Content Table -->
    <div class="card-base overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Content</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Author</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Updated</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse($contents as $content)
                        <tr class="hover:bg-neutral-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($content->images->isNotEmpty())
                                        @php $img = $content->images->first(); @endphp
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-lg object-cover" src="{{ $img->url }}" alt="{{ $img->alt_text ?? $content->title }}" loading="lazy">
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-neutral-900">{{ $content->title }}</div>
                                        <div class="text-sm text-neutral-500">{{ Str::limit($content->excerpt, 60) }}</div>
                                        <div class="text-xs text-neutral-400">/{{ $content->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    {{ ucfirst($content->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($content->status === 'published') bg-green-100 text-green-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($content->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $content->creator->name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $content->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ $content->url }}" target="_blank" rel="noopener noreferrer" aria-label="Open {{ $content->title }} in a new tab" class="text-neutral-400 hover:text-neutral-500">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.content.show', $content) }}" class="text-primary-600 hover:text-primary-700">View</a>
                                    <a href="{{ route('admin.content.edit', $content) }}" class="text-primary-600 hover:text-primary-700">Edit</a>
                                    <form method="POST" action="{{ route('admin.content.destroy', $content) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this content?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-neutral-900">No content found</h3>
                                <p class="mt-1 text-sm text-neutral-500">Get started by creating your first piece of content.</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.content.create') }}" class="inline-flex items-center btn-primary px-4 py-2 text-sm">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Create Content
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contents->hasPages())
            <div class="px-6 py-4 border-t border-neutral-200">
                {{ $contents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
