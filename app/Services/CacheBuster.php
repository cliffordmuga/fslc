<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheBuster
{
    private const KEY = 'cache:buster:public';

    /**
     * Read the current buster value (changes invalidate full-page cache keys).
     */
    public static function current(): string
    {
        return (string) Cache::get(self::KEY, '1');
    }

    /**
     * Bump buster (invalidates full-page cached HTML + any keys that include it).
     */
    public static function bump(): string
    {
        $next = (string) ((int) self::current() + 1);
        Cache::forever(self::KEY, $next);

        // Keep ContentService fragment cache keys in sync with full-page cache bust.
        $contentKey = 'content:cache_buster';
        Cache::forever($contentKey, (int) Cache::get($contentKey, 0) + 1);

        return $next;
    }
}
