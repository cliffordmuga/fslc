<?php

namespace Database\Seeders;

use App\Models\Redirect;
use Database\Seeders\Support\ForefrontSeederContent;
use Illuminate\Database\Seeder;

class RedirectsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ForefrontSeederContent::redirects() as $redirect) {
            Redirect::updateOrCreate(
                ['old_path' => $redirect['old_path']],
                [
                    'new_path' => $redirect['new_path'],
                    'status_code' => $redirect['status_code'] ?? 301,
                    'is_active' => true,
                ]
            );
        }
    }
}
