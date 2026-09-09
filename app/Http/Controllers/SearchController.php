<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    public function __construct(protected ContentService $contentService) {}

    public function index(Request $request)
    {
        $query = trim((string) $request->get('q', ''));
        $filterTypes = ['portfolio', 'services', 'blog'];
        $activeType = (string) $request->query('type', '');
        $activeType = in_array($activeType, $filterTypes, true) ? $activeType : '';

        if ($query === '') {
            $results = new LengthAwarePaginator([], 0, 15);
        } else {
            $results = $this->contentService->searchPublishedContents(
                $query,
                15,
                $activeType !== '' ? $activeType : null,
            );
        }

        $typeFilters = config('forefront.search_type_filters', []);
        $suggestedQueries = config('forefront.search_suggested_queries', []);
        $activeTypeLabel = $typeFilters[$activeType] ?? 'All';

        $seoData = [
            'title' => $query === '' ? 'Search — Insights & Portfolio' : "Search: {$query}",
            'description' => $query === ''
                ? 'Search HMIS guides, software case studies, and digital strategy insights for Kenyan institutions.'
                : "Results for \"{$query}\" — HMIS, software, branding, and public engagement content from Forefront Solutions.",
            'canonical_url' => request()->fullUrl(),
            'noindex' => true,
        ];
        $pageSchemas = $this->contentService->getPageSchemas('search', [
            'query' => $query,
            'results' => $query !== '' ? $results : null,
        ]);

        return view('frontend.search', compact(
            'query',
            'results',
            'seoData',
            'pageSchemas',
            'activeType',
            'activeTypeLabel',
            'typeFilters',
            'suggestedQueries',
        ));
    }
}
