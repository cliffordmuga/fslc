<?php

namespace App\Models;

use App\Enums\ContentType;
use App\Traits\Cacheable;
use App\Traits\Imageable;
use App\Traits\Seoable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Content extends Model
{
    use Cacheable, HasFactory, Imageable, LogsActivity, Seoable;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'excerpt',
        'content',
        'status',
        'published_at',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Admin type labels — sourced from ContentType enum.
     *
     * @return array<string, string>
     */
    public static function typeLabels(): array
    {
        return ContentType::labels();
    }

    public const STATUSES = [
        'draft' => 'Draft',
        'published' => 'Published',
    ];

    protected static function booted(): void
    {
        static::creating(function (Content $content) {
            if (empty($content->slug) && ! empty($content->title)) {
                $content->slug = static::makeUniqueSlug(Str::slug($content->title));
            }
            if (empty($content->excerpt) && ! empty($content->content)) {
                $content->excerpt = generate_excerpt($content->content, 155);
            }
        });

        // Slug: only fix if empty; if user changed slug, make it unique
        static::updating(function (Content $content) {
            if (empty($content->slug) && ! empty($content->title)) {
                $content->slug = static::makeUniqueSlug(Str::slug($content->title), $content->id);
            } elseif ($content->isDirty('slug') && ! empty($content->slug)) {
                $content->slug = static::makeUniqueSlug($content->slug, $content->id);
            }
            if (empty($content->excerpt) && ! empty($content->content)) {
                $content->excerpt = generate_excerpt($content->content, 155);
            }
        });
    }

    protected static function makeUniqueSlug(string $slug, ?int $excludeId = null): string
    {
        $base = $slug;
        $i = 1;

        while (static::query()
            ->where('slug', $slug)
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->exists()
        ) {
            $i++;
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeOfType($query, string|array $type)
    {
        return is_array($type)
            ? $query->whereIn('type', $type)
            : $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at');
    }

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ctas(): HasMany
    {
        return $this->hasMany(Cta::class);
    }

    public function leadsGenerated(): HasMany
    {
        return $this->hasMany(Lead::class, 'source_content_id');
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(PageAnalytic::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'content_tag')->withTimestamps();
    }

    public function testimonials()
    {
        return $this->belongsToMany(Testimonial::class, 'content_testimonial')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('content_testimonial.sort_order');
    }

    // Accessors (use withCount in listings for performance)
    public function getViewsCountAttribute(): int
    {
        return (int) $this->analytics()->sum('views');
    }

    public function getLeadsGeneratedCountAttribute(): int
    {
        return (int) $this->leadsGenerated()->count();
    }

    public function getUrlAttribute(): string
    {
        $cacheKey = "content:url:{$this->id}:{$this->type}:{$this->slug}";

        return Cache::remember($cacheKey, now()->addHour(), function () {
            $typeRoutes = config('routes.content_types', [
                'portfolio' => 'portfolio.show',
                'services' => 'services.show',
                'blog' => 'blog.show',
                'page' => 'page.show',
                'about' => 'about',
                'mission' => 'mission',
                'vision' => 'vision',
                'intro' => 'intro',
                'default' => 'page.show',
            ]);

            $routeName = $typeRoutes[$this->type] ?? $typeRoutes['default'];

            if (Route::has($routeName)) {
                $singletonRoutes = ['about', 'mission', 'vision', 'intro'];
                if (in_array($routeName, $singletonRoutes, true)) {
                    return route($routeName);
                }

                return route($routeName, ['slug' => $this->slug]);
            }

            return url($this->slug);
        });
    }

    public function getFeaturedImageAttribute(): ?string
    {
        // ✅ Correct grouping: (collection=featured) OR (variant=main) should be scoped to same content
        $image = $this->images()
            ->where(function ($q) {
                $q->where('collection', 'featured')
                    ->orWhere('variant', 'main');
            })
            ->orderBy('collection') // featured first typically
            ->orderBy('order')
            ->first() ?? $this->images()->orderBy('order')->first();

        return $image?->url; // assumes Image model has getUrlAttribute
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image;
    }

    /**
     * Stable key for grouping all variants (main, thumbnail, …) of one uploaded gallery file.
     */
    public function galleryImageGroupKey(string $path): string
    {
        $stripped = preg_replace('/-(main|small|mobile|mobile_retina|thumbnail)\.(webp|jpe?g)$/i', '', $path);

        return $stripped !== '' ? $stripped : $path;
    }

    /**
     * One Image per logical gallery item (prefers main variant when multiple rows are loaded).
     */
    public function getGalleryAttribute(): Collection
    {
        if ($this->relationLoaded('images')) {
            return $this->images
                ->where('collection', 'gallery')
                ->groupBy(fn (Image $img) => $this->galleryImageGroupKey($img->image_url))
                ->map(fn ($group) => $group->firstWhere('variant', 'main') ?? $group->first())
                ->sortBy(fn (Image $img) => sprintf('%011d-%010d', (int) $img->order, $img->id))
                ->values();
        }

        return $this->images()
            ->where('collection', 'gallery')
            ->where('variant', 'main')
            ->orderBy('order')
            ->orderBy('id')
            ->get();
    }

    /**
     * Gallery tiles for the frontend: small thumbs + full-size URL for lightbox.
     */
    public function getGalleryForViewAttribute(): Collection
    {
        $variantsConfig = config('image_service.variants', [
            'main' => [800, 400],
            'small' => [400, 200],
            'thumbnail' => [150, 150],
        ]);

        $galleryRows = $this->relationLoaded('images')
            ? $this->images->where('collection', 'gallery')
            : $this->images()
                ->where('collection', 'gallery')
                ->whereIn('variant', ['thumbnail', 'small', 'main'])
                ->orderBy('order')
                ->orderBy('id')
                ->get();

        if ($galleryRows->isEmpty()) {
            return collect();
        }

        $grouped = $galleryRows->groupBy(fn (Image $img) => $this->galleryImageGroupKey($img->image_url));

        return $grouped
            ->sortBy(fn ($group) => sprintf('%011d-%010d', (int) $group->min('order'), $group->min('id')))
            ->values()
            ->map(function ($group) use ($variantsConfig) {
                $byVariant = $group->keyBy('variant');
                $mainImg = $byVariant->get('main') ?? $group->first();
                $thumbImg = $byVariant->get('thumbnail') ?? $byVariant->get('small') ?? $mainImg;

                $srcsetParts = [];
                foreach (['thumbnail', 'small'] as $v) {
                    if ($byVariant->has($v)) {
                        $im = $byVariant->get($v);
                        $w = (int) (($variantsConfig[$v] ?? [150])[0] ?? 150);
                        $srcsetParts[] = $im->url.' '.$w.'w';
                    }
                }
                $thumbSrcset = count($srcsetParts) ? implode(', ', $srcsetParts) : null;

                $tc = $variantsConfig[$thumbImg->variant] ?? [150, 150];

                return [
                    'alt' => $mainImg->alt_text ?? '',
                    'thumb_url' => $thumbImg->url,
                    'thumb_srcset' => $thumbSrcset,
                    'thumb_sizes' => '(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 220px',
                    'thumb_width' => (int) ($tc[0] ?? 150),
                    'thumb_height' => (int) ($tc[1] ?? 150),
                    'lightbox_url' => $byVariant->get('main')?->url ?? $mainImg->url,
                ];
            });
    }

    // Helpers
    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at?->isPast();
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function hasImages(): bool
    {
        return $this->images()->exists();
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(
                fn (string $eventName) => "Content '{$this->title}' was {$eventName}"
            );
    }

    // Cache keys (your Cacheable trait will use these)
    public function getCacheKeys(): array
    {
        return [
            "content:{$this->id}",
            "content:slug:{$this->slug}",
            "content:type:{$this->type}",
            "content:url:{$this->id}:{$this->type}:{$this->slug}",
        ];
    }
}
