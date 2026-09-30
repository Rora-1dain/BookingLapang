<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BookingApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthApiController::class, 'login'])
    ->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthApiController::class, 'me']);
    Route::post('/logout', [AuthApiController::class, 'logout']);

    Route::get('/booking', [BookingApiController::class, 'index']);
    Route::get('/booking/{booking}', [BookingApiController::class, 'show']);
    Route::post('/booking', [BookingApiController::class, 'store']);
    Route::post('/booking/{booking}/cancel', [BookingApiController::class, 'cancel']);
});