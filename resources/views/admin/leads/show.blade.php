@extends('layouts.admin')
@section('title', 'Lead Details')
@section('content')
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="heading-page">Lead: {{ $lead->name }}</h1>
                    <p class="mt-2 text-sm text-neutral-600">Details for inquiry from {{ $lead->created_at->format('M d, Y') }}</p>
                </div>
                <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center btn-secondary px-4 py-2 text-sm">
                    Back to Leads
                </a>
            </div>
        </div>

        <div class="card-base">
            <div class="px-6 py-4 border-b border-neutral-200">
                <h3 class="text-lg font-medium text-neutral-900">Lead Information</h3>
            </div>
            <div class="px-6 py-4 space-y-4 lg:space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Name</label>
                        <p class="mt-1 text-sm text-neutral-900">{{ $lead->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Email</label>
                        <p class="mt-1 text-sm text-neutral-900">{{ $lead->email }}</p>
                    </div>
                    @if ($lead->phone)
                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Phone</label>
                            <p class="mt-1 text-sm text-neutral-900">{{ $lead->phone }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Inquiry Type</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                            {{ ucfirst($lead->inquiry_type ?? 'general') }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium {{ $lead->status == 'new' ? 'bg-yellow-100 text-yellow-800' : ($lead->status == 'converted' ? 'bg-green-100 text-green-800' : 'bg-neutral-100 text-neutral-800') }} mt-1">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </div>
                    @if ($lead->sourceContent)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-neutral-700">Source Page</label>
                            <p class="mt-1 text-sm text-neutral-900">{{ $lead->sourceContent->title }} ({{ ucfirst($lead->sourceContent->type) }})</p>
                        </div>
                    @endif
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-neutral-700">UTM Attribution</label>
                        <dl class="mt-1 grid grid-cols-1 sm:grid-cols-3 gap-2 text-sm text-neutral-900">
                            <div><dt class="text-neutral-500 text-xs">Source</dt><dd>{{ $lead->utm_source ?: '—' }}</dd></div>
                            <div><dt class="text-neutral-500 text-xs">Medium</dt><dd>{{ $lead->utm_medium ?: '—' }}</dd></div>
                            <div><dt class="text-neutral-500 text-xs">Campaign</dt><dd>{{ $lead->utm_campaign ?: '—' }}</dd></div>
                        </dl>
                        @if ($lead->referrer)
                            <p class="mt-2 text-xs text-neutral-500 truncate" title="{{ $lead->referrer }}">Referrer: {{ $lead->referrer }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Project Value (Conversion)</label>
                        @if ($lead->conversion_value)
                            <p class="mt-1 text-lg font-semibold text-emerald-600">${{ number_format($lead->conversion_value, 0) }}</p>
                        @else
                            <p class="mt-1 text-sm text-neutral-500">Not set — <a href="{{ route('admin.leads.edit', $lead) }}" class="text-primary-600 hover:underline">Add when converted</a></p>
                        @endif
                    </div>
                    @if ($lead->spam_score)
                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Spam score</label>
                            <p class="mt-1 text-sm text-neutral-900">{{ $lead->spam_score }}</p>
                        </div>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700">Message</label>
                    <p class="mt-1 text-sm text-neutral-900 whitespace-pre-line">{{ $lead->message }}</p>
                </div>
                @if ($lead->attachment_path)
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Attachment</label>
                        <p class="mt-1 text-sm text-neutral-900">
                            {{ $lead->attachment_original_name ?: basename($lead->attachment_path) }}
                        </p>
                        <a href="{{ route('admin.leads.attachment', $lead) }}"
                           class="inline-flex items-center gap-2 mt-2 text-sm font-semibold text-primary-600 hover:text-primary-700">
                            Download attachment
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/></svg>
                        </a>
                    </div>
                @endif
                @if ($lead->is_spam)
                    <div class="bg-red-50 border border-red-200 p-4">
                        <p class="text-sm text-red-800 font-semibold">Marked as spam</p>
                        @if (! empty($lead->spam_reasons))
                            <ul class="mt-2 text-sm text-red-700 list-disc pl-5">
                                @foreach ((array) $lead->spam_reasons as $reason)
                                    <li>{{ is_string($reason) ? $reason : json_encode($reason) }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if ($activities->isNotEmpty())
            <div class="mt-6 card-base">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Activity</h3>
                </div>
                <div class="px-6 py-4 divide-y divide-neutral-100">
                    @foreach ($activities as $activity)
                        <div class="py-3 text-sm">
                            <p class="text-neutral-900">{{ $activity->description }}</p>
                            <p class="text-xs text-neutral-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('admin.leads.edit', $lead) }}" class="btn-primary px-4 py-2 text-sm">Update Status</a>
            @if ($lead->is_spam)
                <form method="POST" action="{{ route('admin.leads.not-spam', $lead) }}">@csrf @method('PATCH')<button type="submit" class="btn-secondary px-4 py-2 text-sm">Not spam</button></form>
            @else
                <form method="POST" action="{{ route('admin.leads.spam', $lead) }}">@csrf @method('PATCH')<button type="submit" class="btn-secondary px-4 py-2 text-sm">Mark spam</button></form>
            @endif
        </div>
    </div>
@endsection
