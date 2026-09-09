@extends('layouts.admin')
@section('title', 'CTAs')
@section('header', 'Call-to-Actions')
@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="heading-page">CTA Management</h1>
                <p class="mt-2 text-sm text-neutral-600">Manage call-to-action buttons across pages</p>
            </div>
            <a href="{{ route('admin.ctas.create') }}" class="btn-primary px-4 py-2 text-sm">Add CTA</a>
        </div>

        <div class="card-base overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Text</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Content</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Clicks</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200">
                        @forelse ($ctas as $cta)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-neutral-900">{{ $cta->text }}</td>
                                <td class="px-6 py-4 text-sm text-neutral-600">{{ ucfirst($cta->type) }}</td>
                                <td class="px-6 py-4 text-sm text-neutral-600">{{ $cta->content?->title ?? 'Global' }}</td>
                                <td class="px-6 py-4 text-sm text-neutral-600">{{ number_format($cta->clicks) }}</td>
                                <td class="px-6 py-4 text-right text-sm space-x-2">
                                    <a href="{{ route('admin.ctas.show', $cta) }}" class="text-neutral-600 hover:text-neutral-800">View</a>
                                    <a href="{{ route('admin.ctas.edit', $cta) }}" class="text-primary-600 hover:text-primary-800">Edit</a>
                                    <form method="POST" action="{{ route('admin.ctas.destroy', $cta) }}" class="inline" onsubmit="return confirm('Delete this CTA?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-neutral-500">No CTAs yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($ctas->hasPages())
                <div class="px-6 py-4 border-t border-neutral-200">{{ $ctas->links() }}</div>
            @endif
        </div>
    </div>
@endsection
