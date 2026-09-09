<?php

namespace App\Observers;

use App\Models\Redirect;
use App\Services\CacheBuster;
use App\Support\ContentCache;

class RedirectObserver
{
    public function saved(Redirect $redirect): void
    {
        ContentCache::bust();
        CacheBuster::bump();
    }

    public function deleted(Redirect $redirect): void
    {
        ContentCache::bust();
        CacheBuster::bump();
    }
}
