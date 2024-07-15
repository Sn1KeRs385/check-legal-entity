<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::job(App\Jobs\UpdatePhysicalPersonOrganizationsJob::class)
    ->timezone('Europe/Moscow')
    ->dailyAt('23:00');
