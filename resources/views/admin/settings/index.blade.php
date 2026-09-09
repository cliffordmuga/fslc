@extends('layouts.admin')
@section('title', 'Settings Management')
@section('header', 'Settings')
@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <x-cms.admin-page-header title="Settings Management" description="Configure your website settings and preferences">
        <x-slot name="actions">
            <form method="POST" action="{{ route('admin.sitemap.generate') }}" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center btn-secondary px-4 py-2 text-sm mr-2">Regenerate Sitemap</button>
            </form>
            <a href="{{ route('admin.settings.create') }}" class="inline-flex items-center btn-primary px-4 py-2 text-sm">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Setting
            </a>
        </x-slot>
    </x-cms.admin-page-header>

    <div class="card-base mb-6">
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('admin.settings.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-0">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search settings..." class="input-base">
                </div>
                <div class="min-w-0">
                    <select name="category" class="input-base">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">Filter</button>
                @if(request()->hasAny(['search', 'category']))
                    <a href="{{ route('admin.settings.index') }}" class="btn-secondary px-4 py-2 text-sm">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card-base overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Setting</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Value</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse($settings as $setting)
                        <tr class="hover:bg-neutral-50">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-neutral-900">{{ $setting->key }}</div>
                                    @if($setting->description)
                                        <div class="text-sm text-neutral-500">{{ $setting->description }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-neutral-900">
                                    @if($setting->type === 'boolean')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $setting->value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $setting->value ? 'True' : 'False' }}
                                        </span>
                                    @elseif($setting->type === 'textarea' || strlen($setting->value) > 50)
                                        {{ Str::limit($setting->value, 50) }}...
                                    @else
                                        {{ $setting->value }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($setting->category) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ ucfirst($setting->type) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.settings.show', $setting) }}" class="text-primary-600 hover:text-primary-700">View</a>
                                    <a href="{{ route('admin.settings.edit', $setting) }}" class="text-primary-600 hover:text-primary-700">Edit</a>
                                    <form method="POST" action="{{ route('admin.settings.destroy', $setting) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this setting?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-neutral-900">No settings found</h3>
                                <p class="mt-1 text-sm text-neutral-500">Get started by creating your first setting.</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.settings.create') }}" class="inline-flex items-center btn-primary px-4 py-2 text-sm">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Setting
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($settings->hasPages())
            <div class="px-6 py-4 border-t border-neutral-200">
                {{ $settings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection