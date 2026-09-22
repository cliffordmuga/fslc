<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Models\Content;
use App\Models\Tag;
use Illuminate\Contracts\Console\Kernel;

$base = rtrim($argv[1] ?? 'http://127.0.0.1:8000', '/');

$paths = ['/mission', '/vision', '/intro', '/about', '/portfolio', '/services', '/insights', '/contact', '/privacy', '/terms'];

foreach (Content::published()->whereIn('type', ['portfolio', 'services', 'blog'])->limit(12)->get() as $c) {
    $paths[] = match ($c->type) {
        'portfolio' => '/portfolio/'.$c->slug,
        'services' => '/services/'.$c->slug,
        'blog' => '/insights/'.$c->slug,
        default => null,
    };
}

foreach (Tag::query()->limit(5)->pluck('slug') as $slug) {
    $paths[] = '/tags/'.$slug;
}

// Hardcoded about-page pillar links (from about.blade / config)
foreach (config('forefront.about_pillars', []) as $pillar) {
    if (! empty($pillar['slug'])) {
        $paths[] = '/services/'.$pillar['slug'];
    }
}

$paths = array_values(array_unique(array_filter($paths)));
$failures = [];

foreach ($paths as $path) {
    $ch = curl_init($base.$path);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HEADER => true,
        CURLOPT_USERAGENT => 'FSLC-Detail-Smoke/1.0',
    ]);
    $raw = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $ok = in_array($status, [200, 301, 302], true)
        && ! (is_string($raw) && (str_contains($raw, 'QueryException') || str_contains($raw, 'ViewException')));

    echo ($ok ? 'OK  ' : 'FAIL')." {$status} {$path}\n";
    if (! $ok) {
        $failures[] = "{$path} => {$status}";
    }
}

echo "\n".(count($paths) - count($failures)).'/'.count($paths)." passed\n";
exit($failures ? 1 : 0);
