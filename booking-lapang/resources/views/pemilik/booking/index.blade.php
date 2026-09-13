<x-partner-layout :title="'Daftar Booking Masuk - Booking Lapang Partner'">

    <x-partner-sidebar active="booking" />

    <div class="flex-1 ml-72 flex flex-col min-h-screen">
        <header class="fixed top-0 right-0 left-72 z-20 flex justify-between items-center px-8 h-16 bg-surface border-b border-outline-variant">
            <div class="flex items-center gap-2 text-on-surface-variant text-xs font-label-md">
                <span>Booking Lapang Partner</span>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-primary font-bold">Daftar Booking Masuk</span>
            </div>
        </header>

        <main class="ml-0 pt-16 flex-1 bg-surface min-h-screen pb-16">
            <div class="max-w-[1200px] mx-auto px-8 py-8 space-y-6">

                <div>
                    <h1 class="font-headline-lg text-headline-lg text-primary font-extrabold tracking-tight">Daftar Booking &amp; Reservasi Masuk</h1>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                        Pantau booking yang masuk untuk lapangan-lapangan lu.
                    </p>
                </div>

                {{-- Ringkasan Cepat --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant shadow-sm">
                        <span class="font-label-md text-label-md text-on-surface-variant">Booking Hari Ini</span>
                        <div class="font-headline-lg text-headline-lg text-primary font-extrabold mt-1">{{ $counts['hari_ini'] }}</div>
                    </div>
                    <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant shadow-sm">
                        <span class="font-label-md text-label-md text-on-surface-variant">Akan Datang</span>
                        <div class="font-headline-lg text-headline-lg text-on-surface font-extrabold mt-1">{{ $counts['akan_datang'] }}</div>
                    </div>
                    <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant shadow-sm">
                        <span class="font-label-md text-label-md text-on-surface-variant">Selesai</span>
                        <div class="font-headline-lg text-headline-lg text-on-surface font-extrabold mt-1">{{ $counts['selesai'] }}</div>
                    </div>
                    <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant shadow-sm">
                        <span class="font-label-md text-label-md text-on-surface-variant">Dibatalkan</span>
                        <div class="font-headline-lg text-headline-lg text-secondary font-extrabold mt-1">{{ $counts['dibatalkan'] }}</div>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant shadow-sm space-y-4">
                    <div class="flex items-center gap-2 overflow-x-auto pb-1">
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mr-1">Status:</span>
                        @foreach ([
                            'semua' => 'Semua',
                            'hari_ini' => 'Hari Ini',
                            'akan_datang' => 'Akan Datang',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                        ] as $key => $label)
                            <a href="{{ route('pemilik.booking.index', array_merge(request()->query(), ['filter' => $key])) }}"
                               class="px-3 py-1.5 rounded-lg font-label-md text-label-md flex items-center gap-1.5 transition-colors {{ $filter === $key ? 'bg-primary-container text-on-primary shadow-sm' : 'border border-outline-variant bg-surface hover:bg-surface-container text-on-surface' }}">
                                {{ $label }}
                                <span class="{{ $filter === $key ? 'bg-on-primary/20 text-on-primary' : 'bg-surface-container-high text-on-surface-variant' }} px-1.5 py-0.5 rounded text-xs font-bold">{{ $counts[$key] }}</span>
                            </a>
                        @endforeach
                    </div>

                    @if ($lapangans->count() > 1)
                        <div class="flex items-center gap-2 flex-wrap pt-3 border-t border-outline-variant/60">
                            <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mr-1">Lapangan:</span>
                            <a href="{{ route('pemilik.booking.index', array_merge(request()->except('lapangan_id'))) }}"
                               class="px-2.5 py-1 rounded-lg font-label-sm text-label-sm border border-outline-variant {{ !request('lapangan_id') ? 'bg-surface-container font-bold text-on-surface' : 'bg-surface text-on-surface-variant hover:bg-surface-container' }}">
                                Semua Lapangan
                            </a>
                            @foreach ($lapangans as $l)
                                <a href="{{ route('pemilik.booking.index', array_merge(request()->query(), ['lapangan_id' => $l->id])) }}"
                                   class="px-2.5 py-1 rounded-lg font-label-sm text-label-sm border border-outline-variant {{ (string) request('lapangan_id') === (string) $l->id ? 'bg-surface-container font-bold text-on-surface' : 'bg-surface text-on-surface-variant hover:bg-surface-container' }}">
                                    {{ $l->nama_lapangan }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- List Booking --}}
                <div class="space-y-3.5">
                    @forelse ($bookings as $booking)
                        @php
                            $isCancelled = $booking->status === 'cancelled';
                            $statusBadge = match(true) {
                                $isCancelled => ['Dibatalkan', 'bg-error-container text-error'],
                                $booking->status_pembayaran === 'paid' => ['Lunas', 'bg-primary-fixed/50 text-primary'],
                                $booking->status_pembayaran === 'failed' => ['Pembayaran Gagal', 'bg-error-container text-error'],
                                default => ['Menunggu Pembayaran', 'bg-secondary-fixed text-secondary'],
                            };
                        @endphp
                        <article class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-5 shadow-sm {{ $isCancelled ? 'opacity-70' : '' }}">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                <div class="md:col-span-3">
                                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold bg-primary-fixed/40 text-primary uppercase tracking-wide">{{ $booking->lapangan->jenis }}</span>
                                    <h4 class="font-title-md text-title-md text-on-surface font-bold {{ $isCancelled ? 'line-through' : '' }}">{{ $booking->lapangan->nama_lapangan }}</h4>
                                    <p class="text-xs text-on-surface-variant">{{ $booking->nomor_invoice ?? '—' }}</p>
                                </div>

                                <div class="md:col-span-3">
                                    <span class="font-title-md text-title-md text-on-surface font-bold block truncate">{{ $booking->user->name }}</span>
                                    <span class="text-xs text-on-surface-variant">{{ $booking->user->email }}</span>
                                </div>

                                <div class="md:col-span-3">
                                    <div class="bg-surface-container-low px-3.5 py-2 rounded-xl border border-outline-variant/60">
                                        <span class="font-title-md text-title-md text-primary font-bold tracking-tight block">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }} WIB</span>
                                        <span class="text-xs text-on-surface-variant">{{ $booking->tanggal_booking->translatedFormat('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="md:col-span-1">
                                    <span class="text-xs text-on-surface-variant block">Total</span>
                                    <span class="font-title-md text-title-md text-on-surface font-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                </div>

                                <div class="md:col-span-2 flex justify-end">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-label-md font-bold {{ $statusBadge[1] }}">{{ $statusBadge[0] }}</span>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-10 text-center text-on-surface-variant">
                            Belum ada booking yang cocok dengan filter ini.
                        </div>
                    @endforelse
                </div>

                @if ($bookings->hasPages())
                    <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant shadow-sm">
                        {{ $bookings->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</x-partner-layout>