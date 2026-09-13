<x-partner-layout :title="'Dashboard - Booking Lapang Partner'">

    <x-partner-sidebar active="dashboard" />

    {{-- ==================== TOP BAR ==================== --}}
    <header class="fixed top-0 right-0 left-72 z-20 flex justify-between items-center px-8 h-16 bg-[#FBF7F0]/90 backdrop-blur-md border-b border-surface-container-high">
        <div class="flex items-center gap-2.5">
            <span class="text-title-md font-bold text-primary tracking-tight">Booking Lapang</span>
            <span class="text-outline-variant">/</span>
            <span class="text-label-md font-semibold text-on-surface-variant">Partner Hub</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('pemilik.lapangan.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-label-sm font-bold transition-all shadow-sm">
                <span class="material-symbols-outlined text-lg">add_circle</span>
                <span>Ajukan Lapangan Baru</span>
            </a>
            <div class="flex items-center gap-2.5 pl-2 border-l border-surface-container-highest">
                <div class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold text-label-sm shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="hidden xl:block leading-tight text-left">
                    <p class="text-label-sm font-bold text-on-surface">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-outline">Pemilik Lapangan</p>
                </div>
            </div>
        </div>
    </header>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <main class="ml-72 pt-16 flex-1 min-h-screen bg-background p-8">
        <div class="max-w-[1340px] mx-auto space-y-7">

            {{-- Header ringkasan --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-surface-container-high shadow-sm">
                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-headline-sm font-extrabold text-on-surface tracking-tight">Halo, {{ auth()->user()->name }}</h2>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{ $totalLapanganAktif }} Lapangan Aktif
                        </span>
                    </div>
                    <p class="text-body-sm text-on-surface-variant flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-base">calendar_today</span>
                        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                    </p>
                </div>
            </div>

            {{-- 3 Kartu Metrik --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                <div class="lg:col-span-5 bg-gradient-to-br from-[#1E5631] to-[#12361e] text-white p-6 rounded-3xl shadow-md flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-44 h-44 rounded-full bg-white/5 pointer-events-none"></div>
                    <div class="flex items-start justify-between relative z-10">
                        <div class="space-y-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-200 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm">account_balance_wallet</span> Pendapatan Bulan Ini
                            </span>
                            <div class="text-3xl font-extrabold tracking-tight mt-1">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-5 pt-4 border-t border-white/15 relative z-10">
                        <div class="bg-white/10 rounded-2xl p-3">
                            <span class="text-[11px] text-white/70 block">Booking Bulan Ini</span>
                            <strong class="text-base font-bold text-white">{{ $jumlahBookingBulanIni }}</strong>
                        </div>
                        <div class="bg-white/10 rounded-2xl p-3">
                            <span class="text-[11px] text-white/70 block">Lapangan Aktif</span>
                            <strong class="text-base font-bold text-tertiary-fixed">{{ $totalLapanganAktif }} / {{ $lapangans->count() }}</strong>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 bg-white p-5 rounded-3xl border border-surface-container-high shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between pb-3 border-b border-surface-container-high">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                                <span class="material-symbols-outlined text-lg">star</span>
                            </span>
                            <div>
                                <h3 class="text-label-md font-bold text-on-surface">Rating Rata-rata</h3>
                                <p class="text-[11px] text-outline">Dari {{ $totalUlasan }} ulasan penyewa</p>
                            </div>
                        </div>
                        <span class="text-xl font-extrabold text-primary">{{ $ratingRataRata }}</span>
                    </div>
                    <div class="flex items-center text-amber-500 mt-3">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' {{ $i <= round($ratingRataRata) ? 1 : 0 }};">star</span>
                        @endfor
                    </div>
                </div>

                <div class="lg:col-span-3 bg-white p-5 rounded-3xl border border-surface-container-high shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wide">Total Lapangan Terdaftar</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-on-surface">{{ $lapangans->count() }}</span>
                    </div>
                    <a href="{{ route('pemilik.lapangan.index') }}" class="text-xs font-bold text-primary hover:underline mt-2 inline-flex items-center gap-1">
                        Kelola semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            {{-- Daftar Lapangan Saya --}}
            <div class="bg-white p-6 rounded-3xl border border-surface-container-high shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-surface-container-high">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl">stadium</span>
                        </div>
                        <div>
                            <h3 class="text-title-md font-bold text-on-surface">Lapangan Saya</h3>
                            <p class="text-body-sm text-on-surface-variant">Status &amp; tarif tiap lapangan yang lu daftarin</p>
                        </div>
                    </div>
                    <a href="{{ route('pemilik.lapangan.create') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                        + Tambah Lapangan
                    </a>
                </div>

                @if ($lapangans->isEmpty())
                    <p class="text-body-sm text-on-surface-variant py-6 text-center">Belum ada lapangan terdaftar. Ajukan lapangan pertama lu.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                        @foreach ($lapangans as $lapangan)
                            @php
                                $foto = $lapangan->fotos->firstWhere('is_utama', true) ?? $lapangan->fotos->first();
                                $badge = match($lapangan->status_approval) {
                                    'disetujui' => ['Aktif', 'bg-emerald-100 text-emerald-800 border-emerald-200'],
                                    'ditolak' => ['Ditolak', 'bg-red-100 text-red-800 border-red-200'],
                                    default => ['Menunggu Persetujuan', 'bg-amber-100 text-amber-900 border-amber-300'],
                                };
                            @endphp
                            <div class="rounded-2xl border border-surface-container-high bg-[#FBF7F0]/60 p-4 space-y-3">
                                <div class="relative h-28 rounded-xl overflow-hidden shadow-sm bg-surface-container">
                                    @if ($foto)
                                        <img src="{{ Storage::url($foto->path_file) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-outline">
                                            <span class="material-symbols-outlined text-3xl">image</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent flex items-end p-2.5">
                                        <span class="text-xs font-bold uppercase tracking-wider bg-primary/80 text-white px-2 py-0.5 rounded">{{ $lapangan->jenis }}</span>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-on-surface truncate">{{ $lapangan->nama_lapangan }}</p>
                                    <p class="text-xs text-outline">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}/jam</p>
                                </div>
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-1 rounded-md border {{ $badge[1] }}">{{ $badge[0] }}</span>
                                <a href="{{ route('pemilik.lapangan.edit', $lapangan) }}" class="block text-center text-xs font-bold text-primary hover:underline pt-1">Kelola</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Booking Terbaru --}}
            <div class="bg-white p-6 rounded-3xl border border-surface-container-high shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-surface-container-high">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-xl">receipt_long</span>
                        <h3 class="text-title-md font-bold text-on-surface">Booking Terbaru</h3>
                    </div>
                </div>

                @if ($bookingTerbaru->isEmpty())
                    <p class="text-body-sm text-on-surface-variant py-6 text-center">Belum ada booking masuk.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($bookingTerbaru as $booking)
                            @php
                                $statusBadge = match($booking->status_pembayaran) {
                                    'paid' => ['Lunas', 'text-emerald-700 bg-emerald-50 border-emerald-200'],
                                    'pending' => ['Menunggu Pembayaran', 'text-amber-800 bg-amber-50 border-amber-200'],
                                    default => ['Gagal/Batal', 'text-red-700 bg-red-50 border-red-200'],
                                };
                            @endphp
                            <div class="p-3.5 rounded-2xl bg-[#FBF7F0] border border-surface-container-high flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-sm text-on-surface">{{ $booking->user->name }}</span>
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold">{{ $booking->lapangan->nama_lapangan }}</span>
                                        <span class="text-xs text-outline">{{ $booking->nomor_invoice }}</span>
                                    </div>
                                    <p class="text-xs text-on-surface-variant mt-0.5">
                                        {{ $booking->tanggal_booking->translatedFormat('d M Y') }} • {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }} WIB
                                    </p>
                                    <span class="text-xs font-bold text-primary">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                </div>
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-md border {{ $statusBadge[1] }}">{{ $statusBadge[0] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </main>
</x-partner-layout>