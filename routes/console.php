<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Felicitaciones de cumpleaños: todos los días a las 7:00 AM
Schedule::command('cumpleanos:enviar')
    ->dailyAt('07:00')
    ->timezone('America/Guatemala')
    ->withoutOverlapping();
