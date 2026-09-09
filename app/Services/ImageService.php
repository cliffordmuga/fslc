<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class ImageService
{
    protected string $disk;
    protected array $config;
    protected array $variants;
    protected string $baseUploadPath = 'uploads';

    public function __construct()
    {
        $this->config = [
            'disk' => config('image.disk', 'public_uploads'),
            'allowed_mimes' => config('uploads.images.allowed_mimes'),
            'allowed_extensions' => config('uploads.images.allowed_extensions'),
            'max_size' => config('image.max_size', 2048),
            'default_quality' => config('image.default_quality', 80),
            'resize_mode' => config('image.resize_mode', 'crop'),
            'bg_color' => config('image.bg_color', '#FFFFFF'),
            'blur_width' => config('image.blur_width', 20),
            'variants' => config('image.variants'),
        ];

        $this->disk = $this->config['disk'];
        $this->variants = $this->config['variants'];

        if (! array_key_exists($this->disk, config('filesystems.disks', []))) {
            throw new Exception("Invalid disk configuration: {$this->disk}");
        }
    }

    /**
     * Create .htaccess file for security and caching
     */
    protected function createHtaccess(string $directory): void
    {
        $htaccessPath = $directory . '/.htaccess';

        if (!file_exists($htaccessPath)) {
            $content = <<<'EOT'
# Prevent directory listing
Options -Indexes

# Block script execution
<FilesMatch "\.(php|php3|php4|php5|phtml|pl|py|cgi|asp|aspx|jsp|shtml|sh)$">
    Require all denied
</FilesMatch>

# Allow image files
<FilesMatch "\.(jpg|jpeg|png|gif|bmp|webp|svg|ico)$">
    Require all granted
</FilesMatch>

# Block sensitive files
<FilesMatch "\.(ini|log|conf|bak|env|sql|sh)$">
    Require all denied
</FilesMatch>

# Disable PHP execution
<IfModule mod_php7.c>
    php_flag engine off
</IfModule>
<IfModule mod_php8.c>
    php_flag engine off
</IfModule>

# Enable image caching (1 year)
<IfModule mod_headers.c>
    Header set Cache-Control "max-age=31536000, public, immutable"
    Header set X-Content-Type-Options "nosniff"
</IfModule>

# Optional: Enable Gzip compression for SVG
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE image/svg+xml
</IfModule>
EOT;

            if (!file_put_contents($htaccessPath, $content)) {
                Log::error('Failed to create .htaccess', [
                    'path' => $htaccessPath,
                    'directory_perms' => substr(sprintf('%o', fileperms($directory)), -4),
                ]);
                throw new Exception("Failed to create .htaccess file at {$htaccessPath}");
            }

            chmod($htaccessPath, 0644);
            Log::info('Created .htaccess file', ['path' => $htaccessPath]);
        }
    }

    /**
     * Ensure directory exists with proper permissions
     */
    protected function ensureDirectory(string $path): void
    {
        $fullPath = Storage::disk($this->disk)->path($path);

        if (!file_exists($fullPath)) {
            if (!mkdir($fullPath, 0755, true)) {
                Log::error('Failed to create directory', [
                    'path' => $fullPath,
                    'parent_perms' => substr(sprintf('%o', fileperms(dirname($fullPath))), -4),
                ]);
                throw new Exception("Failed to create directory: {$fullPath}");
            }

            chmod($fullPath, 0755);
            Log::info('Created directory with permissions 755', ['path' => $fullPath]);
        } elseif (!is_writable($fullPath)) {
            chmod($fullPath, 0755);
            Log::info('Updated directory permissions to 755', ['path' => $fullPath]);
        }
    }

    /**
     * Get public URL for an image with validation and caching
     */
    public function getImageUrl(string $path, string $fallback = 'images/default-post.png'): string
    {
        $path = ltrim($path, '/');

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return filter_var($path, FILTER_VALIDATE_URL) ? $path : upload_asset($fallback);
        }

        if (preg_match('/\.\.\//', $path) || preg_match('/[^a-zA-Z0-9\/._-]/', $path)) {
            Log::warning('Invalid image path', ['path' => $path]);

            return upload_asset($fallback);
        }

        $cacheKey = "image_url_{$this->disk}_" . md5($path);

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($path, $fallback) {
            if (! Storage::disk($this->disk)->exists($path)) {
                Log::warning('Image file not found on disk', ['path' => $path, 'disk' => $this->disk]);

                return upload_asset($fallback);
            }

            return upload_asset($path);
        });
    }

    public function getImageVariants(
        string $modelClass,
        int $modelId,
        string $collection = 'featured'
    ): ?array {
        $cacheKey = 'img_variants:' . md5($modelClass . ':' . $modelId . ':' . $collection);

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($modelClass, $modelId, $collection) {
            $images = Image::where('imageable_type', $modelClass)
                ->where('imageable_id', $modelId)
                ->where('collection', $collection)
                ->get();

            if ($images->isEmpty()) {
                return null;
            }

            $variants = [];
            foreach ($images as $image) {
                $variantConfig = $this->variants[$image->variant] ?? [800, 400];
                $variants[$image->variant] = [
                    'url' => $this->getImageUrl($image->image_url),
                    'alt' => $image->alt_text,
                    'width' => $variantConfig[0] ?? 800,
                    'height' => $variantConfig[1] ?? 400,
                    'blur_placeholder' => $image->blur_placeholder,
                ];
            }

            return $variants;
        });
    }

    /**
     * Upload image and create all variants
     */
    public function uploadImageVariants(
        UploadedFile $file,
        string $folder,
        string $slug,
        ?string $altText,
        string $modelClass,
        int $modelId,
        string $collection = 'default',
        ?array $customVariants = null,
        int $sortOrder = 0,
    ): array {
        try {
            $this->validateFile($file);

            if (!class_exists($modelClass) || !in_array('Illuminate\Database\Eloquent\Model', class_parents($modelClass))) {
                throw new Exception("Invalid model class: {$modelClass}");
            }

            $filenameBase = $this->sanitizeSlug($slug) . '-' . Str::uuid();
            $variants = $customVariants ?? $this->variants;

            // Validate custom variants
            if ($customVariants) {
                foreach ($customVariants as $variant => $variantConfig) {
                    if (!isset($this->variants[$variant]) && !is_array($variantConfig)) {
                        throw new Exception("Invalid variant: {$variant}");
                    }
                }
            }

            $savedImages = [];
            $uploadFolder = "{$this->baseUploadPath}/{$folder}";

            // Setup directories and security
            $this->ensureDirectory($this->baseUploadPath);
            $this->createHtaccess(Storage::disk($this->disk)->path($this->baseUploadPath));
            $this->ensureDirectory($uploadFolder);

            Log::info('Attempting to upload image', [
                'file' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'folder' => $uploadFolder,
                'model' => $modelClass,
                'model_id' => $modelId,
                'collection' => $collection,
            ]);

            $imageable = $modelClass::findOrFail($modelId);

            DB::transaction(function () use (
                $file,
                $uploadFolder,
                $filenameBase,
                $variants,
                $altText,
                $modelClass,
                $modelId,
                $collection,
                $sortOrder,
                &$savedImages,
                $imageable
            ) {
                // Disable events temporarily for performance
                \Illuminate\Database\Eloquent\Model::withoutEvents(function () use (
                    $file,
                    $uploadFolder,
                    $filenameBase,
                    $variants,
                    $altText,
                    $modelClass,
                    $modelId,
                    $collection,
                    $sortOrder,
                    &$savedImages
                ) {
                    $blurPlaceholder = $this->generateBlurDataUri($file->getRealPath());

                    foreach ($variants as $variant => $variantConfig) {
                        [$width, $height, $mode] = is_array($variantConfig)
                            ? $variantConfig
                            : array_merge($variantConfig, [$this->config['resize_mode']]);

                        try {
                            // Try WebP first
                            $resizedImage = $this->resizeAndConvertToWebP(
                                $file->getRealPath(),
                                $width,
                                $height,
                                $this->config['default_quality'],
                                'webp',
                                $mode,
                                $this->config['bg_color']
                            );
                            $extension = 'webp';
                        } catch (Exception $e) {
                            // Narrow fallback: Only catch WebP-specific failures
                            if (str_contains($e->getMessage(), 'webp')) {
                                Log::warning("WebP conversion failed for {$modelClass} ID {$modelId}, variant {$variant}: {$e->getMessage()}");
                                $resizedImage = $this->resizeAndConvertToWebP(
                                    $file->getRealPath(),
                                    $width,
                                    $height,
                                    $this->config['default_quality'],
                                    'jpeg',
                                    $mode,
                                    $this->config['bg_color']
                                );
                                $extension = 'jpg';
                            } else {
                                throw $e;
                            }
                        }

                        $filename = $this->generateFilename($filenameBase, $variant, $extension);
                        $relativePath = trim($uploadFolder, '/') . '/' . $filename;

                        try {
                            // Use Storage facade for consistency
                            if (!Storage::disk($this->disk)->put($relativePath, $resizedImage, 'public')) {
                                Log::error('Failed to store image', [
                                    'path' => $relativePath,
                                    'disk' => $this->disk,
                                ]);
                                throw new Exception("Failed to store image at {$relativePath}");
                            }

                            // Optimize image if possible
                            $this->optimizeIfLocal($relativePath);

                            // Sanitize alt text if provided
                            $sanitizedAlt = $altText ? strip_tags($altText) : null;

                            // Create database record
                            $savedImages[] = Image::create([
                                'image_url' => $relativePath,
                                'alt_text' => $sanitizedAlt ?? $this->generateAltText($filenameBase, class_basename($modelClass)),
                                'blur_placeholder' => $variant === 'main' ? $blurPlaceholder : null,
                                'variant' => $variant,
                                'imageable_type' => $modelClass,
                                'imageable_id' => $modelId,
                                'collection' => $collection,
                                'order' => $sortOrder,
                            ]);

                            // Clear variant-specific cache
                            $cacheKey = "image_url_{$this->disk}_" . md5($relativePath);
                            Cache::forget($cacheKey);

                            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
                            $disk = Storage::disk($this->disk);
                            Log::info('Successfully stored image', [
                                'path' => $relativePath,
                                'variant' => $variant,
                                'mode' => $mode,
                                'url' => $disk->url($relativePath),
                            ]);
                        } catch (Exception $e) {
                            Log::error("Failed to process image for {$modelClass} ID {$modelId}, variant {$variant}: {$e->getMessage()}", [
                                'path' => $relativePath,
                                'exception' => $e->getTraceAsString(),
                            ]);
                            throw $e;
                        }
                    }
                });

                // Clear caches after successful upload
                if (class_exists(ClearCacheService::class)) {
                    app(ClearCacheService::class)->clearModelCaches($imageable);
                    if ($imageable instanceof Content) {
                        app(ClearCacheService::class)->clearContentCache($imageable->type);
                    }
                }
            });

            return $savedImages;
        } catch (\Exception $e) {
            Log::error("Image upload failed for {$modelClass} ID {$modelId}: {$e->getMessage()}");
            throw new \Exception("Failed to upload image: {$e->getMessage()}");
        }
    }

    /**
     * Validate uploaded file
     */
    protected function validateFile(UploadedFile $file): void
    {
        app(SecureUploadService::class)->validateImage($file);

        if (! extension_loaded('gd')) {
            Log::warning('GD extension not loaded; image processing may fail.');
        }
    }

    protected function generateBlurDataUri(string $sourcePath): ?string
    {
        if (! extension_loaded('gd')) {
            return null;
        }

        try {
            $source = $this->createImageResource($sourcePath);
            if (! $source) {
                return null;
            }

            $srcWidth = imagesx($source);
            $srcHeight = imagesy($source);
            $targetWidth = max(8, (int) ($this->config['blur_width'] ?? 20));
            $aspectRatio = $srcHeight / max($srcWidth, 1);
            $targetHeight = max(8, (int) round($targetWidth * $aspectRatio));

            $thumb = imagecreatetruecolor($targetWidth, $targetHeight);
            if (! $thumb) {
                imagedestroy($source);

                return null;
            }

            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $srcWidth, $srcHeight);
            imagedestroy($source);

            ob_start();
            $ok = imagejpeg($thumb, null, 45);
            $buffer = ob_get_clean();
            imagedestroy($thumb);

            if (! $ok || $buffer === false || $buffer === '') {
                return null;
            }

            return 'data:image/jpeg;base64,' . base64_encode($buffer);
        } catch (\Throwable $e) {
            Log::debug('Blur placeholder generation skipped', ['error' => $e->getMessage()]);

            return null;
        }
    }

    public function blurPlaceholderFromPath(string $relativePath): ?string
    {
        if (! $this->imageExists($relativePath)) {
            return null;
        }

        return $this->generateBlurDataUri($this->getDisk()->path($relativePath));
    }

    /**
     * Resize and convert image with proper aspect ratio handling
     */
    protected function resizeAndConvertToWebP(
        string $sourcePath,
        int $width,
        int $height,
        int $quality,
        string $format = 'webp',
        string $mode = 'crop',  // 'crop', 'fit', 'adaptive'
        string $bgColor = '#FFFFFF'  // For 'fit' padding; supports hex or transparent
    ): string {
        $source = $this->createImageResource($sourcePath);
        if (!$source) {
            Log::error('Failed to create image resource', ['path' => $sourcePath]);
            throw new Exception('Failed to create image resource.');
        }

        $srcWidth = imagesx($source);
        $srcHeight = imagesy($source);
        $srcRatio = $srcWidth / $srcHeight;
        $destRatio = $width / $height;
        $ratioDiff = abs($srcRatio - $destRatio);

        // Adaptive mode: Choose based on extreme diff
        if ($mode === 'adaptive') {
            $mode = ($ratioDiff > 0.5) ? 'fit' : 'crop';
        }

        $newImage = null;
        $tempImage = null;

        if ($mode === 'crop') {
            // Scale to cover, center-crop
            if ($srcRatio > $destRatio) {
                $tempHeight = $height;
                $tempWidth = (int) ($height * $srcRatio);
            } else {
                $tempWidth = $width;
                $tempHeight = (int) ($width / $srcRatio);
            }

            $tempImage = imagecreatetruecolor($tempWidth, $tempHeight);
            if (!$tempImage) {
                throw new Exception('Failed to create temporary image resource.');
            }
            imagealphablending($tempImage, false);
            imagesavealpha($tempImage, true);
            imagecopyresampled($tempImage, $source, 0, 0, 0, 0, $tempWidth, $tempHeight, $srcWidth, $srcHeight);

            $newImage = imagecreatetruecolor($width, $height);
            if (!$newImage) {
                throw new Exception('Failed to create new image resource.');
            }
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);

            $x = (int) (($tempWidth - $width) / 2);
            $y = (int) (($tempHeight - $height) / 2);
            imagecopy($newImage, $tempImage, 0, 0, $x, $y, $width, $height);
        } else {  // 'fit' mode: Scale to fit, center with padding
            // Scale factor: Use smaller of width/height ratios to fit inside
            $scaleX = $width / $srcWidth;
            $scaleY = $height / $srcHeight;
            $scale = min($scaleX, $scaleY);

            $newWidth = (int) ($srcWidth * $scale);
            $newHeight = (int) ($srcHeight * $scale);

            // Create canvas
            $newImage = imagecreatetruecolor($width, $height);
            if (!$newImage) {
                throw new Exception('Failed to create new image resource.');
            }

            // Handle background
            if ($bgColor === 'transparent') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $bg = imagecolorallocatealpha($newImage, 0, 0, 0, 127);  // Transparent
                imagefill($newImage, 0, 0, $bg);
            } else {
                // Simple white for now; extend for full hex parsing if needed
                $bg = imagecolorallocate($newImage, 255, 255, 255);
                imagefill($newImage, 0, 0, $bg);
            }

            // Resize proportionally
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            if (!$resized) {
                throw new Exception('Failed to create resized image resource.');
            }
            imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);

            // Center on canvas
            $x = (int) (($width - $newWidth) / 2);
            $y = (int) (($height - $newHeight) / 2);
            imagecopy($newImage, $resized, $x, $y, 0, 0, $newWidth, $newHeight);

            imagedestroy($resized);
        }

        // Output to buffer
        ob_start();
        $success = match ($format) {
            'webp' => imagewebp($newImage, null, $quality),
            'jpeg' => imagejpeg($newImage, null, $quality),
            'png' => imagepng($newImage, null, round(9 * $quality / 100)),
            default => false,
        };

        if (!$success) {
            ob_end_clean();
            Log::error('Failed to convert image', ['format' => $format, 'path' => $sourcePath]);
            throw new Exception("Failed to convert image to {$format}.");
        }

        $output = ob_get_clean();

        // Clean up
        imagedestroy($source);
        if (isset($tempImage)) imagedestroy($tempImage);
        imagedestroy($newImage);

        return $output;
    }

    /**
     * Create GD image resource from file
     */
    protected function createImageResource(string $sourcePath): ?\GdImage
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $sourcePath);
        finfo_close($finfo);

        $resource = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($sourcePath),
            'image/png' => imagecreatefrompng($sourcePath),
            'image/gif' => imagecreatefromgif($sourcePath),
            'image/bmp' => imagecreatefrombmp($sourcePath),
            'image/webp' => imagecreatefromwebp($sourcePath),
            default => null,
        };

        if (!$resource) {
            Log::error('GD failed to create resource from MIME type', ['mime' => $mimeType, 'path' => $sourcePath]);
        }

        return $resource;
    }

    /**
     * Sanitize slug for filename
     */
    protected function sanitizeSlug(string $slug): string
    {
        $slug = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower(trim($slug)));
        return empty($slug) ? 'image' : $slug;
    }

    /**
     * Generate safe filename
     */
    protected function generateFilename(string $base, string $variant, string $extension = 'webp'): string
    {
        $filename = "{$base}-{$variant}.{$extension}";

        if (preg_match('/[^a-zA-Z0-9._-]/', $filename)) {
            throw new Exception('Generated filename contains invalid characters.');
        }

        return $filename;
    }

    /**
     * Generate descriptive alt text for accessibility and SEO.
     * Accepts title (e.g. "My Project") or slug (e.g. "my-project") and context.
     */
    public function generateAltText(string $titleOrSlug, string $context = ''): string
    {
        $base = ucwords(str_replace(['-', '_'], ' ', $titleOrSlug));
        $base = htmlspecialchars($base, ENT_QUOTES, 'UTF-8');

        return $context ? trim("{$base} – {$context}") : $base;
    }

    /**
     * Optimize image if Spatie Image Optimizer is available
     */
    protected function optimizeIfLocal(string $relativePath): void
    {
        if (! config('image.optimize', true)) {
            return;
        }

        if (!class_exists('\Spatie\ImageOptimizer\OptimizerChainFactory')) {
            return;
        }

        try {
            $fullPath = Storage::disk($this->disk)->path($relativePath);

            if (file_exists($fullPath)) {
                \Spatie\ImageOptimizer\OptimizerChainFactory::create()->optimize($fullPath);
                Log::info('Image optimized', ['path' => $relativePath]);
            }
        } catch (\Exception $e) {
            Log::warning('Image optimization failed', [
                'path' => $relativePath,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Invalidate list/detail caches for any Content rows tied to these image records.
     */
    protected function clearContentCachesFromImages(iterable $images): void
    {
        if (!class_exists(ClearCacheService::class)) {
            return;
        }

        $ids = collect($images)
            ->filter(fn (Image $img) => $img->imageable_type === Content::class)
            ->pluck('imageable_id')
            ->unique()
            ->filter();

        if ($ids->isEmpty()) {
            return;
        }

        Content::query()
            ->whereIn('id', $ids)
            ->select('type')
            ->distinct()
            ->pluck('type')
            ->filter()
            ->each(fn (string $type) => app(ClearCacheService::class)->clearContentCache($type));
    }

    /**
     * Delete image file from disk
     */
    public function deleteImage(string $imageUrl): void
    {
        $imageUrl = trim($imageUrl, '/');

        // Security validation
        if (preg_match('/\.\.\//', $imageUrl) || preg_match('/[^a-zA-Z0-9\/._-]/', $imageUrl)) {
            Log::warning('Invalid image URL for deletion: Suspicious characters detected.', ['url' => $imageUrl]);
            return;
        }

        if (Storage::disk($this->disk)->exists($imageUrl)) {
            Storage::disk($this->disk)->delete($imageUrl);

            // Clear cache
            $cacheKey = "image_url_{$this->disk}_" . md5($imageUrl);
            Cache::forget($cacheKey);

            Log::info("Deleted image file: {$imageUrl}");
        } else {
            Log::warning("Image file not found: {$imageUrl}");
        }
    }

    /**
     * Delete all images for a specific model
     */
    public function deleteImagesByModel(string $modelClass, int $modelId): void
    {
        if (!class_exists($modelClass) || !in_array('Illuminate\Database\Eloquent\Model', class_parents($modelClass))) {
            throw new Exception("Invalid model class: {$modelClass}");
        }

        $snapshot = Image::where('imageable_type', $modelClass)
            ->where('imageable_id', $modelId)
            ->get();

        DB::transaction(function () use ($modelClass, $modelId) {
            $images = Image::where('imageable_type', $modelClass)
                ->where('imageable_id', $modelId)
                ->get();

            foreach ($images as $image) {
                $this->deleteImage($image->image_url);

                if (method_exists($image, 'delete')) {
                    $image->delete(); // Soft delete
                } else {
                    $image->forceDelete();
                }

                Log::info("Deleted image record for model {$modelClass}:{$modelId}, URL:{$image->image_url}");
            }
        });

        $this->clearContentCachesFromImages($snapshot);
    }

    /**
     * Get disk instance for advanced operations
     */
    public function getDisk(): \Illuminate\Contracts\Filesystem\Filesystem
    {
        return Storage::disk($this->disk);
    }

    /**
     * Check if an image exists
     */
    public function imageExists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }

    /**
     * Get image size
     */
    public function getImageSize(string $path): ?int
    {
        return Storage::disk($this->disk)->exists($path)
            ? Storage::disk($this->disk)->size($path)
            : null;
    }


    /**
     * Delete specific images by IDs (file + DB record)
     */
    public function deleteImagesByIds(array $imageIds): void
    {
        if (empty($imageIds)) {
            return;
        }

        $snapshot = Image::whereIn('id', $imageIds)->get();

        DB::transaction(function () use ($imageIds) {
            $images = Image::whereIn('id', $imageIds)->get();

            foreach ($images as $image) {
                $this->deleteImage($image->image_url);

                if (method_exists($image, 'delete')) {
                    $image->delete(); // Soft delete
                } else {
                    $image->forceDelete();
                }

                Log::info("Deleted image record ID: {$image->id} for model {$image->imageable_type}:{$image->imageable_id}");
            }
        });

        $this->clearContentCachesFromImages($snapshot);
    }


    /**
     * Delete all variants for image groups by UUIDs (file + DB record)
     */
    public function deleteImageGroupByUuids(array $uuids): void
    {
        if (empty($uuids)) {
            return;
        }

        $snapshot = collect();
        foreach ($uuids as $uuid) {
            $snapshot = $snapshot->merge(Image::where('image_url', 'like', "%-{$uuid}-%")->get());
        }

        DB::transaction(function () use ($uuids) {
            foreach ($uuids as $uuid) {
                // Fetch all images with matching UUID in filename (e.g., '*-uuid-*')
                $images = Image::where('image_url', 'like', "%-{$uuid}-%")
                    ->get();

                foreach ($images as $image) {
                    $this->deleteImage($image->image_url);

                    if (method_exists($image, 'delete')) {
                        $image->delete(); // Soft delete
                    } else {
                        $image->forceDelete();
                    }

                    Log::info("Deleted image group (UUID: {$uuid}) - Record ID: {$image->id}");
                }
            }
        });

        $this->clearContentCachesFromImages($snapshot);
    }


    // Add to the end of Version 2

    public function generateSrcset(array $variants, array $variantOrder = ['mobile', 'mobile_retina', 'small', 'main']): string
    {
        $srcset = [];
        foreach ($variantOrder as $variantName) {
            if (isset($variants[$variantName])) {
                $width = $variants[$variantName]['width'] ?? 800;
                $url = $variants[$variantName]['url'];
                $srcset[] = "{$url} {$width}w";
            }
        }
        return implode(', ', $srcset);
    }

    public function getPictureElement(
        array $variants,
        string $altText = '',
        string $class = '',
        string $sizes = '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 800px',
        bool $lazy = true
    ): string {
        if (empty($variants)) {
            Log::warning('getPictureElement called with empty variants array');
            return '';
        }

        $html = '<picture>';

        // WebP sources first
        $webpVariants = $this->filterWebPVariants($variants);
        if (!empty($webpVariants)) {
            $webpSrcset = $this->generateSrcset($webpVariants);
            $html .= sprintf(
                '<source type="image/webp" srcset="%s" sizes="%s">',
                htmlspecialchars($webpSrcset),
                htmlspecialchars($sizes)
            );
        }

        // Fallback sources
        $regularVariants = $this->filterNonWebPVariants($variants);
        if (!empty($regularVariants)) {
            $regularSrcset = $this->generateSrcset($regularVariants);
            $html .= sprintf(
                '<source srcset="%s" sizes="%s">',
                htmlspecialchars($regularSrcset),
                htmlspecialchars($sizes)
            );
        }

        // Fallback img
        $fallbackVariant = $variants['main'] ?? $variants['small'] ?? reset($variants);
        $html .= sprintf(
            '<img src="%s" alt="%s" class="%s" %s%s%s>',
            htmlspecialchars($fallbackVariant['url']),
            htmlspecialchars($altText ?: ($fallbackVariant['alt'] ?? '')),
            htmlspecialchars($class),
            $lazy ? 'loading="lazy" ' : '',
            isset($fallbackVariant['width']) ? 'width="' . $fallbackVariant['width'] . '" ' : '',
            isset($fallbackVariant['height']) ? 'height="' . $fallbackVariant['height'] . '" ' : ''
        );

        $html .= '</picture>';
        return $html;
    }

    public function getResponsiveImage(
        array $variants,
        string $altText = '',
        string $class = '',
        string $sizes = '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 800px',
        bool $lazy = true
    ): string {
        if (empty($variants)) {
            Log::warning('getResponsiveImage called with empty variants array');
            return '';
        }

        $srcset = $this->generateSrcset($variants);
        $fallbackVariant = $variants['main'] ?? $variants['small'] ?? reset($variants);

        return sprintf(
            '<img src="%s" srcset="%s" sizes="%s" alt="%s" class="%s" %s%s%s>',
            htmlspecialchars($fallbackVariant['url']),
            htmlspecialchars($srcset),
            htmlspecialchars($sizes),
            htmlspecialchars($altText ?: ($fallbackVariant['alt'] ?? '')),
            htmlspecialchars($class),
            $lazy ? 'loading="lazy" ' : '',
            isset($fallbackVariant['width']) ? 'width="' . $fallbackVariant['width'] . '" ' : '',
            isset($fallbackVariant['height']) ? 'height="' . $fallbackVariant['height'] . '" ' : ''
        );
    }

    public function getSizesAttribute(string $layout = 'half'): string
    {
        return match ($layout) {
            'full' => '100vw',
            'hero' => '100vw',
            'half' => '(max-width: 768px) 100vw, 50vw',
            'third' => '(max-width: 768px) 100vw, 33vw',
            'quarter' => '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 25vw',
            'sidebar' => '(max-width: 1024px) 100vw, 300px',
            default => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 800px',
        };
    }

    protected function filterWebPVariants(array $variants): array
    {
        return array_filter($variants, function ($variant) {
            return str_ends_with($variant['url'], '.webp');
        });
    }

    protected function filterNonWebPVariants(array $variants): array
    {
        return array_filter($variants, function ($variant) {
            return !str_ends_with($variant['url'], '.webp');
        });
    }

    /**
     * Group uploaded image variants by UUID embedded in the stored filename.
     *
     * @param  iterable<\App\Models\Image>  $images
     * @return array<string|int, array{representative: ?\App\Models\Image, images: list<\App\Models\Image>}>
     */
    public function groupImagesByUuid(iterable $images): array
    {
        $groupedImages = [];

        foreach ($images as $image) {
            $urlParts = explode('/', $image->image_url);
            $filename = end($urlParts);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $baseName = basename($filename, '.' . $extension);

            preg_match('/([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})/', $baseName, $matches);
            $uuid = $matches[1] ?? null;

            if ($uuid) {
                if (! isset($groupedImages[$uuid])) {
                    $groupedImages[$uuid] = [
                        'representative' => null,
                        'images' => [],
                    ];
                }
                $groupedImages[$uuid]['images'][] = $image;
                if ($image->variant === 'main') {
                    $groupedImages[$uuid]['representative'] = $image;
                }
            } else {
                $fallbackKey = $image->id;
                if (! isset($groupedImages[$fallbackKey])) {
                    $groupedImages[$fallbackKey] = [
                        'representative' => $image,
                        'images' => [$image],
                    ];
                }
            }
        }

        foreach ($groupedImages as &$group) {
            if (! $group['representative'] && ! empty($group['images'])) {
                $group['representative'] = $group['images'][0];
            }
        }

        return $groupedImages;
    }
}
