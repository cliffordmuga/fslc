<?php

namespace Tests\Unit;

use App\Models\Content;
use App\Models\Image;
use Tests\TestCase;

class ImageCacheKeysTest extends TestCase
{
    public function test_image_cache_keys_include_image_service_lookup_keys(): void
    {
        $content = new Content(['id' => 42]);
        $image = new Image([
            'id' => 7,
            'imageable_type' => Content::class,
            'imageable_id' => 42,
            'image_url' => 'uploads/featured/hero.webp',
            'collection' => 'featured',
        ]);

        $keys = $image->getCacheKeys();
        $disk = config('image.disk', 'public_uploads');

        $this->assertContains('image_url_'.$disk.'_'.md5('uploads/featured/hero.webp'), $keys);
        $this->assertContains('img_variants:'.md5(Content::class.':42:featured'), $keys);
    }
}
