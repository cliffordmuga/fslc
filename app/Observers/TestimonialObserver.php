<?php

namespace App\Observers;

use App\Models\Testimonial;
use App\Services\CacheBuster;
use App\Support\ContentCache;

class TestimonialObserver
{
    public function saved(Testimonial $testimonial): void
    {
        $this->bust();
    }

    public function deleted(Testimonial $testimonial): void
    {
        $this->bust();
    }

    private function bust(): void
    {
        ContentCache::bust();
        CacheBuster::bump();
    }
}
