<?php

use App\Http\Controllers\Admin\LaporanPlatformController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminLapanganController;
use App\Http\Controllers\AdminPayoutController;
use App\Http\Controllers\AdminUlasanController;
use App\Http\Controllers\AdminVerifikasiController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FotoLapanganController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\InvoiceVerifikasiController;
use App\Http\Controllers\JadwalOperasionalController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PaymentNotificationController;
use App\Http\Controllers\PemilikBookingController;
use App\Http\Controllers\PemilikLapanganController;
use App\Http\Controllers\PemilikPayoutController;
use App\Http\Controllers\PemilikVerifikasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecurringBookingController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\WaitlistController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.publik.index');
Route::get('/lapangan/{lapangan}', [FrontendController::class, 'showLapangan'])->name('lapangan.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/poin', [FrontendController::class, 'points'])->name('poin.index');
    Route::get('/waitlist', [FrontendController::class, 'waitlist'])->name('waitlist.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/ajak-teman', [ReferralController::class, 'index'])->name('referral.index');
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
    Route::get('/booking/{booking}/invoice', [BookingController::class, 'invoice'])->name('booking.invoice');
    Route::post('/booking/{booking}/invoice/kirim-ulang', [BookingController::class, 'kirimUlangInvoice'])->name('booking.invoice.kirim-ulang');
    Route::post('/booking/cek-ketersediaan', [BookingController::class, 'cekKetersediaanAjax'])
        ->name('booking.cekKetersediaan');
    Route::post('/waitlist/daftar', [WaitlistController::class, 'daftar'])
        ->name('waitlist.daftar');
    Route::post('/waitlist/{waitlist}/cancel', [WaitlistController::class, 'cancel'])
        ->name('waitlist.cancel');
    Route::post('/voucher/cek', [VoucherController::class, 'cek'])->name('voucher.cek');
});

Route::middleware('auth')->group(function () {
    Route::get('/chat/{lapangan}', [ChatController::class, 'mulai'])
        ->name('chat.mulai');
    Route::post('/chat/{percakapan}/kirim', [ChatController::class, 'kirim'])
        ->name('chat.kirim');
    Route::post('/chat/{percakapan}/tandai-dibaca', [ChatController::class, 'tandaiDibaca'])
        ->name('chat.tandaiDibaca');
    Route::get('/booking/chat/{lapanganId}', [ChatController::class, 'bukaChatPemesan'])->name('booking.chat');
    Route::get('/pemilik/chat/{percakapan}', [ChatController::class, 'bukaChatPemilik'])->name('pemilik.chat');
    Route::post('/chat/{percakapan}/kirim', [ChatController::class, 'kirim'])->name('chat.kirim');
});

Route::get('/leaderboard-referral', [ReferralController::class, 'leaderboard'])->name('referral.leaderboard');

Route::middleware(['auth', 'throttle:5,1'])->group(function () {
    Route::get('/booking/{booking}/rating', [FrontendController::class, 'rating'])->name('booking.rating');
    Route::post('/booking/{booking}/ulasan', [UlasanController::class, 'store'])
        ->name('ulasan.store');
});
Route::middleware(['auth'])->group(function () {
    Route::put('/ulasan/{ulasan}', [UlasanController::class, 'update'])
        ->name('ulasan.update');
    Route::post('/ulasan/{ulasan}/laporkan', [UlasanController::class, 'laporkan'])
        ->name('ulasan.laporkan');
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

Route::get('/verifikasi-invoice/{nomor}', [InvoiceVerifikasiController::class, 'show'])
    ->name('invoice.verifikasi')
    ->where('nomor', '.*');

Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        $dbStatus = 'ok';
    } catch (Exception $e) {
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

Route::middleware(['auth'])->group(function () {
    Route::post('/poin/redeem', [LoyaltyController::class, 'redeem'])
        ->name('poin.redeem');

    Route::get('/lapangan/{lapangan}/booking-berulang', [RecurringBookingController::class, 'create'])
        ->name('booking.berulang.create');
    Route::post('/booking-berulang', [RecurringBookingController::class, 'store'])
        ->name('booking.berulang.store');
    Route::post('/booking-berulang/{recurringBookingId}/bayar', [RecurringBookingController::class, 'bayar'])
        ->name('booking.berulang.bayar');
});

Route::middleware('auth')->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/lapangan', [PemilikLapanganController::class, 'index'])->name('lapangan.index');
    Route::get('/lapangan/create', [PemilikLapanganController::class, 'create'])->name('lapangan.create');
    Route::post('/lapangan', [PemilikLapanganController::class, 'store'])->name('lapangan.store');
    Route::get('/lapangan/{lapangan}/edit', [PemilikLapanganController::class, 'edit'])->name('lapangan.edit');
    Route::put('/lapangan/{lapangan}', [PemilikLapanganController::class, 'update'])->name('lapangan.update');
    Route::get('/dashboard', [PemilikLapanganController::class, 'dashboard'])->name('dashboard');

    Route::get('/booking', [PemilikBookingController::class, 'index'])->name('booking.index');

    Route::get('/payout', [PemilikPayoutController::class, 'index'])
        ->name('payout.index');
    Route::get('/payout/{payout}/download', [PemilikPayoutController::class, 'download'])
        ->name('payout.download');

    Route::get('/verifikasi', [PemilikVerifikasiController::class, 'create'])
        ->name('verifikasi.create');
    Route::post('/verifikasi', [PemilikVerifikasiController::class, 'store'])
        ->name('verifikasi.store');

    Route::get('/lapangan/{lapangan}/jadwal', [JadwalOperasionalController::class, 'edit'])
        ->name('jadwal.edit');
    Route::put('/lapangan/{lapangan}/jadwal', [JadwalOperasionalController::class, 'update'])
        ->name('jadwal.update');

    Route::get('/lapangan/{lapangan}/hari-libur', [HariLiburController::class, 'index'])
        ->name('hari-libur.index');
    Route::post('/lapangan/{lapangan}/hari-libur', [HariLiburController::class, 'store'])
        ->name('hari-libur.store');
    Route::delete('/lapangan/{lapangan}/hari-libur/{hariLibur}', [HariLiburController::class, 'destroy'])
        ->name('hari-libur.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/refund', [AdminBookingController::class, 'refundIndex'])
        ->name('refund.index');

    Route::post('/booking/{booking}/refund', [AdminBookingController::class, 'refund'])
        ->name('refund.store');

    Route::get('/lapangan/approval', [AdminLapanganController::class, 'approval'])
        ->name('lapangan.approval');
    Route::post('/lapangan/{lapangan}/setujui', [AdminLapanganController::class, 'setujui'])
        ->name('lapangan.setujui');
    Route::post('/lapangan/{lapangan}/tolak', [AdminLapanganController::class, 'tolak'])
        ->name('lapangan.tolak');

    Route::get('/payout', [AdminPayoutController::class, 'index'])
        ->name('payout.index');
    Route::get('/payout/create', [AdminPayoutController::class, 'create'])
        ->name('payout.create');
    Route::post('/payout', [AdminPayoutController::class, 'store'])
        ->name('payout.store');
    Route::post('/payout/{payoutId}/selesai', [AdminPayoutController::class, 'selesai'])
        ->name('payout.selesai');

    Route::get('/ulasan', [AdminUlasanController::class, 'index'])
        ->name('ulasan.index');
    Route::post('/ulasan/{ulasan}/publikasikan', [AdminUlasanController::class, 'publikasikan'])
        ->name('ulasan.publikasikan');
    Route::post('/ulasan/{ulasan}/sembunyikan', [AdminUlasanController::class, 'sembunyikan'])
        ->name('ulasan.sembunyikan');

    Route::get('/verifikasi', [AdminVerifikasiController::class, 'index'])
        ->name('verifikasi.index');
    Route::get('/verifikasi/{pemilik}/dokumen', [AdminVerifikasiController::class, 'lihatDokumen'])
        ->name('verifikasi.dokumen');
    Route::post('/verifikasi/{pemilik}/tinjau', [AdminVerifikasiController::class, 'tinjau'])
        ->name('verifikasi.tinjau');

    Route::get('/laporan/platform', [LaporanPlatformController::class, 'index'])
        ->name('laporan.platform');
    Route::get('/laporan/platform/export', [LaporanPlatformController::class, 'export'])
        ->name('laporan.platform.export');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/lapangan/{lapangan}/galeri', [FotoLapanganController::class, 'index'])
        ->name('lapangan.foto.index');

    Route::post('/lapangan/{lapangan}/foto', [FotoLapanganController::class, 'store'])
        ->name('lapangan.foto.store');

    Route::delete('/foto/{foto}', [FotoLapanganController::class, 'destroy'])
        ->name('lapangan.foto.destroy');

    Route::post('/foto/{foto}/jadikan-utama', [FotoLapanganController::class, 'jadikanUtama'])
        ->name('lapangan.foto.utama');
});

Route::middleware('auth')->prefix('membership')->name('membership.')->group(function () {
    Route::get('/', [MembershipController::class, 'index'])->name('index');
    Route::post('/berlangganan/{paket}', [MembershipController::class, 'berlangganan'])->name('berlangganan');
    Route::get('/status', [MembershipController::class, 'status'])->name('status');
});