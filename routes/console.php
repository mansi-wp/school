<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Nightly fee defaulter & reminder alert jobs
Schedule::command('fees:calculate-late-fees')->dailyAt('00:01');
Schedule::command('notifications:dispatch-reminders')->dailyAt('09:00');
