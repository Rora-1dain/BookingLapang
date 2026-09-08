<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Payout #{{ $payout->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h2 { margin-bottom: 0; }
        .info { margin-bottom: 20px; }
        .info td { padding: 2px 8px 2px 0; }
        table.rincian { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.rincian th, table.rincian td {
            border: 1px solid #ccc; padding: 6px 8px; text-align: left; font-size: 11px;
        }
        table.rincian th { background: #f2f2f2; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; background: #f9f9f9; }
    </style>
</head>
<body>
    <h2>Laporan Payout</h2>
    <p>Booking Lapang - Marketplace</p>

    <table class="info">
        <tr>
            <td><strong>Pemilik</strong></td>
            <td>: {{ $payout->pemilik->name }}</td>
        </tr>
        <tr>
            <td><strong>Periode</strong></td>
            <td>: {{ \Carbon\Carbon::parse($payout->periode_mulai)->format('d M Y') }}
                - {{ \Carbon\Carbon::parse($payout->periode_selesai)->format('d M Y') }}</td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>: {{ ucfirst($payout->status) }}</td>
        </tr>
        <tr>
            <td><strong>Total Nominal</strong></td>
            <td>: Rp{{ number_format($payout->total_nominal, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table class="rincian">
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal Booking</th>
                <th>Lapangan</th>
                <th class="text-right">Total Harga</th>
                <th class="text-right">Komisi</th>
                <th class="text-right">Pendapatan Pemilik</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $i => $booking)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</td>
                    <td>{{ $booking->lapangan->nama_lapangan ?? '-' }}</td>
                    <td class="text-right">Rp{{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($booking->nominal_komisi, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($booking->pendapatan_pemilik, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" class="text-right">Total</td>
                <td class="text-right">Rp{{ number_format($payout->total_nominal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>