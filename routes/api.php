<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BootstrapController;
use App\Http\Controllers\Api\DriverApplicationController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\PassengerController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\SupportController;
use Illuminate\Support\Facades\Route;

Route::get('/bootstrap', BootstrapController::class);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/reset', [AuthController::class, 'reset']);
});

Route::get('/settings/languages', [SettingsController::class, 'languages']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/me', [ProfileController::class, 'me']);
    Route::put('/me', [ProfileController::class, 'update']);

    Route::prefix('passenger')->group(function () {
        Route::get('/home', [PassengerController::class, 'home']);
        Route::get('/history', [PassengerController::class, 'history']);
        Route::post('/rides', [PassengerController::class, 'store']);
        Route::get('/rides/{ride}', [PassengerController::class, 'show']);
        Route::get('/rides/{ride}/tracking', [PassengerController::class, 'tracking']);
        Route::post('/rides/{ride}/rating', [PassengerController::class, 'rate']);
    });

    Route::prefix('driver')->group(function () {
        Route::get('/home', [DriverController::class, 'home']);
        Route::get('/history', [DriverController::class, 'history']);
        Route::post('/status', [DriverController::class, 'status']);
        Route::post('/location', [DriverController::class, 'location']);
        Route::put('/profile', [DriverController::class, 'profile']);
        Route::get('/rides/{ride}/tracking', [DriverController::class, 'tracking']);
        Route::post('/rides/{ride}/accept', [DriverController::class, 'accept']);
        Route::post('/rides/{ride}/reject', [DriverController::class, 'reject']);
        Route::post('/rides/{ride}/arrived', [DriverController::class, 'arrived']);
        Route::post('/rides/{ride}/start', [DriverController::class, 'start']);
        Route::post('/rides/{ride}/complete', [DriverController::class, 'complete']);
    });

    Route::get('/support/tickets', [SupportController::class, 'index']);
    Route::post('/support/tickets', [SupportController::class, 'store']);

    Route::post('/driver-applications', [DriverApplicationController::class, 'store']);
    Route::put('/settings/language', [SettingsController::class, 'language']);
});
