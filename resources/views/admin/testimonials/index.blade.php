@extends('layouts.admin')
@section('title', 'Testimonials Management')
@section('header', 'Testimonials')
@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="heading-page">Testimonials Management</h1>
                    <p class="mt-2 text-sm text-neutral-600">Manage customer testimonials and reviews</p>
                </div>
                <a href="{{ route('admin.testimonials.create') }}"
                    class="inline-flex items-center btn-primary px-4 py-2 text-sm">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Testimonial
                </a>
            </div>
        </div>

        <div class="card-base mb-6">
            <div class="px-6 py-4">
                <form method="GET" action="{{ route('admin.testimonials.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-0">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by client name..."
                            class="block w-full input-base">
                    </div>
                    <div class="min-w-0">
                        <select name="status"
                            class="block w-full input-base">
                            <option value="">All Status</option>
                            @foreach ($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-0">
                        <select name="featured"
                            class="block w-full input-base">
                            <option value="">All Featured</option>
                            <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured Only
                            </option>
                            <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Non-Featured</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary px-4 py-2 text-sm">Filter</button>
                    @if(request()->hasAny(['search', 'status', 'featured']))
                        <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary px-4 py-2 text-sm">Clear</a>
                    @endif
                </form>
            </div>
        </div>

        @php $pendingIds = $testimonials->where('status', 'pending')->pluck('id'); @endphp
        @if ($pendingIds->isNotEmpty())
            <form method="POST" action="{{ route('admin.testimonials.bulk-approve') }}" class="mb-4">
                @csrf @method('PATCH')
                @foreach ($pendingIds as $id)
                    <input type="hidden" name="testimonial_ids[]" value="{{ $id }}">
                @endforeach
                <button type="submit" class="btn-secondary px-4 py-2 text-sm">Approve all pending on this page ({{ $pendingIds->count() }})</button>
            </form>
        @endif

        <div class="card-base overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Client</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Testimonial</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Rating</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date
                            </th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @forelse($testimonials as $testimonial)
                            <tr class="hover:bg-neutral-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-neutral-900">{{ $testimonial->client_name }}</div>
                                        @if ($testimonial->is_featured)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor"
                                                    viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Featured
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-neutral-900">{{ Str::limit($testimonial->testimonial, 100) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="h-5 w-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-neutral-300' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm text-neutral-500">({{ $testimonial->rating }})</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if ($testimonial->status === 'approved') bg-green-100 text-green-800
                                    @elseif($testimonial->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($testimonial->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                    {{ $testimonial->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.testimonials.show', $testimonial) }}"
                                            class="text-primary-600 hover:text-primary-700">View</a>
                                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                            class="text-primary-600 hover:text-primary-700">Edit</a>

                                        @if ($testimonial->status === 'pending')
                                            <form method="POST"
                                                action="{{ route('admin.testimonials.approve', $testimonial) }}"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="text-green-600 hover:text-green-900">Approve</button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('admin.testimonials.reject', $testimonial) }}"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900">Reject</button>
                                            </form>
                                        @endif

                                        <form method="POST"
                                            action="{{ route('admin.testimonials.toggle-featured', $testimonial) }}"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                                {{ $testimonial->is_featured ? 'Unfeature' : 'Feature' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-neutral-900">No testimonials found</h3>
                                    <p class="mt-1 text-sm text-neutral-500">Get started by adding your first testimonial.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($testimonials->hasPages())
                <div class="px-6 py-4 border-t border-neutral-200">
                    {{ $testimonials->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
