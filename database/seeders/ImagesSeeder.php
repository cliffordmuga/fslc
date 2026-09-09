<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Image;
use Illuminate\Database\Seeder;

class ImagesSeeder extends Seeder
{
    private const TYPE_IMAGES = [
        'portfolio' => '/images/og/portfolio.svg',
        'services'  => '/images/og/services.svg',
        'blog'      => '/images/og/insights.svg',
        'page'      => '/images/og/hmis.svg',
        'about'     => '/images/og/company.svg',
        'gallery'   => '/images/og/portfolio.svg',
        'default'   => '/images/subtle-pattern.svg',
    ];

    public function run(): void
    {
        $contents = Content::where('status', 'published')->get();

        $imageVariants = ['main', 'thumbnail', 'mobile', 'mobile_retina'];

        foreach ($contents as $content) {
            $imagePath = $this->imagePathFor($content->type, $content->slug);

            Image::updateOrCreate(
                [
                    'imageable_type' => Content::class,
                    'imageable_id' => $content->id,
                    'collection' => 'featured',
                    'variant' => 'main',
                ],
                [
                    'image_url' => url($imagePath),
                    'alt_text' => "Featured image for {$content->title}",
                    'order' => 1,
                ]
            );

            if ($content->type === 'portfolio') {
                for ($i = 1; $i <= 3; $i++) {
                    Image::updateOrCreate(
                        [
                            'imageable_type' => Content::class,
                            'imageable_id' => $content->id,
                            'collection' => 'gallery',
                            'variant' => 'main',
                            'order' => $i,
                        ],
                        [
                            'image_url' => url(self::TYPE_IMAGES['gallery']),
                            'alt_text' => "Gallery image {$i} for {$content->title}",
                        ]
                    );
                }
            }

            foreach ($imageVariants as $variant) {
                if ($variant === 'main') {
                    continue;
                }

                Image::updateOrCreate(
                    [
                        'imageable_type' => Content::class,
                        'imageable_id' => $content->id,
                        'collection' => 'featured',
                        'variant' => $variant,
                    ],
                    [
                        'image_url' => url($imagePath),
                        'alt_text' => "{$variant} image for {$content->title}",
                        'order' => 1,
                    ]
                );
            }
        }
    }

    private function imagePathFor(string $type, string $slug): string
    {
        if ($type === 'services' && str_contains($slug, 'hmis')) {
            return self::TYPE_IMAGES['page'];
        }

        if ($type === 'blog' && (str_contains($slug, 'hmis') || str_contains($slug, 'hospital') || str_contains($slug, 'sha'))) {
            return self::TYPE_IMAGES['page'];
        }

        if ($type === 'blog' && (str_contains($slug, 'campaign') || str_contains($slug, 'election') || str_contains($slug, 'political') || str_contains($slug, 'voter'))) {
            return '/images/og/campaign.svg';
        }

        return self::TYPE_IMAGES[$type] ?? self::TYPE_IMAGES['default'];
    }
}
