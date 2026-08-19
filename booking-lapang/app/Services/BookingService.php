<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Notifications\BookingDikonfirmasi;
use Carbon\Carbon;
use Exception;

class BookingService
{
    public function cekKetersediaan(int $lapanganId, string $tanggal, string $jamMulai, string $jamSelesai): bool
    {
        $bentrok = Booking::where('lapangan_id', $lapanganId)
            ->where('tanggal_booking', $tanggal)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                    ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                    ->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                        $q->where('jam_mulai', '<=', $jamMulai)
                            ->where('jam_selesai', '>=', $jamSelesai);
                    });
            })
            ->exists();

        return !$bentrok;
    }

    public function hitungTotalHarga(Lapangan $lapangan, string $jamMulai, string $jamSelesai): float
    {
        $mulai = Carbon::parse($jamMulai);
        $selesai = Carbon::parse($jamSelesai);
        $durasiJam = abs($selesai->diffInMinutes($mulai)) / 60;

        if ($durasiJam <= 0) {
            throw new Exception('Jam selesai harus lebih besar dari jam mulai.');
        }

        return $durasiJam * $lapangan->harga_per_jam;
    }

    public function buatBooking(array $data): Booking
    {
        $lapangan = Lapangan::findOrFail($data['lapangan_id']);

        // Validasi: lapangan nonaktif tidak boleh dibooking
        if ($lapangan->status !== 'aktif') {
            throw new Exception('Lapangan ini sedang tidak aktif dan tidak bisa dibooking.');
        }

        $tersedia = $this->cekKetersediaan(
            $lapangan->id, $data['tanggal_booking'], $data['jam_mulai'], $data['jam_selesai']
        );

        if (!$tersedia) {
            throw new Exception('Lapangan sudah dibooking pada jam tersebut.');
        }

        $totalHarga = $this->hitungTotalHarga($lapangan, $data['jam_mulai'], $data['jam_selesai']);

        return Booking::create([
            'user_id' => $data['user_id'],
            'lapangan_id' => $lapangan->id,
            'tanggal_booking' => $data['tanggal_booking'],
            'jam_mulai' => $data['jam_mulai'],
            'jam_selesai' => $data['jam_selesai'],
            'total_harga' => $totalHarga,
            'status' => 'pending',
        ]);
    }

    public function batalkanBooking(Booking $booking): Booking
    {
        $booking->update(['status' => 'cancelled']);
        return $booking;
    }
    public function konfirmasiBooking(Booking $booking): Booking
    {
        if ($booking->status !== 'pending') {
            throw new Exception('Hanya booking dengan status pending yang bisa dikonfirmasi.');
        }

        $booking->update(['status' => 'confirmed']);

        $booking->user->notify(new BookingDikonfirmasi($booking));

        return $booking;
    }

}