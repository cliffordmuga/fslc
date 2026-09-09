<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\Cacheable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Testimonial extends Model
{
    use HasFactory, Cacheable, LogsActivity;

    protected $fillable = [
        'client_name',
        'testimonial',
        'rating',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'rating' => 'integer',
    ];

    const STATUSES = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];

    // Relationships
    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(Content::class, 'content_testimonial')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('content_testimonial.sort_order');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByRating($query, int $minRating = 4)
    {
        return $query->where('rating', '>=', $minRating);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getIsHighRatingAttribute(): bool
    {
        return $this->rating >= 4;
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Testimonial from {$this->client_name} was {$eventName}");
    }

    // Cache keys
    public function getCacheKeys(): array
    {
        return [
            "testimonial_{$this->id}",
            "featured_testimonials",
            "approved_testimonials",
        ];
    }
}
