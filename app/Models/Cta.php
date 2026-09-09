<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cta extends Model
{
    use Cacheable, HasFactory;

    protected $fillable = [
        'content_id',
        'text',
        'type',
        'action',
        'impressions',
        'clicks',
        'conversions',
        'priority',
    ];

    // Relationships
    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderByDesc('priority');
    }

    // Accessors
    public function getConversionRateAttribute(): float
    {
        return $this->impressions > 0 ? ($this->conversions / $this->impressions) * 100 : 0.0;
    }

    public function getClickThroughRateAttribute(): float
    {
        return $this->impressions > 0 ? ($this->clicks / $this->impressions) * 100 : 0.0;
    }

    // Cache keys
    public function getCacheKeys(): array
    {
        return [
            "cta_{$this->id}",
            "cta_content_{$this->content_id}",
        ];
    }

    public function getCacheTags(): array
    {
        return ['ctas'];
    }
}
