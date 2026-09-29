<?php

namespace Database\Seeders;

use App\Models\Lapangan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PemilikLapanganSeeder extends Seeder
{
    public function run(): void
    {
        // Pemilik 1: sudah punya 1 lapangan disetujui, 1 masih pending
        $pemilik1 = User::create([
            'name' => 'Pemilik Satu',
            'email' => 'pemilik1@bookinglapang.test',
            'password' => Hash::make('password'),
            'role' => 'pemilik_lapangan',
        ]);

        Lapangan::create([
            'nama_lapangan' => 'Lapangan Futsal Pemilik 1',
            'jenis' => 'futsal',
            'harga_per_jam' => 100000,
            'status' => 'aktif',
            'pemilik_id' => $pemilik1->id,
            'status_approval' => 'disetujui',
        ]);

        Lapangan::create([
            'nama_lapangan' => 'Lapangan Badminton Pemilik 1 (baru diajukan)',
            'jenis' => 'badminton',
            'harga_per_jam' => 50000,
            'status' => 'nonaktif',
            'pemilik_id' => $pemilik1->id,
            'status_approval' => 'pending',
        ]);

        // Pemilik 2: cuma punya lapangan yang masih pending (belum ada yang disetujui)
        // — berguna buat testing "lapangan pending tidak boleh tampil di publik"
        $pemilik2 = User::create([
            'name' => 'Pemilik Dua',
            'email' => 'pemilik2@bookinglapang.test',
            'password' => Hash::make('password'),
            'role' => 'pemilik_lapangan',
        ]);

        Lapangan::create([
            'nama_lapangan' => 'Lapangan Basket Pemilik 2',
            'jenis' => 'basket',
            'harga_per_jam' => 80000,
            'status' => 'nonaktif',
            'pemilik_id' => $pemilik2->id,
            'status_approval' => 'pending',
        ]);

        // Lapangan yang pernah ditolak juga disiapkan, buat testing tampilan
        // halaman riwayat pengajuan pemilik (bagian Bintang/Revano)
        Lapangan::create([
            'nama_lapangan' => 'Lapangan Voli Pemilik 2 (ditolak)',
            'jenis' => 'voli',
            'harga_per_jam' => 60000,
            'status' => 'nonaktif',
            'pemilik_id' => $pemilik2->id,
            'status_approval' => 'ditolak',
        ]);
    }
}
