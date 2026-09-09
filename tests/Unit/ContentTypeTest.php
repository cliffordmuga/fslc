<?php

namespace Tests\Unit;

use App\Enums\ContentType;
use App\Models\Content;
use Tests\TestCase;

class ContentTypeTest extends TestCase
{
    public function test_labels_include_fragment_types(): void
    {
        $labels = ContentType::labels();

        $this->assertArrayHasKey('timeline_item', $labels);
        $this->assertArrayHasKey('faq_item', $labels);
        $this->assertSame($labels, Content::typeLabels());
    }

    public function test_fragment_types_have_no_detail_path(): void
    {
        $this->assertNull(ContentType::TimelineItem->detailPath('2015-founded'));
        $this->assertNull(ContentType::FaqItem->detailPath('how-long'));
        $this->assertNull(ContentType::redirectPathFor('timeline_item', '2015-founded'));
    }

    public function test_public_types_resolve_detail_paths(): void
    {
        $this->assertSame('/portfolio/county-hmis', ContentType::Portfolio->detailPath('county-hmis'));
        $this->assertSame('/insights/sha-guide', ContentType::Blog->detailPath('sha-guide'));
        $this->assertSame('/page/privacy-policy', ContentType::Page->detailPath('privacy-policy'));
    }
}
