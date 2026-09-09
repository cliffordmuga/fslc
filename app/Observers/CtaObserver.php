<?php

namespace App\Observers;

use App\Models\Cta;
use App\Services\CacheBuster;
use App\Support\ContentCache;

class CtaObserver
{
    public function saved(Cta $cta): void
    {
        $this->bust();
    }

    public function deleted(Cta $cta): void
    {
        $this->bust();
    }

    private function bust(): void
    {
        ContentCache::bust();
        CacheBuster::bump();
    }
}
