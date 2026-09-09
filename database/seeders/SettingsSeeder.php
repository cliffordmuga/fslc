<?php

namespace Database\Seeders;

use App\Models\Setting;
use Database\Seeders\Support\ForefrontSeederContent;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = array_merge(ForefrontSeederContent::settings(), [
            ['key' => 'facebook_url', 'value' => '', 'type' => 'text', 'category' => 'social'],
            ['key' => 'twitter_url', 'value' => '', 'type' => 'text', 'category' => 'social'],
            ['key' => 'linkedin_url', 'value' => '', 'type' => 'text', 'category' => 'social'],
            ['key' => 'instagram_url', 'value' => '', 'type' => 'text', 'category' => 'social'],
            ['key' => 'google_analytics_id', 'value' => '', 'type' => 'text', 'category' => 'seo'],
            ['key' => 'google_tagmanager_id', 'value' => '', 'type' => 'text', 'category' => 'seo'],
            ['key' => 'smtp_host', 'value' => '', 'type' => 'text', 'category' => 'email'],
            ['key' => 'smtp_port', 'value' => '587', 'type' => 'integer', 'category' => 'email'],
            ['key' => 'active_theme', 'value' => 'default', 'type' => 'text', 'category' => 'theme'],
            ['key' => 'primary_color', 'value' => '#0ea5e9', 'type' => 'text', 'category' => 'theme'],
            ['key' => 'secondary_color', 'value' => '#64748B', 'type' => 'text', 'category' => 'theme'],
            ['key' => 'auto_respond_to_leads', 'value' => 'true', 'type' => 'boolean', 'category' => 'leads'],
            ['key' => 'lead_follow_up_days', 'value' => '3', 'type' => 'integer', 'category' => 'leads'],
        ]);

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
