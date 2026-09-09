@extends('layouts.admin')
@section('title', 'View Setting')
@section('content')
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="heading-page">Setting: {{ $setting->key }}</h1>
                <p class="mt-2 text-sm text-neutral-600">View configuration details</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('admin.settings.edit', $setting) }}" class="inline-flex items-center btn-secondary px-3 py-2 text-sm">
                    Edit
                </a>
                <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center btn-secondary px-3 py-2 text-sm">
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="card-base">
        <div class="px-6 py-4 border-b border-neutral-200">
            <h3 class="text-lg font-medium text-neutral-900">Setting Details</h3>
        </div>
        <div class="px-6 py-4 space-y-4 lg:space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-700">Key</label>
                    <p class="mt-1 text-sm text-neutral-900">{{ $setting->key }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700">Category</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                        {{ ucfirst($setting->category) }}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700">Type</label>
                    <p class="mt-1 text-sm text-neutral-900">{{ ucfirst($setting->type) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700">Value</label>
                    <p class="mt-1 text-sm text-neutral-900">
                        @if($setting->type === 'boolean')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $setting->value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $setting->value ? 'True' : 'False' }}
                            </span>
                        @else
                            {{ $setting->value }}
                        @endif
                    </p>
                </div>
            </div>
            @if($setting->description)
                <div>
                    <label class="block text-sm font-medium text-neutral-700">Description</label>
                    <p class="mt-1 text-sm text-neutral-900">{{ $setting->description }}</p>
                </div>
            @endif
            <div>
                <label class="block text-sm font-medium text-neutral-700">Created At</label>
                <p class="mt-1 text-sm text-neutral-500">{{ $setting->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700">Updated At</label>
                <p class="mt-1 text-sm text-neutral-500">{{ $setting->updated_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection