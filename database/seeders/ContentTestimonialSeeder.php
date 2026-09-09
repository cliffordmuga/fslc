<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Testimonial;
use Database\Seeders\Support\ForefrontSeederContent;
use Illuminate\Database\Seeder;

class ContentTestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $map = ForefrontSeederContent::testimonialContentMap();

        $testimonialsByName = Testimonial::query()
            ->where('status', 'approved')
            ->get()
            ->keyBy('client_name');

        $contents = Content::query()
            ->whereIn('type', ['portfolio', 'services'])
            ->where('status', 'published')
            ->get();

        foreach ($contents as $content) {
            $content->testimonials()->detach();

            $names = $map[$content->slug] ?? [];
            if ($names === []) {
                continue;
            }

            foreach ($names as $index => $name) {
                $testimonial = $testimonialsByName->get($name);
                if (! $testimonial) {
                    continue;
                }

                $content->testimonials()->attach($testimonial->id, ['sort_order' => $index + 1]);
            }
        }
    }
}
