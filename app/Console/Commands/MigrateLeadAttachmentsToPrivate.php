<?php

namespace App\Console\Commands;

use App\Models\Lead;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateLeadAttachmentsToPrivate extends Command
{
    protected $signature = 'leads:migrate-attachments
                            {--dry-run : Preview without moving files}';

    protected $description = 'Move legacy lead attachments from the public disk to private storage';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $leads = Lead::query()
            ->whereNotNull('attachment_path')
            ->where('attachment_path', '!=', '')
            ->orderBy('id')
            ->get();

        if ($leads->isEmpty()) {
            $this->info('No leads with attachments found.');

            return self::SUCCESS;
        }

        $migrated = 0;
        $alreadyPrivate = 0;
        $missing = 0;

        $bar = $this->output->createProgressBar($leads->count());
        $bar->start();

        foreach ($leads as $lead) {
            $path = $lead->attachment_path;

            if (Storage::disk('local')->exists($path)) {
                $alreadyPrivate++;
                $bar->advance();

                continue;
            }

            if (! Storage::disk('public')->exists($path)) {
                $missing++;
                $bar->advance();

                continue;
            }

            if (! $dryRun) {
                $contents = Storage::disk('public')->get($path);
                Storage::disk('local')->put($path, $contents);
                Storage::disk('public')->delete($path);

                if (! $lead->attachment_original_name) {
                    $lead->update([
                        'attachment_original_name' => basename($path),
                    ]);
                }
            }

            $migrated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Result', 'Count'],
            [
                [$dryRun ? 'Would migrate' : 'Migrated', $migrated],
                ['Already on private disk', $alreadyPrivate],
                ['Missing on both disks', $missing],
            ]
        );

        if ($dryRun) {
            $this->comment('Dry run only — no files were moved.');
        } elseif ($migrated > 0) {
            $this->info('Legacy files removed from storage/app/public. They are no longer web-accessible.');
        }

        return self::SUCCESS;
    }
}
