<?php
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceVerifikasiController;
use App\Http\Controllers\PaymentNotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\WaitlistController;
use App\Http\Controllers\PoinController;  
use App\Http\Controllers\ReferralController;  
use App\Http\Controllers\AdminLapanganController;
use App\Http\Controllers\PemilikLapanganController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\AdminPayoutController;
use App\Http\Controllers\PemilikPayoutController;
use App\Http\Controllers\AdminVerifikasiController;
use App\Http\Controllers\PemilikVerifikasiController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.publik.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
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
    Route::post('/voucher/cek', [VoucherController::class, 'cek'])->name('voucher.cek');
});
    Route::get('/leaderboard-referral', [ReferralController::class, 'leaderboard'])->name('referral.leaderboard');
    
Route::middleware(['auth', 'throttle:5,1'])->group(function () {
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
    Route::post('/poin/redeem', [\App\Http\Controllers\LoyaltyController::class, 'redeem'])
        ->name('poin.redeem');
});

Route::middleware('auth')->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/lapangan', [PemilikLapanganController::class, 'index'])->name('lapangan.index');
    Route::get('/lapangan/create', [PemilikLapanganController::class, 'create'])->name('lapangan.create');
    Route::post('/lapangan', [PemilikLapanganController::class, 'store'])->name('lapangan.store');
    Route::get('/lapangan/{lapangan}/edit', [PemilikLapanganController::class, 'edit'])->name('lapangan.edit');
    Route::put('/lapangan/{lapangan}', [PemilikLapanganController::class, 'update'])->name('lapangan.update');
    Route::get('/dashboard', [PemilikLapanganController::class, 'dashboard'])->name('dashboard');

    Route::get('/payout', [PemilikPayoutController::class, 'index'])
        ->name('payout.index');
    Route::get('/payout/{payout}/download', [PemilikPayoutController::class, 'download'])
        ->name('payout.download');

    Route::get('/verifikasi', [PemilikVerifikasiController::class, 'create'])
        ->name('verifikasi.create');
    Route::post('/verifikasi', [PemilikVerifikasiController::class, 'store'])
        ->name('verifikasi.store');
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

    Route::get('/verifikasi', [AdminVerifikasiController::class, 'index'])
        ->name('verifikasi.index');
    Route::get('/verifikasi/{pemilik}/dokumen', [AdminVerifikasiController::class, 'lihatDokumen'])
        ->name('verifikasi.dokumen');
    Route::post('/verifikasi/{pemilik}/tinjau', [AdminVerifikasiController::class, 'tinjau'])
        ->name('verifikasi.tinjau');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/lapangan/{lapangan}/galeri', [\App\Http\Controllers\FotoLapanganController::class, 'index'])
        ->name('lapangan.foto.index');

    Route::post('/lapangan/{lapangan}/foto', [\App\Http\Controllers\FotoLapanganController::class, 'store'])
        ->name('lapangan.foto.store');

    Route::delete('/foto/{foto}', [\App\Http\Controllers\FotoLapanganController::class, 'destroy'])
        ->name('lapangan.foto.destroy');

    Route::post('/foto/{foto}/jadikan-utama', [\App\Http\Controllers\FotoLapanganController::class, 'jadikanUtama'])
        ->name('lapangan.foto.utama');
});