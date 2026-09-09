<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use InvalidArgumentException;

class SecureUploadService
{
    public function validateDocument(UploadedFile $file): void
    {
        $this->assertValidUpload($file, 'document');

        $config = config('uploads.documents');
        $mime = $this->detectMime($file);
        $extension = strtolower($file->getClientOriginalExtension());

        $this->assertMimeAndExtension($mime, $extension, $config['allowed_mimes'], $config['allowed_extensions']);
        $this->assertMaxSize($file, (int) $config['max_kb']);

        if (str_starts_with($mime, 'image/') && ! @getimagesize($file->getRealPath())) {
            throw new InvalidArgumentException('The uploaded image file is not valid.');
        }
    }

    public function validateImage(UploadedFile $file): void
    {
        $this->assertValidUpload($file, 'image');

        $config = config('uploads.images');
        $mime = $this->detectMime($file);
        $extension = strtolower($file->getClientOriginalExtension());

        $this->assertMimeAndExtension($mime, $extension, $config['allowed_mimes'], $config['allowed_extensions']);
        $this->assertMaxSize($file, (int) $config['max_kb']);

        if (! @getimagesize($file->getRealPath())) {
            throw new InvalidArgumentException('The uploaded file is not a valid image.');
        }
    }

    /**
     * @return array{path: string, original_name: string}
     */
    public function storeLeadAttachment(UploadedFile $file): array
    {
        $this->validateDocument($file);

        $extension = strtolower($file->getClientOriginalExtension());
        $path = $file->storeAs(
            'leads',
            Str::uuid() . '.' . $extension,
            'local'
        );

        return [
            'path' => $path,
            'original_name' => self::sanitizeFilename($file->getClientOriginalName()),
        ];
    }

    public static function sanitizeFilename(string $name): string
    {
        $name = basename(str_replace(['\\', '/'], '', $name));
        $name = preg_replace('/[^\w.\- ]+/u', '', $name) ?? 'attachment';

        return $name !== '' ? $name : 'attachment';
    }

    protected function assertValidUpload(UploadedFile $file, string $label): void
    {
        if (! $file->isValid()) {
            throw new InvalidArgumentException("The {$label} upload failed. Please try again.");
        }

        $realPath = $file->getRealPath();
        if (! $realPath || ! is_readable($realPath)) {
            throw new InvalidArgumentException("The {$label} upload could not be read.");
        }
    }

    protected function detectMime(UploadedFile $file): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        if (! is_string($mime) || $mime === '') {
            throw new InvalidArgumentException('Unable to determine the uploaded file type.');
        }

        return $mime;
    }

    protected function assertMimeAndExtension(
        string $mime,
        string $extension,
        array $allowedMimes,
        array $allowedExtensions,
    ): void {
        if (! in_array($mime, $allowedMimes, true)) {
            throw new InvalidArgumentException('This file type is not allowed.');
        }

        if (! in_array($extension, $allowedExtensions, true)) {
            throw new InvalidArgumentException('This file extension is not allowed.');
        }
    }

    protected function assertMaxSize(UploadedFile $file, int $maxKb): void
    {
        if ($file->getSize() > ($maxKb * 1024)) {
            throw new InvalidArgumentException('The file exceeds the maximum allowed size.');
        }
    }
}
