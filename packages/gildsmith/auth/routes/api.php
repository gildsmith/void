<?php

declare(strict_types=1);

use Gildsmith\Auth\Controllers\CurrentSessionDeleteController;
use Gildsmith\Auth\Controllers\CurrentUserController;
use Gildsmith\Auth\Controllers\RegisterController;
use Gildsmith\Auth\Controllers\SessionCreateController;
use Gildsmith\Auth\Controllers\SessionDeleteController;
use Gildsmith\Auth\Controllers\SessionIndexController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('/register', RegisterController::class);
    Route::post('/sessions', SessionCreateController::class);

    Route::middleware('auth:gildsmith')->group(function (): void {
        Route::get('/user', CurrentUserController::class);
        Route::get('/sessions', SessionIndexController::class);
        Route::delete('/sessions/current', CurrentSessionDeleteController::class);
        Route::delete('/sessions/{session}', SessionDeleteController::class);
    });
});
