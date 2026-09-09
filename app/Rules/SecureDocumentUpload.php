<?php

namespace App\Rules;

use App\Services\SecureUploadService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

class SecureDocumentUpload implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            return;
        }

        try {
            app(SecureUploadService::class)->validateDocument($value);
        } catch (InvalidArgumentException $e) {
            $fail($e->getMessage());
        }
    }
}
