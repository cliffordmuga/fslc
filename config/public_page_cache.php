<?php

return [
    // Micro-cache TTL (server-side Laravel cache)
    'ttl_seconds' => env('PUBLIC_PAGE_CACHE_TTL', 300),

    // Optional HTTP Cache-Control max-age for anonymous browsers (0 = omit header)
    'browser_max_age_seconds' => (int) env('PUBLIC_PAGE_CACHE_BROWSER_MAX_AGE', 120),

    // Prevent caching huge responses on shared hosting
    'max_bytes' => env('PUBLIC_PAGE_CACHE_MAX_BYTES', 1048576), // 1MB

    // Cache only these query params (others like utm_* are ignored)
    'allowed_query_params' => ['page', 'tag', 'category', 'pillar', 'type', 'inquiry_type', 'service'],

    // Exclude by path (Apache/cPanel friendly patterns)
    'excluded_paths' => [
        '/admin*',
        '/login*',
        '/register*',
        '/password*',
        '/email*',
        '/verify*',
        '/2fa*',
        '/auth*',
        '/api*',

        '/contact',
        '/leads',
        '/newsletter/subscribe',

        '/search',
        '/preview*',
        '/csp-report',
        '/health',

        '/sitemap',
        '/sitemap.xml',
    ],

    // Exclude by route name patterns (more precise when available)
    'excluded_route_names' => [
        'admin.*',
        'api.*',
        'profile.*',
        'dashboard',
        'login',
        'register',
        'password.*',
        'verification.*',
        '2fa.*',
        'socialite.*',

        'contact',
        'contact.store',
        'leads.store',
        'newsletter.subscribe',

        'search',
        'csp.report',
        'health',
        'sitemap',
        'sitemap.xml',
    ],
];
