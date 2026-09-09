<?php

namespace App\Http\Requests;

use App\Rules\SecureDocumentUpload;
use App\Models\Lead;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->routeIs('admin.*')) {
            return $this->user()?->isAdmin() ?? false;
        }

        return true;
    }

    public function rules(): array
    {
        $inquiryType = normalize_inquiry_type($this->input('inquiry_type'));
        $requireService = contact_form_ux($inquiryType)['require_service'] ?? false;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:2000'],
            'inquiry_type' => ['nullable', Rule::in(array_keys(Lead::INQUIRY_TYPES))],
            'service_content_id' => [
                Rule::requiredIf($requireService || $inquiryType === 'service'),
                'nullable',
                Rule::exists('contents', 'id')->where(fn ($q) => $q->where('type', 'services')),
            ],
            'source_content_id' => ['nullable', 'exists:contents,id'],
            'utm_source' => ['nullable', 'string', 'max:120'],
            'utm_medium' => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:120'],
            'referrer' => ['nullable', 'string', 'max:500'],
        ];

        if (! $this->routeIs('admin.*')) {
            $rules['file'] = ['nullable', 'file', 'max:' . (int) config('uploads.documents.max_kb', 5120), new SecureDocumentUpload()];
        }

        if ($this->user() && $this->user()->isAdmin()) {
            $rules = array_merge($rules, [
                'status' => ['sometimes', Rule::in(array_keys(Lead::STATUSES))],
                'is_spam' => ['boolean'],
                'conversion_value' => ['nullable', 'numeric', 'min:0'],
            ]);
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $inquiryType = normalize_inquiry_type($this->input('inquiry_type'));
        $this->merge(['inquiry_type' => $inquiryType]);

        if ($inquiryType === 'hmis-checklist' && ! $this->filled('message')) {
            $defaultMessage = contact_form_ux('hmis-checklist')['default_message']
                ?? 'HMIS procurement checklist request';
            $this->merge(['message' => $defaultMessage]);
        }

        if (! $this->isMethod('PUT') && ! $this->filled('status')) {
            $this->merge(['status' => 'new']);
        }
    }
}
