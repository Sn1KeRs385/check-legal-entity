<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\Web\PhysicalPerson\IndexController::class)->name('index');
