<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory, Cacheable;

    protected $fillable = [
        'name',
        'slug',
        'hub_intro',
        'meta_description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            $tag->slug = $tag->slug ?? Str::slug($tag->name);
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name')) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    // Relationships
    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(Content::class, 'content_tag');
    }

    // Scopes (optional but useful)
    public function scopePopular(Builder $query, int $minCount = 1): Builder
    {
        return $query->withCount('contents')
            ->having('contents_count', '>=', $minCount)
            ->orderByDesc('contents_count');
    }

    // Cache methods
    public function getCacheKeys(): array
    {
        return [
            "tag_{$this->id}",
            "tag_slug_{$this->slug}",
        ];
    }

    public function getCachePatterns(): array
    {
        return [
            "tag_{$this->id}*",
            "tag_slug_{$this->slug}*",
        ];
    }

    public function getCacheTags(): array
    {
        return ['tags', "tag_{$this->id}"];
    }
}
