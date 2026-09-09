<?php

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        'public_uploads' => [
            'driver'     => 'local',
            'root'       => public_path('uploads'),
            'visibility' => 'public',
            'throw'      => false,
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

    /*
    | Optional CDN origin for static assets (heroes, OG images). Falls back to ASSET_URL.
    | Example: https://cdn.forefrontsolutions.co.ke
    */
    'cdn_url' => env('CDN_URL', env('ASSET_URL')),

];
