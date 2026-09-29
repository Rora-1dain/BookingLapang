<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\RecurringBooking;
use Carbon\Carbon;
use Exception;

class RecurringBookingService
{
    public function __construct(protected BookingService $bookingService) {}

    protected function hitungTanggalSesi(Carbon $mulai, int $hari, int $jumlahSesi): array
    {
        $tanggalSesi = [];
        $tanggal = $mulai->copy();

        while ($tanggal->dayOfWeek !== $hari) {
            $tanggal->addDay();
        }

        for ($i = 0; $i < $jumlahSesi; $i++) {
            $tanggalSesi[] = $tanggal->copy();
            $tanggal->addWeek();
        }

        return $tanggalSesi;
    }

    public function buatPaket(array $data): array
    {
        if ($data['jumlah_sesi'] > 12) {
            throw new Exception('Maksimal 12 sesi per paket booking berulang.');
        }

        $paket = RecurringBooking::create($data);

        $tanggalSesi = $this->hitungTanggalSesi(
            Carbon::parse($data['tanggal_mulai']), (int) $data['hari'], (int) $data['jumlah_sesi']
        );

        $berhasil = [];
        $gagal = [];

        foreach ($tanggalSesi as $tanggal) {
            try {
                $booking = $this->bookingService->buatBooking([
                    'lapangan_id' => $data['lapangan_id'],
                    'user_id' => $data['user_id'],
                    'tanggal_booking' => $tanggal->format('Y-m-d'),
                    'jam_mulai' => $data['jam_mulai'],
                    'jam_selesai' => $data['jam_selesai'],
                ]);

                $booking->update(['recurring_booking_id' => $paket->id]);

                $berhasil[] = $booking;
            } catch (Exception $e) {
                $gagal[] = [
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'alasan' => $e->getMessage(),
                ];
            }
        }

        $paket->update([
            'status' => empty($gagal) ? 'selesai' : 'gagal_sebagian',
        ]);

        return [
            'paket' => $paket,
            'berhasil' => $berhasil,
            'gagal' => $gagal,
        ];
    }
}
