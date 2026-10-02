<?php

namespace Database\Factories;

use App\Models\Lapangan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'lapangan_id' => Lapangan::factory(),
            'tanggal_booking' => now()->toDateString(),
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '11:00:00',
            'total_harga' => 100000,
            'status' => 'pending',
            'metode_pembayaran' => 'transfer',
            'status_pembayaran' => 'pending',
            'nomor_invoice' => 'INV-' . fake()->unique()->numerify('########'),
            'nominal_komisi' => 0,
            'pendapatan_pemilik' => 0,
        ];
    }
}
