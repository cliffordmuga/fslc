{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Date Range Filter --}}
        <div class="mb-6 card-base p-6">
            <form method="GET" action="{{ route('admin.dashboard') }}" id="dashboard-date-form" class="flex flex-wrap items-end gap-4">
                <div class="min-w-0 sm:w-48">
                    <label for="date_range" class="block text-sm font-medium text-neutral-700 mb-1">Date Range</label>
                    <select name="date_range" id="date_range" class="block w-full input-base" onchange="this.form.submit()">
                        <option value="7" {{ (request('date_range') ?: 30) == 7 ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30" {{ (request('date_range') ?: 30) == 30 ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="90" {{ (request('date_range') ?: 30) == 90 ? 'selected' : '' }}>Last 90 Days</option>
                        <option value="365" {{ (request('date_range') ?: 30) == 365 ? 'selected' : '' }}>Last Year</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-primary px-4 py-2 text-sm">Apply</button>
                </div>
            </form>
        </div>

        {{-- Key Metrics Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            {{-- Total Content --}}
            <div class="card-base overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-neutral-500 truncate">Total Content</dt>
                                <dd class="text-3xl font-bold text-neutral-900">{{ $stats['total_content'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Published Content --}}
            <div class="card-base overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-neutral-500 truncate">Published</dt>
                                <dd class="text-3xl font-bold text-neutral-900">{{ $stats['published_content'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Leads --}}
            <div class="card-base overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-neutral-500 truncate">Total Leads</dt>
                                <dd class="text-3xl font-bold text-neutral-900">{{ $stats['total_leads'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- New Leads --}}
            <div class="card-base overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-neutral-500 truncate">New Leads</dt>
                                <dd class="text-3xl font-bold text-neutral-900">{{ $stats['new_leads'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Converted Leads --}}
            <div class="card-base overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-emerald-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-neutral-500 truncate">Converted</dt>
                                <dd class="text-3xl font-bold text-neutral-900">{{ $stats['converted_leads'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Revenue (Conversion Value) --}}
            <div class="card-base overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-neutral-500 truncate">Revenue</dt>
                                <dd class="text-3xl font-bold text-neutral-900">
                                    {{ isset($stats['total_conversion_value']) && $stats['total_conversion_value'] > 0 ? '$' . number_format($stats['total_conversion_value'], 0) : '—' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actionable Leads (High Priority + Follow-Up Needed) --}}
        @if (($highPriorityLeads ?? collect())->isNotEmpty() || ($leadsNeedingFollowUp ?? collect())->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                @if (($highPriorityLeads ?? collect())->isNotEmpty())
                    <div class="card-base border-l-4 border-l-emerald-500">
                        <div class="px-6 py-4 border-b border-neutral-200">
                            <h3 class="text-lg font-medium text-neutral-900">High Priority Leads</h3>
                            <p class="text-sm text-neutral-500">Service/portfolio inquiries with known source — respond first</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach ($highPriorityLeads as $lead)
                                    <div class="flex items-center justify-between">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-neutral-900 truncate">{{ $lead->name }}</p>
                                            <p class="text-sm text-neutral-500">{{ $lead->email }} • {{ $lead->sourceContent?->title ?? 'Direct' }}</p>
                                        </div>
                                        <a href="{{ route('admin.leads.show', $lead) }}"
                                            class="text-primary-600 hover:text-primary-700 text-sm font-medium">Respond →</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                @if (($leadsNeedingFollowUp ?? collect())->isNotEmpty())
                    <div class="card-base border-l-4 border-l-amber-500">
                        <div class="px-6 py-4 border-b border-neutral-200">
                            <h3 class="text-lg font-medium text-neutral-900">Needs Follow-Up</h3>
                            <p class="text-sm text-neutral-500">New leads untouched for {{ setting('lead_follow_up_days', 3) }}+ days</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach ($leadsNeedingFollowUp as $lead)
                                    <div class="flex items-center justify-between">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-neutral-900 truncate">{{ $lead->name }}</p>
                                            <p class="text-sm text-neutral-500">{{ $lead->created_at->diffForHumans() }}</p>
                                        </div>
                                        <a href="{{ route('admin.leads.show', $lead) }}"
                                            class="text-amber-600 hover:text-amber-700 text-sm font-medium">Follow up →</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Recent Activity Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Recent Leads --}}
            <div class="card-base">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Recent Leads</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recent_leads as $lead)
                            <div class="flex items-center justify-between {{ $lead->is_high_intent ? 'bg-emerald-50/50 -mx-2 px-2 py-1 rounded' : '' }}">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-neutral-900 truncate">
                                        {{ $lead->name }}
                                        @if ($lead->is_high_intent)
                                            <span class="ml-1 text-xs text-emerald-600">High intent</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-neutral-500">{{ $lead->email }} • {{ ucfirst($lead->status) }}
                                    </p>
                                </div>
                                <div class="ml-2 flex-shrink-0">
                                    <a href="{{ route('admin.leads.show', $lead) }}"
                                        class="text-primary-600 hover:text-primary-700 text-sm">View</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-500">No recent leads.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Recent Content --}}
            <div class="card-base">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Recent Content</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recent_content as $content)
                            <div class="flex items-center justify-between">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-neutral-900 truncate">{{ $content->title }}</p>
                                    <p class="text-sm text-neutral-500">{{ ucfirst($content->type) }} •
                                        {{ ucfirst($content->status) }}</p>
                                </div>
                                <div class="ml-2 flex-shrink-0">
                                    <a href="{{ route('admin.content.edit', $content) }}"
                                        class="text-primary-600 hover:text-primary-700 text-sm">Edit</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-500">No recent content.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Performance Insights Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Performing Content --}}
            <div class="card-base">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Top Performing Content</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($topContent ?? [] as $row)
                            <div class="flex items-center justify-between">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-neutral-900 truncate">
                                        {{ $row->content_title ?? 'Unknown Content' }}
                                    </p>
                                    <p class="text-sm text-neutral-500">
                                        {{ number_format($row->total_views ?? 0) }} views •
                                        {{ $row->total_leads ?? 0 }} leads
                                    </p>
                                </div>
                                <div class="ml-2 flex-shrink-0">
                                    @if (($row->total_views ?? 0) > 0)
                                        <span class="text-sm text-green-600">
                                            {{ number_format((($row->total_leads ?? 0) / $row->total_views) * 100, 1) }}%
                                        </span>
                                    @else
                                        <span class="text-sm text-neutral-400">0%</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-500">No content data available for this period.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Top Lead Sources --}}
            <div class="card-base">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Top Lead Sources</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($leadSources ?? [] as $source)
                            <div class="flex items-center justify-between">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-neutral-900 truncate">
                                        {{ $source->sourceContent ? $source->sourceContent->title : 'Direct/Other' }}
                                    </p>
                                    <p class="text-sm text-neutral-500">
                                        {{ $source->sourceContent ? ucfirst($source->sourceContent->type) : 'Direct' }}
                                    </p>
                                </div>
                                <div class="ml-2 flex-shrink-0">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $source->lead_count }} leads
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-500">No lead source data available for this period.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Marketing Attribution (Conditional) --}}
        @if (($utmSources ?? collect())->isNotEmpty())
            <div class="card-base mt-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Marketing Attribution (UTM Sources)</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($utmSources as $utm)
                            <div class="bg-neutral-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-neutral-900">
                                            {{ $utm->utm_source ?: 'Direct' }}
                                        </p>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                        {{ $utm->lead_count }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Inquiry type breakdown --}}
        @if (($inquiryTypes ?? collect())->isNotEmpty())
            <div class="card-base mt-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Leads by Inquiry Type</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach ($inquiryTypes as $row)
                            <div class="bg-neutral-50 rounded-lg p-4">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-medium text-neutral-900 truncate">
                                        {{ str_replace('-', ' ', ucwords($row->inquiry_type, '-')) }}
                                    </p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 flex-shrink-0">
                                        {{ $row->lead_count }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
