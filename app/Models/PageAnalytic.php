<?php

namespace App\Models;

use App\Traits\Cacheable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageAnalytic extends Model
{
    use HasFactory, Cacheable;

    protected $table = 'page_analytics';

    protected $fillable = [
        'content_id',
        'date',
        'views',
        'unique_visitors',
        'cta_clicks',
        'leads_generated',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relationships
    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    // Scopes
    public function scopeByDateRange($query, CarbonInterface $startDate, CarbonInterface $endDate)
    {
        // Ensure proper date-only comparison (table column is DATE)
        return $query->whereBetween('date', [
            $startDate->toDateString(),
            $endDate->toDateString(),
        ]);
    }

    public function scopeByMonth($query, int $year, int $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }

    public function scopeForContent($query, int $contentId)
    {
        return $query->where('content_id', $contentId);
    }

    // Accessors
    public function getTotalEngagementAttribute(): int
    {
        return (int) $this->views + (int) $this->unique_visitors + (int) $this->cta_clicks;
    }

    /**
     * ✅ Computed conversion rate (no DB column)
     * leads_generated / views * 100
     */
    public function getConversionRateAttribute(): float
    {
        $views = (int) $this->views;
        if ($views <= 0) {
            return 0.0;
        }

        return round(((int) $this->leads_generated / $views) * 100, 2);
    }

    // Cache keys
    public function getCacheKeys(): array
    {
        $date = $this->date?->format('Y-m-d') ?? 'unknown';

        return [
            "analytics:{$this->id}",
            "analytics:content:{$this->content_id}:{$date}",
        ];
    }
}
