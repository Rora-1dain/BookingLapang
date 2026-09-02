<?php

namespace App\Services;

use App\Models\Waitlist;
use Exception;

class WaitlistService
{
    public function __construct(protected BookingService $bookingService) {}

    public function daftarTunggu(array $data): Waitlist
    {
        $tersedia = $this->bookingService->cekKetersediaan(
            $data['lapangan_id'],
            $data['tanggal_booking'],
            $data['jam_mulai'],
            $data['jam_selesai']
        );

        if ($tersedia) {
            throw new Exception('Jadwal ini masih tersedia, silakan booking langsung.');
        }

        return Waitlist::create([
            'lapangan_id'     => $data['lapangan_id'],
            'user_id'         => $data['user_id'],
            'tanggal_booking' => $data['tanggal_booking'],
            'jam_mulai'       => $data['jam_mulai'],
            'jam_selesai'     => $data['jam_selesai'],
            'status'          => 'menunggu',
        ]);
    }

    public function prosesAntrian(int $lapanganId, string $tanggal, string $jamMulai, string $jamSelesai): ?Waitlist
    {
        $antrian = Waitlist::where('lapangan_id', $lapanganId)
           ->whereDate('tanggal_booking', $tanggal)
            ->where('jam_mulai', $jamMulai)
            ->where('jam_selesai', $jamSelesai)
            ->where('status', 'menunggu')
            ->oldest()
            ->first();

        if (!$antrian) {
            return null;
        }

        $antrian->update([
            'status'          => 'ditawarkan',
            'ditawarkan_pada' => now(),
        ]);

        return $antrian;
    }
}