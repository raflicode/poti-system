<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);
    Route::post('/transactions', [TransactionController::class, 'store'])->middleware('role:piket,admin');
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::get('/attendances', [AttendanceController::class, 'index']);
    Route::post('/attendances/check-in', [AttendanceController::class, 'checkIn'])->middleware('role:piket,admin');
    Route::post('/attendances/check-out', [AttendanceController::class, 'checkOut'])->middleware('role:piket,admin');

    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::apiResource('products', ProductController::class)->except(['index']);
        Route::apiResource('schedules', ScheduleController::class)->except(['index', 'show']);
    });
});
