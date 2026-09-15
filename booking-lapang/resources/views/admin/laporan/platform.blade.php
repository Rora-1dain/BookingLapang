@extends('layouts.admin')

@section('title', 'Laporan Keuangan Platform - Super Admin Booking Lapang')

@section('content')
<div class="max-w-[1400px] mx-auto space-y-8">

    {{-- PAGE TITLE & HERO INTRO --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pt-2">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-surface-container text-primary font-label-sm text-xs mb-2">
                <span class="material-symbols-outlined text-sm">account_balance</span>
                Konsolidasi Finansial Multivendor
            </div>
            <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight">Laporan Keuangan &amp; Arus Kas Platform</h1>
            <p class="font-body-md text-on-surface-variant mt-1 max-w-3xl">
                Ringkasan Gross Merchandise Value (GMV), komisi platform, dan alokasi payout mitra pemilik lapangan lintas seluruh venue.
            </p>
        </div>
        <div class="flex items-center gap-3 bg-surface-container-lowest p-2 px-3.5 rounded-xl border border-[#E8E0D3] custom-warm-shadow">
            <div class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></div>
            <div>
                <div class="font-label-sm text-[10px] text-on-surface-variant uppercase tracking-wider">Periode Laporan</div>
                <div class="font-title-md text-xs text-on-surface">{{ $mulai->format('d M Y') }} - {{ $selesai->format('d M Y') }}</div>
            </div>
        </div>
    </div>

    {{-- FILTER PERIODE --}}
    <section class="bg-surface-container-lowest p-4 rounded-2xl border border-[#E8E0D3] custom-warm-shadow">
        <form action="{{ route('admin.laporan.platform') }}" method="GET"
            class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-2 bg-surface-container-low px-3 py-1.5 rounded-xl border border-outline-variant">
                    <span class="material-symbols-outlined text-outline text-lg">calendar_today</span>
                    <input type="date" name="mulai" value="{{ $mulai->format('Y-m-d') }}"
                        class="bg-transparent font-title-md text-xs text-on-surface focus:outline-none">
                    <span class="text-outline text-xs">sampai</span>
                    <input type="date" name="selesai" value="{{ $selesai->format('Y-m-d') }}"
                        class="bg-transparent font-title-md text-xs text-on-surface focus:outline-none">
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <button type="submit"
                    class="px-4 py-2 rounded-xl bg-primary hover:bg-[#3A7D44] text-on-primary font-label-md text-xs flex items-center gap-1.5 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-base">filter_alt</span>
                    <span>Terapkan Filter</span>
                </button>
                <a href="{{ route('admin.laporan.platform.export', request()->query()) }}"
                    class="px-4 py-2 rounded-xl bg-transparent border-[1.5px] border-secondary text-secondary hover:bg-secondary/5 font-label-md text-xs flex items-center gap-1.5 transition-colors">
                    <span class="material-symbols-outlined text-base">download</span>
                    <span>Ekspor Laporan (XLSX)</span>
                </a>
            </div>
        </form>
    </section>

    {{-- 4 KARTU RINGKASAN — dari PlatformReportService::ringkasanKeuanganPlatform() --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="relative bg-surface-container-lowest p-5 rounded-2xl border-t-4 border-t-[#1E5631] border-x border-b border-[#E8E0D3] custom-warm-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="font-label-sm text-xs text-on-surface-variant font-semibold">Gross Merchandise Value</span>
                <span class="w-8 h-8 rounded-lg bg-primary-fixed/60 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-xl">monetization_on</span>
                </span>
            </div>
            <div class="font-headline-md text-headline-md font-bold text-primary tracking-tight">
                Rp {{ number_format($ringkasan['total_gmv'], 0, ',', '.') }}
            </div>
            <p class="font-label-md text-xs text-on-surface font-medium mt-1">Total Nilai Transaksi Kotor Seluruh Arena</p>
        </div>

        <div class="relative bg-surface-container-lowest p-5 rounded-2xl border-t-4 border-t-[#E9C46A] border-x border-b border-[#E8E0D3] custom-warm-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="font-label-sm text-xs text-on-surface-variant font-semibold">Net Platform Revenue</span>
                <span class="w-8 h-8 rounded-lg bg-[#ffdf96]/60 flex items-center justify-center text-[#5a4400]">
                    <span class="material-symbols-outlined text-xl">savings</span>
                </span>
            </div>
            <div class="font-headline-md text-headline-md font-bold text-on-surface tracking-tight">
                Rp {{ number_format($ringkasan['total_komisi_platform'], 0, ',', '.') }}
            </div>
            <p class="font-label-md text-xs text-on-surface font-medium mt-1">Total Komisi Platform</p>
        </div>

        <div class="relative bg-surface-container-lowest p-5 rounded-2xl border-t-4 border-t-secondary border-x border-b border-[#E8E0D3] custom-warm-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="font-label-sm text-xs text-on-surface-variant font-semibold">Payout ke Pemilik</span>
                <span class="w-8 h-8 rounded-lg bg-secondary-fixed/70 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-xl">account_balance_wallet</span>
                </span>
            </div>
            <div class="font-headline-md text-headline-md font-bold text-secondary tracking-tight">
                Rp {{ number_format($ringkasan['total_ke_pemilik'], 0, ',', '.') }}
            </div>
            <p class="font-label-md text-xs text-on-surface font-medium mt-1">Dana Masuk ke Rekening Mitra Arena</p>
        </div>

        <div class="relative bg-surface-container-lowest p-5 rounded-2xl border-t-4 border-t-[#2A9D8F] border-x border-b border-[#E8E0D3] custom-warm-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="font-label-sm text-xs text-on-surface-variant font-semibold">Volume Transaksi</span>
                <span class="w-8 h-8 rounded-lg bg-[#b5f1bf]/40 flex items-center justify-center text-[#2A9D8F]">
                    <span class="material-symbols-outlined text-xl">sports_soccer</span>
                </span>
            </div>
            <div class="font-headline-md text-headline-md font-bold text-on-surface tracking-tight">
                {{ number_format($ringkasan['jumlah_booking'], 0, ',', '.') }} Reservasi
            </div>
            <p class="font-label-md text-xs text-on-surface font-medium mt-1">Total Booking Berstatus Lunas</p>
        </div>
    </section>

    {{-- GRAFIK TREN GMV BULANAN — Chart.js, data dari $trenBulanan --}}
    <section class="bg-surface-container-lowest p-6 rounded-2xl border border-[#E8E0D3] custom-warm-shadow space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-outline-variant/40 pb-4">
            <div>
                <h2 class="font-title-lg text-title-lg text-primary tracking-tight">Tren GMV &amp; Komisi Bulanan</h2>
                <p class="font-body-sm text-on-surface-variant mt-0.5">12 bulan terakhir</p>
            </div>
            <div class="flex items-center gap-3 text-xs font-label-sm">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-sm" style="background:#1E5631"></span>
                    <span class="text-on-surface font-medium">GMV Arena</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-sm" style="background:#E9C46A"></span>
                    <span class="text-on-surface font-medium">Komisi Platform</span>
                </div>
            </div>
        </div>
        <div class="relative h-72">
            <canvas id="chart-tren-gmv"></canvas>
        </div>
    </section>

    {{-- TOP PEMILIK — dari PlatformReportService::topPemilikBerdasarkanPendapatan() --}}
    <section class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="font-headline-sm text-headline-sm text-on-surface">Top {{ $topPemilik->count() }} Mitra Berdasarkan Pendapatan</h2>
                <p class="font-body-sm text-on-surface-variant mt-0.5">Pemilik lapangan dengan pendapatan tertinggi pada periode terpilih.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @forelse ($topPemilik as $index => $pemilik)
                <div class="bg-surface-container-lowest p-4 rounded-2xl border {{ $index === 0 ? 'border-2 border-[#E9C46A]' : 'border-[#E8E0D3]' }} custom-warm-shadow flex items-center gap-4">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-headline-sm text-xs font-extrabold flex-shrink-0
                        {{ $index === 0 ? 'bg-[#E9C46A] text-[#251a00]' : 'bg-surface-container text-on-surface border border-outline-variant' }}">
                        #{{ $index + 1 }}
                    </div>
                    <div class="flex-1 flex items-center justify-between gap-3">
                        <div>
                            <h3 class="font-title-md text-title-md text-primary font-bold">{{ $pemilik->name }}</h3>
                            <p class="font-body-sm text-[12px] text-on-surface-variant">{{ $pemilik->email }}</p>
                        </div>
                        <div class="text-right">
                            <div class="font-label-sm text-[10px] text-on-surface-variant uppercase">Pendapatan</div>
                            <div class="font-headline-sm text-base text-primary font-bold">
                                Rp {{ number_format($pemilik->pendapatan ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-body-sm text-on-surface-variant col-span-2">Belum ada data pendapatan pada periode ini.</p>
            @endforelse
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labelsTren = @json($tren->pluck('label'));
    const dataGmv = @json($tren->pluck('gmv'));
    const dataKomisi = @json($tren->pluck('komisi'));

    new Chart(document.getElementById('chart-tren-gmv'), {
        type: 'bar',
        data: {
            labels: labelsTren,
            datasets: [
                {
                    label: 'GMV Arena',
                    data: dataGmv,
                    backgroundColor: '#1E5631',
                    borderRadius: 4,
                },
                {
                    label: 'Komisi Platform',
                    data: dataKomisi,
                    backgroundColor: '#E9C46A',
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Rp ' + (value / 1000000) + ' Jt'
                    }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush
@endsection