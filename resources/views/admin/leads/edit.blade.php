@extends('layouts.admin')
@section('title', 'Edit Lead - ' . $lead->name)
@section('content')
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="heading-page">Edit Lead</h1>
                <p class="mt-2 text-sm text-neutral-600">{{ $lead->name }} - {{ $lead->email }}</p>
            </div>
            <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center btn-secondary px-4 py-2 text-sm">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Leads
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="space-y-4 lg:space-y-4 lg:space-y-6">
        @csrf
        @method('PUT')
        
        <div class="card-base">
            <div class="px-6 py-4 border-b border-neutral-200">
                <h3 class="text-lg font-medium text-neutral-900">Lead Information</h3>
            </div>
            <div class="px-6 py-4 space-y-4 lg:space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="status" class="block text-sm font-medium text-neutral-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full input-base">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $lead->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="conversion_value" class="block text-sm font-medium text-neutral-700">Project Value ($) — when converted</label>
                        <input type="number" name="conversion_value" id="conversion_value" value="{{ old('conversion_value', $lead->conversion_value) }}" step="0.01" min="0" placeholder="e.g. 5000" class="mt-1 block w-full input-base">
                        <p class="mt-1 text-xs text-neutral-500">Record deal value to track revenue by source</p>
                        @error('conversion_value')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_spam" id="is_spam" value="1" {{ old('is_spam', $lead->is_spam) ? 'checked' : '' }} class="rounded border-neutral-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <label for="is_spam" class="ml-2 block text-sm text-neutral-900">Mark as spam</label>
                </div>
            </div>
        </div>

        <div class="card-base">
            <div class="px-6 py-4 border-b border-neutral-200">
                <h3 class="text-lg font-medium text-neutral-900">Contact Details (Read Only)</h3>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-neutral-500">Name</dt>
                        <dd class="mt-1 text-sm text-neutral-900">{{ $lead->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-neutral-500">Email</dt>
                        <dd class="mt-1 text-sm text-neutral-900">{{ $lead->email }}</dd>
                    </div>
                    @if($lead->phone)
                    <div>
                        <dt class="text-sm font-medium text-neutral-500">Phone</dt>
                        <dd class="mt-1 text-sm text-neutral-900">{{ $lead->phone }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-neutral-500">Inquiry Type</dt>
                        <dd class="mt-1 text-sm text-neutral-900">{{ ucfirst($lead->inquiry_type) }}</dd>
                    </div>
                </dl>
                
                <div class="mt-6">
                    <dt class="text-sm font-medium text-neutral-500">Message</dt>
                    <dd class="mt-2 text-sm text-neutral-900 bg-neutral-50 p-3 rounded-md whitespace-pre-line">{{ $lead->message }}</dd>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center btn-secondary px-4 py-2 text-sm">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center btn-primary px-4 py-2 text-sm">
                Update Lead
            </button>
        </div>
    </form>
</div>
@endsection