<x-partner-layout :title="'Payout Mitra - Booking Lapang Admin'">

    <x-admin-sidebar active="payout" />

    <div class="flex-1 flex flex-col min-h-screen ml-64 bg-background">
        <header class="sticky top-0 right-0 h-16 w-full bg-surface-container-lowest border-b border-outline-variant/60 shadow-sm z-30 flex items-center px-8">
            <nav class="flex items-center gap-2 text-label-md text-on-surface-variant">
                <span>Booking Lapang Admin</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-primary font-bold">Payout Mitra</span>
            </nav>
        </header>

        <main class="flex-1 p-8 max-w-6xl mx-auto w-full space-y-6">

            @if (session('success'))
                <div class="bg-primary/10 border border-primary/30 text-primary p-4 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-error-container border border-error/30 text-error p-4 rounded-xl text-sm font-semibold">{{ session('error') }}</div>
            @endif

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-headline-md text-on-surface tracking-tight font-bold">Kelola Payout Mitra Lapangan</h1>
                    <p class="text-body-md text-on-surface-variant mt-0.5">Buat batch pencairan dari booking lunas per pemilik, lalu tandai selesai setelah transfer manual dilakukan.</p>
                </div>
                <a href="{{ route('admin.payout.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-label-md shadow-sm hover:bg-primary-container transition-all">
                    <span class="material-symbols-outlined text-lg">add_circle</span>
                    Buat Payout Baru
                </a>
            </div>

            {{-- KPI --}}
            <section class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/60 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 h-1.5 w-full bg-secondary"></div>
                    <span class="text-label-md text-on-surface-variant font-medium">Total Payout Menunggu Diproses</span>
                    <div class="text-headline-lg text-secondary tracking-tight font-extrabold mt-1">Rp {{ number_format($totalMenunggu, 0, ',', '.') }}</div>
                    <p class="text-body-sm text-on-surface-variant mt-2">{{ $counts['menunggu'] }} batch payout</p>
                </div>
                <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/60 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 h-1.5 w-full bg-primary-container"></div>
                    <span class="text-label-md text-on-surface-variant font-medium">Payout Selesai Bulan Ini</span>
                    <div class="text-headline-lg text-primary tracking-tight font-extrabold mt-1">Rp {{ number_format($totalSelesaiBulanIni, 0, ',', '.') }}</div>
                    <p class="text-body-sm text-on-surface-variant mt-2">{{ $counts['selesai'] }} payout total selesai</p>
                </div>
            </section>

            {{-- Filter --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                @foreach (['semua' => 'Semua', 'menunggu' => 'Menunggu', 'selesai' => 'Selesai'] as $key => $label)
                    <a href="{{ route('admin.payout.index', ['filter' => $key]) }}"
                       class="px-3.5 py-2 rounded-xl text-label-md font-semibold transition-colors whitespace-nowrap {{ $filter === $key ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container' }}">
                        {{ $label }} <span class="ml-1 {{ $filter === $key ? 'text-on-primary/80' : 'text-outline' }}">{{ $counts[$key] }}</span>
                    </a>
                @endforeach
            </div>

            {{-- List --}}
            <div class="space-y-3.5">
                @forelse ($payouts as $payout)
                    @php
                        $gross = $payout->bookings->sum('total_harga');
                        $komisi = $payout->bookings->sum('nominal_komisi');
                        $badge = match($payout->status) {
                            'selesai' => ['Selesai Dicairkan', 'bg-primary-container text-on-primary'],
                            'diproses' => ['Sedang Diproses', 'bg-tertiary-fixed text-tertiary'],
                            default => ['Menunggu Diproses', 'bg-tertiary-fixed text-tertiary'],
                        };
                    @endphp
                    <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant hover:border-primary/40 shadow-sm transition-all">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                            <div class="flex items-start gap-3.5">
                                <div class="w-12 h-12 rounded-2xl bg-primary-container text-on-primary flex items-center justify-center font-bold text-title-lg flex-shrink-0">
                                    {{ strtoupper(substr($payout->pemilik->name ?? '-', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-title-lg text-on-surface font-bold">{{ $payout->pemilik->name ?? '-' }}</h3>
                                        <span class="px-2.5 py-0.5 rounded-md font-bold text-label-sm {{ $badge[1] }}">{{ $badge[0] }}</span>
                                    </div>
                                    <p class="text-body-sm text-on-surface-variant mt-0.5">ID: #PO-{{ str_pad($payout->id, 6, '0', STR_PAD_LEFT) }}</p>
                                    <div class="flex items-center gap-2 mt-2 text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-base">date_range</span>
                                        <span>Periode: {{ $payout->periode_mulai->translatedFormat('d M Y') }} - {{ $payout->periode_selesai->translatedFormat('d M Y') }}</span>
                                        <span class="w-1 h-1 rounded-full bg-outline"></span>
                                        <span class="text-primary font-medium">{{ $payout->bookings->count() }} Sesi Booking</span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right flex-shrink-0">
                                <span class="text-label-sm text-on-surface-variant block">Net ke Pemilik</span>
                                <span class="text-headline-sm text-secondary font-bold block mt-0.5">Rp {{ number_format($payout->total_nominal, 0, ',', '.') }}</span>
                                <span class="text-body-sm text-on-surface-variant block mt-0.5">Gross: Rp {{ number_format($gross, 0, ',', '.') }} &bull; Komisi: Rp {{ number_format($komisi, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if ($payout->status === 'menunggu')
                            <div class="mt-4 pt-3 border-t border-outline-variant/60 flex justify-end">
                                <form action="{{ route('admin.payout.selesai', $payout->id) }}" method="POST"
                                      onsubmit="return confirm('Tandai payout ini sudah ditransfer manual ke pemilik?')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-on-primary hover:bg-primary-container font-bold text-label-md transition-all active:scale-95">
                                        <span class="material-symbols-outlined text-lg">check_circle</span>
                                        Tandai Sudah Ditransfer
                                    </button>
                                </form>
                            </div>
                        @elseif ($payout->selesai_pada)
                            <div class="mt-4 pt-3 border-t border-outline-variant/60 text-body-sm text-on-surface-variant">
                                Ditandai selesai oleh {{ $payout->admin->name ?? '-' }} pada {{ $payout->selesai_pada->translatedFormat('d M Y, H:i') }} WIB
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-10 text-center text-on-surface-variant">
                        Belum ada payout dibuat.
                    </div>
                @endforelse
            </div>

            @if ($payouts->hasPages())
                <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant shadow-sm">
                    {{ $payouts->onEachSide(1)->links() }}
                </div>
            @endif
        </main>
    </div>
</x-partner-layout>