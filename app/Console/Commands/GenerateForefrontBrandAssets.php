<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateForefrontBrandAssets extends Command
{
    protected $signature = 'forefront:generate-brand-assets';

    protected $description = 'Generate default OG image, logo PNG, and hero WebP placeholders';

    public function handle(): int
    {
        if (! extension_loaded('gd')) {
            $this->error('PHP GD extension is required to generate brand assets.');

            return self::FAILURE;
        }

        $this->ensureDirectory(public_path('images'));
        $this->ensureDirectory(public_path('assets/bg/hero'));

        $this->generateOgImage(public_path('images/default-og-image.png'), 1200, 630);
        $this->generateLogo(public_path('images/logo.png'), 512, 512);

        $heroPalettes = [
            [14, 165, 233],   // sky
            [2, 132, 199],
            [3, 105, 161],
            [15, 118, 110],   // teal
            [22, 101, 52],    // green
            [67, 56, 202],    // indigo
            [124, 58, 237],   // violet
        ];

        foreach ($heroPalettes as $i => $rgb) {
            $n = $i + 1;
            $this->generateHeroWebp(public_path("assets/bg/hero/hero-{$n}.webp"), 1920, 1080, $rgb);
            $this->generateHeroWebp(public_path("assets/bg/hero/hero-{$n}-1280.webp"), 1280, 720, $rgb);
            $this->generateHeroWebp(public_path("assets/bg/hero/hero-{$n}-640.webp"), 640, 360, $rgb);
        }

        $this->info('Brand assets generated successfully.');

        return self::SUCCESS;
    }

    protected function ensureDirectory(string $path): void
    {
        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    protected function generateOgImage(string $path, int $w, int $h): void
    {
        $img = imagecreatetruecolor($w, $h);
        $this->fillGradient($img, $w, $h, [14, 165, 233], [15, 23, 42]);
        $white = imagecolorallocate($img, 255, 255, 255);
        $muted = imagecolorallocate($img, 203, 213, 225);
        imagestring($img, 5, 80, (int) ($h * 0.38), 'Forefront Solutions (K) Ltd', $white);
        imagestring($img, 4, 80, (int) ($h * 0.48), 'HMIS · Software · Branding · Public Engagement', $muted);
        imagestring($img, 3, 80, (int) ($h * 0.58), 'Digital transformation partner — Kenya & East Africa', $muted);
        imagepng($img, $path, 6);
        imagedestroy($img);
    }

    protected function generateLogo(string $path, int $w, int $h): void
    {
        $img = imagecreatetruecolor($w, $h);
        imagesavealpha($img, true);
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparent);
        $primary = imagecolorallocate($img, 14, 165, 233);
        $dark = imagecolorallocate($img, 15, 23, 42);
        imagefilledrectangle($img, 0, 0, $w, $h, $primary);
        imagestring($img, 5, (int) ($w * 0.28), (int) ($h * 0.42), 'FS', $dark);
        imagepng($img, $path, 6);
        imagedestroy($img);
    }

    /** @param array{0:int,1:int,2:int} $from */
    protected function generateHeroWebp(string $path, int $w, int $h, array $from): void
    {
        $img = imagecreatetruecolor($w, $h);
        $to = [max(0, $from[0] - 40), max(0, $from[1] - 40), max(0, $from[2] - 30)];
        $this->fillGradient($img, $w, $h, $from, $to);
        imagewebp($img, $path, 82);
        imagedestroy($img);
    }

    /**
     * @param  array{0:int,1:int,2:int}  $from
     * @param  array{0:int,1:int,2:int}  $to
     */
    protected function fillGradient(\GdImage $img, int $w, int $h, array $from, array $to): void
    {
        for ($y = 0; $y < $h; $y++) {
            $ratio = $h > 1 ? $y / ($h - 1) : 0;
            $r = (int) ($from[0] + ($to[0] - $from[0]) * $ratio);
            $g = (int) ($from[1] + ($to[1] - $from[1]) * $ratio);
            $b = (int) ($from[2] + ($to[2] - $from[2]) * $ratio);
            $color = imagecolorallocate($img, $r, $g, $b);
            imageline($img, 0, $y, $w, $y, $color);
        }
    }
}
