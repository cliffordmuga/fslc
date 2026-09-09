<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    public function __construct(
        protected SitemapService $sitemapService,
    ) {}

    /**
     * Serve sitemap.xml, self-healing when missing or stale.
     */
    public function xml(): Response
    {
        $path = public_path('sitemap.xml');

        if (! file_exists($path) || $this->sitemapService->needsRegeneration()) {
            $this->sitemapService->generate();
        }

        if (! file_exists($path)) {
            return response(
                '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>',
                200,
                ['Content-Type' => 'application/xml'],
            );
        }

        return response()->file($path, ['Content-Type' => 'application/xml']);
    }

    /**
     * HTML sitemap for visitors.
     */
    public function html(): View
    {
        return view('sitemap', [
            'lastGenerated' => $this->sitemapService->getLastGeneratedAt(),
        ]);
    }

    /**
     * Admin-triggered sitemap regeneration.
     */
    public function generate(): RedirectResponse
    {
        if ($this->sitemapService->generate()) {
            return back()->with('success', 'Sitemap generated successfully!');
        }

        return back()->with('error', 'Failed to generate sitemap. Check logs.');
    }
}
