<?php

namespace App\Observers;

use App\Models\Lapangan;

/**
 * PENTING — kenapa Observer ini perlu dibuat padahal tidak diminta eksplisit:
 *
 * Soal bilang "Buat seeder default: setiap lapangan baru otomatis mendapat
 * jadwal buka 08.00-22.00". Kata "otomatis" di situ berarti aturan ini harus
 * berlaku setiap kali ADA LAPANGAN BARU dibuat (baik lewat PemilikLapanganController::store()
 * atau dari mana pun) — bukan cuma sekali dijalankan manual lewat
 * `php artisan db:seed`.
 *
 * Seeder (JadwalOperasionalSeeder) HANYA membackfill data lama yang sudah ada
 * di database SEBELUM Observer ini dipasang. Untuk lapangan yang dibuat SETELAH
 * ini, Observer inilah yang menjamin jadwal default selalu otomatis dibuat.
 *
 * Cara pasang: daftarkan di app/Providers/AppServiceProvider.php, method boot():
 *
 *     use App\Models\Lapangan;
 *     use App\Observers\LapanganObserver;
 *
 *     public function boot(): void
 *     {
 *         Lapangan::observe(LapanganObserver::class);
 *     }
 */
class LapanganObserver
{
    public function created(Lapangan $lapangan): void
    {
        foreach (range(0, 6) as $hari) {
            $lapangan->jadwalOperasionals()->create([
                'hari' => $hari,
                'jam_buka' => '08:00:00',
                'jam_tutup' => '22:00:00',
                'is_tutup' => false,
            ]);
        }
    }
}
