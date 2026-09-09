<?php

namespace App\Http\Middleware;

use App\Models\Content;
use App\Models\PageAnalytic;
use Closure;
use Illuminate\Database\UniqueConstraintViolationException;
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
        return $next($request);
    }

    /**
     * Record the view after the response has been sent to the browser.
     *
     * This runs in terminate() rather than handle() so that:
     *  - the analytics writes never block response delivery, and
     *  - cache HITs are counted. PublicPageCache short-circuits handle() on a
     *    HIT (so the old handle()-based tracking never saw cached traffic —
     *    the bulk of anonymous visits), but the kernel still calls terminate().
     */
    public function terminate(Request $request, Response $response): void
    {
        if (! $this->shouldTrack($request, $response)) {
            return;
        }

        $contentId = $this->resolveContentId($request);
        if (! $contentId) {
            return;
        }

        try {
            $today = now()->toDateString();

            // whereDate() (not where('date', ...)) because PageAnalytic casts
            // `date`, which persists as "Y-m-d 00:00:00" — a bare Y-m-d string
            // never matches on SQLite.
            $row = $this->dailyRow($contentId, $today);
            $row->increment('views');

            $visitorKey = 'pv:' . md5($request->ip() . '|' . ($request->userAgent() ?? '') . '|' . $contentId . '|' . $today);

            if (! Cache::has($visitorKey)) {
                Cache::put($visitorKey, true, now()->endOfDay());
                $row->increment('unique_visitors');
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * The page_analytics row for this content + day, created if missing.
     * Retries once on a concurrent-insert race.
     */
    protected function dailyRow(int $contentId, string $date): PageAnalytic
    {
        $find = fn () => PageAnalytic::query()
            ->where('content_id', $contentId)
            ->whereDate('date', $date)
            ->first();

        if ($row = $find()) {
            return $row;
        }

        try {
            return PageAnalytic::create([
                'content_id' => $contentId,
                'date' => $date,
                'views' => 0,
                'unique_visitors' => 0,
                'cta_clicks' => 0,
                'leads_generated' => 0,
            ]);
        } catch (UniqueConstraintViolationException $e) {
            return $find() ?? throw $e;
        }
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
        $route = (string) ($request->route()?->getName() ?? '');
        $slug = (string) ($request->route('slug') ?? '');

        // Listing / archive routes are page-level, not content-level.
        if (in_array($route, ['portfolio.index', 'services.index', 'insights.index', 'blog.index', 'tags.show'], true)) {
            return null;
        }

        // Cache the route+slug -> id mapping so cached page views don't each
        // cost a DB lookup. Misses are cached as 0 to avoid re-querying.
        $cacheKey = 'pv:content-id:' . md5($route . '|' . $slug);

        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached ?: null;
        }

        $id = $this->lookupContentId($route, $slug);
        Cache::put($cacheKey, (int) $id, now()->addHour());

        return $id;
    }

    protected function lookupContentId(string $route, string $slug): ?int
    {
        if ($slug !== '' && in_array($route, ['portfolio.show', 'services.show', 'insights.show', 'blog.show', 'page.show'], true)) {
            $type = match ($route) {
                'portfolio.show' => 'portfolio',
                'services.show' => 'services',
                'insights.show', 'blog.show' => 'blog',
                'page.show' => 'page',
                default => null,
            };

            if ($type === null) {
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
                ->where(fn ($q) => $q->where('slug', 'contact')->orWhere('slug', 'intro'))
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

        return null;
    }
}
