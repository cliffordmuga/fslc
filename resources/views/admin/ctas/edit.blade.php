@extends('layouts.admin')
@section('title', 'Edit CTA')
@section('header', 'Edit CTA')
@section('content')
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.ctas.update', $cta) }}" class="card-base p-6 space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Text</label>
                <input type="text" name="text" value="{{ old('text', $cta->text) }}" required class="input-base w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Type</label>
                <select name="type" required class="input-base w-full">
                    @foreach (['primary', 'secondary', 'outline'] as $type)
                        <option value="{{ $type }}" @selected(old('type', $cta->type) === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Action URL</label>
                <input type="text" name="action" value="{{ old('action', $cta->action) }}" required class="input-base w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Linked Content (optional)</label>
                <select name="content_id" class="input-base w-full">
                    <option value="">Global / none</option>
                    @foreach ($contents as $id => $title)
                        <option value="{{ $id }}" @selected((string) old('content_id', $cta->content_id) === (string) $id)>{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Priority</label>
                <input type="number" name="priority" value="{{ old('priority', $cta->priority ?? 5) }}" min="0" class="input-base w-full">
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary px-4 py-2 text-sm">Update</button>
                <a href="{{ route('admin.ctas.index') }}" class="btn-secondary px-4 py-2 text-sm">Cancel</a>
            </div>
        </form>
    </div>
@endsection
