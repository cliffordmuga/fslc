<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sitemap search-engine ping
    |--------------------------------------------------------------------------
    |
    | When enabled in production, notifies Bing (and optionally Google) after
    | sitemap regeneration. Google deprecated their ping endpoint; prefer
    | submitting the sitemap in Google Search Console.
    |
    */
    'ping_search_engines' => (bool) env('SITEMAP_PING_SEARCH_ENGINES', false),

    'ping_google' => (bool) env('SITEMAP_PING_GOOGLE', false),

];
