<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\SeoMetadata;
use Database\Seeders\Support\ForefrontSeederContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SeoMetadataSeeder extends Seeder
{
    public function run(): void
    {
        $contents = Content::where('status', 'published')->get();

        foreach ($contents as $content) {
            $canonicalUrl = $this->canonicalUrlFor($content);

            SeoMetadata::updateOrCreate(
                [
                    'seoable_type' => Content::class,
                    'seoable_id' => $content->id,
                ],
                [
                    'meta_title' => $this->generateMetaTitle($content),
                    'meta_description' => $this->generateMetaDescription($content),
                    'meta_keywords' => $this->keywordsFor($content),
                    'og_title' => $content->title,
                    'og_description' => $this->generateMetaDescription($content),
                    'og_image' => $this->getOgImageUrl($content),
                    'og_type' => $this->ogTypeFor($content),
                    'structured_data' => $this->generateStructuredData($content, $canonicalUrl),
                    'canonical_url' => $canonicalUrl,
                    'noindex' => false,
                    'nofollow' => false,
                    'lastmod' => $content->updated_at ?? now(),
                ]
            );
        }
    }

    private function canonicalUrlFor(Content $content): string
    {
        return match ($content->type) {
            'portfolio' => route('portfolio.show', $content->slug),
            'services'  => route('services.show', $content->slug),
            'blog'      => route('insights.show', $content->slug),
            'about'     => route('about'),
            default     => url('/'),
        };
    }

    private function generateMetaTitle(Content $content): string
    {
        $suffix = ' | ' . ForefrontSeederContent::COMPANY;
        $max = 60;
        $base = $content->title;

        if (mb_strlen($base . $suffix) <= $max) {
            return $base . $suffix;
        }

        $allowed = $max - mb_strlen($suffix);
        $truncated = mb_substr($base, 0, max(10, $allowed));
        $cut = mb_strrpos($truncated, ' ');

        if ($cut !== false && $cut > (int) ($allowed * 0.6)) {
            $truncated = mb_substr($truncated, 0, $cut);
        }

        return rtrim($truncated, ' -–—') . $suffix;
    }

    private function generateMetaDescription(Content $content): string
    {
        $description = $content->excerpt ?: strip_tags($content->content);
        $description = preg_replace('/\s+/', ' ', trim(html_entity_decode($description, ENT_QUOTES, 'UTF-8')));

        if (mb_strlen($description) <= 160) {
            return $description;
        }

        $truncated = mb_substr($description, 0, 157);
        $cut = mb_strrpos($truncated, ' ');

        if ($cut !== false && $cut > 120) {
            $truncated = mb_substr($truncated, 0, $cut);
        }

        return $truncated . '...';
    }

    private function keywordsFor(Content $content): string
    {
        if ($content->relationLoaded('tags') || $content->tags()->exists()) {
            $fromTags = $content->tags()->pluck('name')->take(8)->implode(', ');
            if ($fromTags !== '') {
                return $fromTags;
            }
        }

        return match ($content->type) {
            'services'  => 'HMIS Kenya, web development Nairobi, ERP Kenya, IT consultancy Kenya',
            'portfolio' => 'case studies Kenya, HMIS implementation, digital projects East Africa',
            'blog'      => 'healthcare IT Kenya, digital marketing Kenya, election digital strategy',
            default     => 'Forefront Solutions, HMIS Kenya, digital transformation Kenya',
        };
    }

    private function getOgImageUrl(Content $content): ?string
    {
        $featuredImage = $content->images()
            ->where('collection', 'featured')
            ->where('variant', 'main')
            ->first();

        return $featuredImage?->image_url ?? asset('images/default-og-image.png');
    }

    private function ogTypeFor(Content $content): string
    {
        return match ($content->type) {
            'blog' => 'article',
            'portfolio', 'services' => 'article',
            default => 'website',
        };
    }

    private function generateStructuredData(Content $content, string $canonicalUrl): array
    {
        $company = ForefrontSeederContent::COMPANY;

        $base = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $content->title,
            'description' => Str::limit(strip_tags($content->excerpt ?? ''), 300),
            'url' => $canonicalUrl,
            'datePublished' => $content->published_at?->toIso8601String(),
            'dateModified' => ($content->updated_at ?? $content->published_at)?->toIso8601String(),
            'inLanguage' => 'en-KE',
            'author' => ['@type' => 'Organization', 'name' => $company],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $company,
                'logo' => ['@type' => 'ImageObject', 'url' => asset('images/logo.png')],
            ],
        ];

        return match ($content->type) {
            'portfolio' => array_merge($base, ['@type' => 'CreativeWork', 'genre' => 'Digital Portfolio']),
            'services'  => array_merge($base, [
                '@type' => 'Service',
                'serviceType' => $content->title,
                'provider' => ['@type' => 'Organization', 'name' => $company, 'url' => url('/')],
                'areaServed' => ['KE', 'East Africa'],
            ]),
            'blog'      => array_merge($base, ['@type' => 'BlogPosting']),
            default     => $base,
        };
    }
}
