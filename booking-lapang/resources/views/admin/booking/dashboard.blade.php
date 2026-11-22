@extends('layouts.app')

@section('content')
<h2>Dashboard Admin</h2>

<form method="GET" action="{{ route('admin.dashboard') }}">
    <label>Dari: <input type="date" name="dari" value="{{ $dari }}"></label>
    <label>Sampai: <input type="date" name="sampai" value="{{ $sampai }}"></label>
    <button type="submit">Filter</button>
</form>

<div class="ringkasan" style="display:flex; gap:16px; margin:16px 0;">
    <div class="kartu">Total Pendapatan<br><strong>Rp{{ number_format($totalPendapatan) }}</strong></div>
    <div class="kartu">Total Booking<br><strong>{{ array_sum($bookingPerStatus) }}</strong></div>
    <div class="kartu">Lapangan Terfavorit<br>
        <strong>{{ $lapanganFavorit->first()->lapangan->nama_lapangan ?? '-' }}</strong>
    </div>
    <div class="kartu">Tingkat Pembatalan<br><strong>{{ $tingkatPembatalan }}%</strong></div>
</div>

<div style="display:flex; gap:24px;">
    <canvas id="chartPendapatan" height="120"></canvas>
    <canvas id="chartStatus" height="120"></canvas>
</div>

<h3>5 Booking Terbaru</h3>
<table border="1" cellpadding="6">
    <tr><th>User</th><th>Lapangan</th><th>Tanggal</th><th>Status</th></tr>
    @forelse ($bookingTerbaru as $b)
        <tr>
            <td>{{ $b->user->name ?? '-' }}</td>
            <td>{{ $b->lapangan->nama_lapangan ?? '-' }}</td>
            <td>{{ $b->tanggal_booking }}</td>
            <td>{{ $b->status }}</td>
        </tr>
    @empty
        <tr><td colspan="4">Belum ada booking.</td></tr>
    @endforelse
</table>

<h3>5 User Paling Aktif</h3>
<table border="1" cellpadding="6">
    <tr><th>Nama</th><th>Jumlah Booking</th></tr>
    @foreach ($userAktif as $item)
        <tr>
            <td>{{ $item->user->name ?? '-' }}</td>
            <td>{{ $item->total_booking }}</td>
        </tr>
    @endforeach
</table>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartPendapatan'), {
    type: 'bar',
    data: {
        labels: @json(array_keys($pendapatanBulanan)),
        datasets: [{
            label: 'Pendapatan per Bulan',
            data: @json(array_values($pendapatanBulanan)),
            backgroundColor: '#4e73df'
        }]
    }
});

new Chart(document.getElementById('chartStatus'), {
    type: 'pie',
    data: {
        labels: @json(array_keys($bookingPerStatus)),
        datasets: [{
            data: @json(array_values($bookingPerStatus)),
            backgroundColor: ['#f6c23e', '#1cc88a', '#e74a3b']
        }]
    }
});
</script>
@endsection