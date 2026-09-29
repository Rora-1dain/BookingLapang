<x-partner-layout :title="'Kelola Listing Lapangan - Booking Lapang Partner'">

    <x-partner-sidebar active="lapangan" />

    <div class="flex-1 ml-72 flex flex-col min-h-screen">
        <header class="fixed top-0 right-0 left-72 z-20 flex justify-between items-center px-8 h-16 bg-surface-container-lowest border-b border-outline-variant shadow-sm">
            <div class="flex items-center gap-2">
                <span class="font-title-lg text-title-lg text-primary tracking-tight font-extrabold">Booking Lapang Partner</span>
                <span class="text-outline-variant">/</span>
                <span class="font-label-md text-label-md text-on-surface-variant bg-surface-container px-2.5 py-1 rounded-md">Kelola Listing Lapangan</span>
            </div>
            <a href="{{ route('pemilik.lapangan.create') }}" class="flex items-center gap-2 bg-primary-container text-on-primary hover:bg-primary px-4 py-2 rounded-xl font-label-md text-label-md shadow-sm transition-all active:scale-98">
                <span class="material-symbols-outlined text-lg">add</span>
                <span>Ajukan Lapangan Baru</span>
            </a>
        </header>

        <main class="mt-16 p-8 space-y-8 flex-1">

            @if (session('success'))
                <div class="bg-emerald-50 text-emerald-800 border border-emerald-200 p-3 rounded-xl flex items-center gap-2 text-sm font-semibold">
                    <span class="material-symbols-outlined text-lg">check_circle</span> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 text-red-700 border border-red-200 p-3 rounded-xl flex items-center gap-2 text-sm font-semibold">
                    <span class="material-symbols-outlined text-lg">error</span> {{ session('error') }}
                </div>
            @endif

            <section class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-2 border-b border-outline-variant/60">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-primary uppercase tracking-wider mb-1">
                        <span class="material-symbols-outlined text-base">stadium</span>
                        <span>Arena Manajemen</span>
                    </div>
                    <h2 class="font-headline-md text-headline-md text-primary tracking-tight">Katalog Lapangan Saya</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">Kelola listing, tarif, dan status persetujuan tiap lapangan yang lu daftarin.</p>
                </div>
            </section>

            <section class="space-y-4">
                <div class="flex items-center gap-3">
                    <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Daftar Lapangan Terdaftar</h3>
                    <span class="bg-surface-container text-on-surface-variant px-2.5 py-0.5 rounded-full font-label-md text-label-md border border-outline-variant">Total {{ $lapangans->count() }}</span>
                </div>

                @if ($lapangans->isEmpty())
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-10 text-center text-on-surface-variant">
                        Belum ada lapangan yang diajukan.
                        <a href="{{ route('pemilik.lapangan.create') }}" class="block mt-2 text-primary font-bold hover:underline">+ Ajukan lapangan pertama lu</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                        @foreach ($lapangans as $lapangan)
                            @php
                                $foto = $lapangan->fotos->firstWhere('is_utama', true) ?? $lapangan->fotos->first();
                                $badge = match($lapangan->status_approval) {
                                    'disetujui' => ['Disetujui', 'bg-primary-container text-on-primary'],
                                    'ditolak' => ['Ditolak', 'bg-red-100 text-red-800'],
                                    default => ['Menunggu Persetujuan', 'bg-amber-100 text-amber-900'],
                                };
                            @endphp
                            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant overflow-hidden grass-shadow hover:shadow-md transition-all duration-200 flex flex-col">
                                <div class="relative aspect-[16/10] overflow-hidden bg-surface-container">
                                    @if ($foto)
                                        <img class="w-full h-full object-cover" src="{{ Storage::url($foto->path_file) }}" alt="{{ $lapangan->nama_lapangan }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-outline">
                                            <span class="material-symbols-outlined text-3xl">image</span>
                                        </div>
                                    @endif
                                    <div class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-semibold shadow-sm {{ $badge[1] }}">
                                        {{ $badge[0] }}
                                    </div>
                                    <div class="absolute bottom-3 left-3 bg-inverse-surface/80 backdrop-blur-xs text-inverse-on-surface px-2.5 py-1 rounded-md text-[11px] font-semibold flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">sports_tennis</span>
                                        <span>{{ $lapangan->jenis }}</span>
                                    </div>
                                </div>
                                <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                                    <div>
                                        <h4 class="font-title-md text-title-md text-on-surface font-bold truncate">{{ $lapangan->nama_lapangan }}</h4>
                                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $lapangan->kota ?? 'Kota belum diisi' }} &bull; {{ ucfirst($lapangan->status) }}</p>
                                    </div>
                                    <div class="flex items-baseline justify-between pt-2 border-t border-outline-variant/60">
                                        <p class="text-base font-bold text-primary">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }} <span class="text-xs font-normal text-on-surface-variant">/jam</span></p>
                                    </div>
                                    <a href="{{ route('pemilik.lapangan.edit', $lapangan) }}" class="w-full flex items-center justify-center gap-1 bg-surface-container hover:bg-surface-container-high text-on-surface border border-outline-variant py-2 rounded-xl text-xs font-semibold transition-colors">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                        <span>Edit Detail</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </main>
    </div>
</x-partner-layout>