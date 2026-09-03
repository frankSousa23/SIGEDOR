<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Respaldo institucional diario de base de datos
Schedule::command('backup:clean')->dailyAt('01:00');
Schedule::command('backup:run --only-db')->dailyAt('02:00');

// Mantenimiento y depuración semanal de logs antiguos en Laravel Telescope
Schedule::command('telescope:prune --hours=72')->weekly();
