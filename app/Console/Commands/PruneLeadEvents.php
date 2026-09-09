<?php

namespace App\Console\Commands;

use App\Models\LeadEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class PruneLeadEvents extends Command
{
    protected $signature = 'lead-events:prune {--days= : Retention window in days (default: config forefront.lead_events.retention_days)}';

    protected $description = 'Delete lead_events rows older than the configured retention window';

    public function handle(): int
    {
        $days = (int) ($this->option('days') ?: config('forefront.lead_events.retention_days', 90));

        if ($days < 1) {
            $this->error('Retention window must be at least 1 day.');

            return self::FAILURE;
        }

        $cutoff = now()->subDays($days);
        $deleted = 0;

        LeadEvent::query()
            ->where('created_at', '<', $cutoff)
            ->select('id')
            ->chunkById(1000, function (Collection $rows) use (&$deleted): void {
                $deleted += LeadEvent::whereIn('id', $rows->pluck('id'))->delete();
            });

        $this->info("Pruned {$deleted} lead event(s) older than {$days} day(s) (before {$cutoff->toDateTimeString()}).");

        return self::SUCCESS;
    }
}
