<?php

use Mchev\Banhammer\Banhammer;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Banhammer::unbanExpired();
})->daily();

Schedule::command('otp:clean')->daily();

