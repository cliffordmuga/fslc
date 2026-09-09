<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Content;

class ContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $contentId = $this->route('content') ? $this->route('content')->id : null;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('contents')->ignore($contentId)
            ],
            'type' => ['required', Rule::in(array_keys(Content::TYPES))],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'status' => ['required', Rule::in(array_keys(Content::STATUSES))],
            'published_at' => ['nullable', 'date', 'after_or_equal:now'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'images.*' => ['nullable', 'file', 'image', 'max:2048'],
            'featured_image' => ['nullable', 'file', 'image', 'max:2048'],
            'gallery_images.*' => ['nullable', 'file', 'image', 'max:2048'],
            'image_alt' => ['nullable', 'array'],
            'image_alt.*' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],

            // SEO fields
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'og_title' => ['nullable', 'string', 'max:95'],
            'og_description' => ['nullable', 'string', 'max:200'],
            'canonical_url' => ['nullable', 'url'],
            'noindex' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'The slug may only contain lowercase letters, numbers, and dashes.',
            'slug.unique' => 'This slug is already taken.',
            'meta_title.max' => 'Meta title should not exceed 60 characters.',
            'meta_description.max' => 'Meta description should not exceed 160 characters.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('title') && empty($this->slug)) {
            $this->merge(['slug' => \Illuminate\Support\Str::slug($this->title)]);
        }

        if (empty($this->excerpt) && $this->filled('content')) {
            $this->merge(['excerpt' => generate_excerpt($this->content, 155)]);
        }

        if ($this->status === 'published' && !$this->filled('published_at')) {
            $this->merge(['published_at' => now()]);
        }
    }
}
