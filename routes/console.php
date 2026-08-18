<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
|
| Process queued jobs (sponsorship notifications, etc.) every minute.
| Uses --stop-when-empty so the process exits once the queue is drained,
| which is ideal for shared hosting where a persistent worker isn't viable.
|
*/

Schedule::command('queue:work --stop-when-empty --tries=3 --timeout=60')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

// Check for expiring gift sponsorships daily at 8am
Schedule::command('sponsorships:check-expiring')->dailyAt('08:00');
