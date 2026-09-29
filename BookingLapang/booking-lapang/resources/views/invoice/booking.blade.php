<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px; border-bottom: 1px solid #ddd; }
        .header { text-align: center; margin-bottom: 20px; }
        .qr { text-align: right; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Booking Lapang</h2>
        <p>Invoice Booking Lapang</p>
    </div>

    <p><b>No. Invoice:</b> {{ $booking->nomor_invoice }}</p>
    <p><b>Lapangan:</b> {{ $booking->lapangan->nama_lapangan }}</p>
    <p><b>Tanggal:</b> {{ $booking->tanggal_booking->format('d-m-Y') }}, {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</p>
    <p><b>Atas Nama:</b> {{ $booking->user->name }}</p>

    <table>
        <tr><td>Subtotal</td><td>Rp{{ number_format($subtotal) }}</td></tr>
        <tr><td>Diskon Voucher</td><td>-Rp{{ number_format($booking->total_diskon) }}</td></tr>
        <tr><td><b>Total Dibayar</b></td><td><b>Rp{{ number_format($booking->total_harga) }}</b></td></tr>
    </table>

    <p><b>Status Pembayaran:</b> {{ strtoupper($booking->status_pembayaran) }}</p>

    <div class="qr">
        <img src="{{ $qrCodePath }}" width="100">
        <p style="font-size: 10px;">Scan untuk verifikasi keaslian invoice</p>
    </div>
</body>
</html>