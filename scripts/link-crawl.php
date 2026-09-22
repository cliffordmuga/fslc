<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$base = rtrim($argv[1] ?? 'http://127.0.0.1:8000', '/');
$appUrl = rtrim((string) config('app.url'), '/');

$pagesToCrawl = ['/', '/about', '/services', '/portfolio', '/insights', '/contact', '/sitemap'];
$failures = [];

function fetch(string $url): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_USERAGENT => 'FSLC-Link-Crawl/1.0',
    ]);
    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [$status, is_string($body) ? $body : ''];
}

$toCheck = [];

foreach ($pagesToCrawl as $page) {
    [$status, $html] = fetch($base.$page);
    if ($status !== 200) {
        $failures[] = "crawl seed {$page} => {$status}";

        continue;
    }

    preg_match_all('/href=["\']([^"\']+)["\']/i', $html, $m);
    foreach ($m[1] as $href) {
        $href = html_entity_decode($href, ENT_QUOTES);
        if ($href === '' || str_starts_with($href, '#') || str_starts_with($href, 'mailto:') || str_starts_with($href, 'tel:') || str_starts_with($href, 'javascript:')) {
            continue;
        }

        $path = null;
        if (preg_match('#^https?://#i', $href)) {
            // Accept links rooted at APP_URL or the crawl base (common local mismatch).
            foreach ([$base, $appUrl] as $root) {
                if ($root !== '' && str_starts_with($href, $root)) {
                    $path = substr($href, strlen($root)) ?: '/';
                    break;
                }
            }
            if ($path === null) {
                continue; // external
            }
        } else {
            $path = str_starts_with($href, '/') ? $href : '/'.$href;
        }

        $path = explode('#', $path, 2)[0];
        $path = explode('?', $path, 2)[0];
        if ($path === '') {
            $path = '/';
        }

        // Ignore static/build assets and storage
        if (preg_match('#^/(build|storage|plugins|favicon|images)/#', $path) || str_ends_with($path, '.css') || str_ends_with($path, '.js')) {
            continue;
        }

        $toCheck[$path] = true;
    }
}

ksort($toCheck);
echo "APP_URL={$appUrl}\nCrawl base={$base}\nUnique internal links: ".count($toCheck).PHP_EOL;

foreach (array_keys($toCheck) as $path) {
    if (str_starts_with($path, '/logout')) {
        continue;
    }

    [$status] = fetch($base.$path);
    $ok = in_array($status, [200, 301, 302, 401, 403], true);
    if (! $ok) {
        echo "FAIL {$status} {$path}\n";
        $failures[] = "{$path} => {$status}";
    }
}

echo (count($toCheck) - count($failures)).'/'.count($toCheck)." link targets OK\n";
if ($appUrl !== $base) {
    echo "NOTE: APP_URL ({$appUrl}) differs from crawl base ({$base}). Generated absolute links follow APP_URL.\n";
}
if ($failures) {
    echo "Failures:\n".implode("\n", $failures)."\n";
    exit(1);
}
