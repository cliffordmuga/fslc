<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Lead extends Model
{
    use HasFactory, Cacheable, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'inquiry_type',
        'service_content_id',
        'source_content_id',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'referrer',
        'attachment_path',
        'attachment_original_name',
        'ip_address',
        'user_agent',
        'status',
        'is_spam',
        'spam_score',
        'spam_reasons',
        'conversion_value',
    ];

    protected $casts = [
        'is_spam' => 'boolean',
        'spam_score' => 'integer',
        'spam_reasons' => 'array',
        'conversion_value' => 'decimal:2',
    ];

    public const INQUIRY_TYPES = [
        'general'           => 'General Inquiry',
        'portfolio'         => 'Portfolio Inquiry',
        'service'           => 'Service Inquiry',
        'newsletter'        => 'Newsletter Signup',
        'hmis-demo'         => 'HMIS Demo Request',
        'hmis-checklist'    => 'HMIS Checklist Request',
        'software-quote'    => 'Software Project Quote',
        'marketing-consult' => 'Brand Consultation',
        'campaign-strategy' => 'Campaign Strategy Session',
    ];

    public const STATUSES = [
        'new'       => 'New',
        'contacted' => 'Contacted',
        'qualified' => 'Qualified',
        'converted' => 'Converted',
        'lost'      => 'Lost',
    ];

    // Relationships
    public function sourceContent(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'source_content_id');
    }

    // Scopes (DRY)
    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeNotSpam(Builder $query): Builder
    {
        return $query->where('is_spam', false);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeByInquiryType(Builder $query, string $type): Builder
    {
        return $query->where('inquiry_type', $type);
    }

    public function scopeByUtmSource(Builder $query, string $source): Builder
    {
        return $query->where('utm_source', $source);
    }

    public function scopeHasSourceContent(Builder $query): Builder
    {
        return $query->whereNotNull('source_content_id');
    }

    /** Leads with service/portfolio intent + known source = higher conversion potential */
    public function scopeHighIntent(Builder $query): Builder
    {
        return $query->whereNotIn('inquiry_type', ['general', 'newsletter'])
            ->whereNotNull('source_content_id')
            ->where('is_spam', false);
    }

    /** New leads older than X days without status change (need follow-up) */
    public function scopeNeedsFollowUp(Builder $query, int $days = 3): Builder
    {
        return $query->where('status', 'new')
            ->where('is_spam', false)
            ->where('created_at', '<=', now()->subDays($days));
    }

    // Accessors
    public function getIsHighIntentAttribute(): bool
    {
        return ! in_array($this->inquiry_type, ['general', 'newsletter'], true)
            && $this->source_content_id !== null
            && ! $this->is_spam;
    }

    public function getIsHighValueAttribute(): bool
    {
        return $this->conversion_value !== null && (float) $this->conversion_value >= 100.00;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(
                fn(string $eventName) =>
                "Lead from {$this->name} ({$this->email}) was {$eventName}"
            );
    }

    // Cache keys
    public function getCacheKeys(): array
    {
        return [
            "lead:{$this->id}",
            "lead:email:" . strtolower($this->email),
            "lead:source:" . ($this->source_content_id ?? 'none'),
        ];
    }
}
