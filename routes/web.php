<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\PhysicalPerson\IndexController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [IndexController::class, 'index'])->name('index');
