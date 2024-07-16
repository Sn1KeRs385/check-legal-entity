<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::job(App\Jobs\CheckNewLegalEntitiesJob::class)
    ->timezone('Europe/Moscow')
    ->dailyAt('23:00');
