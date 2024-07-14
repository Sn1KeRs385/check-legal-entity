<?php

use Illuminate\Support\Facades\Schedule;

Schedule::job(\App\Jobs\UpdatePhysicalPersonOrganizationsJob::class)
    ->dailyAt('18:00');
