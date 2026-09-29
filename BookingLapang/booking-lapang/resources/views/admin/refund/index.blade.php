<x-partner-layout :title="'Dispute & Refund - Booking Lapang Admin'">

    <x-admin-sidebar active="refund" />

    <div class="flex-1 flex flex-col min-h-screen ml-64 bg-background">
        <header class="sticky top-0 right-0 h-16 w-full bg-surface-container-lowest border-b border-outline-variant shadow-sm z-30 flex items-center px-8">
            <nav class="flex items-center gap-2 text-label-md text-on-surface-variant">
                <span>Booking Lapang Admin</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-primary font-bold">Dispute &amp; Refund</span>
            </nav>
        </header>

        <main class="flex-1 p-8 space-y-6" x-data="{ activeId: {{ $bookings->first()->id ?? 'null' }} }">

            @if (session('success'))
                <div class="bg-primary/10 border border-primary/30 text-primary p-4 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-error-container border border-error/30 text-error p-4 rounded-xl text-sm font-semibold">{{ session('error') }}</div>
            @endif

            <div>
                <h1 class="text-headline-md text-on-surface font-bold tracking-tight">Kelola Pengajuan Refund</h1>
                <p class="text-body-md text-on-surface-variant mt-1">
                    Refund diproses otomatis lewat Midtrans begitu diajukan — 100% kalau dibatalkan &ge; 24 jam sebelum jadwal, 50% kalau kurang dari itu.
                </p>
            </div>

            {{-- Filter status --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-1 bg-surface-container-lowest p-3 rounded-2xl border border-outline-variant">
                @foreach (['semua' => 'Semua', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $key => $label)
                    <a href="{{ route('admin.refund.index', $key === 'semua' ? [] : ['status' => $key]) }}"
                       class="px-4 py-2 rounded-xl text-label-md font-semibold transition-colors whitespace-nowrap {{ $statusAktif === $key ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container' }}">
                        {{ $label }} <span class="ml-1 {{ $statusAktif === $key ? 'text-on-primary/80' : 'text-outline' }}">{{ $counts[$key] }}</span>
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
                {{-- LEFT: LIST --}}
                <div class="xl:col-span-5 space-y-3">
                    @forelse ($bookings as $booking)
                        @php
                            $badge = match($booking->status_refund) {
                                'diproses' => ['Diproses', 'bg-tertiary-fixed text-tertiary'],
                                'selesai' => ['Selesai', 'bg-primary-fixed text-on-primary-fixed'],
                                default => ['Ditolak', 'bg-secondary-fixed text-on-secondary-fixed-variant'],
                            };
                        @endphp
                        <button type="button" @click="activeId = {{ $booking->id }}"
                                class="w-full text-left p-4 rounded-2xl border transition-all bg-surface-container-lowest"
                                :class="activeId === {{ $booking->id }} ? 'border-2 border-primary shadow-md' : 'border-outline-variant hover:border-primary/40 shadow-sm'">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-title-md font-bold text-on-surface truncate">{{ $booking->user->name }}</p>
                                    <p class="text-body-sm text-on-surface-variant truncate">{{ $booking->lapangan->nama_lapangan }}</p>
                                    <p class="text-body-sm text-outline mt-1">{{ $booking->tanggal_booking->translatedFormat('d M Y') }} &bull; Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                                </div>
                                <span class="shrink-0 px-2.5 py-1 rounded-full text-[11px] font-bold {{ $badge[1] }}">{{ $badge[0] }}</span>
                            </div>
                        </button>
                    @empty
                        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-10 text-center text-on-surface-variant">
                            Belum ada pengajuan refund.
                        </div>
                    @endforelse

                    @if ($bookings->hasPages())
                        <div class="pt-2">{{ $bookings->onEachSide(1)->links() }}</div>
                    @endif
                </div>

                {{-- RIGHT: DETAIL --}}
                <aside class="xl:col-span-7 bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden sticky top-24">
                    @forelse ($bookings as $booking)
                        @php
                            $badge = match($booking->status_refund) {
                                'diproses' => ['Diproses', 'bg-tertiary-fixed text-tertiary'],
                                'selesai' => ['Selesai', 'bg-primary-fixed text-on-primary-fixed'],
                                default => ['Ditolak', 'bg-secondary-fixed text-on-secondary-fixed-variant'],
                            };
                            $log = $booking->refundLogs->last();
                        @endphp
                        <div x-show="activeId === {{ $booking->id }}" x-cloak>
                            <div class="p-5 border-b border-outline-variant bg-surface-container-low flex items-center justify-between">
                                <div>
                                    <h3 class="text-headline-sm font-bold text-on-surface">{{ $booking->user->name }}</h3>
                                    <p class="text-body-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
                                        <span class="material-symbols-outlined text-base text-primary">stadium</span>
                                        {{ $booking->lapangan->nama_lapangan }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-label-md font-bold {{ $badge[1] }}">{{ $badge[0] }}</span>
                            </div>

                            <div class="p-5 space-y-5">
                                {{-- Parameter transaksi --}}
                                <div class="rounded-xl border border-outline-variant p-4 bg-surface space-y-3">
                                    <span class="text-label-sm text-outline uppercase tracking-wider">Parameter Transaksi</span>
                                    <div class="grid grid-cols-2 gap-3 text-body-sm">
                                        <div>
                                            <span class="text-outline block text-label-sm">Total Harga</span>
                                            <span class="font-bold text-on-surface">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-outline block text-label-sm">Tanggal Booking</span>
                                            <span class="font-semibold text-on-surface">{{ $booking->tanggal_booking->translatedFormat('d F Y') }}</span>
                                        </div>
                                        <div class="col-span-2">
                                            <span class="text-outline block text-label-sm">Payment Reference</span>
                                            <span class="font-mono font-semibold text-on-surface">{{ $booking->payment_reference ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Alasan --}}
                                @if ($booking->alasan_pembatalan)
                                    <div class="p-3.5 rounded-xl bg-surface-container-low border-l-4 border-secondary">
                                        <span class="text-label-sm font-bold text-secondary block mb-1">Alasan Pembatalan</span>
                                        <p class="text-body-sm text-on-surface">{{ $booking->alasan_pembatalan }}</p>
                                    </div>
                                @endif

                                {{-- Kalkulasi refund --}}
                                @if ($log)
                                    <div class="rounded-xl p-4 bg-surface-container-low border border-outline-variant space-y-2">
                                        <span class="text-label-sm text-outline uppercase tracking-wider block">Kalkulasi Refund (Otomatis)</span>
                                        <div class="flex items-center justify-between text-body-sm">
                                            <span class="text-on-surface-variant">Persentase Refund</span>
                                            <span class="font-bold text-on-surface">{{ $log->persentase }}% {{ $log->persentase >= 100 ? '(≥ 24 jam sebelum jadwal)' : '(< 24 jam sebelum jadwal)' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-body-sm pt-2 border-t border-outline-variant/60 font-bold text-primary">
                                            <span>Nominal Dikembalikan</span>
                                            <span>Rp {{ number_format($log->nominal, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-body-sm text-on-surface-variant">
                                            <span>Diproses oleh</span>
                                            <span>{{ $log->admin->name ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-body-sm">
                                            <span class="text-on-surface-variant">Hasil Midtrans</span>
                                            <span class="font-semibold {{ str_contains($log->hasil, 'berhasil') ? 'text-primary' : 'text-secondary' }}">{{ $log->hasil }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if ($booking->status_refund === 'diproses')
                                    <div class="p-3 rounded-xl bg-tertiary-fixed/40 text-tertiary text-body-sm font-semibold flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">hourglass_top</span>
                                        Refund sedang diproses oleh Midtrans.
                                    </div>
                                @endif

                                @if ($booking->catatan_refund)
                                    <div class="p-3 rounded-xl bg-error-container text-error text-body-sm">
                                        <span class="font-bold block mb-1">Catatan Kegagalan</span>
                                        {{ $booking->catatan_refund }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center text-on-surface-variant">
                            Pilih pengajuan di sebelah kiri untuk melihat detail.
                        </div>
                    @endforelse
                </aside>
            </div>
        </main>
    </div>
</x-partner-layout>