@extends('layouts.admin')

@section('title', 'Import Redirects')

@section('content')
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="heading-page">Import Redirects</h1>
        <p class="mt-2 text-sm text-neutral-600">
            Paste CSV lines. Format:
            <span class="font-mono">old_path,new_path,status_code,is_active</span>
            (status_code + is_active optional).
        </p>
    </div>

    <x-cms.ui.card variant="elevated">
        <form method="POST" action="{{ route('admin.redirects.import') }}" class="space-y-4">
            @csrf

            <textarea name="csv" rows="12"
                      class="w-full font-mono text-sm input-base font-mono"
                      placeholder="/old,/new,301,1
/legacy-page,https://external.com/new,301,1
# comment lines are ignored">{{ old('csv') }}</textarea>

            @error('csv') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="btn-primary px-4 py-2 text-sm">
                    Import
                </button>
                <a href="{{ route('admin.redirects.index') }}" class="btn-secondary px-4 py-2 text-sm">Cancel</a>
            </div>
        </form>
    </x-cms.ui.card>
</div>
@endsection
