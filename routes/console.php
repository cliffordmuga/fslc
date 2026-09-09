<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
| Shared hosting: add to crontab — * * * * * php /path/to/artisan schedule:run
| Processes queued mail (ContactReceived) when QUEUE_CONNECTION=database.
*/
Schedule::command('queue:work --stop-when-empty --max-time=45')->everyMinute()->withoutOverlapping();

Schedule::command('sitemap:generate')->daily()->at('03:00');

Schedule::command('lead-events:prune')->weekly()->sundays()->at('02:30');
