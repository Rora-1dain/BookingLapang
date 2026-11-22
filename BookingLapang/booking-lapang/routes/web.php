<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Seluruh UI frontend disajikan secara terpisah menggunakan React SPA
| (front-booking-lapang). Rute web di backend ini hanya melayani health check
| dan informasi status API.
*/

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'app' => config('app.name', 'Booking Lapang'),
        'api_url' => url('/api'),
        'docs' => 'Semua fitur aplikasi dilayani melalui endpoint REST API /api/*',
    ]);
})->name('home');

Route::get('/up', function () {
    return response()->json(['status' => 'ok']);
});