<?php

namespace Database\Seeders;

use App\Models\Lapangan;
use Illuminate\Database\Seeder;

class JadwalOperasionalSeeder extends Seeder
{
    /**
     * Memberi jadwal default 08.00-22.00 (semua hari) ke SETIAP lapangan yang
     * sudah ada di database dan belum punya jadwal operasional sama sekali.
     * Jalankan ini sekali untuk membackfill data lama:
     *
     *   php artisan db:seed --class=JadwalOperasionalSeeder
     */
    public function run(): void
    {
        Lapangan::doesntHave('jadwalOperasionals')->each(function (Lapangan $lapangan) {
            foreach (range(0, 6) as $hari) {
                $lapangan->jadwalOperasionals()->create([
                    'hari' => $hari,
                    'jam_buka' => '08:00:00',
                    'jam_tutup' => '22:00:00',
                    'is_tutup' => false,
                ]);
            }
        });
    }
}
