<?php

namespace App\Enums;

enum ContentType: string
{
    case Portfolio = 'portfolio';
    case Services  = 'services';
    case Page      = 'page';
    case About     = 'about';
    case Mission   = 'mission';
    case Vision    = 'vision';
    case Intro     = 'intro';
    case Blog      = 'blog';

    public static function values(): array
    {
        return array_map(fn(self $c) => $c->value, self::cases());
    }
}
