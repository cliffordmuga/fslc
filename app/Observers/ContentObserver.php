<?php

namespace App\Observers;

use App\Enums\ContentType;
use App\Jobs\RegenerateSitemapJob;
use App\Models\Content;
use App\Models\Redirect;
use App\Support\AdminUiCache;
use App\Support\ContentCache;

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

                // Fragment types (timeline/FAQ) have no public URL — skip redirect rows.
                if ($oldPath === null || $newPath === null) {
                    return;
                }

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
        // Regenerate sitemap when content is published, or when it just left the
        // published state (e.g. draft) — the sitemap needs to drop the URL either way.
        if ($content->status === 'published' || $content->wasChanged('status')) {
            RegenerateSitemapJob::dispatch()->onQueue('default');
        }

        ContentCache::bust();
        AdminUiCache::forgetPublishedContentOptions();
        AdminUiCache::forgetDashboardAndAnalytics();
    }

    public function deleted(Content $content): void
    {
        // If published content is deleted, sitemap should refresh
        RegenerateSitemapJob::dispatch()->onQueue('default');

        ContentCache::bust();
        AdminUiCache::forgetPublishedContentOptions();
        AdminUiCache::forgetDashboardAndAnalytics();
    }

    private function pathFor(string $type, string $slug): ?string
    {
        return ContentType::redirectPathFor($type, $slug);
    }
}
