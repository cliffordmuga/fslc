<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Traits\Cacheable;

class SeoMetadata extends Model
{
    use HasFactory, Cacheable;

    protected $fillable = [
        'seoable_type',
        'seoable_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_type',
        'og_image',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'structured_data',
        'canonical_url',
        'noindex',
        'nofollow',
        'lastmod',
    ];

    protected $casts = [
        'structured_data' => 'array',
        'noindex' => 'boolean',
        'nofollow' => 'boolean',
        'lastmod' => 'datetime',
    ];

    // Relationships
    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    // Accessors / Helpers
    public function shouldIndex(): bool
    {
        return !$this->noindex;
    }

    public function getMetaTitleAttribute($value): string
    {
        return $value ?? $this->seoable?->title ?? config('app.name');
    }

    public function getMetaDescriptionAttribute($value): string
    {
        return $value ?? $this->seoable?->excerpt ?? config('app.name');
    }

    public function isStale(): bool
    {
        return $this->lastmod && $this->lastmod->lt(now()->subWeek());
    }

    // Cache keys
    public function getCacheKeys(): array
    {
        return [
            "seo_{$this->id}",
            "seo_{$this->seoable_type}_{$this->seoable_id}",
        ];
    }
}