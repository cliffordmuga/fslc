@php($selectedTagIds = collect(old('tags', $selectedTagIds ?? []))->map(fn ($id) => (int) $id)->all())

<div>
    <label class="block text-sm font-medium text-neutral-700">Tags</label>
    <p class="mt-1 text-sm text-neutral-500">Power the insights hub, tag/content hubs, and related-content blocks.</p>

    @if ($allTags->isNotEmpty())
        <div class="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-3">
            @foreach ($allTags as $tag)
                <label class="flex items-center gap-2 text-sm text-neutral-700">
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                        @checked(in_array($tag->id, $selectedTagIds, true))
                        class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                    <span>{{ $tag->name }}</span>
                </label>
            @endforeach
        </div>
    @endif

    <input type="text" name="new_tags" value="{{ old('new_tags') }}"
        class="mt-3 block w-full input-base"
        placeholder="Add new tags, comma-separated">
    <p class="mt-1 text-sm text-neutral-500">New tags are created on save.</p>

    @error('new_tags')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @error('tags.*')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
