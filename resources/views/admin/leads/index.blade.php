@extends('layouts.admin')

@section('title', 'Leads Management')
@section('header', 'Leads')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-cms.admin-page-header title="Leads Management" description="Manage and track your incoming leads">
            <x-slot name="actions">
                        <a href="{{ route('admin.leads.export', request()->only(['search', 'status', 'inquiry_type', 'priority', 'spam_filter']) ?: ['spam_filter' => 'exclude']) }}"
                    class="inline-flex items-center btn-primary px-4 py-2 text-sm">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export CSV
                </a>
            </x-slot>
        </x-cms.admin-page-header>

        <x-admin.filter-bar :action="route('admin.leads.index')" :clear-url="request()->hasAny(['search', 'status', 'inquiry_type', 'priority', 'spam_filter']) ? route('admin.leads.index', ['spam_filter' => 'exclude']) : false">
            <div class="flex-1 min-w-0">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by name or email..."
                    class="input-base">
            </div>
            <div class="min-w-0">
                <select name="status" class="input-base">
                    <option value="">All Status</option>
                    @foreach ($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-0">
                <select name="inquiry_type" class="input-base">
                    <option value="">All Types</option>
                    @foreach ($inquiryTypes as $key => $label)
                        <option value="{{ $key }}" {{ request('inquiry_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-0">
                <select name="priority" class="input-base">
                    <option value="">All Priorities</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High Intent Only</option>
                </select>
            </div>
            <div class="min-w-0">
                <select name="spam_filter" class="input-base">
                    <option value="exclude" {{ ($spamFilter ?? 'exclude') === 'exclude' ? 'selected' : '' }}>Exclude Spam</option>
                    <option value="" {{ ($spamFilter ?? 'exclude') === '' ? 'selected' : '' }}>Include Spam</option>
                </select>
            </div>
        </x-admin.filter-bar>

        <form method="POST" action="{{ route('admin.leads.bulk') }}" id="leads-bulk-form" class="mb-4 flex flex-wrap items-center gap-3">
            @csrf
            <select name="action" class="input-base text-sm" required>
                <option value="">Bulk action…</option>
                <option value="mark_spam">Mark as spam</option>
                <option value="mark_not_spam">Mark not spam</option>
                <option value="status">Change status</option>
            </select>
            <select name="status" class="input-base text-sm">
                <option value="">Status…</option>
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary px-4 py-2 text-sm">Apply to selected</button>
        </form>

        <!-- Leads Table (legacy filters removed) -->
        <div class="card-base overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th scope="col" class="px-4 py-3"><span class="sr-only">Select</span></th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Contact</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Inquiry</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Source</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Value</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date
                            </th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @forelse($leads as $lead)
                            <tr class="hover:bg-neutral-50 {{ $lead->is_spam ? 'bg-red-50' : '' }}">
                                <td class="px-4 py-4">
                                    <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}" form="leads-bulk-form" class="rounded border-neutral-300">
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-neutral-900">{{ $lead->name }}</div>
                                        <div class="text-sm text-neutral-500">{{ $lead->email }}</div>
                                        @if ($lead->phone)
                                            <div class="text-sm text-neutral-500">{{ $lead->phone }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($lead->inquiry_type) }}
                                        </span>
                                        @if ($lead->is_high_intent)
                                            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">High intent</span>
                                        @endif
                                        <div class="text-sm text-neutral-500 mt-1">{{ Str::limit($lead->message, 50) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-500">
                                    @if ($lead->sourceContent)
                                        <a href="{{ $lead->sourceContent->url }}" target="_blank" rel="noopener noreferrer"
                                            class="text-primary-600 hover:text-primary-700">
                                            {{ Str::limit($lead->sourceContent->title, 30) }}
                                        </a>
                                    @else
                                        Direct
                                    @endif
                                    @if ($lead->utm_source)
                                        <div class="text-xs text-neutral-400">UTM: {{ $lead->utm_source }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if ($lead->status === 'new') bg-yellow-100 text-yellow-800
                                    @elseif($lead->status === 'contacted') bg-blue-100 text-blue-800
                                    @elseif($lead->status === 'qualified') bg-purple-100 text-purple-800
                                    @elseif($lead->status === 'converted') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($lead->status) }}
                                    </span>
                                    @if ($lead->is_spam)
                                        <div class="text-xs text-red-600 mt-1">SPAM</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($lead->conversion_value)
                                        <span class="font-medium text-emerald-600">${{ number_format($lead->conversion_value, 0) }}</span>
                                    @else
                                        <span class="text-neutral-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                    {{ $lead->created_at->format('M d, Y') }}
                                    <div class="text-xs text-neutral-400">{{ $lead->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.leads.show', $lead) }}"
                                            class="text-primary-600 hover:text-primary-700">View</a>
                                        <a href="{{ route('admin.leads.edit', $lead) }}"
                                            class="text-primary-600 hover:text-primary-700">Edit</a>
                                        @if ($lead->is_spam)
                                            <form method="POST" action="{{ route('admin.leads.not-spam', $lead) }}"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900">Not
                                                    Spam</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.leads.spam', $lead) }}"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Mark
                                                    Spam</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-neutral-900">No leads found</h3>
                                    <p class="mt-1 text-sm text-neutral-500">Try adjusting your filters or check back later for new inquiries.</p>
                                    @if(request()->hasAny(['search', 'status', 'inquiry_type', 'priority', 'spam_filter']))
                                        <a href="{{ route('admin.leads.index') }}" class="mt-4 inline-flex items-center btn-secondary px-4 py-2 text-sm">Clear filters</a>
                                    @else
                                        <a href="{{ url('/contact') }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex items-center btn-secondary px-4 py-2 text-sm">View contact form</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($leads->hasPages())
                <div class="px-6 py-4 border-t border-neutral-200">
                    {{ $leads->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
