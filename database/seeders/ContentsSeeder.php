<?php



namespace Database\Seeders;



use App\Models\Content;

use App\Models\SeoMetadata;

use App\Models\Tag;

use App\Models\User;

use Database\Seeders\Support\ForefrontSeederContent;

use Illuminate\Database\Seeder;



class ContentsSeeder extends Seeder

{

    public function run(): void

    {

        $adminUser = User::where('role', 'admin')->first();



        if (! $adminUser) {

            $this->command->error('No admin user found. Run AdminUserSeeder first.');



            return;

        }



        $sortOrder = 1;



        Content::updateOrCreate(

            ['slug' => 'welcome'],

            [

                'title' => 'Forefront Solutions — Healthcare Technology & Digital Transformation',

                'type' => 'intro',

                'content' => ForefrontSeederContent::resolveLinks(ForefrontSeederContent::introHtml()),

                'excerpt' => ForefrontSeederContent::POSITIONING,

                'status' => 'published',

                'published_at' => now(),

                'sort_order' => $sortOrder++,

                'created_by' => $adminUser->id,

            ]

        );



        Content::updateOrCreate(

            ['slug' => 'about-us'],

            [

                'title' => 'About Forefront Solutions (K) Ltd',

                'type' => 'about',

                'content' => ForefrontSeederContent::resolveLinks(ForefrontSeederContent::aboutHtml()),

                'excerpt' => 'Since 2015, Forefront Solutions has specialized in HMIS, custom software, digital communications, and public engagement for healthcare, government, NGO, and political clients in Kenya.',

                'status' => 'published',

                'published_at' => now(),

                'sort_order' => 1,

                'created_by' => $adminUser->id,

            ]

        );



        Content::updateOrCreate(

            ['slug' => 'our-mission'],

            [

                'title' => 'Our Mission',

                'type' => 'mission',

                'content' => ForefrontSeederContent::resolveLinks(ForefrontSeederContent::missionHtml()),

                'excerpt' => 'Empower Kenyan healthcare and institutions with HMIS, digital health, software engineering, and strategic communications.',

                'status' => 'published',

                'published_at' => now(),

                'sort_order' => 1,

                'created_by' => $adminUser->id,

            ]

        );



        Content::updateOrCreate(

            ['slug' => 'our-vision'],

            [

                'title' => 'Our Vision',

                'type' => 'vision',

                'content' => ForefrontSeederContent::resolveLinks(ForefrontSeederContent::visionHtml()),

                'excerpt' => 'To be East Africa\'s most trusted HMIS, digital transformation, and strategic communications partner.',

                'status' => 'published',

                'published_at' => now(),

                'sort_order' => 1,

                'created_by' => $adminUser->id,

            ]

        );



        $leadMagnet = ForefrontSeederContent::leadMagnetPage();

        Content::updateOrCreate(

            ['slug' => $leadMagnet['slug']],

            [

                'title' => $leadMagnet['title'],

                'type' => 'page',

                'content' => ForefrontSeederContent::resolveLinks($leadMagnet['content']),

                'excerpt' => $leadMagnet['excerpt'],

                'status' => 'published',

                'published_at' => now(),

                'sort_order' => 1,

                'created_by' => $adminUser->id,

            ]

        );



        foreach (ForefrontSeederContent::services() as $index => $item) {

            $service = Content::updateOrCreate(

                ['slug' => $item['slug']],

                [

                    'title' => $item['title'],

                    'type' => 'services',

                    'content' => ForefrontSeederContent::resolveLinks($item['content']),

                    'excerpt' => $item['excerpt'],

                    'status' => 'published',

                    'published_at' => now()->subDays(30 + $index),

                    'sort_order' => $index + 1,

                    'created_by' => $adminUser->id,

                ]

            );



            $this->syncTags($service, $item['tags'] ?? []);

        }



        foreach (ForefrontSeederContent::portfolio() as $index => $item) {

            $portfolio = Content::updateOrCreate(

                ['slug' => $item['slug']],

                [

                    'title' => $item['title'],

                    'type' => 'portfolio',

                    'content' => ForefrontSeederContent::resolveLinks($item['content']),

                    'excerpt' => $item['excerpt'],

                    'status' => 'published',

                    'published_at' => now()->subDays(45 + $index * 7),

                    'sort_order' => $index + 1,

                    'created_by' => $adminUser->id,

                ]

            );



            $this->syncTags($portfolio, $item['tags'] ?? []);

        }



        foreach (ForefrontSeederContent::blogPosts() as $index => $item) {

            $post = Content::updateOrCreate(

                ['slug' => $item['slug']],

                [

                    'title' => $item['title'],

                    'type' => 'blog',

                    'content' => ForefrontSeederContent::resolveLinks($item['content']),

                    'excerpt' => $item['excerpt'],

                    'status' => 'published',

                    'published_at' => now()->subDays(14 + $index * 10),

                    'sort_order' => $index + 1,

                    'created_by' => $adminUser->id,

                ]

            );



            $this->syncTags($post, $item['tags'] ?? []);

        }



        foreach (ForefrontSeederContent::timeline() as $i => $t) {

            Content::updateOrCreate(

                ['type' => 'timeline_item', 'slug' => 'timeline-' . ($i + 1)],

                [

                    'title' => $t['title'],

                    'excerpt' => $t['year'],

                    'content' => $t['description'],

                    'status' => 'published',

                    'published_at' => now(),

                    'sort_order' => $i + 1,

                    'created_by' => $adminUser->id,

                ]

            );

        }



        foreach (ForefrontSeederContent::faqs() as $i => $f) {

            Content::updateOrCreate(

                ['type' => 'faq_item', 'slug' => 'faq-' . ($i + 1)],

                [

                    'title' => $f['question'],

                    'content' => $f['answer'],

                    'status' => 'published',

                    'published_at' => now(),

                    'sort_order' => $i + 1,

                    'created_by' => $adminUser->id,

                ]

            );

        }



        $this->unpublishLegacyContent();

        $this->removeLegacySeoMetadata();

    }



    private function unpublishLegacyContent(): void

    {

        Content::query()

            ->whereIn('slug', ForefrontSeederContent::legacySlugsToUnpublish())

            ->update(['status' => 'draft']);

    }



    private function removeLegacySeoMetadata(): void

    {

        $legacyIds = Content::query()

            ->whereIn('slug', ForefrontSeederContent::legacySlugsToUnpublish())

            ->pluck('id');



        if ($legacyIds->isEmpty()) {

            return;

        }



        SeoMetadata::query()

            ->where('seoable_type', Content::class)

            ->whereIn('seoable_id', $legacyIds)

            ->delete();

    }



    private function syncTags(Content $content, array $tagNames): void

    {

        if ($tagNames === []) {

            return;

        }



        $ids = Tag::query()

            ->whereIn('name', $tagNames)

            ->pluck('id');



        $content->tags()->sync($ids);

    }

}


