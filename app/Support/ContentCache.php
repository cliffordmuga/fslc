<?php

namespace App\Support;

class ContentCache
{
    public static function bust(): void
    {
        \App\Services\CacheBuster::bump();
    }
}