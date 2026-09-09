@extends('layouts.admin')
@section('title', 'Testimonial Details')
@section('header', 'Testimonials')
@section('content')
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="heading-page">{{ $testimonial->client_name }}</h1>
            <p class="mt-2 text-sm text-neutral-600">Submitted {{ $testimonial->created_at->format('M d, Y') }}</p>
        </div>
        <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary px-4 py-2 text-sm">Back to list</a>
    </div>

    <div class="card-base">
        <div class="px-6 py-4 border-b border-neutral-200 flex flex-wrap items-center gap-3">
            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium {{ $testimonial->status === 'approved' ? 'bg-green-100 text-green-800' : ($testimonial->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                {{ ucfirst($testimonial->status) }}
            </span>
            @if ($testimonial->is_featured)
                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-primary-100 text-primary-800">Featured</span>
            @endif
            <span class="text-sm text-neutral-500">{{ $testimonial->rating }}/5 stars</span>
        </div>
        <div class="px-6 py-6 space-y-4">
            <p class="text-neutral-800 leading-relaxed whitespace-pre-line">{{ $testimonial->testimonial }}</p>
            @if ($testimonial->company)
                <p class="text-sm text-neutral-600"><strong>Company:</strong> {{ $testimonial->company }}</p>
            @endif
        </div>
        <div class="px-6 py-4 border-t border-neutral-200 flex flex-wrap gap-3">
            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn-primary px-4 py-2 text-sm">Edit</a>
            @if ($testimonial->status === 'pending')
                <form method="POST" action="{{ route('admin.testimonials.approve', $testimonial) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-secondary px-4 py-2 text-sm">Approve</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
