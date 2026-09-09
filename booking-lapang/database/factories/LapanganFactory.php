<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LapanganFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_lapangan' => 'Lapangan ' . fake()->unique()->words(2, true),
            'jenis' => fake()->randomElement(['Futsal', 'Basket', 'Badminton', 'Tenis', 'Voli']),
            'harga_per_jam' => fake()->numberBetween(50000, 300000),
            'status' => 'aktif',
            'kota' => fake()->randomElement(['Bandung', 'Jakarta', 'Surabaya', 'Yogyakarta', 'Semarang']),
            'status_approval' => 'disetujui',
            'persentase_komisi' => 10,
            'pemilik_id' => User::factory(),
        ];
    }
}