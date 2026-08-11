<?php

namespace Database\Seeders;

use App\Models\Lapangan;
use Illuminate\Database\Seeder;

class LapanganSeeder extends Seeder
{
    public function run(): void
    {
        Lapangan::insert([
            [
                'nama_lapangan' => 'Lapangan Futsal A',
                'jenis' => 'futsal',
                'harga_per_jam' => 100000,
                'status' => 'aktif',
            ],
            [
                'nama_lapangan' => 'Lapangan Badminton B',
                'jenis' => 'badminton',
                'harga_per_jam' => 50000,
                'status' => 'aktif',
            ],
            [
                'nama_lapangan' => 'Lapangan Basket C',
                'jenis' => 'basket',
                'harga_per_jam' => 120000,
                'status' => 'aktif',
            ],
        ]);
    }
}
