<?php

use App\Http\Controllers\Api\AdminBookingApiController;
use App\Http\Controllers\Api\AdminLapanganApiController;
use App\Http\Controllers\Api\AdminLaporanApiController;
use App\Http\Controllers\Api\AdminPayoutApiController;
use App\Http\Controllers\Api\AdminUlasanApiController;
use App\Http\Controllers\Api\AdminVerifikasiApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\ChatApiController;
use App\Http\Controllers\Api\LapanganApiController;
use App\Http\Controllers\Api\PemilikLapanganApiController;
use App\Http\Controllers\Api\PemilikPayoutApiController;
use App\Http\Controllers\Api\PoinApiController;
use App\Http\Controllers\Api\ReferralApiController;
use App\Http\Controllers\Api\UlasanApiController;
use App\Http\Controllers\Api\VerifikasiApiController;
use App\Http\Controllers\Api\VoucherApiController;
use App\Http\Controllers\Api\WaitlistApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth (publik, tidak perlu login)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthApiController::class, 'register'])
    ->middleware('throttle:5,1');

Route::post('/login', [AuthApiController::class, 'login'])
    ->middleware('throttle:5,1');

/*
|--------------------------------------------------------------------------
| Lapangan (publik, tidak perlu login)
|--------------------------------------------------------------------------
*/
Route::get('/lapangan', [LapanganApiController::class, 'index']);
Route::get('/lapangan/{lapangan}', [LapanganApiController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Referral leaderboard (publik, tidak perlu login)
|--------------------------------------------------------------------------
*/
Route::get('/referral/leaderboard', [ReferralApiController::class, 'leaderboard']);

/*
|--------------------------------------------------------------------------
| Rute yang wajib login (auth:sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/me', [AuthApiController::class, 'me']);
    Route::post('/logout', [AuthApiController::class, 'logout']);

    // Booking
    Route::get('/booking', [BookingApiController::class, 'index']);
    Route::get('/booking/{booking}', [BookingApiController::class, 'show']);
    Route::post('/booking', [BookingApiController::class, 'store']);
    Route::post('/booking/{booking}/cancel', [BookingApiController::class, 'cancel']);

    // Voucher
    Route::post('/voucher/cek', [VoucherApiController::class, 'cek']);

    // Poin & Loyalitas
    Route::get('/poin', [PoinApiController::class, 'index']);
    Route::post('/poin/redeem', [PoinApiController::class, 'redeem']);

    // Referral (punya sendiri, beda dari leaderboard yang publik di atas)
    Route::get('/referral', [ReferralApiController::class, 'index']);

    // Ulasan
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('/booking/{booking}/ulasan', [UlasanApiController::class, 'store']);
    });
    Route::put('/ulasan/{ulasan}', [UlasanApiController::class, 'update']);
    Route::post('/ulasan/{ulasan}/laporkan', [UlasanApiController::class, 'laporkan']);

    // Waitlist
    Route::post('/waitlist/daftar', [WaitlistApiController::class, 'daftar']);

    Route::get('/percakapan', [ChatApiController::class, 'index']);
    Route::post('/percakapan', [ChatApiController::class, 'store']);
    Route::get('/percakapan/{percakapan}', [ChatApiController::class, 'show']);
    Route::post('/percakapan/{percakapan}/pesan', [ChatApiController::class, 'kirimPesan']);
    Route::post('/percakapan/{percakapan}/tandai-dibaca', [ChatApiController::class, 'tandaiDibaca']);

    // Pemilik lapangan
    Route::prefix('pemilik')->group(function () {
        Route::get('/lapangan', [PemilikLapanganApiController::class, 'index']);
        Route::post('/lapangan', [PemilikLapanganApiController::class, 'store']);
        Route::put('/lapangan/{lapangan}', [PemilikLapanganApiController::class, 'update']);
        Route::get('/dashboard', [PemilikLapanganApiController::class, 'dashboard']);
        Route::get('/payout', [PemilikPayoutApiController::class, 'index']);
        Route::post('/verifikasi', [VerifikasiApiController::class, 'ajukan']);
    });

});

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Booking & Refund
    Route::get('/booking', [AdminBookingApiController::class, 'index']);
    Route::post('/booking/{booking}/refund', [AdminBookingApiController::class, 'refund']);

    // Approval Lapangan
    Route::get('/lapangan/approval', [AdminLapanganApiController::class, 'approval']);
    Route::post('/lapangan/{lapangan}/setujui', [AdminLapanganApiController::class, 'setujui']);
    Route::post('/lapangan/{lapangan}/tolak', [AdminLapanganApiController::class, 'tolak']);

    // Verifikasi Pemilik (KYC)
    Route::get('/verifikasi', [AdminVerifikasiApiController::class, 'index']);
    Route::post('/verifikasi/{pemilik}/tinjau', [AdminVerifikasiApiController::class, 'tinjau']);

    // Payout
    Route::get('/payout', [AdminPayoutApiController::class, 'index']);
    Route::post('/payout', [AdminPayoutApiController::class, 'store']);
    Route::post('/payout/{payout}/selesai', [AdminPayoutApiController::class, 'selesai']);

    // Laporan Platform
    Route::get('/laporan-platform', [AdminLaporanApiController::class, 'platform']);

    // Ulasan Dilaporkan
    Route::get('/ulasan/dilaporkan', [AdminUlasanApiController::class, 'dilaporkan']);
});
