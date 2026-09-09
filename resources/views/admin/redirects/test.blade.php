@extends('layouts.admin')

@section('title', 'Test Redirect')

@section('content')
<div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="heading-page">Test Redirect</h1>
        <p class="mt-2 text-sm text-neutral-600">Enter a path and see how the redirect chain resolves.</p>
    </div>

    <x-cms.ui.card variant="elevated">
        <form method="POST" action="{{ route('admin.redirects.test') }}" class="flex flex-wrap gap-3 items-center">
            @csrf
            <input type="text" name="path" value="{{ old('path', $path ?? '/old') }}"
                   class="w-96 input-base w-96"
                   placeholder="/old-path" />
            <button type="submit"
                    class="px-4 py-2 btn-primary px-4 py-2 text-sm">
                Test
            </button>
            <a href="{{ route('admin.redirects.index') }}" class="btn-secondary px-4 py-2 text-sm">Back</a>
        </form>

        @error('path') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror

        @isset($result)
            <div class="mt-6 border-t pt-6">
                @if (!$result['matched'])
                    <div class="p-4 rounded bg-yellow-50 border border-yellow-200 text-yellow-900">
                        No active redirect matched for <span class="font-mono">{{ $path }}</span>.
                    </div>
                @else
                    @if ($result['loop'])
                        <div class="p-4 rounded bg-red-50 border border-red-200 text-red-900">
                            Loop detected in redirect chain.
                        </div>
                    @endif

                    <div class="mt-4">
                        <div class="text-sm font-semibold text-neutral-700 mb-2">Hops</div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-neutral-200 text-sm">
                                <thead class="bg-neutral-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-neutral-500 uppercase">Old</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-neutral-500 uppercase">New</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-neutral-500 uppercase">Code</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-neutral-200">
                                    @foreach ($result['hops'] as $h)
                                        <tr>
                                            <td class="px-4 py-2 font-mono">{{ $h['old'] }}</td>
                                            <td class="px-4 py-2 font-mono">{{ $h['new'] }}</td>
                                            <td class="px-4 py-2">{{ $h['code'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6 p-4 rounded bg-green-50 border border-green-200 text-green-900">
                        Final destination: <span class="font-mono">{{ $result['final'] }}</span>
                    </div>
                @endif
            </div>
        @endisset
    </x-cms.ui.card>
</div>
@endsection
