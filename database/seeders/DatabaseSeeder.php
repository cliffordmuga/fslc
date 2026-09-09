<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            AdminUserSeeder::class,
            SettingsSeeder::class,
            TagsSeeder::class,            
            TestimonialsSeeder::class,
            ContentsSeeder::class,            
            ImagesSeeder::class,
            SeoMetadataSeeder::class,
            RedirectsSeeder::class,
            ContentTestimonialSeeder::class,
            CtaSeeder::class,
            /* PortfolioSeeder::class, */
        ]);
    }
}
