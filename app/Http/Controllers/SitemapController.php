<?php

// app/Http/Controllers/SitemapController.php
namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate and return sitemap XML
     *
     * @return Response
     */
    public function index(): Response
    {
        $sitemap = Cache::remember('sitemap.xml', 3600, function () {
            return $this->generateSitemap();
        });

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate sitemap XML content
     *
     * @return string
     */
    protected function generateSitemap(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Add homepage
        $xml .= $this->generateUrlEntry(url('/'), now(), '1.0', 'daily');

        // Add static pages
        $staticPages = [
            ['url' => url('/portfolio'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => url('/services'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => url('/about'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => url('/contact'), 'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $xml .= $this->generateUrlEntry(
                $page['url'],
                now(),
                $page['priority'],
                $page['changefreq']
            );
        }

        // Add published content
        $contents = Content::where('status', 'published')
            ->with('seoMetadata')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($contents as $content) {
            $lastmod = $content->seoMetadata?->lastmod ?? $content->updated_at;
            $priority = $this->getContentPriority($content->type);
            $changefreq = $this->getContentChangefreq($content->type);

            $xml .= $this->generateUrlEntry(
                url($content->slug),
                $lastmod,
                $priority,
                $changefreq
            );
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Generate single URL entry
     *
     * @param string $url
     * @param \Carbon\Carbon $lastmod
     * @param string $priority
     * @param string $changefreq
     * @return string
     */
    protected function generateUrlEntry(
        string $url,
        $lastmod,
        string $priority,
        string $changefreq
    ): string {
        $xml = '<url>';
        $xml .= '<loc>' . htmlspecialchars($url) . '</loc>';
        $xml .= '<lastmod>' . $lastmod->toW3cString() . '</lastmod>';
        $xml .= '<changefreq>' . $changefreq . '</changefreq>';
        $xml .= '<priority>' . $priority . '</priority>';
        $xml .= '</url>';

        return $xml;
    }

    /**
     * Get priority based on content type
     *
     * @param string $type
     * @return string
     */
    protected function getContentPriority(string $type): string
    {
        return match ($type) {
            'portfolio' => '0.8',
            'blog' => '0.7',
            'services' => '0.8',
            'about' => '0.6',
            default => '0.5',
        };
    }

    /**
     * Get change frequency based on content type
     *
     * @param string $type
     * @return string
     */
    protected function getContentChangefreq(string $type): string
    {
        return match ($type) {
            'portfolio' => 'monthly',
            'blog' => 'weekly',
            'services' => 'monthly',
            default => 'monthly',
        };
    }

    /**
     * Clear sitemap cache
     */
    public function clearCache(): void
    {
        Cache::forget('sitemap.xml');
    }
}
