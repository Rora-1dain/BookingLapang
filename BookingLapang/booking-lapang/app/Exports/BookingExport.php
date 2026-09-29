<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingExport implements FromCollection, WithHeadings
{
    public function collection(): Enumerable
    {
        return Booking::with(['lapangan', 'user'])->latest()->get()->map(function ($booking) {
            return [
                'nama_pemesan' => $booking->user->name,
                'lapangan' => $booking->lapangan->nama_lapangan,
                'tanggal_booking' => $booking->tanggal_booking->format('d-m-Y'),
                'jam' => $booking->jam_mulai.' - '.$booking->jam_selesai,
                'total_harga' => $booking->total_harga,
                'status' => $booking->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama Pemesan', 'Lapangan', 'Tanggal', 'Jam', 'Total Harga', 'Status'];
    }
}
