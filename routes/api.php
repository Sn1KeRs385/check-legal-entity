<?php

use Illuminate\Support\Facades\Route;

Route::prefix('physical-persons')
    ->name('physicalPersons')
    ->group(function(){
        Route::get('/', \App\Http\Controllers\Api\PhysicalPerson\PaginatedController::class);
        Route::post('/', \App\Http\Controllers\Api\PhysicalPerson\CreateController::class);
        Route::delete('/mass-delete', \App\Http\Controllers\Api\PhysicalPerson\MassDeleteController::class);
        Route::post('/organizations-parse', \App\Http\Controllers\Api\PhysicalPerson\OrganizationsParseController::class);

        Route::prefix('{id}')
            ->name('byId')
            ->group(function(){
                Route::get('/', \App\Http\Controllers\Api\PhysicalPerson\FindController::class);
                Route::put('/', \App\Http\Controllers\Api\PhysicalPerson\UpdateController::class);
                Route::delete('/', \App\Http\Controllers\Api\PhysicalPerson\DeleteController::class);
            });
    });
