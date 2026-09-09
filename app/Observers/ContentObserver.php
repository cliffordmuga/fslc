<?php

namespace App\Observers;

use App\Jobs\RegenerateSitemapJob;
use App\Models\Content;
use App\Models\Redirect;

class ContentObserver
{
    public function updating(Content $content): void
    {
        // If slug is changing, capture old path -> new path
        if ($content->isDirty('slug')) {
            $oldSlug = (string) $content->getOriginal('slug');
            $newSlug = (string) $content->slug;

            if ($oldSlug && $newSlug && $oldSlug !== $newSlug) {
                $oldPath = $this->pathFor($content->type, $oldSlug);
                $newPath = $this->pathFor($content->type, $newSlug);

                // Don't create self-redirects
                if ($oldPath !== $newPath) {
                    Redirect::updateOrCreate(
                        ['old_path' => $oldPath],
                        ['new_path' => $newPath, 'status_code' => 301]
                    );
                }
            }
        }
    }

    public function saved(Content $content): void
    {
        // Regenerate sitemap when content is published or URL-impacting fields change
        if ($content->status === 'published') {
            RegenerateSitemapJob::dispatch()->onQueue('default');
        }

        \App\Support\ContentCache::bust();
        \App\Support\AdminUiCache::forgetPublishedContentOptions();
        \App\Support\AdminUiCache::forgetDashboardAndAnalytics();
    }

    public function deleted(Content $content): void
    {
        // If published content is deleted, sitemap should refresh
        RegenerateSitemapJob::dispatch()->onQueue('default');

        \App\Support\ContentCache::bust();
        \App\Support\AdminUiCache::forgetPublishedContentOptions();
        \App\Support\AdminUiCache::forgetDashboardAndAnalytics();
    }

    /* private function pathFor(string $type, string $slug): string
    {
        // Keep this aligned with your routes:
        // portfolio detail: /portfolio/{slug}
        // services detail: /services/{slug}
        // blog detail: /blog/{slug}
        // pages: /{slug}
        $slug = ltrim($slug, '/');

        return match ($type) {
            'portfolio' => '/portfolio/' . $slug,
            'services'  => '/services/' . $slug,
            'blog'      => '/blog/' . $slug,
            default     => '/' . $slug,
        };
    } */

    private function pathFor(string $type, string $slug): string
    {
        $slug = ltrim($slug, '/');

        return match ($type) {
            \App\Enums\ContentType::Portfolio->value => '/portfolio/' . $slug,
            \App\Enums\ContentType::Services->value  => '/services/' . $slug,
            \App\Enums\ContentType::Blog->value      => '/insights/' . $slug,
            \App\Enums\ContentType::Page->value      => '/page/' . $slug,
            default => '/' . $slug,
        };
    }
}
