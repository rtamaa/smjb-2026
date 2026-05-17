<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Schedule untuk reminders (dijalankan setiap menit)
Schedule::command('reminders:send')->everyMinute();

// Command inspire (biarkan seperti ini)
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');