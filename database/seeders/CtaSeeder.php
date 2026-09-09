<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Cta;
use Illuminate\Database\Seeder;

class CtaSeeder extends Seeder
{
    public function run(): void
    {
        $serviceCtas = [
            'hmis-digital-health-solutions-kenya' => [
                ['text' => 'Request HMIS Demo', 'type' => 'primary', 'action' => '/contact?inquiry_type=hmis-demo#contact-form'],
                ['text' => 'Download HMIS Checklist', 'type' => 'secondary', 'action' => '/page/hmis-procurement-checklist'],
            ],
            'custom-software-web-development-kenya' => [
                ['text' => 'Get Project Quote', 'type' => 'primary', 'action' => '/contact?inquiry_type=software-quote#contact-form'],
                ['text' => 'View Software Projects', 'type' => 'secondary', 'action' => '/portfolio?pillar=software'],
            ],
            'digital-strategy-branding-marketing-kenya' => [
                ['text' => 'Brand Consultation', 'type' => 'primary', 'action' => '/contact?inquiry_type=marketing-consult#contact-form'],
            ],
            'political-public-engagement-kenya' => [
                ['text' => 'Campaign Strategy Session', 'type' => 'primary', 'action' => '/contact?inquiry_type=campaign-strategy#contact-form'],
                ['text' => 'View Campaign Work', 'type' => 'secondary', 'action' => '/portfolio?pillar=political'],
            ],
        ];

        $services = Content::query()
            ->where('type', 'services')
            ->where('status', 'published')
            ->get()
            ->keyBy('slug');

        foreach ($serviceCtas as $slug => $ctas) {
            $content = $services->get($slug);
            if (! $content) {
                continue;
            }

            foreach ($ctas as $index => $ctaData) {
                Cta::updateOrCreate(
                    [
                        'content_id' => $content->id,
                        'text' => $ctaData['text'],
                    ],
                    [
                        'type' => $ctaData['type'],
                        'action' => $ctaData['action'],
                        'priority' => 10 - $index,
                    ]
                );
            }
        }

        $globalCtas = [
            [
                'text' => 'Request HMIS Demo',
                'type' => 'primary',
                'action' => '/contact?inquiry_type=hmis-demo#contact-form',
                'priority' => 10,
            ],
            [
                'text' => 'HMIS Procurement Checklist',
                'type' => 'secondary',
                'action' => '/page/hmis-procurement-checklist',
                'priority' => 8,
            ],
        ];

        foreach ($globalCtas as $cta) {
            Cta::updateOrCreate(
                [
                    'text' => $cta['text'],
                    'type' => $cta['type'],
                    'content_id' => null,
                ],
                [
                    'action' => $cta['action'],
                    'priority' => $cta['priority'],
                ]
            );
        }

        $fallbackTemplates = [
            ['text' => 'Contact Us', 'type' => 'primary', 'action' => '/contact#contact-form'],
            ['text' => 'View Services', 'type' => 'secondary', 'action' => '/services'],
        ];

        $otherContents = Content::query()
            ->where('status', 'published')
            ->whereNotIn('type', ['services'])
            ->get();

        foreach ($otherContents as $content) {
            if ($content->ctas()->exists()) {
                continue;
            }

            foreach ($fallbackTemplates as $index => $ctaData) {
                Cta::firstOrCreate(
                    [
                        'content_id' => $content->id,
                        'text' => $ctaData['text'],
                    ],
                    [
                        'type' => $ctaData['type'],
                        'action' => $ctaData['action'],
                        'priority' => 5 - $index,
                    ]
                );
            }
        }
    }
}
