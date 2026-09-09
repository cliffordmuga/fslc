<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Image;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MaintenanceCommandsTest extends TestCase
{
    use RefreshDatabase;

    public function test_backfill_blur_placeholders_for_main_variant_images(): void
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension required.');
        }

        Storage::fake('public_uploads');

        $relativePath = 'uploads/portfolio/1/test-main.jpg';
        $this->writeTinyJpeg(Storage::disk('public_uploads')->path($relativePath));

        $content = Content::create([
            'title' => 'Blur Test',
            'slug' => 'blur-test',
            'type' => 'portfolio',
            'excerpt' => 'Test',
            'content' => '<p>Test</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $image = Image::create([
            'imageable_type' => Content::class,
            'imageable_id' => $content->id,
            'image_url' => $relativePath,
            'variant' => 'main',
            'collection' => 'featured',
            'order' => 0,
        ]);

        $this->assertNull($image->fresh()->blur_placeholder);

        $this->artisan('images:backfill-blur')
            ->assertSuccessful();

        $blur = $image->fresh()->blur_placeholder;
        $this->assertNotNull($blur);
        $this->assertStringStartsWith('data:image/jpeg;base64,', $blur);
    }

    public function test_backfill_blur_dry_run_does_not_persist(): void
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension required.');
        }

        Storage::fake('public_uploads');

        $relativePath = 'uploads/portfolio/2/test-main.jpg';
        $this->writeTinyJpeg(Storage::disk('public_uploads')->path($relativePath));

        $content = Content::create([
            'title' => 'Blur Dry Run',
            'slug' => 'blur-dry-run',
            'type' => 'portfolio',
            'excerpt' => 'Test',
            'content' => '<p>Test</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Image::create([
            'imageable_type' => Content::class,
            'imageable_id' => $content->id,
            'image_url' => $relativePath,
            'variant' => 'main',
            'collection' => 'featured',
            'order' => 0,
        ]);

        $this->artisan('images:backfill-blur --dry-run')
            ->assertSuccessful();

        $this->assertDatabaseHas('images', [
            'image_url' => $relativePath,
            'blur_placeholder' => null,
        ]);
    }

    public function test_migrate_lead_attachments_from_public_to_private_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        Storage::disk('public')->put('leads/legacy.pdf', '%PDF-1.4 legacy');

        $lead = Lead::create([
            'name' => 'Legacy Lead',
            'email' => 'legacy@example.com',
            'message' => 'Attachment test',
            'inquiry_type' => 'general',
            'status' => 'new',
            'attachment_path' => 'leads/legacy.pdf',
            'is_spam' => false,
        ]);

        $this->artisan('leads:migrate-attachments')
            ->assertSuccessful();

        $this->assertTrue(Storage::disk('local')->exists('leads/legacy.pdf'));
        $this->assertFalse(Storage::disk('public')->exists('leads/legacy.pdf'));
        $this->assertSame('legacy.pdf', $lead->fresh()->attachment_original_name);
    }

    public function test_migrate_attachments_skips_leads_already_on_private_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        Storage::disk('local')->put('leads/current.pdf', 'private copy');
        Storage::disk('public')->put('leads/current.pdf', 'public copy');

        Lead::create([
            'name' => 'Current Lead',
            'email' => 'current@example.com',
            'message' => 'Already private',
            'inquiry_type' => 'general',
            'status' => 'new',
            'attachment_path' => 'leads/current.pdf',
            'attachment_original_name' => 'current.pdf',
            'is_spam' => false,
        ]);

        $this->artisan('leads:migrate-attachments')
            ->assertSuccessful();

        $this->assertTrue(Storage::disk('local')->exists('leads/current.pdf'));
        $this->assertSame('private copy', Storage::disk('local')->get('leads/current.pdf'));
        $this->assertTrue(Storage::disk('public')->exists('leads/current.pdf'));
    }

    private function writeTinyJpeg(string $absolutePath): void
    {
        $directory = dirname($absolutePath);
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $image = imagecreatetruecolor(40, 20);
        $color = imagecolorallocate($image, 30, 90, 180);
        imagefilledrectangle($image, 0, 0, 40, 20, $color);
        imagejpeg($image, $absolutePath, 80);
        imagedestroy($image);
    }
}
