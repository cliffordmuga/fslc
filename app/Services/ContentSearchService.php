<?php

namespace App\Services;

use App\Models\Content;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContentSearchService
{
    public function __construct(protected ContentCacheManager $cache) {}

    /**
     * Search published hub content. Cached briefly (10 min) for repeated queries;
     * invalidated via ContentCache::bust() on publish.
     *
     * MySQL FULLTEXT requires ft_min_word_len (typically 4) — short queries use LIKE.
     */
    public function searchPublishedContents(string $term, int $perPage = 15, ?string $type = null): LengthAwarePaginator
    {
        $term = trim($term);
        $page = max(1, (int) request()->query('page', 1));

        $key = $this->cache->makeCacheKey('search', [
            'term' => mb_strtolower($term),
            'type' => $type ?? '',
            'perPage' => $perPage,
            'page' => $page,
        ]);

        return $this->cache->remember($key, now()->addMinutes(10), function () use ($term, $perPage, $type) {
            return $this->runSearchQuery($term, $perPage, $type);
        });
    }

    protected function runSearchQuery(string $term, int $perPage, ?string $type): LengthAwarePaginator
    {
        $searchableTypes = ['portfolio', 'services', 'blog', 'page'];
        $filterTypes = ['portfolio', 'services', 'blog'];

        if ($type !== null && $type !== '' && ! in_array($type, $filterTypes, true)) {
            $type = null;
        }

        $driver = config('database.connections.' . config('database.default') . '.driver');
        $like = '%' . addcslashes($term, '%_\\') . '%';
        $useLikeOnly = mb_strlen($term) < 4;

        $query = Content::query()
            ->published()
            ->whereIn('type', $searchableTypes)
            ->when($type, fn ($q) => $q->where('type', $type))
            ->with([
                'tags',
                'seoMetadata',
                'images' => fn ($q) => $q
                    ->select(['id', 'image_url', 'alt_text', 'imageable_id', 'imageable_type', 'variant', 'collection', 'order'])
                    ->where('collection', 'featured')
                    ->orderBy('order')
                    ->orderBy('id'),
            ])
            ->ordered();

        if (in_array($driver, ['mysql', 'mariadb'], true) && ! $useLikeOnly) {
            $query->where(function ($q) use ($term, $like) {
                $q->whereFullText(['title', 'excerpt', 'content'], $term)
                    ->orWhere('title', 'like', $like)
                    ->orWhere('excerpt', 'like', $like);
            });
        } else {
            $query->where(function ($q) use ($like) {
                $q->where('title', 'like', $like)
                    ->orWhere('excerpt', 'like', $like)
                    ->orWhere('content', 'like', $like);
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
