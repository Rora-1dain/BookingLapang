@component('mail::message')
# Invoice Booking Anda

Halo {{ $booking->user->name }},

Berikut invoice untuk booking Anda:

- **No. Invoice:** {{ $booking->nomor_invoice }}
- **Lapangan:** {{ $booking->lapangan->nama_lapangan }}
- **Tanggal:** {{ $booking->tanggal_booking->format('d-m-Y') }}
- **Total:** Rp{{ number_format($booking->total_harga) }}

File invoice PDF terlampir pada email ini.

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent