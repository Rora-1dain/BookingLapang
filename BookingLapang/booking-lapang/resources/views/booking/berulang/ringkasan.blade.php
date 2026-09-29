@extends('layouts.frontend')
@section('title', 'Ringkasan Hasil Paket Booking Berulang')
@section('content')

@php
    $totalDiminta = count($berhasil) + count($gagal);
    $totalBerhasil = count($berhasil);
    $totalGagal = count($gagal);
    $totalTagihan = collect($berhasil)->sum('total_harga');

    $statusMap = [
        'selesai' => ['label' => 'Semua Sesi Berhasil Dijadwalkan', 'badge' => 'bg-primary-fixed text-on-primary-fixed', 'icon' => 'check_circle'],
        'gagal_sebagian' => ['label' => 'Sebagian Berhasil Dijadwalkan', 'badge' => 'bg-tertiary-container text-on-tertiary', 'icon' => 'warning'],
        'diproses' => ['label' => 'Sedang Diproses', 'badge' => 'bg-surface-container text-on-surface', 'icon' => 'hourglass_empty'],
    ];
    $status = $statusMap[$paket->status] ?? $statusMap['diproses'];
@endphp

<main class="flex-grow w-full max-w-7xl mx-auto px-6 md:px-12 py-8 space-y-8">

    <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-label-md font-label-md text-on-surface-variant flex-wrap">
        <a class="hover:text-primary transition-colors" href="{{ route('booking.index') }}">Beranda</a>
        <span class="text-outline-variant">/</span>
        @if(isset($lapangan))
            <span>{{ $lapangan->nama_lapangan }}</span>
            <span class="text-outline-variant">/</span>
        @endif
        <span class="text-primary font-bold">Hasil Jadwal Paket #{{ $paket->id }}</span>
    </nav>

    @if(isset($lapangan))
    <section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 md:p-8 court-shadow">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <h1 class="text-headline-sm md:text-headline-md font-headline-md text-on-surface tracking-tight">
                    {{ $lapangan->nama_lapangan }}
                </h1>
                <div class="flex items-center gap-1.5 mt-1 text-body-md font-body-md text-on-surface-variant">
                    <span class="material-symbols-outlined text-base text-primary">location_on</span>
                    <span>{{ $lapangan->kota ?? '-' }}</span>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Status Banner -->
    <section class="rounded-2xl border border-tertiary-fixed-dim/60 bg-surface-container-low p-6 court-shadow">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl {{ $status['badge'] }} flex items-center justify-center shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-2xl">{{ $status['icon'] }}</span>
                </div>
                <div>
                    <span class="px-3 py-1 rounded-full {{ $status['badge'] }} font-bold text-label-md">{{ $status['label'] }}</span>
                    <p class="text-body-md font-body-md text-on-surface mt-2 max-w-3xl leading-relaxed">
                        Sistem memverifikasi ketersediaan {{ $totalDiminta }} sesi. {{ $totalBerhasil }} sesi berhasil dijadwalkan
                        @if($totalGagal > 0)
                            dan {{ $totalGagal }} sesi dilewati karena bentrok/tidak tersedia — sesi yang dilewati tidak ditagih.
                        @else
                            .
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Metric Cards -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 court-shadow">
            <span class="text-label-md font-label-md text-on-surface-variant">Total Sesi Diminta</span>
            <div class="flex items-baseline gap-2 mt-4">
                <span class="text-headline-xl font-headline-xl text-on-surface font-extrabold">{{ $totalDiminta }}</span>
                <span class="text-title-lg font-title-lg text-outline">Sesi</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest border-2 border-primary-container rounded-2xl p-6 court-shadow-lg">
            <span class="text-label-md font-label-md text-primary font-bold">Sesi Berhasil Dijadwalkan</span>
            <div class="flex items-baseline gap-2 mt-4">
                <span class="text-headline-xl font-headline-xl text-primary font-extrabold">{{ $totalBerhasil }}</span>
                <span class="text-title-lg font-title-lg text-primary font-semibold">Sesi Siap</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-secondary-container/80 rounded-2xl p-6 court-shadow">
            <span class="text-label-md font-label-md text-secondary font-bold">Sesi Gagal / Dilewati</span>
            <div class="flex items-baseline gap-2 mt-4">
                <span class="text-headline-xl font-headline-xl text-secondary font-extrabold">{{ $totalGagal }}</span>
                <span class="text-title-lg font-title-lg text-secondary font-semibold">Sesi Bentrok</span>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-8 space-y-8">

            <!-- Sesi Berhasil -->
            <section class="space-y-4">
                <h2 class="text-title-lg font-title-lg text-on-surface flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-on-primary-fixed">
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                    </span>
                    Sesi Siap Dipesan <span class="text-primary font-bold">({{ $totalBerhasil }} Sesi)</span>
                </h2>

                @forelse($berhasil as $i => $booking)
                    <div class="bg-surface-container-lowest border border-outline-variant border-l-4 border-l-primary-container rounded-r-xl p-5 court-shadow flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-surface-container flex flex-col items-center justify-center shrink-0 border border-outline-variant">
                                <span class="text-label-sm font-label-sm text-outline">SESI</span>
                                <span class="text-title-md font-title-md font-bold text-primary">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-title-md font-title-md text-on-surface font-bold">
                                        {{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('l, d F Y') }}
                                    </h3>
                                    <span class="px-2.5 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-bold text-label-sm flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">check</span>
                                        Terjadwal &amp; Slot Aman
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 mt-1.5 text-body-sm font-body-sm text-on-surface-variant">
                                    <span class="flex items-center gap-1 font-medium text-on-surface">
                                        <span class="material-symbols-outlined text-xs text-primary">schedule</span>
                                        {{ $booking->jam_mulai }} – {{ $booking->jam_selesai }} WIB
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex md:flex-col items-end justify-between md:justify-center border-t md:border-t-0 pt-3 md:pt-0 border-outline-variant">
                            <span class="text-title-md font-title-md font-bold text-on-surface">Rp {{ number_format($booking->total_harga ?? 0,0,',','.') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-body-sm text-outline">Belum ada sesi yang berhasil dibooking.</p>
                @endforelse
            </section>

            <!-- Sesi Gagal -->
            @if($totalGagal > 0)
            <section class="space-y-4 pt-2">
                <h2 class="text-title-lg font-title-lg text-on-surface flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-secondary-fixed text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-lg">event_busy</span>
                    </span>
                    Sesi yang Dilewati / Tidak Dapat Dipesan <span class="text-secondary font-bold">({{ $totalGagal }} Sesi)</span>
                </h2>

                @foreach($gagal as $i => $item)
                    <div class="bg-[#FFFBF9] border border-secondary-container/60 border-l-4 border-l-secondary rounded-r-xl p-5 court-shadow">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-secondary-fixed-dim/40 flex flex-col items-center justify-center shrink-0 border border-secondary-container/40">
                                    <span class="text-label-sm font-label-sm text-secondary font-semibold">SESI</span>
                                    <span class="text-title-md font-title-md font-bold text-secondary">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-title-md font-title-md text-on-surface font-bold line-through decoration-secondary/50">
                                            {{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('l, d F Y') }}
                                        </h3>
                                        <span class="px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-bold text-label-sm">
                                            Dilewati (Tidak Ditagih)
                                        </span>
                                    </div>
                                    <div class="mt-3 p-3 rounded-lg bg-white border border-secondary-container/50 flex items-start gap-2.5">
                                        <span class="material-symbols-outlined text-base text-secondary mt-0.5">cancel</span>
                                        <div>
                                            <span class="text-label-sm font-label-sm text-secondary block font-bold">Penyebab Ketiadaan Slot:</span>
                                            <p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">{{ $item['alasan'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>
            @endif

            @if($totalBerhasil === 0)
            <div class="p-5 rounded-2xl bg-surface-container-low border border-dashed border-outline-variant flex items-start gap-4">
                <span class="material-symbols-outlined text-xl text-outline">info</span>
                <div class="text-body-sm font-body-sm">
                    <span class="font-bold text-on-surface block text-label-md font-label-md">Jadwal Penuh - Tidak Ada Sesi Berhasil</span>
                    <p class="text-on-surface-variant mt-0.5">Semua tanggal yang diminta bentrok. Tidak ada penagihan atau transaksi yang dibuat untuk paket ini.</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Kolom Kanan: Pembayaran -->
        <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-28">
            @if($totalBerhasil > 0)
            <div class="bg-surface-container-lowest border-2 border-outline-variant rounded-2xl p-6 court-shadow-lg">
                <div class="flex items-center justify-between pb-4 border-b border-outline-variant">
                    <h2 class="text-title-lg font-title-lg text-on-surface font-bold">Rincian Pembayaran</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed text-label-sm font-bold">1x Transaksi</span>
                </div>

                <div class="space-y-3.5 py-5 text-body-md font-body-md border-b border-outline-variant">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-on-surface font-medium block">{{ $totalBerhasil }} Sesi Berhasil</span>
                        </div>
                        <span class="font-bold text-on-surface">Rp {{ number_format($totalTagihan,0,',','.') }}</span>
                    </div>
                    @if($totalGagal > 0)
                    <div class="p-2.5 rounded-xl bg-surface-container flex items-center justify-between text-body-sm font-body-sm text-secondary">
                        <span class="flex items-center gap-1 font-semibold">
                            <span class="material-symbols-outlined text-sm">check</span>
                            {{ $totalGagal }} Sesi Bentrok (Dilewati)
                        </span>
                        <span class="font-bold">Rp 0</span>
                    </div>
                    @endif
                </div>

                <div class="pt-5 pb-6">
                    <div class="flex justify-between items-baseline">
                        <span class="text-title-md font-title-md text-on-surface font-semibold">Total Tagihan</span>
                        <span class="text-headline-sm font-headline-sm font-extrabold text-primary">Rp {{ number_format($totalTagihan,0,',','.') }}</span>
                    </div>
                </div>

                <button id="btnBayar" data-paket-id="{{ $paket->id }}"
                    class="w-full h-12 bg-primary-container hover:bg-surface-tint active:scale-[0.98] text-white rounded-xl font-label-lg flex items-center justify-center gap-2 shadow-md transition-all">
                    <span class="material-symbols-outlined text-lg">lock</span>
                    <span>Bayar Sekarang (1x Transaksi)</span>
                </button>

                <div class="mt-5 pt-4 border-t border-outline-variant flex items-start gap-2 text-body-sm font-body-sm text-outline">
                    <span class="material-symbols-outlined text-sm text-primary shrink-0 mt-0.5">verified</span>
                    <p>Satu pembayaran untuk semua sesi berhasil. Sesi gagal otomatis dilewati dan tidak dikenakan biaya.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</main>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
document.getElementById('btnBayar')?.addEventListener('click', function () {
    const btn = this;
    const paketId = btn.dataset.paketId;
    btn.disabled = true;
    btn.innerHTML = '<span>Memuat pembayaran...</span>';

    fetch(`{{ url('/booking-berulang') }}/${paketId}/bayar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
    })
    .then(res => res.json())
    .then(data => {
        if (data.snap_token) {
            snap.pay(data.snap_token, {
                onSuccess: () => window.location.reload(),
                onPending: () => window.location.reload(),
                onError: () => alert('Pembayaran gagal, coba lagi.'),
                onClose: () => {
                    btn.disabled = false;
                    btn.innerHTML = '<span class="material-symbols-outlined text-lg">lock</span><span>Bayar Sekarang (1x Transaksi)</span>';
                },
            });
        } else {
            alert(data.message || 'Gagal membuat transaksi.');
            btn.disabled = false;
        }
    })
    .catch(() => {
        alert('Terjadi kesalahan, coba lagi.');
        btn.disabled = false;
    });
});
</script>
@endsection