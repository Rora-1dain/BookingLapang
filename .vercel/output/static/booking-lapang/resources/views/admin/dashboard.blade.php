@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

    <h2 class="text-2xl font-bold text-gray-800 mb-4">Dashboard Admin</h2>

    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-end gap-4 mb-6 bg-white p-4 rounded-lg shadow">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Dari</label>
            <input type="date" name="dari" value="{{ $dari }}" class="border-gray-300 rounded-md shadow-sm text-sm">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Sampai</label>
            <input type="date" name="sampai" value="{{ $sampai }}" class="border-gray-300 rounded-md shadow-sm text-sm">
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-md">
            Filter
        </button>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Total Pendapatan</p>
            <p class="text-xl font-bold text-gray-800">Rp{{ number_format($totalPendapatan) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Total Booking</p>
            <p class="text-xl font-bold text-gray-800">{{ array_sum($bookingPerStatus) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Lapangan Terfavorit</p>
            <p class="text-xl font-bold text-gray-800">{{ $lapanganFavorit[0]['nama_lapangan'] ?? '-' }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Tingkat Pembatalan</p>
            <p class="text-xl font-bold text-gray-800">{{ $tingkatPembatalan }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-semibold text-gray-600 mb-2">Pendapatan per Bulan</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="chartPendapatan"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-semibold text-gray-600 mb-2">Booking per Status</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 mb-6 overflow-x-auto">
        <h3 class="text-sm font-semibold text-gray-600 mb-3">5 Booking Terbaru</h3>
        <table class="min-w-full text-sm text-left">
            <thead class="text-gray-500 border-b">
                <tr>
                    <th class="py-2 pr-4">User</th>
                    <th class="py-2 pr-4">Lapangan</th>
                    <th class="py-2 pr-4">Tanggal</th>
                    <th class="py-2 pr-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($bookingTerbaru as $b)
                    <tr>
                        <td class="py-2 pr-4">{{ $b->user->name ?? '-' }}</td>
                        <td class="py-2 pr-4">{{ $b->lapangan->nama_lapangan ?? '-' }}</td>
                        <td class="py-2 pr-4">{{ $b->tanggal_booking }}</td>
                        <td class="py-2 pr-4">{{ $b->status }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-3 text-gray-400">Belum ada booking.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-lg shadow p-4 mb-6 overflow-x-auto">
        <h3 class="text-sm font-semibold text-gray-600 mb-3">5 User Paling Aktif</h3>
        <table class="min-w-full text-sm text-left">
            <thead class="text-gray-500 border-b">
                <tr>
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4">Jumlah Booking</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($userAktif as $item)
                    <tr>
                        <td class="py-2 pr-4">{{ $item['nama'] ?? '-' }}</td>
                        <td class="py-2 pr-4">{{ $item['total_booking'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-lg shadow p-4 mb-6 overflow-x-auto">
        <h3 class="text-sm font-semibold text-gray-600 mb-3">Ulasan Dilaporkan — Menunggu Tinjauan</h3>
        <table class="min-w-full text-sm text-left">
            <thead class="text-gray-500 border-b">
                <tr>
                    <th class="py-2 pr-4">User</th>
                    <th class="py-2 pr-4">Rating</th>
                    <th class="py-2 pr-4">Komentar</th>
                    <th class="py-2 pr-4">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($ulasanDilaporkan as $ulasan)
                    <tr>
                        <td class="py-2 pr-4">{{ $ulasan->booking->user->name ?? '-' }}</td>
                        <td class="py-2 pr-4">{{ str_repeat('★', $ulasan->rating) }} ({{ $ulasan->rating }})</td>
                        <td class="py-2 pr-4">{{ $ulasan->komentar ?? '-' }}</td>
                        <td class="py-2 pr-4">{{ $ulasan->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-3 text-gray-400">Tidak ada ulasan yang dilaporkan.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $ulasanDilaporkan->links() }}</div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 overflow-x-auto">
        <h3 class="text-sm font-semibold text-gray-600 mb-3">Statistik Voucher Terpakai</h3>
        <table class="min-w-full text-sm text-left">
            <thead class="text-gray-500 border-b">
                <tr>
                    <th class="py-2 pr-4">Kode Voucher</th>
                    <th class="py-2 pr-4">Total Dipakai</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($statistikVoucher as $item)
                    <tr>
                        <td class="py-2 pr-4">{{ $item->voucher->kode ?? '-' }}</td>
                        <td class="py-2 pr-4">{{ $item->total_pakai }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="py-3 text-gray-400">Belum ada voucher yang dipakai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

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
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
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
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
@endsection