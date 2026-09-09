<?php

// app/Http/Requests/SettingRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin(); // FIX: Was returning false
    }

    public function rules(): array
    {
        $settingId = $this->route('setting') ? $this->route('setting')->id : null;

        return [
            'key' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('settings')->ignore($settingId)
            ],
            'value' => ['required'],
            'type' => ['required', Rule::in(['text', 'textarea', 'boolean', 'integer', 'float', 'json', 'array'])],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'key.regex' => 'The key may only contain lowercase letters, numbers, and underscores.',
            'key.unique' => 'This setting key is already taken.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Sanitize key
        if ($this->filled('key')) {
            $this->merge([
                'key' => strtolower(str_replace([' ', '-'], '_', $this->key))
            ]);
        }
    }
}
