<x-partner-layout :title="'Payout & Finansial - Booking Lapang Partner'">

    <x-partner-sidebar active="payout" />

    <div class="flex-1 ml-72 flex flex-col min-h-screen">
        <header class="fixed top-0 right-0 left-72 z-20 flex justify-between items-center px-8 h-16 bg-surface-container-lowest border-b border-outline-variant shadow-warm-sm">
            <div class="flex items-center gap-2 text-body-sm text-outline">
                <span>Booking Lapang Partner</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-primary font-bold">Payout &amp; Finansial</span>
            </div>
        </header>

        <main class="ml-0 pt-16 p-8 max-w-[1200px] mx-auto space-y-8">

            <div>
                <h1 class="text-headline-lg text-on-surface font-extrabold tracking-tight">Riwayat Payout</h1>
                <p class="text-body-md text-outline mt-1">Payout dicairkan lewat proses admin secara berkala, sesuai booking yang udah lunas dari lapangan lu.</p>
            </div>

            {{-- Ringkasan --}}
            <section class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="p-5 bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-warm-sm flex items-center justify-between">
                    <div class="space-y-1">
                        <span class="text-label-sm text-outline uppercase tracking-wide">Sedang Diproses</span>
                        <p class="text-headline-sm text-on-surface font-extrabold">Rp {{ number_format($totalDiproses, 0, ',', '.') }}</p>
                        <p class="text-body-sm text-outline">{{ $counts['diproses'] }} batch payout</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined text-2xl">hourglass_top</span>
                    </div>
                </div>
                <div class="p-5 bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-warm-sm flex items-center justify-between">
                    <div class="space-y-1">
                        <span class="text-label-sm text-outline uppercase tracking-wide">Selesai Bulan Ini</span>
                        <p class="text-headline-sm text-primary font-extrabold">Rp {{ number_format($totalSelesaiBulanIni, 0, ',', '.') }}</p>
                        <p class="text-body-sm text-outline">{{ $counts['selesai'] }} payout total selesai</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">verified</span>
                    </div>
                </div>
            </section>

            {{-- Filter --}}
            <section class="space-y-4">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <h2 class="text-headline-sm text-on-surface font-bold">Daftar Riwayat Penarikan Dana</h2>
                    <div class="flex items-center gap-2 overflow-x-auto pb-1">
                        @foreach ([
                            'semua' => 'Semua',
                            'menunggu' => 'Menunggu',
                            'diproses' => 'Diproses',
                            'selesai' => 'Selesai',
                        ] as $key => $label)
                            <a href="{{ route('pemilik.payout.index', ['filter' => $key]) }}"
                               class="px-3.5 py-1.5 rounded-full text-label-md transition-colors {{ $filter === $key ? 'bg-primary-container text-on-primary shadow-warm-sm' : 'bg-surface-container-lowest border border-outline-variant text-on-surface-variant hover:border-primary' }}">
                                {{ $label }} ({{ $counts[$key] }})
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-3.5">
                    @forelse ($payouts as $payout)
                        @php
                            $statusMap = [
                                'menunggu' => ['Menunggu Diproses', 'bg-surface-container-high text-on-surface-variant', 'schedule'],
                                'diproses' => ['Sedang Diproses', 'bg-tertiary-fixed/40 text-tertiary border border-tertiary-fixed-dim/30', 'hourglass_top'],
                                'selesai' => ['Berhasil Dicairkan', 'bg-primary/10 text-primary border border-primary/20', 'check_circle'],
                            ];
                            [$label, $badgeClass, $icon] = $statusMap[$payout->status] ?? ['—', 'bg-surface-container text-on-surface-variant', 'help'];
                        @endphp
                        <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant shadow-warm-sm">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl {{ $badgeClass }} flex items-center justify-center mt-1">
                                        <span class="material-symbols-outlined text-2xl">{{ $icon }}</span>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="text-headline-sm text-on-surface font-extrabold">Rp {{ number_format($payout->total_nominal, 0, ',', '.') }}</span>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold {{ $badgeClass }}">{{ $label }}</span>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-y-1 gap-x-3 text-body-sm text-outline">
                                            <span class="text-on-surface font-medium">
                                                {{ $payout->periode_mulai->translatedFormat('d M Y') }} - {{ $payout->periode_selesai->translatedFormat('d M Y') }}
                                            </span>
                                            <span>&bull;</span>
                                            <span>ID Payout: <strong class="font-mono text-on-surface">#PO-{{ str_pad($payout->id, 6, '0', STR_PAD_LEFT) }}</strong></span>
                                            <span>&bull;</span>
                                            <span>{{ $payout->bookings->count() }} booking</span>
                                            @if ($payout->selesai_pada)
                                                <span>&bull;</span>
                                                <span>Selesai: {{ $payout->selesai_pada->translatedFormat('d M Y, H:i') }} WIB</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 self-end lg:self-center">
                                    @if ($payout->status === 'selesai')
                                        <a href="{{ route('pemilik.payout.download', $payout->id) }}"
                                           class="px-4 py-2 rounded-xl border border-outline-variant bg-surface-container-lowest hover:bg-surface-container text-on-surface text-label-md flex items-center gap-2 transition-colors shadow-warm-sm">
                                            <span class="material-symbols-outlined text-base">receipt_long</span>
                                            <span>Unduh Bukti PDF</span>
                                        </a>
                                    @else
                                        <span class="text-body-sm text-outline">Laporan tersedia setelah selesai</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-10 text-center text-outline">
                            Belum ada riwayat payout.
                        </div>
                    @endforelse
                </div>

                @if ($payouts->hasPages())
                    <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant shadow-warm-sm">
                        {{ $payouts->onEachSide(1)->links() }}
                    </div>
                @endif
            </section>
        </main>
    </div>
</x-partner-layout>