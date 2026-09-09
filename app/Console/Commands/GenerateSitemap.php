<?php

// Console/Commands/GenerateSitemap.php
namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {--ping : Ping search engines after generation (requires SITEMAP_PING_SEARCH_ENGINES=true)}';
    protected $description = 'Generate the sitemap.xml file for search engines';

    public function handle(SitemapService $sitemapService): int
    {
        $this->info('Generating sitemap...');

        if ($sitemapService->generate()) {
            $this->info('Sitemap generated successfully!');
            $this->info('Location: ' . public_path('sitemap.xml'));
            $this->info('Last generated: ' . now()->format('Y-m-d H:i:s'));

            if (config('seo.ping_search_engines') && $this->option('ping')) {
                $sitemapService->pingSearchEngines();
                $this->info('Search engines pinged (where configured).');
            }

            return self::SUCCESS;
        }

        $this->error('Failed to generate sitemap. Check logs for details.');
        return self::FAILURE;
    }
}
