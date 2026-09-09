<?php

namespace App\Enums;

enum ContentType: string
{
    case Portfolio = 'portfolio';
    case Services = 'services';
    case Page = 'page';
    case About = 'about';
    case Mission = 'mission';
    case Vision = 'vision';
    case Intro = 'intro';
    case Blog = 'blog';
    case TimelineItem = 'timeline_item';
    case FaqItem = 'faq_item';

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * Admin labels keyed by type value (single source of truth for Content::types()).
     *
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            self::Portfolio->value => 'Portfolio Item',
            self::Services->value => 'Services',
            self::Page->value => 'Page',
            self::About->value => 'About',
            self::Mission->value => 'Mission',
            self::Vision->value => 'Vision',
            self::Intro->value => 'Intro',
            self::Blog->value => 'Blog',
            self::TimelineItem->value => 'Timeline Item',
            self::FaqItem->value => 'FAQ Item',
        ];
    }

    /**
     * Fragment types are embedded on hub pages — no public detail URL.
     */
    public function isFragment(): bool
    {
        return in_array($this, [self::TimelineItem, self::FaqItem], true);
    }

    /**
     * Public detail path for slug-change redirects, or null for fragments.
     */
    public function detailPath(string $slug): ?string
    {
        if ($this->isFragment()) {
            return null;
        }

        $slug = ltrim($slug, '/');

        return match ($this) {
            self::Portfolio => '/portfolio/'.$slug,
            self::Services => '/services/'.$slug,
            self::Blog => '/insights/'.$slug,
            self::Page => '/page/'.$slug,
            default => '/'.$slug,
        };
    }

    /**
     * Resolve a stored type string to a redirect path, if applicable.
     */
    public static function redirectPathFor(string $type, string $slug): ?string
    {
        $enum = self::tryFrom($type);

        return $enum?->detailPath($slug);
    }
}
