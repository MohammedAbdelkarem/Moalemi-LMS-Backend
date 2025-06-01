<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Midnight
//Addons
Schedule::command('addon:discount-notifier')
    ->dailyAt('00:00')
    ->runInBackground()
    ->withoutOverlapping();
Schedule::command('addon:discount-notifier')
    ->dailyAt('00:00')
    ->runInBackground()
    ->withoutOverlapping();

//Plans
Schedule::command('plan:handle-users-plan')
    ->dailyAt('00:00')
    ->runInBackground()
    ->withoutOverlapping();
Schedule::command('plan:discount-notifier')
    ->dailyAt('00:00')
    ->runInBackground()
    ->withoutOverlapping();
Schedule::command('plan:discount-notifier')
    ->dailyAt('00:00')
    ->runInBackground()
    ->withoutOverlapping();


//User
Schedule::command('app:ban-remove')
    ->everyMinute()
    ->runInBackground()
    ->withoutOverlapping();
Schedule::command('app:delete-unverified')
    ->everySixHours()
    ->runInBackground()
    ->withoutOverlapping();

//Others
Schedule::command('app:delete-unverified')
    ->everySixHours()
    ->runInBackground()
    ->withoutOverlapping();

Schedule::command('banner:remove')
    ->everyTwoMinutes()
    ->runInBackground()
    ->withoutOverlapping();

Schedule::command('story:remove')
    ->everyTwoMinutes()
    ->runInBackground()
    ->withoutOverlapping();

Schedule::command('app:delete-otp')
    ->hourly()
    ->runInBackground()
    ->withoutOverlapping();

Schedule::command('app:delete-tokens')
    ->hourly()
    ->runInBackground()
    ->withoutOverlapping();

Schedule::command('app:payment-remover')
    ->everyTwoHours()
    ->runInBackground()
    ->withoutOverlapping();

Schedule::command('app:payment-remover')
    ->everyTwoHours()
    ->runInBackground()
    ->withoutOverlapping();
