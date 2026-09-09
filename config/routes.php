<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Content type → named route mapping
    |--------------------------------------------------------------------------
    | Used by Content::getUrlAttribute() for cards, sitemap, and search results.
    */
    'content_types' => [
        'portfolio' => 'portfolio.show',
        'services'  => 'services.show',
        'blog'      => 'insights.show',
        'page'      => 'page.show',
        'about'     => 'about',
        'mission'   => 'mission',
        'vision'    => 'vision',
        'intro'     => 'intro',
        'default'   => 'page.show',
    ],
];
