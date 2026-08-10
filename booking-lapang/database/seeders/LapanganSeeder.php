<?php

namespace Database\Seeders;

use App\Models\Lapangan;
use Illuminate\Database\Seeder;

class LapanganSeeder extends Seeder
{
    /**
     * Jalankan dengan: php artisan db:seed --class=LapanganSeeder
     */
    public function run(): void
    {
        Lapangan::create([
            'nama_lapangan' => 'Lapangan Futsal A',
            'jenis' => 'Futsal',
            'harga_per_jam' => 100000,
            'status' => 'aktif',
        ]);

        Lapangan::create([
            'nama_lapangan' => 'Lapangan Basket B',
            'jenis' => 'Basket',
            'harga_per_jam' => 150000,
            'status' => 'aktif',
        ]);

        Lapangan::create([
            'nama_lapangan' => 'Lapangan Badminton C',
            'jenis' => 'Badminton',
            'harga_per_jam' => 50000,
            'status' => 'aktif',
        ]);
    }
}
