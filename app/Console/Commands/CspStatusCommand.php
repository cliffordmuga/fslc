<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CspStatusCommand extends Command
{
    protected $signature = 'csp:status {--days=7 : Look back this many days for violation counts}';

    protected $description = 'Show CSP mode and recent violation report counts';

    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));
        $enforce = (bool) config('security.csp_enforce', false);
        $reporting = (bool) config('security.csp_report_enabled', true);

        $this->info('CSP enforce: ' . ($enforce ? 'ON (blocking)' : 'OFF (report-only)'));
        $this->info('CSP reporting: ' . ($reporting ? 'enabled' : 'disabled'));

        $total = 0;
        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($i)->toDateString();
            $count = (int) Cache::get("csp:violations:{$date}", 0);
            if ($count > 0) {
                $this->line("  {$date}: {$count} unique violation(s)");
            }
            $total += $count;
        }

        $this->newLine();
        $this->info("Unique violations (last {$days} days): {$total}");

        if (! $enforce && $total === 0) {
            $this->comment('No recent violations recorded — safe to set SECURITY_CSP_ENFORCE=true in production.');
        } elseif (! $enforce && $total > 0) {
            $this->warn('Resolve violations in storage/logs before enabling SECURITY_CSP_ENFORCE=true.');
        }

        return self::SUCCESS;
    }
}
