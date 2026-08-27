<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentNotificationController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/booking/{booking}/bayar', [BookingController::class, 'bayar'])->name('booking.bayar');
    Route::get('/booking/{booking}/status', [BookingController::class, 'status'])->name('booking.status');
    Route::post('/booking/{booking}/cek-status', [BookingController::class, 'cekStatus'])->name('booking.cek-status');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/booking', [AdminBookingController::class, 'index'])
            ->name('admin.booking.index');
        Route::get('/booking/export', [AdminBookingController::class, 'export'])
            ->name('admin.booking.export');
        Route::post('/booking/{booking}/confirm', [AdminBookingController::class, 'confirm'])
            ->name('admin.booking.confirm');
        Route::post('/booking/{booking}/cancel', [AdminBookingController::class, 'cancel'])
            ->name('admin.booking.cancel');
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');
    });

Route::post('/payment/notification', [PaymentNotificationController::class, 'handle'])
    ->name('payment.notification');

Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        $dbStatus = 'ok';
    } catch (\Exception $e) {
        $dbStatus = 'error';
    }
    Cache::put('health_check', 'ok', 10);
    $cacheStatus = Cache::get('health_check') === 'ok' ? 'ok' : 'error';
    $status = ($dbStatus === 'ok' && $cacheStatus === 'ok') ? 200 : 500;

    return response()->json([
        'database' => $dbStatus,
        'cache' => $cacheStatus,
    ], $status);
});