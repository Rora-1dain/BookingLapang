<x-partner-layout :title="'Moderasi Ulasan - Booking Lapang Admin'">

    <x-admin-sidebar active="ulasan" />

    <div class="flex-1 flex flex-col min-h-screen ml-64 bg-background">
        <header class="sticky top-0 right-0 h-16 w-full border-b border-outline-variant bg-surface-container-lowest z-30 shadow-sm flex items-center px-8">
            <nav class="flex items-center gap-2 text-label-md text-outline">
                <span class="text-on-surface-variant">Booking Lapang Admin</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-primary font-bold">Moderasi Ulasan</span>
            </nav>
        </header>

        <main class="flex-1 p-8 max-w-4xl mx-auto w-full space-y-6">

            @if (session('success'))
                <div class="bg-primary/10 border border-primary/30 text-primary p-4 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
            @endif

            <div>
                <h1 class="text-headline-md text-on-surface font-bold tracking-tight">Moderasi Ulasan Dilaporkan</h1>
                <p class="text-body-md text-on-surface-variant mt-1">
                    Ulasan yang dilaporkan pengguna masuk ke sini. Pertahankan kalau wajar, sembunyikan kalau melanggar.
                </p>
            </div>

            <div class="flex items-center gap-2 bg-surface-container-lowest p-3 rounded-2xl border border-outline-variant/80">
                <a href="{{ route('admin.ulasan.index', ['filter' => 'dilaporkan']) }}"
                   class="px-4 py-2 rounded-xl text-label-md font-semibold transition-colors {{ $filter === 'dilaporkan' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container' }}">
                    Perlu Ditinjau <span class="ml-1 {{ $filter === 'dilaporkan' ? 'text-on-primary/80' : 'text-outline' }}">{{ $counts['dilaporkan'] }}</span>
                </a>
                <a href="{{ route('admin.ulasan.index', ['filter' => 'disembunyikan']) }}"
                   class="px-4 py-2 rounded-xl text-label-md font-semibold transition-colors {{ $filter === 'disembunyikan' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container' }}">
                    Sudah Disembunyikan <span class="ml-1 {{ $filter === 'disembunyikan' ? 'text-on-primary/80' : 'text-outline' }}">{{ $counts['disembunyikan'] }}</span>
                </a>
            </div>

            <div class="space-y-4">
                @forelse ($ulasan as $item)
                    <article class="bg-surface-container-lowest border border-outline-variant/80 rounded-2xl p-5 shadow-sm">
                        <div class="flex items-center justify-between pb-3 border-b border-surface-variant">
                            <div>
                                <span class="text-title-md font-bold text-on-surface">{{ $item->booking->lapangan->nama_lapangan ?? '-' }}</span>
                                <p class="text-body-sm text-outline">{{ $item->booking->user->name ?? '-' }} &bull; Booking #{{ $item->booking_id }}</p>
                            </div>
                            <div class="flex items-center gap-1 text-tertiary-fixed-dim" style="color:#e7c268">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' {{ $i <= $item->rating ? 1 : 0 }};">star</span>
                                @endfor
                                <span class="text-label-md font-bold text-tertiary ml-1">{{ $item->rating }}.0 / 5.0</span>
                            </div>
                        </div>

                        <div class="mt-3 bg-surface-container-low/70 p-4 rounded-xl border border-outline-variant/40">
                            <p class="text-body-md text-on-surface leading-relaxed">{{ $item->komentar ?? '(Tidak ada komentar)' }}</p>
                        </div>

                        <p class="text-body-sm text-outline mt-2">Ditulis {{ $item->created_at->translatedFormat('d M Y, H:i') }} WIB</p>

                        @if ($filter === 'dilaporkan')
                            <div class="mt-4 pt-3 border-t border-surface-variant flex items-center gap-3">
                                <form action="{{ route('admin.ulasan.sembunyikan', $item) }}" method="POST"
                                      onsubmit="return confirm('Sembunyikan ulasan ini dari publik?')">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 px-3.5 py-2 rounded-xl border border-secondary text-secondary hover:bg-secondary-fixed/40 font-label-md text-label-md transition-all">
                                        <span class="material-symbols-outlined text-lg">visibility_off</span>
                                        Sembunyikan
                                    </button>
                                </form>
                                <form action="{{ route('admin.ulasan.publikasikan', $item) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-on-primary hover:bg-primary-container font-label-md text-label-md shadow-sm transition-all">
                                        <span class="material-symbols-outlined text-lg">check_circle</span>
                                        Pertahankan &amp; Publikasikan
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="mt-4 pt-3 border-t border-surface-variant flex items-center justify-between">
                                <span class="px-2.5 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant text-label-sm font-bold">Disembunyikan dari publik</span>
                                <form action="{{ route('admin.ulasan.publikasikan', $item) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-primary hover:underline font-label-md text-label-md font-semibold">Tampilkan Lagi</button>
                                </form>
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-10 text-center text-on-surface-variant">
                        Tidak ada ulasan di kategori ini.
                    </div>
                @endforelse
            </div>

            @if ($ulasan->hasPages())
                <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant shadow-sm">
                    {{ $ulasan->onEachSide(1)->links() }}
                </div>
            @endif
        </main>
    </div>
</x-partner-layout>