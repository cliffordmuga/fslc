<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Database\Seeders\Support\ForefrontSeederContent;
use Illuminate\Database\Seeder;

class TestimonialsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ForefrontSeederContent::testimonials() as $testimonial) {
            Testimonial::updateOrCreate(
                ['client_name' => $testimonial['client_name']],
                $testimonial
            );
        }
    }
}
