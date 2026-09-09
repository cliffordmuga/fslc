@extends('layouts.admin')
@section('title', 'Activity Log')
@section('header', 'Activity')
@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <x-cms.admin-page-header title="Activity Log" description="View recent system activity" />

    <x-admin.filter-bar :action="route('admin.activity.index')" :clear-url="request()->hasAny(['search', 'causer_id', 'subject_type']) ? route('admin.activity.index') : false">
        <div class="flex-1 min-w-0">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description…" class="input-base">
        </div>
        <div class="min-w-0">
            <select name="causer_id" class="input-base">
                <option value="">All users</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ (string) request('causer_id') === (string) $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="min-w-0">
            <select name="subject_type" class="input-base">
                <option value="">All subjects</option>
                @foreach ($subjectTypes as $type)
                    <option value="{{ $type }}" {{ request('subject_type') === $type ? 'selected' : '' }}>{{ class_basename($type) }}</option>
                @endforeach
            </select>
        </div>
    </x-admin.filter-bar>

    <div class="card-base overflow-hidden">
        <div class="p-6">
            <div class="space-y-4">
                @forelse($activities as $activity)
                    <div class="border-l-4 border-primary-200 pl-4 py-2">
                        <h4 class="font-medium text-neutral-900">{{ $activity->description }}</h4>
                        <p class="text-sm text-neutral-600">By: {{ $activity->causer?->name ?? 'System' }}
                            @if ($activity->subject_type)
                                · {{ class_basename($activity->subject_type) }}
                            @endif
                        </p>
                        <p class="text-xs text-neutral-500">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-neutral-500 py-8 text-center">No activities recorded</p>
                @endforelse
            </div>
            @if ($activities->hasPages())
                <div class="mt-6">{{ $activities->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
