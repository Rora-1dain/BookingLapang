<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Invoice</title>
</head>
<body style="font-family: sans-serif; padding: 40px;">
    @if($valid)
        <h2 style="color: green;">✓ Invoice Valid</h2>
        <p><b>No. Invoice:</b> {{ $booking->nomor_invoice }}</p>
        <p><b>Lapangan:</b> {{ $booking->lapangan->nama_lapangan }}</p>
        <p><b>Tanggal:</b> {{ $booking->tanggal_booking->format('d-m-Y') }}</p>
        <p><b>Total:</b> Rp{{ number_format($booking->total_harga) }}</p>
    @else
        <h2 style="color: red;">✗ Invoice Tidak Ditemukan</h2>
        <p>Nomor invoice ini tidak valid atau tidak terdaftar dalam sistem kami.</p>
    @endif
</body>
</html>