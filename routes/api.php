<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Auth routes (login only — no middleware needed)
Route::prefix('auth')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
    Route::get('/auth/me', [\App\Http\Controllers\Auth\AuthController::class, 'me']);

    // Bot management — accessible at api/bots
    Route::apiResource('bots', \App\Http\Controllers\BotController::class);

    // Analytics
    Route::prefix('analytics')->group(function () {
        Route::get('/overview', [\App\Http\Controllers\AnalyticsController::class, 'overview']);
        Route::get('/bot/{id}', [\App\Http\Controllers\AnalyticsController::class, 'botStats']);
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [\App\Http\Controllers\SettingsController::class, 'index']);
        Route::put('/ai', [\App\Http\Controllers\SettingsController::class, 'updateAiSettings']);
        Route::put('/password', [\App\Http\Controllers\SettingsController::class, 'updatePassword']);
    });

    // AI Model Discovery
    Route::post('/models/fetch', [\App\Http\Controllers\ModelFetchController::class, 'fetch']);
});

Route::post('/chat', [\App\Http\Controllers\ChatController::class, 'chat']);
