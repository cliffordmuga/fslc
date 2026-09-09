@extends('layouts.admin')
@section('title', 'Content Analytics')
@section('header', 'Content Analytics')
@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="heading-page">Content Analytics</h1>
                <p class="mt-2 text-sm text-neutral-600">{{ $startDate->format('M j, Y') }} — {{ $endDate->format('M j, Y') }}</p>
            </div>
            <a href="{{ route('admin.analytics.index') }}" class="btn-secondary px-4 py-2 text-sm">Back to Dashboard</a>
        </div>

        <x-admin.analytics-nav />

        <div class="mb-6 card-base p-6">
            <form method="GET" action="{{ route('admin.analytics.content') }}" class="flex flex-wrap items-end gap-4">
                <div class="min-w-0">
                    <label for="start_date" class="block text-sm font-medium text-neutral-700 mb-1">Start</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="input-base">
                </div>
                <div class="min-w-0">
                    <label for="end_date" class="block text-sm font-medium text-neutral-700 mb-1">End</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="input-base">
                </div>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">Apply</button>
            </form>
        </div>

        <div class="card-base overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Content</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Views</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Leads</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Conv. Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200">
                        @forelse ($contentAnalytics as $row)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-neutral-900">
                                    @if ($row->content)
                                        <a href="{{ $row->content->url }}" target="_blank" class="text-primary-600 hover:underline">{{ $row->content->title }}</a>
                                    @else
                                        Unknown
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-600">{{ $row->content?->type ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-neutral-600">{{ number_format($row->total_views) }}</td>
                                <td class="px-6 py-4 text-sm text-neutral-600">{{ number_format($row->total_leads) }}</td>
                                <td class="px-6 py-4 text-sm text-neutral-600">{{ number_format($row->avg_conversion_rate ?? 0, 1) }}%</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-neutral-500">No analytics data for this period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
