<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Notifications\BookingDikonfirmasi;
use Carbon\Carbon;
use Exception;

class BookingService
{
    /**
     * Mengecek apakah suatu lapangan tersedia pada tanggal dan rentang jam tertentu.
     */
    public function cekKetersediaan(int $lapanganId, string $tanggal, string $jamMulai, string $jamSelesai): bool
    {
        $bentrok = Booking::where('lapangan_id', $lapanganId)
            ->whereDate('tanggal_booking', $tanggal)
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

        return ! $bentrok;
    }

    /**
     * Mengecek apakah jam booking berada dalam jam operasional lapangan pada hari yang sesuai.
     */
    public function dalamJamOperasional(Lapangan $lapangan, string $tanggal, string $jamMulai, string $jamSelesai): bool
    {
        $hari = Carbon::parse($tanggal)->dayOfWeek;

        $jadwal = $lapangan->jadwalOperasionals()->where('hari', $hari)->first();

        if (! $jadwal || $jadwal->is_tutup) {
            return false;
        }

        return $jamMulai >= $jadwal->jam_buka && $jamSelesai <= $jadwal->jam_tutup;
    }

    /**
     * Mengecek apakah tanggal tertentu adalah hari libur khusus (blackout date).
     */
    public function tanggalLibur(Lapangan $lapangan, string $tanggal): bool
    {
        return $lapangan->hariLiburs()->whereDate('tanggal', $tanggal)->exists();
    }

    /**
     * Menghitung total harga booking berdasarkan durasi jam dikali harga per jam lapangan.
     */
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

    /**
     * Membuat booking baru setelah memvalidasi jadwal operasional, hari libur,
     * status lapangan, dan ketersediaan jadwal.
     */
    public function buatBooking(array $data, ?VoucherService $voucherService = null): Booking
    {
        $lapangan = Lapangan::findOrFail($data['lapangan_id']);

        if ($this->tanggalLibur($lapangan, $data['tanggal_booking'])) {
            throw new Exception('Lapangan tutup pada tanggal yang dipilih.');
        }

        if (! $this->dalamJamOperasional($lapangan, $data['tanggal_booking'], $data['jam_mulai'], $data['jam_selesai'])) {
            throw new Exception('Jam booking di luar jam operasional lapangan.');
        }

        if ($lapangan->status !== 'aktif') {
            throw new Exception('Lapangan ini sedang tidak aktif dan tidak bisa dibooking.');
        }

        $tersedia = $this->cekKetersediaan(
            $lapangan->id, $data['tanggal_booking'], $data['jam_mulai'], $data['jam_selesai']
        );

        if (! $tersedia) {
            throw new Exception('Lapangan sudah dibooking pada jam tersebut.');
        }

        $totalHarga = $this->hitungTotalHarga($lapangan, $data['jam_mulai'], $data['jam_selesai']);

        $totalDiskon = 0;
        $voucherId = null;

        if (! empty($data['kode_voucher']) && $voucherService) {
            $voucher = $voucherService->validasiVoucher(
                $data['kode_voucher'], $data['user_id'], $totalHarga
            );

            $totalDiskon = $voucherService->hitungDiskon($voucher, $totalHarga);
            $voucherService->catatPemakaian($voucher, $data['user_id']);
            $voucherId = $voucher->id;
        }

        return Booking::create([
            'user_id' => $data['user_id'],
            'lapangan_id' => $lapangan->id,
            'tanggal_booking' => $data['tanggal_booking'],
            'jam_mulai' => $data['jam_mulai'],
            'jam_selesai' => $data['jam_selesai'],
            'total_harga' => $totalHarga - $totalDiskon,
            'total_diskon' => $totalDiskon,
            'voucher_id' => $voucherId,
            'status' => 'pending',
        ]);
    }

    /**
     * Membatalkan booking dengan mengubah status menjadi 'cancelled'.
     */
    public function batalkanBooking(Booking $booking): Booking
    {
        $booking->update(['status' => 'cancelled']);

        return $booking;
    }

    /**
     * Mengonfirmasi booking berstatus pending menjadi confirmed.
     */
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
