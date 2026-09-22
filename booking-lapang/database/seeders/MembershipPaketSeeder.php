<?php

namespace Database\Seeders;

use App\Models\MembershipPaket;
use Illuminate\Database\Seeder;

class MembershipPaketSeeder extends Seeder
{
    public function run(): void
    {
        MembershipPaket::create([
            'nama' => 'Basic',
            'harga_bulanan' => 50000,
            'persentase_diskon_booking' => 5,
            'kuota_booking_gratis' => 0,
        ]);

        MembershipPaket::create([
            'nama' => 'Silver',
            'harga_bulanan' => 100000,
            'persentase_diskon_booking' => 10,
            'kuota_booking_gratis' => 1,
        ]);

        MembershipPaket::create([
            'nama' => 'Gold',
            'harga_bulanan' => 200000,
            'persentase_diskon_booking' => 20,
            'kuota_booking_gratis' => 3,
        ]);
    }
}