<?php

namespace App\Services;

use App\Models\Content;

/**
 * SEO scoring based on established best practices.
 * Returns 0-100 score and actionable suggestions.
 */
class SeoScoreService
{
    /** Meta title ideal: 50-60 chars (Google displays ~50-60) */
    public const META_TITLE_MIN = 30;
    public const META_TITLE_IDEAL_MIN = 50;
    public const META_TITLE_IDEAL_MAX = 60;
    public const META_TITLE_MAX = 70;

    /** Meta description ideal: 150-160 chars */
    public const META_DESC_MIN = 120;
    public const META_DESC_IDEAL_MIN = 150;
    public const META_DESC_IDEAL_MAX = 160;
    public const META_DESC_MAX = 165;

    /** Title ideal: 30-60 chars */
    public const TITLE_MIN = 10;
    public const TITLE_IDEAL_MAX = 70;

    /** Excerpt ideal: 120-160 chars */
    public const EXCERPT_IDEAL_MIN = 120;
    public const EXCERPT_IDEAL_MAX = 160;

    /** Minimum content length (words) for blog/portfolio */
    public const CONTENT_MIN_WORDS = 300;

    public function scoreContent(Content $content): array
    {
        $metaTitle = $content->seoMetadata?->meta_title ?? $content->title ?? '';
        $metaDesc = $content->seoMetadata?->meta_description ?? $content->excerpt ?? '';
        $excerpt = $content->excerpt ?? '';
        $contentText = strip_tags($content->content ?? '');
        $wordCount = str_word_count($contentText);

        $checks = [];
        $totalWeight = 0;
        $earned = 0;

        // Meta title (25 pts)
        $w = 25;
        $totalWeight += $w;
        $len = mb_strlen($metaTitle);
        if ($len >= self::META_TITLE_IDEAL_MIN && $len <= self::META_TITLE_IDEAL_MAX) {
            $checks[] = ['pass' => true, 'msg' => "Meta title length ({$len} chars) is ideal (50-60).", 'pts' => $w];
            $earned += $w;
        } elseif ($len >= self::META_TITLE_MIN && $len <= self::META_TITLE_MAX) {
            $checks[] = ['pass' => true, 'msg' => "Meta title length ({$len} chars) is acceptable.", 'pts' => $w * 0.7];
            $earned += $w * 0.7;
        } elseif ($len > 0) {
            $sug = $len < self::META_TITLE_MIN ? "Add more (aim for 50-60 chars)" : "Shorten to 50-60 chars for best display";
            $checks[] = ['pass' => false, 'msg' => "Meta title: {$sug} ({$len} chars).", 'pts' => 0];
        } else {
            $checks[] = ['pass' => false, 'msg' => 'Meta title is empty (auto-generated if blank).', 'pts' => 0];
        }

        // Meta description (25 pts)
        $w = 25;
        $totalWeight += $w;
        $len = mb_strlen($metaDesc);
        if ($len >= self::META_DESC_IDEAL_MIN && $len <= self::META_DESC_IDEAL_MAX) {
            $checks[] = ['pass' => true, 'msg' => "Meta description ({$len} chars) is ideal (150-160).", 'pts' => $w];
            $earned += $w;
        } elseif ($len >= self::META_DESC_MIN && $len <= self::META_DESC_MAX) {
            $checks[] = ['pass' => true, 'msg' => "Meta description ({$len} chars) is good.", 'pts' => $w * 0.8];
            $earned += $w * 0.8;
        } elseif ($len > 0) {
            $sug = $len < self::META_DESC_MIN ? "Extend to 150-160 chars for better CTR" : "Shorten to ~160 chars";
            $checks[] = ['pass' => false, 'msg' => "Meta description: {$sug} ({$len} chars).", 'pts' => 0];
        } else {
            $checks[] = ['pass' => false, 'msg' => 'Meta description is empty.', 'pts' => 0];
        }

        // Excerpt (15 pts)
        $w = 15;
        $totalWeight += $w;
        $exLen = mb_strlen($excerpt);
        if ($exLen >= self::EXCERPT_IDEAL_MIN && $exLen <= self::EXCERPT_IDEAL_MAX) {
            $checks[] = ['pass' => true, 'msg' => "Excerpt ({$exLen} chars) is ideal.", 'pts' => $w];
            $earned += $w;
        } elseif ($exLen > 0) {
            $checks[] = ['pass' => true, 'msg' => "Excerpt present ({$exLen} chars). Aim for 120-160 for listings.", 'pts' => $w * 0.6];
            $earned += $w * 0.6;
        } else {
            $checks[] = ['pass' => false, 'msg' => 'Add an excerpt for listings and meta fallback.', 'pts' => 0];
        }

        // Content length (15 pts)
        $w = 15;
        $totalWeight += $w;
        if ($wordCount >= self::CONTENT_MIN_WORDS) {
            $checks[] = ['pass' => true, 'msg' => "Content length ({$wordCount} words) is good.", 'pts' => $w];
            $earned += $w;
        } elseif ($wordCount >= 150) {
            $checks[] = ['pass' => true, 'msg' => "Content has {$wordCount} words. 300+ is ideal for SEO.", 'pts' => $w * 0.5];
            $earned += $w * 0.5;
        } else {
            $checks[] = ['pass' => false, 'msg' => "Content is short ({$wordCount} words). Aim for 300+ for blog/portfolio.", 'pts' => 0];
        }

        // Slug (10 pts)
        $w = 10;
        $totalWeight += $w;
        $slug = $content->slug ?? '';
        $slugOk = preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug) && mb_strlen($slug) >= 3 && mb_strlen($slug) <= 80;
        if ($slugOk) {
            $checks[] = ['pass' => true, 'msg' => "Slug is URL-friendly.", 'pts' => $w];
            $earned += $w;
        } elseif (!empty($slug)) {
            $checks[] = ['pass' => false, 'msg' => 'Use lowercase, hyphens, no spaces in slug.', 'pts' => 0];
        } else {
            $checks[] = ['pass' => false, 'msg' => 'Slug is empty (auto-generated from title).', 'pts' => 0];
        }

        // Images with alt (10 pts)
        $w = 10;
        $totalWeight += $w;
        $images = $content->images ?? collect();
        $withAlt = $images->filter(fn ($i) => !empty(trim($i->alt_text ?? '')))->count();
        $total = $images->count();
        if ($total === 0) {
            $checks[] = ['pass' => true, 'msg' => 'No images (N/A for alt text).', 'pts' => $w];
            $earned += $w;
        } elseif ($withAlt >= $total) {
            $checks[] = ['pass' => true, 'msg' => "All {$total} image(s) have alt text.", 'pts' => $w];
            $earned += $w;
        } elseif ($withAlt > 0) {
            $checks[] = ['pass' => false, 'msg' => "{$withAlt}/{$total} images have alt text. Add alt for accessibility.", 'pts' => $w * 0.5];
            $earned += $w * 0.5;
        } else {
            $checks[] = ['pass' => false, 'msg' => "Images missing alt text. Add for accessibility and SEO.", 'pts' => 0];
        }

        $score = $totalWeight > 0 ? (int) round(($earned / $totalWeight) * 100) : 0;
        $score = min(100, max(0, $score));

        return [
            'score' => $score,
            'grade' => $this->gradeFromScore($score),
            'checks' => $checks,
            'suggestions' => array_filter($checks, fn ($c) => !$c['pass']),
        ];
    }

    /** Score from raw form data (for API or inline use) */
    public function scoreFromData(array $data): array
    {
        $metaTitle = $data['meta_title'] ?? $data['title'] ?? '';
        $metaDesc = $data['meta_description'] ?? $data['excerpt'] ?? '';
        $excerpt = $data['excerpt'] ?? '';
        $contentText = strip_tags($data['content'] ?? '');
        $wordCount = str_word_count($contentText);
        $slug = $data['slug'] ?? '';
        $imageAlts = $data['image_alts'] ?? [];
        $altValues = array_map('trim', is_array($imageAlts) ? $imageAlts : []);
        $hasImagesWithoutAlt = !empty($altValues) && in_array('', $altValues);

        $earned = 0;
        $checks = [];

        $mtLen = mb_strlen($metaTitle);
        $mtPts = ($mtLen >= self::META_TITLE_IDEAL_MIN && $mtLen <= self::META_TITLE_IDEAL_MAX) ? 25 : (($mtLen >= self::META_TITLE_MIN && $mtLen <= self::META_TITLE_MAX) ? 17 : 0);
        $earned += $mtPts;
        $checks[] = ['pass' => $mtLen >= self::META_TITLE_MIN, 'msg' => "Meta title: {$mtLen}/60 chars", 'pts' => $mtPts];

        $mdLen = mb_strlen($metaDesc);
        $mdPts = ($mdLen >= self::META_DESC_IDEAL_MIN && $mdLen <= self::META_DESC_IDEAL_MAX) ? 25 : (($mdLen >= self::META_DESC_MIN && $mdLen <= self::META_DESC_MAX) ? 20 : 0);
        $earned += $mdPts;
        $checks[] = ['pass' => $mdLen >= self::META_DESC_MIN, 'msg' => "Meta description: {$mdLen}/160 chars", 'pts' => $mdPts];

        $exLen = mb_strlen($excerpt);
        $exPts = $exLen >= self::EXCERPT_IDEAL_MIN ? 15 : ($exLen > 0 ? 9 : 0);
        $earned += $exPts;
        $checks[] = ['pass' => $exLen > 0, 'msg' => "Excerpt: {$exLen} chars", 'pts' => $exPts];

        $wordPts = $wordCount >= 300 ? 15 : ($wordCount >= 150 ? 7 : 0);
        $earned += $wordPts;
        $checks[] = ['pass' => $wordCount >= 150, 'msg' => "Content: {$wordCount} words", 'pts' => $wordPts];

        $slugOk = preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug) && mb_strlen($slug) >= 3;
        $slugPts = $slugOk ? 10 : 0;
        $earned += $slugPts;
        $checks[] = ['pass' => $slugOk, 'msg' => 'Slug is URL-friendly', 'pts' => $slugPts];

        $altPts = $hasImagesWithoutAlt ? 5 : 10;
        $earned += $altPts;
        $checks[] = ['pass' => !$hasImagesWithoutAlt || empty($altValues), 'msg' => 'Images have alt text', 'pts' => $altPts];

        $score = min(100, (int) round($earned));
        return [
            'score' => $score,
            'grade' => $this->gradeFromScore($score),
            'checks' => $checks,
            'suggestions' => array_filter($checks, fn ($c) => !$c['pass']),
        ];
    }

    protected function gradeFromScore(int $score): string
    {
        return match (true) {
            $score >= 90 => 'A',
            $score >= 80 => 'B',
            $score >= 70 => 'C',
            $score >= 60 => 'D',
            default => 'F',
        };
    }

    /** Return client-side scoring rules for Alpine/JS */
    public static function clientRules(): array
    {
        return [
            'metaTitleMin' => self::META_TITLE_MIN,
            'metaTitleIdeal' => [self::META_TITLE_IDEAL_MIN, self::META_TITLE_IDEAL_MAX],
            'metaDescMin' => self::META_DESC_MIN,
            'metaDescIdeal' => [self::META_DESC_IDEAL_MIN, self::META_DESC_IDEAL_MAX],
            'excerptIdeal' => [self::EXCERPT_IDEAL_MIN, self::EXCERPT_IDEAL_MAX],
            'contentMinWords' => self::CONTENT_MIN_WORDS,
        ];
    }
}
