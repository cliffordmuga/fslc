<?php

namespace App\Console\Commands;

use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Console\Command;

class BackfillImageBlurPlaceholders extends Command
{
    protected $signature = 'images:backfill-blur
                            {--force : Regenerate blur even when already set}
                            {--dry-run : Preview counts without writing}
                            {--limit= : Maximum main-variant images to process}';

    protected $description = 'Generate LQIP blur placeholders for existing CMS main-variant images';

    public function handle(ImageService $imageService): int
    {
        if (! extension_loaded('gd')) {
            $this->error('The GD extension is required. Enable it in php.ini / MultiPHP Manager.');

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $limit = $this->option('limit');

        $query = Image::query()
            ->where('variant', 'main')
            ->when(! $this->option('force'), fn ($q) => $q->whereNull('blur_placeholder'))
            ->orderBy('id');

        if ($limit !== null && $limit !== '') {
            $query->limit(max(1, (int) $limit));
        }

        $images = $query->get();
        $total = $images->count();

        if ($total === 0) {
            $this->info('No main-variant images need blur backfill.');

            return self::SUCCESS;
        }

        $this->info(($dryRun ? '[dry-run] ' : '') . "Processing {$total} image(s)...");

        $updated = 0;
        $skippedMissing = 0;
        $skippedFailed = 0;

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($images as $image) {
            if (! $imageService->imageExists($image->image_url)) {
                $skippedMissing++;
                $bar->advance();

                continue;
            }

            $blur = $imageService->blurPlaceholderFromPath($image->image_url);

            if ($blur === null) {
                $skippedFailed++;
                $bar->advance();

                continue;
            }

            if (! $dryRun) {
                $image->update(['blur_placeholder' => $blur]);
            }

            $updated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Result', 'Count'],
            [
                [$dryRun ? 'Would update' : 'Updated', $updated],
                ['Missing file on disk', $skippedMissing],
                ['Generation failed', $skippedFailed],
            ]
        );

        if ($dryRun) {
            $this->comment('Dry run only — no database changes were made.');
        }

        return self::SUCCESS;
    }
}
