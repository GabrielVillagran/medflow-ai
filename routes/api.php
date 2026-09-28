<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('/register', 'register')
            ->middleware('throttle:5,1');

        Route::post('login', 'login')
            ->middleware('throttle:5,1');

        Route::middleware('auth:sanctum')
            ->group(function (): void {
                Route::get('/me', 'me');
                Route::post('/logout', 'logout');
            });
    });
