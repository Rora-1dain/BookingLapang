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
     *
     * @param int $lapanganId ID lapangan yang ingin dicek
     * @param string $tanggal Tanggal booking, format Y-m-d
     * @param string $jamMulai Jam mulai, format H:i
     * @param string $jamSelesai Jam selesai, format H:i
     * @return bool true jika tersedia (tidak bentrok), false jika sudah terisi
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
     * Menghitung total harga booking berdasarkan durasi jam dikali harga per jam lapangan.
     *
     * @param Lapangan $lapangan Lapangan yang dipesan
     * @param string $jamMulai Jam mulai, format H:i
     * @param string $jamSelesai Jam selesai, format H:i
     * @return float Total harga dalam Rupiah
     * @throws \Exception Jika jam selesai tidak lebih besar dari jam mulai
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
     * Membuat booking baru setelah memvalidasi status lapangan dan ketersediaan jadwal.
     * Mendukung kode voucher opsional untuk potongan harga.
     *
     * @param array{lapangan_id: int, user_id: int, tanggal_booking: string, jam_mulai: string, jam_selesai: string, kode_voucher?: string} $data
     * @param VoucherService|null $voucherService Service untuk validasi & hitung diskon voucher (opsional)
     * @return Booking Booking yang baru dibuat dengan status 'pending'
     * @throws \Exception Jika lapangan berstatus nonaktif, jadwal bentrok, atau voucher tidak valid
     */
    public function buatBooking(array $data, ?VoucherService $voucherService = null): Booking
    {
        $lapangan = Lapangan::findOrFail($data['lapangan_id']);

        // Validasi: lapangan nonaktif tidak boleh dibooking
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
     *
     * @param Booking $booking Booking yang akan dibatalkan
     * @return Booking Booking dengan status terbaru
     */
    public function batalkanBooking(Booking $booking): Booking
    {
        $booking->update(['status' => 'cancelled']);

        return $booking;
    }


    /**
     * Mengonfirmasi booking berstatus pending menjadi confirmed, lalu mengirim
     * notifikasi email ke user pemilik booking.
     *
     * @param Booking $booking Booking yang akan dikonfirmasi
     * @return Booking Booking dengan status terbaru
     * @throws \Exception Jika status booking bukan 'pending'
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