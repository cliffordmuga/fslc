<?php

namespace App\Observers;

use App\Models\Tag;
use App\Services\CacheBuster;
use App\Support\ContentCache;

class TagObserver
{
    public function saved(Tag $tag): void
    {
        ContentCache::bust();
        CacheBuster::bump();
    }

    public function deleted(Tag $tag): void
    {
        ContentCache::bust();
        CacheBuster::bump();
    }
}
