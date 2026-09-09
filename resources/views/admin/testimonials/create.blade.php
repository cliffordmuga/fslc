@extends('layouts.admin')
@section('title', 'Create Testimonial')
@section('content')
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="heading-page">Create Testimonial</h1>
                    <p class="mt-2 text-sm text-neutral-600">Add a new customer testimonial</p>
                </div>
                <a href="{{ route('admin.testimonials.index') }}"
                    class="inline-flex items-center btn-secondary px-4 py-2 text-sm">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Testimonials
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.testimonials.store') }}" class="space-y-4 lg:space-y-4 lg:space-y-6">
            @csrf

            <div class="card-base">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Testimonial Information</h3>
                </div>
                <div class="px-6 py-4 space-y-4 lg:space-y-6">
                    <div>
                        <label for="client_name" class="block text-sm font-medium text-neutral-700">Client Name</label>
                        <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}" required
                            class="mt-1 block w-full input-base"
                            placeholder="Enter client's full name">
                        @error('client_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="testimonial" class="block text-sm font-medium text-neutral-700">Testimonial</label>
                        <textarea name="testimonial" id="testimonial" rows="6" required
                            class="mt-1 block w-full input-base"
                            placeholder="Enter the testimonial text...">{{ old('testimonial') }}</textarea>
                        @error('testimonial')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="rating" class="block text-sm font-medium text-neutral-700">Rating</label>
                            <select name="rating" id="rating"
                                class="mt-1 block w-full input-base">
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('rating', 5) == $i ? 'selected' : '' }}>
                                        {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-neutral-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full input-base">
                                @foreach ($statuses as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('status', 'approved') == $key ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1"
                            {{ old('is_featured') ? 'checked' : '' }}
                            class="rounded border-neutral-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        <label for="is_featured" class="ml-2 block text-sm text-neutral-900">
                            Feature this testimonial (will be displayed prominently on the website)
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.testimonials.index') }}"
                    class="inline-flex items-center px-4 py-2 btn-secondary">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center btn-primary px-4 py-2 text-sm">
                    Create Testimonial
                </button>
            </div>
        </form>
    </div>
@endsection
