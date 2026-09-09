<?php

namespace App\Http\Middleware;

use App\Models\Content;
use App\Models\PageAnalytic;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackPageViews
{
    /** @var list<string> */
    protected array $trackableRoutes = [
        'home',
        'about',
        'portfolio.index',
        'services.index',
        'insights.index',
        'blog.index',
        'contact',
        'portfolio.show',
        'services.show',
        'insights.show',
        'blog.show',
        'page.show',
        'tags.show',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldTrack($request, $response)) {
            return $response;
        }

        if ($response->headers->get('X-Page-Cache') === 'HIT') {
            return $response;
        }

        $contentId = $this->resolveContentId($request);
        if (! $contentId) {
            return $response;
        }

        $today = now()->toDateString();
        $visitorKey = 'pv:' . md5($request->ip() . '|' . ($request->userAgent() ?? '') . '|' . $contentId . '|' . $today);

        PageAnalytic::firstOrCreate(
            ['content_id' => $contentId, 'date' => $today],
            ['views' => 0, 'unique_visitors' => 0, 'cta_clicks' => 0, 'leads_generated' => 0]
        )->increment('views');

        if (! Cache::has($visitorKey)) {
            Cache::put($visitorKey, true, now()->endOfDay());
            PageAnalytic::where('content_id', $contentId)
                ->where('date', $today)
                ->increment('unique_visitors');
        }

        return $response;
    }

    protected function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return false;
        }

        if ($request->is('admin*') || $request->is('api*')) {
            return false;
        }

        $route = $request->route()?->getName();
        if (! $route || ! in_array($route, $this->trackableRoutes, true)) {
            return false;
        }

        return ! $request->user();
    }

    protected function resolveContentId(Request $request): ?int
    {
        $route = $request->route()?->getName();
        $slug = $request->route('slug');

        if ($slug && in_array($route, ['portfolio.show', 'services.show', 'insights.show', 'blog.show', 'page.show'], true)) {
            $type = match ($route) {
                'portfolio.show' => 'portfolio',
                'services.show' => 'services',
                'insights.show', 'blog.show' => 'blog',
                'page.show' => 'page',
                default => null,
            };

            if (! $type) {
                return null;
            }

            return Content::query()
                ->published()
                ->where('type', $type)
                ->where('slug', $slug)
                ->value('id');
        }

        if ($route === 'contact') {
            return Content::query()
                ->published()
                ->where(function ($q) {
                    $q->where('slug', 'contact')->orWhere('slug', 'intro');
                })
                ->value('id');
        }

        if ($route === 'home') {
            return Content::query()->published()->where('type', 'intro')->value('id')
                ?? Content::query()->published()->where('slug', 'intro')->value('id');
        }

        if (in_array($route, ['about', 'mission', 'vision', 'intro'], true)) {
            $type = $route === 'about' ? 'about' : $route;

            return Content::query()->published()->where('type', $type)->value('id');
        }

        if (in_array($route, ['portfolio.index', 'services.index', 'insights.index', 'blog.index'], true)) {
            return null;
        }

        return null;
    }
}
