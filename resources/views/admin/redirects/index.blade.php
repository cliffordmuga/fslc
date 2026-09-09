@extends('layouts.admin')
@section('title', 'Redirects')
@section('header', 'Redirects')
@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="heading-page">Redirects</h1>
                <p class="mt-2 text-sm text-neutral-600">Manage 301/302 redirects for SEO and slug changes.</p>
            </div>
            <a href="{{ route('admin.redirects.create') }}"
                class="inline-flex items-center px-4 py-2 btn-primary px-4 py-2 text-sm">
                Add Redirect
            </a>
        </div>

        @if (session('import_errors') && is_array(session('import_errors')) && count(session('import_errors')) > 0)
            <div class="mb-6 p-4 rounded bg-yellow-50 text-yellow-900 border border-yellow-200">
                <div class="font-semibold mb-2">Import notes:</div>
                <ul class="list-disc ml-6 space-y-1 text-sm">
                    @foreach (session('import_errors') as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <x-cms.ui.card variant="elevated">
            <x-slot name="header">
                <form method="GET" action="{{ route('admin.redirects.index') }}"
                    class="flex flex-wrap gap-3 items-center">
                    <input type="text" name="q" value="{{ $q }}" placeholder="Search old/new path..."
                        class="w-72 input-base" />
                    <select name="active"
                        class="input-base">
                        <option value="">All</option>
                        <option value="1" {{ $active === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $active === '0' ? 'selected' : '' }}>Disabled</option>
                    </select>
                    <button type="submit"
                        class="px-4 py-2 btn-primary px-4 py-2 text-sm">
                        Filter
                    </button>
                </form>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.redirects.testForm') }}"
                        class="inline-flex items-center px-4 py-2 btn-primary px-4 py-2 text-sm">
                        Test
                    </a>
                    <a href="{{ route('admin.redirects.importForm') }}"
                        class="inline-flex items-center px-4 py-2 btn-primary px-4 py-2 text-sm">
                        Import
                    </a>
                    <a href="{{ route('admin.redirects.create') }}"
                        class="inline-flex items-center px-4 py-2 btn-primary px-4 py-2 text-sm">
                        Add Redirect
                    </a>
                </div>

            </x-slot>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-sm">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Old</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">New</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Hits</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @forelse ($redirects as $r)
                            <tr class="{{ $r->is_active ? '' : 'bg-neutral-50' }}">
                                <td class="px-6 py-4 font-mono text-neutral-900">{{ $r->old_path }}</td>
                                <td class="px-6 py-4 font-mono text-neutral-700">{{ $r->new_path }}</td>
                                <td class="px-6 py-4">{{ $r->status_code }}</td>
                                <td class="px-6 py-4">
                                    @if ($r->is_active)
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded bg-neutral-200 text-neutral-800">Disabled</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $r->hits }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.redirects.edit', $r) }}"
                                        class="text-primary-600 hover:text-primary-700 font-semibold">Edit</a>

                                    <form method="POST" action="{{ route('admin.redirects.toggle', $r) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-neutral-700 hover:text-neutral-900 font-semibold" type="submit">
                                            {{ $r->is_active ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.redirects.destroy', $r) }}" class="inline"
                                        onsubmit="return confirm('Delete this redirect?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800 font-semibold"
                                            type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-neutral-500">No redirects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $redirects->links() }}
            </div>
        </x-cms.ui.card>
    </div>
@endsection
