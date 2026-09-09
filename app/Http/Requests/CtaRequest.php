<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Cta;

class CtaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'content_id' => ['nullable', 'exists:contents,id'],
            'text' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:50'],
            'action' => ['required', 'string', 'max:500'],
            'priority' => ['nullable', 'integer', 'min:0'],
        ];
    }
}