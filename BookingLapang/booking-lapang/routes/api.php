<?php

use App\Http\Controllers\Api\AdminAuditApiController;
use App\Http\Controllers\Api\AdminBookingApiController;
use App\Http\Controllers\Api\AdminDashboardApiController;
use App\Http\Controllers\Api\AdminLapanganApiController;
use App\Http\Controllers\Api\AdminLaporanApiController;
use App\Http\Controllers\Api\AdminLedgerExportController;
use App\Http\Controllers\Api\AdminPayoutApiController;
use App\Http\Controllers\Api\AdminUlasanApiController;
use App\Http\Controllers\Api\AdminVerifikasiApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\ChatApiController;
use App\Http\Controllers\Api\FotoLapanganApiController;
use App\Http\Controllers\Api\InvoiceVerifikasiApiController;
use App\Http\Controllers\Api\LapanganApiController;
use App\Http\Controllers\Api\MembershipApiController;
use App\Http\Controllers\Api\NotificationPreferenceApiController;
use App\Http\Controllers\Api\PemilikBookingApiController;
use App\Http\Controllers\Api\PemilikDashboardApiController;
use App\Http\Controllers\Api\PemilikHariLiburApiController;
use App\Http\Controllers\Api\PemilikJadwalApiController;
use App\Http\Controllers\Api\PemilikLapanganApiController;
use App\Http\Controllers\Api\PemilikPayoutApiController;
use App\Http\Controllers\Api\PoinApiController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\ReferralApiController;
use App\Http\Controllers\Api\UlasanApiController;
use App\Http\Controllers\Api\VerifikasiApiController;
use App\Http\Controllers\Api\VoucherApiController;
use App\Http\Controllers\Api\WaitlistApiController;
use App\Http\Controllers\PaymentNotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthApiController::class, 'register'])
    ->middleware('throttle:5,1');

Route::post('/login', [AuthApiController::class, 'login'])
    ->middleware('throttle:5,1');

// Lapangan
Route::get('/lapangan', [LapanganApiController::class, 'index']);
Route::get('/lapangan/{lapangan}', [LapanganApiController::class, 'show']);
Route::get('/lapangan/{lapangan}/ulasan', [LapanganApiController::class, 'ulasans']);
Route::get('/lapangan/{lapangan}/jadwal', [LapanganApiController::class, 'jadwal']);

// Referral leaderboard
Route::get('/referral/leaderboard', [ReferralApiController::class, 'leaderboard']);

// Membership
Route::get('/membership/paket', [MembershipApiController::class, 'paket']);

// Invoice QR verification
Route::get('/invoice/verifikasi/{nomor}', [InvoiceVerifikasiApiController::class, 'show']);

// Midtrans webhook payment notification
Route::post('/payment/notification', [PaymentNotificationController::class, 'handle']);

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (auth:sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth & Profil
    Route::get('/me', [AuthApiController::class, 'me']);
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me/profile', [ProfileApiController::class, 'show']);
    Route::put('/me/profile', [ProfileApiController::class, 'update']);
    Route::put('/me/password', [ProfileApiController::class, 'updatePassword']);
    Route::delete('/me', [ProfileApiController::class, 'destroy']);

    // Booking
    Route::get('/booking', [BookingApiController::class, 'index']);
    Route::get('/booking/{booking}', [BookingApiController::class, 'show']);
    Route::post('/booking', [BookingApiController::class, 'store']);
    Route::post('/booking/cek-ketersediaan', [BookingApiController::class, 'cekKetersediaan']);
    Route::post('/booking/{booking}/cancel', [BookingApiController::class, 'cancel']);
    Route::post('/booking/{booking}/refund', [BookingApiController::class, 'mintaRefund']);
    Route::post('/booking/{booking}/bayar', [BookingApiController::class, 'bayar']);
    Route::post('/booking/{booking}/cek-status', [BookingApiController::class, 'cekStatus']);
    Route::get('/booking/{booking}/invoice', [BookingApiController::class, 'invoice']);
    Route::post('/booking/{booking}/invoice/kirim-ulang', [BookingApiController::class, 'kirimUlangInvoice']);

    // Foto Lapangan
    Route::get('/lapangan/{lapangan}/foto', [FotoLapanganApiController::class, 'index']);
    Route::post('/lapangan/{lapangan}/foto', [FotoLapanganApiController::class, 'store']);
    Route::delete('/foto/{foto}', [FotoLapanganApiController::class, 'destroy']);
    Route::post('/foto/{foto}/jadikan-utama', [FotoLapanganApiController::class, 'jadikanUtama']);

    // Voucher
    Route::post('/voucher/cek', [VoucherApiController::class, 'cek']);

    // Poin & Loyalitas
    Route::get('/poin', [PoinApiController::class, 'index']);
    Route::post('/poin/redeem', [PoinApiController::class, 'redeem']);

    // Referral
    Route::get('/referral', [ReferralApiController::class, 'index']);

    // Ulasan
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('/booking/{booking}/ulasan', [UlasanApiController::class, 'store']);
    });
    Route::put('/ulasan/{ulasan}', [UlasanApiController::class, 'update']);
    Route::post('/ulasan/{ulasan}/laporkan', [UlasanApiController::class, 'laporkan']);

    // Waitlist
    Route::get('/waitlist', [WaitlistApiController::class, 'index']);
    Route::post('/waitlist/daftar', [WaitlistApiController::class, 'daftar']);

    // Preferensi Notifikasi
    Route::get('/notifikasi/preferensi', [NotificationPreferenceApiController::class, 'show']);
    Route::put('/notifikasi/preferensi', [NotificationPreferenceApiController::class, 'update']);

    // Chat Realtime
    Route::post('/broadcasting/auth', [\Illuminate\Broadcasting\BroadcastController::class, 'authenticate']);
    Route::get('/percakapan', [ChatApiController::class, 'index']);
    Route::post('/percakapan', [ChatApiController::class, 'store']);
    Route::get('/percakapan/{percakapan}', [ChatApiController::class, 'show']);
    Route::post('/percakapan/{percakapan}/pesan', [ChatApiController::class, 'kirimPesan']);
    Route::post('/percakapan/{percakapan}/tandai-dibaca', [ChatApiController::class, 'tandaiDibaca']);

    // Pemilik Lapangan
    Route::prefix('pemilik')->group(function () {
        Route::get('/dashboard', [PemilikDashboardApiController::class, 'index']);
        Route::get('/booking', [PemilikBookingApiController::class, 'index']);
        Route::get('/lapangan', [PemilikLapanganApiController::class, 'index']);
        Route::post('/lapangan', [PemilikLapanganApiController::class, 'store']);
        Route::put('/lapangan/{lapangan}', [PemilikLapanganApiController::class, 'update']);

        // Jadwal & Hari Libur
        Route::get('/lapangan/{lapangan}/jadwal', [PemilikJadwalApiController::class, 'show']);
        Route::put('/lapangan/{lapangan}/jadwal', [PemilikJadwalApiController::class, 'update']);
        Route::get('/lapangan/{lapangan}/hari-libur', [PemilikHariLiburApiController::class, 'index']);
        Route::post('/lapangan/{lapangan}/hari-libur', [PemilikHariLiburApiController::class, 'store']);
        Route::delete('/lapangan/{lapangan}/hari-libur/{hariLibur}', [PemilikHariLiburApiController::class, 'destroy']);

        // Payout
        Route::get('/payout', [PemilikPayoutApiController::class, 'index']);
        Route::get('/payout/{payout}/download', [PemilikPayoutApiController::class, 'download']);
        Route::post('/verifikasi', [VerifikasiApiController::class, 'ajukan']);
    });

    // Membership
    Route::get('/membership', [MembershipApiController::class, 'saya']);
    Route::post('/membership/berlangganan', [MembershipApiController::class, 'berlangganan']);
    Route::post('/membership/cek-status/{trx}', [MembershipApiController::class, 'cekStatus']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes (auth:sanctum + admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardApiController::class, 'index']);

    // Booking & Refund
    Route::get('/booking', [AdminBookingApiController::class, 'index']);
    Route::get('/refund', [AdminBookingApiController::class, 'refundIndex']);
    Route::post('/booking/{booking}/refund', [AdminBookingApiController::class, 'refund']);
    Route::post('/booking/{booking}/refund/tolak', [AdminBookingApiController::class, 'tolakRefund']);

    // Approval Lapangan
    Route::get('/lapangan/approval', [AdminLapanganApiController::class, 'approval']);
    Route::post('/lapangan/{lapangan}/setujui', [AdminLapanganApiController::class, 'setujui']);
    Route::post('/lapangan/{lapangan}/tolak', [AdminLapanganApiController::class, 'tolak']);
    Route::put('/lapangan/{lapangan}/komisi', [AdminLapanganApiController::class, 'ubahKomisi']);

    // Verifikasi Pemilik (KYC)
    Route::get('/verifikasi', [AdminVerifikasiApiController::class, 'index']);
    Route::get('/verifikasi/{pemilik}/dokumen', [AdminVerifikasiApiController::class, 'lihatDokumen']);
    Route::post('/verifikasi/{pemilik}/tinjau', [AdminVerifikasiApiController::class, 'tinjau']);

    // Payout
    Route::get('/payout', [AdminPayoutApiController::class, 'index']);
    Route::post('/payout', [AdminPayoutApiController::class, 'store']);
    Route::post('/payout/{payout}/selesai', [AdminPayoutApiController::class, 'selesai']);

    // Laporan Platform & Ledger
    Route::get('/laporan-platform', [AdminLaporanApiController::class, 'platform']);
    Route::get('/laporan/ledger/export', [AdminLedgerExportController::class, 'export']);

    // Ulasan
    Route::get('/ulasan', [AdminUlasanApiController::class, 'index']);
    Route::get('/ulasan/dilaporkan', [AdminUlasanApiController::class, 'dilaporkan']);
    Route::post('/ulasan/{ulasan}/publikasikan', [AdminUlasanApiController::class, 'publikasikan']);
    Route::post('/ulasan/{ulasan}/sembunyikan', [AdminUlasanApiController::class, 'sembunyikan']);

    // Audit Log (append-only)
    Route::get('/audit', [AdminAuditApiController::class, 'index']);
});
