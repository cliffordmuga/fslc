<?php

namespace App\Models;

use App\Services\ImageService;
use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use Cacheable, HasFactory;

    protected $fillable = [
        'imageable_type',
        'imageable_id',
        'image_url',
        'alt_text',
        'blur_placeholder',
        'variant',
        'collection',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return app(ImageService::class)->getImageUrl($this->image_url, $this->variant);
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeForCollection($query, string $collection)
    {
        return $query->where('collection', $collection);
    }

    public function scopeForVariant($query, string $variant)
    {
        return $query->where('variant', $variant);
    }

    public function getCacheKeys(): array
    {
        $prefix = $this->cachePrefix();
        $disk = config('image.disk', 'public_uploads');
        $path = ltrim((string) $this->image_url, '/');

        return [
            "{$prefix}:id:{$this->id}",
            "{$prefix}:owner:".strtolower(class_basename($this->imageable_type)).":{$this->imageable_id}:{$this->collection}",
            "image_url_{$disk}_".md5($path),
            'img_variants:'.md5($this->imageable_type.':'.$this->imageable_id.':'.$this->collection),
        ];
    }

    public function getCacheTags(): array
    {
        return ['images'];
    }
}
