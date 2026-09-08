<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Payout</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h2 { margin-bottom: 0; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f0f0f0; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background: #f9f9f9; }
    </style>
</head>
<body>
    <h2>Laporan Payout</h2>
    <div class="info">
        <strong>Pemilik:</strong> {{ $payout->pemilik->name }}<br>
        <strong>Periode:</strong> {{ $payout->periode_mulai->format('d M Y') }} - {{ $payout->periode_selesai->format('d M Y') }}<br>
        <strong>Status:</strong> {{ ucfirst($payout->status) }}<br>
        <strong>Dibuat pada:</strong> {{ $payout->created_at->format('d M Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No. Invoice</th>
                <th>Lapangan</th>
                <th>Tanggal Booking</th>
                <th class="text-right">Total Harga</th>
                <th class="text-right">Komisi</th>
                <th class="text-right">Pendapatan Pemilik</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payout->bookings as $booking)
            <tr>
                <td>{{ $booking->nomor_invoice }}</td>
                <td>{{ $booking->lapangan->nama_lapangan }}</td>
                <td>{{ $booking->tanggal_booking->format('d M Y') }}</td>
                <td class="text-right">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($booking->nominal_komisi, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($booking->pendapatan_pemilik, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">Total Payout</td>
                <td class="text-right">Rp {{ number_format($payout->total_nominal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>