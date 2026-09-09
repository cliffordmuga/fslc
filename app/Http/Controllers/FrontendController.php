<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Tag;
use App\Services\ContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class FrontendController extends Controller
{
    public function __construct(protected ContentService $contentService) {}

    public function index()
    {
        // Log only in debug to avoid log noise in production
        if (config('app.debug')) {
            Log::debug('FrontendController@index hit', [
                'path' => request()->path(),
                'url'  => request()->fullUrl(),
            ]);
        }

        $portfolioItems = $this->contentService->getFeaturedContent('portfolio', 3, ['ctas', 'tags']);
        $services = $this->contentService->getFeaturedContent('services', 3, ['ctas']);
        $latestInsights = $this->contentService->getFeaturedContent('blog', 3, ['tags']);

        $intro = $this->contentService->getContentByType('intro', false);

        $homepageCta = $this->contentService->getCtaForPage('home');

        $seoData = $this->contentService->getSeoData('home');
        $pageSchemas = $this->contentService->getPageSchemas('home', [
            'portfolioItems' => $portfolioItems,
            'services' => $services,
        ]);

        return view('frontend.index', compact(
            'portfolioItems',
            'services',
            'latestInsights',
            'intro',
            'homepageCta',
            'seoData',
            'pageSchemas'
        ));
    }

    public function about()
    {
        $about   = $this->contentService->getContentByType('about', true);
        $mission = $this->contentService->getContentByType('mission', false);
        $vision  = $this->contentService->getContentByType('vision', false);
        $intro   = $this->contentService->getContentByType('intro', false);

        $aboutCta = $this->contentService->getCtaForPage('about');
        $timelineItems = $this->contentService->getTimelineItems();
        $faqItems = $this->contentService->getFaqItems();

        $seoData = $this->contentService->getSeoData('about', $about);
        $pageSchemas = $this->contentService->getPageSchemas('about', ['about' => $about, 'faqItems' => $faqItems]);

        $featuredTestimonial = $this->contentService->getFeaturedTestimonials(1)->first();

        return view('frontend.about', compact(
            'about',
            'mission',
            'vision',
            'intro',
            'seoData',
            'pageSchemas',
            'featuredTestimonial',
            'aboutCta',
            'timelineItems',
            'faqItems'
        ));
    }

    public function portfolio(Request $request)
    {
        $pillars = config('forefront.portfolio_pillars', []);
        $pillar = (string) $request->query('pillar', 'all');
        if (! array_key_exists($pillar, $pillars)) {
            $pillar = 'all';
        }

        $portfolioItems = $this->contentService->getPortfolioByPillar($pillar, true);
        $totalProjects = $pillar === 'all'
            ? $portfolioItems->count()
            : $this->contentService->getPortfolioPublishedCount();

        $portfolioCta = $this->contentService->getCtaForPage('portfolio');

        $seoData = $this->contentService->getSeoData('portfolio');
        if ($pillar !== 'all') {
            $seoData = $this->contentService->applyFilteredHubSeo($seoData, 'portfolio.index');
        }
        $pageSchemas = $this->contentService->getPageSchemas('portfolio', ['portfolioItems' => $portfolioItems]);

        return view('frontend.portfolio', compact(
            'portfolioItems',
            'pillars',
            'pillar',
            'totalProjects',
            'seoData',
            'pageSchemas',
            'portfolioCta'
        ));
    }



    public function services()
    {
        $services = $this->contentService->getPublishedContentsCollection('services', true);

        $servicesCta = $this->contentService->getCtaForPage('services');
        $positioning = $this->contentService->getContentByType('intro', false)?->excerpt ?? setting('site_description');

        $seoData = $this->contentService->getSeoData('services');
        $pageSchemas = $this->contentService->getPageSchemas('services', ['services' => $services]);

        return view('frontend.services', compact('services', 'seoData', 'pageSchemas', 'servicesCta', 'positioning'));
    }



    public function blog(Request $request)
    {
        $category = (string) $request->query('category', '');
        $blogPosts = $this->contentService->getPublishedContents('blog', true, 9);
        $categories = $this->contentService->getTagsForType('blog');

        $activeCategoryLabel = 'All insights';
        if ($category !== '') {
            $activeCategoryLabel = $categories->firstWhere('slug', $category)?->name
                ?? ucwords(str_replace('-', ' ', $category));
        }

        $tagHubSlugs = ['hmis', 'election-digital-strategy', 'sha-integration', 'digital-health', 'laravel'];

        $blogCta = $this->contentService->getCtaForPage('blog');

        $seoData = $this->contentService->getSeoData('blog');
        if ($category !== '') {
            $seoData = $this->contentService->applyFilteredHubSeo($seoData, 'insights.index');
        } else {
            $page = max(1, (int) $request->query('page', 1));
            $seoData = $this->contentService->applyPaginatedHubSeo($seoData, 'insights.index', $page);
        }
        $pageSchemas = $this->contentService->getPageSchemas('blog');

        return view('frontend.blog', compact(
            'blogPosts',
            'categories',
            'category',
            'activeCategoryLabel',
            'tagHubSlugs',
            'blogCta',
            'seoData',
            'pageSchemas'
        ));
    }



    public function mission()
    {
        $content = $this->contentService->getContentWithRelations('mission', 'mission');
        $seoData = $this->contentService->getSeoData('mission', $content);
        $pageSchemas = $this->contentService->getPageSchemas('details', ['content' => $content]);
        return view('frontend.details', [
            'content' => $content,
            'seoData' => $seoData,
            'pageSchemas' => $pageSchemas,
            'relatedItems' => collect(),
            'testimonials' => $this->contentService->getFeaturedTestimonials(3),
        ]);
    }

    public function vision()
    {
        $content = $this->contentService->getContentWithRelations('vision', 'vision');
        $seoData = $this->contentService->getSeoData('vision', $content);
        $pageSchemas = $this->contentService->getPageSchemas('details', ['content' => $content]);
        return view('frontend.details', [
            'content' => $content,
            'seoData' => $seoData,
            'pageSchemas' => $pageSchemas,
            'relatedItems' => collect(),
            'testimonials' => $this->contentService->getFeaturedTestimonials(3),
        ]);
    }

    public function intro()
    {
        $content = $this->contentService->getContentWithRelations('intro', 'intro');
        $seoData = $this->contentService->getSeoData('intro', $content);
        $pageSchemas = $this->contentService->getPageSchemas('details', ['content' => $content]);
        return view('frontend.details', [
            'content' => $content,
            'seoData' => $seoData,
            'pageSchemas' => $pageSchemas,
            'relatedItems' => collect(),
            'testimonials' => null,
        ]);
    }

    public function tag(string $slug)
    {
        $tag  = Tag::where('slug', $slug)->firstOrFail();
        $type = request()->query('type', 'all');

        $items = $this->contentService->getTagPageContent($tag, $type, 12);

        $tagCta      = $this->contentService->getCtaForPage('home');
        $seoData     = $this->contentService->getTagSeoData($tag);
        $pageSchemas = $this->contentService->getPageSchemas('tag', ['tag' => $tag]);
        $tagIntro    = $tag->hub_intro ?: (config("forefront.tag_hub_intros.{$tag->slug}") ?? null);

        return view('frontend.tag', compact('tag', 'items', 'type', 'tagCta', 'seoData', 'pageSchemas', 'tagIntro'));
    }

    public function privacy()
    {
        // CMS-managed page when present (type=page, slug=privacy), else static copy.
        $content = $this->contentService->getPage('privacy');

        // SEO: prefer CMS (if found), else default SEO for privacy
        $seoData = $this->contentService->getSeoData('privacy', $content);
        $seoData['canonical_url'] = url('/privacy');

        return view('frontend.legal', [
            'pageKey' => 'privacy',
            'title' => 'Privacy Policy',
            'content' => $content,
            'seoData' => $seoData,
        ]);
    }

    public function terms()
    {
        // CMS-managed page when present (type=page, slug=terms), else static copy.
        $content = $this->contentService->getPage('terms');

        $seoData = $this->contentService->getSeoData('terms', $content);
        $seoData['canonical_url'] = url('/terms');

        return view('frontend.legal', [
            'pageKey' => 'terms',
            'title' => 'Terms & Conditions',
            'content' => $content,
            'seoData' => $seoData,
        ]);
    }




    public function show(string $slug)
    {
        $routeName = request()->route()?->getName();

        $type = match ($routeName) {
            'portfolio.show' => 'portfolio',
            'services.show'  => 'services',
            'insights.show', 'blog.show' => 'blog',
            'page.show'      => 'page',
            default          => abort(404),
        };

        $content = $this->contentService->getContentWithRelations($type, $slug);

        $relatedItems = $this->contentService->getRelatedContent($type, $content->id, 3);

        $leadConfig = $type === 'services'
            ? (config("forefront.service_lead_config.{$slug}") ?? null)
            : null;

        $testimonials = null;
        if (in_array($type, ['portfolio', 'services', 'page'], true)) {
            $mapped = $content->testimonials
                ->where('status', 'approved')
                ->sortBy(fn ($t) => $t->pivot->sort_order ?? 0)
                ->take(3)
                ->values();
            $testimonials = $mapped->isNotEmpty()
                ? $mapped
                : $this->contentService->getFeaturedTestimonials(3);
        }

        $seoData = $this->contentService->getSeoData($type, $content);
        $pageSchemas = $this->contentService->getPageSchemas('details', ['content' => $content]);

        $metrics = content_detail_metrics($content);
        $detailInquiryType = content_inquiry_type_for_detail($content, $type, $leadConfig);
        View::share('inquiryType', $detailInquiryType);

        // Preload hero image for better LCP (cPanel-friendly, no extra infra)
        if (!isset($seoData['preload_image'])) {
            $featured = $content->images?->where('collection', 'featured')->first();
            $seoData['preload_image'] = $featured?->image_url ?? $seoData['og_image'] ?? null;
        }

        return view('frontend.details', compact(
            'content',
            'seoData',
            'pageSchemas',
            'relatedItems',
            'testimonials',
            'leadConfig',
            'metrics',
            'detailInquiryType',
        ));
    }

    /**
     * Signed draft preview for unpublished content (admin-generated links).
     */
    public function previewContent(Content $content)
    {
        // Draft preview — intentionally uncached; just eager-load for the view.
        $content->load(['tags', 'ctas', 'seoMetadata', 'testimonials', 'creator', 'images']);

        $type = $content->type;
        $relatedItems = collect();
        $testimonials = null;
        $leadConfig = $type === 'services'
            ? (config("forefront.service_lead_config.{$content->slug}") ?? null)
            : null;

        $seoData = $this->contentService->getSeoData($type, $content);
        $seoData['noindex'] = true;
        $seoData['nofollow'] = true;
        $pageSchemas = $this->contentService->getPageSchemas('details', ['content' => $content]);
        $metrics = content_detail_metrics($content);
        $detailInquiryType = content_inquiry_type_for_detail($content, $type, $leadConfig);
        View::share('inquiryType', $detailInquiryType);

        return view('frontend.details', compact(
            'content',
            'seoData',
            'pageSchemas',
            'relatedItems',
            'testimonials',
            'leadConfig',
            'metrics',
            'detailInquiryType',
        ))->with('isPreview', true);
    }
}
