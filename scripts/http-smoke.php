<?php

/**
 * Live HTTP smoke against php artisan serve (uses .env MySQL).
 * Usage: php scripts/http-smoke.php [baseUrl]
 */
$base = rtrim($argv[1] ?? 'http://127.0.0.1:8000', '/');

$paths = [
    '/',
    '/about',
    '/portfolio',
    '/services',
    '/insights',
    '/blog',
    '/contact',
    '/mission',
    '/vision',
    '/intro',
    '/privacy',
    '/terms',
    '/search',
    '/search?q=hmis',
    '/sitemap',
    '/sitemap.xml',
    '/health',
    '/login',
    '/register',
    '/forgot-password',
    '/admin/dashboard',
    '/dashboard',
    '/profile',
    '/does-not-exist-404-check',
];

$failures = [];
$ok = 0;

foreach ($paths as $path) {
    $url = $base.$path;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HEADER => true,
        CURLOPT_NOBODY => false,
        CURLOPT_USERAGENT => 'FSLC-HTTP-Smoke/1.0',
    ]);
    $raw = curl_exec($ch);
    $errno = curl_errno($ch);
    $err = curl_error($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($errno) {
        $failures[] = "{$path} => curl error: {$err}";
        echo "ERR  {$path} ({$err})\n";

        continue;
    }

    $expectOk = ! str_contains($path, 'does-not-exist')
        && ! str_starts_with($path, '/admin')
        && $path !== '/dashboard'
        && $path !== '/profile';

    if (str_contains($path, 'does-not-exist')) {
        if (! in_array($status, [404, 301, 302], true)) {
            $failures[] = "{$path} => expected 404, got {$status}";
            echo "FAIL {$status} {$path}\n";
        } else {
            $ok++;
            echo "OK   {$status} {$path}\n";
        }

        continue;
    }

    if (! $expectOk) {
        // auth-gated: redirect or 401/403 is fine
        if (in_array($status, [200, 301, 302, 401, 403], true)) {
            $ok++;
            echo "OK   {$status} {$path} (gated)\n";
        } else {
            $failures[] = "{$path} => {$status}";
            echo "FAIL {$status} {$path}\n";
        }

        continue;
    }

    if (in_array($status, [200, 301, 302], true)) {
        // For 200 HTML, check body isn't an exception page
        if ($status === 200 && is_string($raw) && (str_contains($raw, 'Illuminate\\Database\\QueryException') || str_contains($raw, 'ErrorException') || str_contains($raw, 'ViewException'))) {
            $failures[] = "{$path} => 200 but exception content";
            echo "FAIL {$status} {$path} (exception in body)\n";
        } else {
            $ok++;
            echo "OK   {$status} {$path}\n";
        }
    } else {
        $failures[] = "{$path} => {$status}";
        echo "FAIL {$status} {$path}\n";
    }
}

echo "\nSummary: {$ok} ok, ".count($failures)." failed\n";
if ($failures) {
    echo implode("\n", $failures)."\n";
    exit(1);
}
