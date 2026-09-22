<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
| VPS: crontab still needs `* * * * * php /path/to/artisan schedule:run` for
| the jobs below. Queue processing itself runs as a persistent worker under
| systemd (see deploy/systemd/forefront-queue.service), not on this schedule.
*/

Schedule::command('sitemap:generate')->daily()->at('03:00');

Schedule::command('lead-events:prune')->weekly()->sundays()->at('02:30');

// Reclaim expired page-cache rows the database store never evicts on its own.
Schedule::command('cache:prune-stale')->hourly();
