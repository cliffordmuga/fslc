<?php

namespace App\Traits;

use App\Models\SeoMetadata;
use App\Services\SeoService;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait Seoable
{
    public function seoMetadata(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    protected function seoCachePrefix(): string
    {
        // Works for any seoable model
        return "seo:" . strtolower(class_basename($this)) . ":{$this->id}";
    }

    public function generateSeoFields(?SeoService $seoService = null): SeoMetadata
    {
        $seoService = $seoService ?? app(SeoService::class);

        return $seoService->updateMetadata(
            model: $this,
            title: $this->seoMetadata?->meta_title,
            description: $this->seoMetadata?->meta_description,
            keywords: $this->seoMetadata?->meta_keywords,
            canonicalUrl: $this->seoMetadata?->canonical_url,
            ogImage: $this->seoMetadata?->og_image,
        );
    }

    public function updateSeoMetadata(array $data): SeoMetadata
    {
        return app(SeoService::class)->updateMetadata(
            model: $this,
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            keywords: $data['keywords'] ?? null,
            canonicalUrl: $data['canonical_url'] ?? null,
            ogImage: $data['og_image'] ?? null,
            ogTitle: $data['og_title'] ?? null,
            ogDescription: $data['og_description'] ?? null,
            noindex: (bool) ($data['noindex'] ?? false),
            nofollow: (bool) ($data['nofollow'] ?? false),
        );
    }

    public function getSeoTitle(): string
    {
        $key = "{$this->seoCachePrefix()}:title";

        return Cache::remember($key, now()->addHours(24), function () {
            return $this->seoMetadata?->meta_title
                ?? ($this->title ?? config('app.name'));
        });
    }

    public function getSeoDescription(): string
    {
        $key = "{$this->seoCachePrefix()}:description";

        return Cache::remember($key, now()->addHours(24), function () {
            if ($this->seoMetadata?->meta_description) {
                return $this->seoMetadata->meta_description;
            }

            $content = $this->content ?? $this->excerpt ?? '';
            return Str::limit(strip_tags($content), 160);
        });
    }

    public function getSeoData(): array
    {
        $key = "{$this->seoCachePrefix()}:data";

        return Cache::remember($key, now()->addHours(6), function () {
            $seoService = app(SeoService::class);
            $route = $this->type ?? 'details';
            return $seoService->getSeoData($route, $this);
        });
    }

    public function getOgImage(): string
    {
        if ($this->seoMetadata?->og_image) {
            return $this->seoMetadata->og_image;
        }

        if (method_exists($this, 'images')) {
            $featured = $this->images()
                ->where('collection', 'featured')
                ->where('variant', 'main')
                ->first();

            if ($featured) {
                return asset($featured->image_url);
            }
        }

        return asset('images/default-og-image.png');
    }

    public function getStructuredData(): array
    {
        if ($this->seoMetadata?->structured_data) {
            return $this->seoMetadata->structured_data;
        }

        return app(SeoService::class)->generateStructuredData($this);
    }

    public function getCanonicalUrl(): string
    {
        return $this->seoMetadata?->canonical_url
            ?? app(SeoService::class)->generateCanonicalUrl($this);
    }

    public function shouldIndex(): bool
    {
        return !($this->seoMetadata?->noindex ?? false);
    }

    public function deleteSeoMetadata(): bool
    {
        if (!$this->seoMetadata) {
            return false;
        }

        return app(SeoService::class)->deleteMetadata($this);
    }

    public function clearSeoCache(): void
    {
        Cache::forget("{$this->seoCachePrefix()}:title");
        Cache::forget("{$this->seoCachePrefix()}:description");
        Cache::forget("{$this->seoCachePrefix()}:data");
    }

    protected static function bootSeoable(): void
    {
        static::saved(function ($model) {
            $model->clearSeoCache();
        });

        static::deleted(function ($model) {
            $model->deleteSeoMetadata();
            $model->clearSeoCache();
        });
    }
}
