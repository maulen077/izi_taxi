<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::get('register', [AuthController::class, 'showRegister'])->name('register.form');
    Route::post('register', [AuthController::class, 'register'])->name('register');

    Route::middleware(\App\Http\Middleware\EnsurePanelUser::class . ':admin')->group(function () {
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('main', [DashboardController::class, 'index'])->name('main');

        Route::get('users', [UserController::class, 'index'])->name('users');
        Route::get('users/{user}', [UserController::class, 'detail'])->name('user_detail');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('user_edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('user_update');

        Route::get('drivers', [DriverController::class, 'index'])->name('drivers');
        Route::get('drivers/{user}', [DriverController::class, 'detail'])->name('driver_detail');
        Route::get('drivers/{user}/edit', [DriverController::class, 'edit'])->name('driver_edit');
        Route::put('drivers/{user}', [DriverController::class, 'update'])->name('driver_update');

        Route::get('orders', [OrderController::class, 'index'])->name('orders');
        Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [OrderController::class, 'store'])->name('orders.store');

        Route::get('support', [SupportController::class, 'index'])->name('support');
    });
});

Route::prefix('dispatcher')->name('dispatcher.')->group(function () {
    Route::middleware(\App\Http\Middleware\EnsurePanelUser::class . ':dispatcher')->group(function () {
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('main', [DashboardController::class, 'index'])->name('main');

        Route::get('orders', [OrderController::class, 'index'])->name('orders');
        Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    });
});
