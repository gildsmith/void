<?php

declare(strict_types=1);

use Gildsmith\Auth\Controllers\Auth\LoginController;
use Gildsmith\Auth\Controllers\Auth\LogoutController;
use Gildsmith\Auth\Controllers\Auth\LogoutEverywhereController;
use Gildsmith\Auth\Controllers\Auth\MeController;
use Gildsmith\Auth\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', RegisterController::class);
Route::post('/auth/login', LoginController::class);
Route::get('/auth/me', MeController::class)->middleware('auth:sanctum');
Route::post('/auth/logout', LogoutController::class)->middleware('auth:sanctum');
Route::post('/auth/logout-everywhere', LogoutEverywhereController::class)->middleware('auth:sanctum');

// TODO: Add email verification routes.
// TODO: Add password reset routes.
