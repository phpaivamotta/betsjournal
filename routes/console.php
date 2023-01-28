<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\EmailValueBetsService;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('logs:clear', function() {
    // deletes storage/logs/laravel.log file
    // note: -f is added to prevent "No such file or directory message"
    exec('rm -f ' . storage_path('logs/*.log'));

    // this is for npm, composer, etc logs in root folder
    // exec('rm -f ' . base_path('*.log'));

    // console message
    $this->comment('Logs have been cleared!');

})->describe('Clear log files');

Artisan::command('valuebetsemails:send', function () {
    // send emails
    (new EmailValueBetsService)->sendValueBetsEmail();

    // console message
    $this->comment('Value bets emails were sent!');

})->purpose('Send value bets email to subscribers');
