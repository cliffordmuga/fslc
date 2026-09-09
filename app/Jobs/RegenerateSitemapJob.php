<?php

namespace App\Jobs;

use App\Services\SitemapService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RegenerateSitemapJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 60;

    public function handle(SitemapService $sitemap): void
    {
        // Debounce with a cache lock (works with database cache + cache_locks table)
        $lock = Cache::lock('sitemap:regenerate', 30);

        if (!$lock->get()) {
            return;
        }

        try {
            $ok = $sitemap->generate();
            if (!$ok) {
                Log::warning('Sitemap generation returned false');
            } elseif (config('seo.ping_search_engines') && app()->environment('production')) {
                $sitemap->pingSearchEngines();
            }
        } finally {
            optional($lock)->release();
        }
    }
}
