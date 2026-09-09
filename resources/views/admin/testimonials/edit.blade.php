@extends('layouts.admin')
@section('title', 'Edit Testimonial')
@section('content')
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="heading-page">Edit Testimonial: {{ $testimonial->client_name }}</h1>
                <p class="mt-2 text-sm text-neutral-600">Update testimonial details</p>
            </div>
            <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center btn-secondary px-4 py-2 text-sm">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Testimonials
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" class="space-y-4 lg:space-y-4 lg:space-y-6">
        @csrf @method('PUT')
        <div class="card-base">
            <div class="px-6 py-4 border-b border-neutral-200">
                <h3 class="text-lg font-medium text-neutral-900">Testimonial Details</h3>
            </div>
            <div class="px-6 py-4 space-y-4 lg:space-y-6">
                <div>
                    <label for="client_name" class="block text-sm font-medium text-neutral-700">Client Name</label>
                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $testimonial->client_name) }}" required class="mt-1 block w-full input-base">
                    @error('client_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="testimonial" class="block text-sm font-medium text-neutral-700">Testimonial</label>
                    <textarea name="testimonial" id="testimonial" rows="4" required class="mt-1 block w-full input-base">{{ old('testimonial', $testimonial->testimonial) }}</textarea>
                    @error('testimonial') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="rating" class="block text-sm font-medium text-neutral-700">Rating</label>
                    <select name="rating" id="rating" required class="mt-1 block w-full input-base">
                        <option value="">Select Rating</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                    @error('rating') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }} class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-neutral-700">Featured</span>
                        </label>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-neutral-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full input-base">
                            <option value="pending" {{ old('status', $testimonial->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('status', $testimonial->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ old('status', $testimonial->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center px-4 py-2 btn-secondary">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center btn-primary px-4 py-2 text-sm">
                Update Testimonial
            </button>
        </div>
    </form>
</div>
@endsection