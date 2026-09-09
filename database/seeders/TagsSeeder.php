<?php

namespace Database\Seeders;

use App\Models\Tag;
use Database\Seeders\Support\ForefrontSeederContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagsSeeder extends Seeder
{
    public function run(): void
    {
        $intros = ForefrontSeederContent::tagHubIntros();

        foreach (ForefrontSeederContent::tags() as $tagName) {
            $slug = Str::slug($tagName);
            $intro = $intros[$slug] ?? null;

            Tag::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $tagName,
                    'hub_intro' => $intro,
                    'meta_description' => $intro ? Str::limit($intro, 160, '') : null,
                ]
            );
        }
    }
}
