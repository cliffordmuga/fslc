<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Testimonial;

class TestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'client_name' => ['required', 'string', 'max:255'],
            'testimonial' => ['required', 'string', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'is_featured' => ['boolean'],
            'status' => ['required', Rule::in(array_keys(Testimonial::STATUSES))],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->filled('status')) {
            $this->merge(['status' => 'pending']);
        }
    }
}