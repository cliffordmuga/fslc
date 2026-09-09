@extends('layouts.admin')
@section('title', 'Analytics')
@section('header', 'Analytics')
@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="heading-page">Analytics Dashboard</h1>
                    <p class="mt-2 text-sm text-neutral-600">View page views, conversions, and performance metrics</p>
                </div>
            </div>
        </div>

        <x-admin.analytics-nav />

        <div class="mb-6 card-base p-6">
            <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex flex-wrap items-end gap-4">
                <div class="min-w-0 sm:w-40">
                    <label for="date_range" class="block text-sm font-medium text-neutral-700 mb-1">Preset</label>
                    <select name="date_range" id="date_range" class="block w-full input-base">
                        <option value="7" {{ request('date_range') == 7 ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30" {{ (request('date_range') ?: 30) == 30 ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="90" {{ request('date_range') == 90 ? 'selected' : '' }}>Last 90 Days</option>
                        <option value="365" {{ request('date_range') == 365 ? 'selected' : '' }}>Last Year</option>
                    </select>
                </div>
                <div class="min-w-0">
                    <label for="start_date" class="block text-sm font-medium text-neutral-700 mb-1">Start</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="input-base">
                </div>
                <div class="min-w-0">
                    <label for="end_date" class="block text-sm font-medium text-neutral-700 mb-1">End</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="input-base">
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center btn-primary px-4 py-2 text-sm">Apply Filter</button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="card-base overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-neutral-500 truncate">Total Views</dt>
                                <dd class="text-3xl font-bold text-neutral-900">{{ $totalViews }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-base overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-neutral-500 truncate">Total Leads</dt>
                                <dd class="text-3xl font-bold text-neutral-900">{{ $totalLeads }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-base overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-neutral-500 truncate">Converted</dt>
                                <dd class="text-3xl font-bold text-neutral-900">{{ $convertedLeads ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="card-base overflow-hidden">
                <div class="p-6">
                    <dl>
                        <dt class="text-sm font-medium text-neutral-500 truncate">Total Revenue (period)</dt>
                        <dd class="text-2xl font-bold text-emerald-600">
                            {{ isset($totalRevenue) && $totalRevenue > 0 ? '$' . number_format($totalRevenue, 0) : '—' }}
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="card-base overflow-hidden">
                <div class="p-6">
                    <dl>
                        <dt class="text-sm font-medium text-neutral-500 truncate">Avg Conversion Rate</dt>
                        <dd class="text-2xl font-bold text-neutral-900">{{ number_format($avgConversionRate ?? 0, 1) }}%</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card-base">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Page Performance</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200">
                            <thead class="bg-neutral-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                        Page</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                        Views</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                        Leads</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                        Conversion Rate</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-neutral-200">
                                @forelse($pageAnalytics as $analytics)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                            {{ $analytics->content->title ?? 'Unknown' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                            {{ $analytics->views }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                            {{ $analytics->leads_generated }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                            {{ number_format($analytics->conversion_rate, 1) }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-neutral-500">No data
                                            available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card-base">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">CTA Performance</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($ctaAnalytics as $cta)
                            <div class="flex items-center justify-between">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-neutral-900 truncate">{{ $cta->text }}</p>
                                    <p class="text-sm text-neutral-500">Type: {{ ucfirst($cta->type) }}</p>
                                </div>
                                <div class="ml-2 flex-shrink-0">
                                    <span class="text-sm text-green-600">{{ $cta->conversions }} conversions</span>
                                    <p class="text-sm text-neutral-500">
                                        ({{ number_format(($cta->conversions / max($cta->clicks, 1)) * 100, 1) }}% of
                                        clicks)</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-500">No CTA data available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Leads by inquiry type (pillar intent) --}}
        <div class="card-base mt-6">
            <div class="px-6 py-4 border-b border-neutral-200">
                <h3 class="text-lg font-medium text-neutral-900">Leads by Inquiry Type</h3>
                <p class="text-sm text-neutral-500">HMIS demo, software quote, campaign strategy, and other lead intents</p>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Inquiry Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Leads</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @forelse ($leadsByInquiryType ?? [] as $row)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-neutral-900">{{ str_replace('-', ' ', ucwords($row->inquiry_type, '-')) }}</td>
                                    <td class="px-6 py-4 text-sm text-neutral-500">{{ $row->lead_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-sm text-neutral-500">No lead data for this period</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Content That Drove Revenue (Converted Leads by Source) --}}
        @if (($convertedBySource ?? collect())->isNotEmpty())
            <div class="card-base mt-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Content That Drove Revenue</h3>
                    <p class="text-sm text-neutral-500">Which pages produced converted (paying) leads</p>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200">
                            <thead class="bg-neutral-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Page</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Converted</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Revenue</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-neutral-200">
                                @foreach ($convertedBySource as $row)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-neutral-900">{{ $row->content_title ?? 'Unknown' }}</td>
                                        <td class="px-6 py-4 text-sm text-neutral-600">{{ $row->converted_count }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-emerald-600">${{ number_format($row->total_value ?? 0, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Lead Funnel Events --}}
        @if (($leadEventCounts ?? collect())->isNotEmpty())
            <div class="card-base mt-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Lead Funnel Events</h3>
                    <p class="text-sm text-neutral-500">Form steps, CTA clicks, and journey events</p>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-4">
                        @foreach ($leadEventCounts as $row)
                            <div class="bg-neutral-50 rounded-lg px-4 py-3">
                                <span class="text-sm font-medium text-neutral-900">{{ $row->event }}</span>
                                <span class="ml-2 text-sm text-neutral-600">{{ number_format($row->event_count) }}×</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
