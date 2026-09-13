<x-partner-layout :title="'Dashboard Admin - Booking Lapang'">

    <x-admin-sidebar active="dashboard" />

    <div class="flex-1 flex flex-col min-h-screen ml-64 bg-background">
        <header class="sticky top-0 right-0 h-16 w-full border-b border-outline-variant bg-surface-container-lowest shadow-sm z-30 flex items-center justify-between px-8">
            <span class="text-title-lg text-on-surface font-bold">Dashboard Admin</span>
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-end gap-3">
                <div>
                    <label class="block text-[11px] text-on-surface-variant mb-1">Dari</label>
                    <input type="date" name="dari" value="{{ $dari }}" class="border border-outline-variant rounded-lg text-body-sm px-2 py-1.5">
                </div>
                <div>
                    <label class="block text-[11px] text-on-surface-variant mb-1">Sampai</label>
                    <input type="date" name="sampai" value="{{ $sampai }}" class="border border-outline-variant rounded-lg text-body-sm px-2 py-1.5">
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-primary text-on-primary text-label-md font-bold shadow-sm hover:bg-primary-container transition-all">
                    Filter
                </button>
                @if ($dari || $sampai)
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-xl border border-outline-variant text-on-surface-variant text-label-md hover:bg-surface-container">Reset</a>
                @endif
            </form>
        </header>

        <main class="flex-1 p-8 space-y-8 max-w-7xl mx-auto w-full">

            @if (session('success'))
                <div class="bg-primary/10 border border-primary/30 text-primary p-4 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
            @endif

            {{-- KPI Utama --}}
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant shadow-sm">
                    <span class="text-label-md text-on-surface-variant">Total Pendapatan (GMV Lunas)</span>
                    <div class="text-headline-md font-bold text-on-surface tracking-tight mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </div>
                <div class="bg-primary p-5 rounded-2xl shadow-sm text-on-primary">
                    <span class="text-label-md text-primary-fixed">Estimasi Komisi Platform</span>
                    <div class="text-headline-md font-bold text-primary-fixed tracking-tight mt-1">Rp {{ number_format($totalKomisi, 0, ',', '.') }}</div>
                </div>
                <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant shadow-sm">
                    <span class="text-label-md text-on-surface-variant">Total Booking</span>
                    <div class="text-headline-md font-bold text-on-surface tracking-tight mt-1">{{ array_sum($bookingPerStatus) }}</div>
                </div>
                <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant shadow-sm">
                    <span class="text-label-md text-on-surface-variant">Tingkat Pembatalan</span>
                    <div class="text-headline-md font-bold text-on-surface tracking-tight mt-1">{{ $tingkatPembatalan }}%</div>
                </div>
            </section>

            {{-- Quick Actions: Antrian butuh tindakan (data asli) --}}
            <section class="bg-surface-container-low p-5 rounded-2xl border border-outline-variant shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-on-primary shadow-sm">
                        <span class="material-symbols-outlined text-title-md">bolt</span>
                    </div>
                    <div>
                        <div class="font-bold text-on-surface text-title-md">Antrian Butuh Tindakan</div>
                        <div class="text-body-sm text-on-surface-variant">Jumlah asli dari database, klik buat langsung ke halamannya</div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="{{ route('admin.verifikasi.index') }}" class="p-4 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary transition-colors flex items-center justify-between">
                        <div>
                            <span class="text-label-md text-on-surface-variant">Verifikasi Mitra Menunggu</span>
                            <div class="text-title-lg font-bold text-on-surface">{{ $mitraMenunggu }}</div>
                        </div>
                        <span class="material-symbols-outlined text-primary text-2xl">verified_user</span>
                    </a>
                    <a href="{{ route('admin.lapangan.approval') }}" class="p-4 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary transition-colors flex items-center justify-between">
                        <div>
                            <span class="text-label-md text-on-surface-variant">Lapangan Menunggu Approval</span>
                            <div class="text-title-lg font-bold text-on-surface">{{ $totalLapanganMenunggu }}</div>
                        </div>
                        <span class="material-symbols-outlined text-primary text-2xl">sports_tennis</span>
                    </a>
                    <a href="{{ route('admin.refund.index') }}" class="p-4 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary transition-colors flex items-center justify-between">
                        <div>
                            <span class="text-label-md text-on-surface-variant">Refund Sedang Diproses</span>
                            <div class="text-title-lg font-bold text-on-surface">{{ $refundMenunggu }}</div>
                        </div>
                        <span class="material-symbols-outlined text-secondary text-2xl">assignment_return</span>
                    </a>
                </div>
            </section>

            {{-- Preview lapangan menunggu approval --}}
            @if ($lapanganMenunggu->isNotEmpty())
                <section class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-title-lg text-on-surface font-bold">Lapangan Menunggu Persetujuan</h2>
                        <a href="{{ route('admin.lapangan.approval') }}" class="text-label-md text-primary font-bold hover:underline">Lihat semua ({{ $totalLapanganMenunggu }}) &rarr;</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($lapanganMenunggu as $lapangan)
                            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant">
                                <div class="font-bold text-on-surface">{{ $lapangan->nama_lapangan }}</div>
                                <div class="text-body-sm text-on-surface-variant mt-0.5">{{ $lapangan->jenis }} &bull; {{ $lapangan->kota ?? 'Kota belum diisi' }}</div>
                                <div class="text-body-sm text-on-surface-variant">Pemilik: {{ $lapangan->pemilik->name ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Charts --}}
            <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm">
                    <h3 class="text-title-lg text-on-surface font-bold mb-3">Pendapatan per Bulan</h3>
                    <div class="relative" style="height: 280px;">
                        <canvas id="chartPendapatan"></canvas>
                    </div>
                </div>
                <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm">
                    <h3 class="text-title-lg text-on-surface font-bold mb-3">Booking per Status</h3>
                    <div class="relative" style="height: 280px;">
                        <canvas id="chartStatus"></canvas>
                    </div>
                </div>
            </section>

            {{-- Lapangan Favorit & User Aktif --}}
            <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm">
                    <h3 class="text-title-lg text-on-surface font-bold mb-3">Lapangan Terfavorit</h3>
                    <div class="space-y-2.5">
                        @forelse ($lapanganFavorit as $i => $item)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-surface-container-low border border-outline-variant">
                                <div class="flex items-center gap-3">
                                    <span class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-label-sm">{{ $i + 1 }}</span>
                                    <span class="font-semibold text-on-surface">{{ $item['nama_lapangan'] }}</span>
                                </div>
                                <span class="text-label-md text-on-surface-variant">{{ $item['total_booking'] }} booking</span>
                            </div>
                        @empty
                            <p class="text-body-sm text-on-surface-variant">Belum ada data booking.</p>
                        @endforelse
                    </div>
                </div>
                <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm">
                    <h3 class="text-title-lg text-on-surface font-bold mb-3">User Paling Aktif</h3>
                    <div class="space-y-2.5">
                        @forelse ($userAktif as $item)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-surface-container-low border border-outline-variant">
                                <span class="font-semibold text-on-surface">{{ $item['nama'] }}</span>
                                <span class="text-label-md text-on-surface-variant">{{ $item['total_booking'] }} booking</span>
                            </div>
                        @empty
                            <p class="text-body-sm text-on-surface-variant">Belum ada data booking.</p>
                        @endforelse
                    </div>
                </div>
            </section>

            {{-- Booking Terbaru --}}
            <section class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                <div class="p-6 border-b border-outline-variant">
                    <h2 class="text-title-lg text-on-surface font-bold">Booking Terbaru</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container text-on-surface-variant text-label-sm uppercase tracking-wider border-b border-outline-variant">
                                <th class="py-3 px-6">User</th>
                                <th class="py-3 px-6">Lapangan</th>
                                <th class="py-3 px-6">Tanggal</th>
                                <th class="py-3 px-6">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant text-body-sm">
                            @forelse ($bookingTerbaru as $b)
                                @php
                                    $badge = match($b->status) {
                                        'confirmed' => 'bg-primary/10 text-primary',
                                        'cancelled' => 'bg-error-container text-error',
                                        default => 'bg-tertiary-fixed/40 text-tertiary',
                                    };
                                @endphp
                                <tr class="hover:bg-surface-container-low transition-colors">
                                    <td class="py-3 px-6 font-medium text-on-surface">{{ $b->user->name ?? '-' }}</td>
                                    <td class="py-3 px-6 text-on-surface-variant">{{ $b->lapangan->nama_lapangan ?? '-' }}</td>
                                    <td class="py-3 px-6 text-on-surface-variant">{{ $b->tanggal_booking->translatedFormat('d M Y') }}</td>
                                    <td class="py-3 px-6"><span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $badge }}">{{ ucfirst($b->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-6 px-6 text-center text-on-surface-variant">Belum ada booking.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Ulasan Dilaporkan --}}
            <section class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                <div class="p-6 border-b border-outline-variant">
                    <h2 class="text-title-lg text-on-surface font-bold">Ulasan Dilaporkan — Menunggu Tinjauan</h2>
                    <p class="text-body-sm text-on-surface-variant">{{ $ulasanDilaporkan->total() }} ulasan dilaporkan oleh pengguna</p>
                </div>
                <div class="divide-y divide-outline-variant">
                    @forelse ($ulasanDilaporkan as $ulasan)
                        <div class="p-5 flex items-start justify-between gap-4">
                            <div>
                                <div class="font-semibold text-on-surface">{{ $ulasan->booking->user->name ?? '-' }}</div>
                                <div class="text-amber-500 text-sm">{{ str_repeat('★', $ulasan->rating) }}{{ str_repeat('☆', 5 - $ulasan->rating) }}</div>
                                <p class="text-body-sm text-on-surface-variant mt-1">{{ $ulasan->komentar ?? '-' }}</p>
                            </div>
                            <span class="text-[11px] text-outline whitespace-nowrap">{{ $ulasan->created_at->format('d M Y') }}</span>
                        </div>
                    @empty
                        <p class="p-6 text-body-sm text-on-surface-variant">Tidak ada ulasan yang dilaporkan.</p>
                    @endforelse
                </div>
                <div class="p-4">{{ $ulasanDilaporkan->links() }}</div>
            </section>

            {{-- Statistik Voucher --}}
            <section class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
                <div class="p-6 border-b border-outline-variant">
                    <h2 class="text-title-lg text-on-surface font-bold">Statistik Voucher Terpakai</h2>
                </div>
                <div class="divide-y divide-outline-variant">
                    @forelse ($statistikVoucher as $item)
                        <div class="p-4 px-6 flex items-center justify-between">
                            <span class="font-mono font-semibold text-on-surface">{{ $item->voucher->kode ?? '-' }}</span>
                            <span class="text-label-md text-on-surface-variant">{{ $item->total_pakai }}x dipakai</span>
                        </div>
                    @empty
                        <p class="p-6 text-body-sm text-on-surface-variant">Belum ada voucher yang dipakai.</p>
                    @endforelse
                </div>
            </section>
        </main>
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
                    backgroundColor: '#1E5631'
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        new Chart(document.getElementById('chartStatus'), {
            type: 'pie',
            data: {
                labels: @json(array_keys($bookingPerStatus)),
                datasets: [{
                    data: @json(array_values($bookingPerStatus)),
                    backgroundColor: ['#E9C46A', '#1E5631', '#E76F51']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>
</x-partner-layout>