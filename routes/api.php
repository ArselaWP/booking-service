<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;

Route::prefix('bookings')->group(function () {

    Route::post('/', [BookingController::class, 'store']);

    Route::get('/{id}', [BookingController::class, 'show']);

    Route::get('/user/{userId}', [BookingController::class, 'getByUser']);

    Route::put('/{id}/approve', [BookingController::class, 'approve']);

    Route::put('/{id}/cancel', [BookingController::class, 'cancel']);
});

Route::prefix('payments')->group(function () {

    Route::get('/', [PaymentController::class, 'index']);

    Route::get('/{id}', [PaymentController::class, 'show']);

    Route::post('/', [PaymentController::class, 'store']);
});