<x-partner-layout :title="'Verifikasi Mitra - Booking Lapang Admin'">

    <x-admin-sidebar active="verifikasi" />

    <div class="flex-1 flex flex-col min-h-screen ml-64 bg-background">
        <header class="sticky top-0 right-0 h-16 w-full border-b border-outline-variant bg-surface-container-lowest shadow-sm z-30 flex items-center px-8">
            <nav class="flex items-center gap-2 text-label-md text-outline">
                <span class="text-on-surface-variant">Booking Lapang Admin</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-primary font-bold">Verifikasi Mitra</span>
            </nav>
        </header>

        <main class="flex-1 p-8 max-w-5xl mx-auto w-full space-y-6">

            @if (session('success'))
                <div class="bg-primary/10 border border-primary/30 text-primary p-4 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
            @endif

            <div>
                <h1 class="text-headline-md text-on-surface font-bold tracking-tight">Verifikasi Identitas Mitra Pemilik Lapangan</h1>
                <p class="text-body-md text-on-surface-variant mt-1">
                    Tinjau dokumen identitas (KTP/SIM) yang diajukan pemilik lapangan sebelum akun mereka bisa mengajukan lapangan baru.
                </p>
            </div>

            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-tertiary-fixed/40 text-tertiary border border-tertiary-fixed-dim font-bold text-label-md">
                <span class="w-2 h-2 rounded-full bg-tertiary-container"></span>
                {{ $menunggu->count() }} Menunggu Ditinjau
            </div>

            @if ($menunggu->isEmpty())
                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-10 text-center text-on-surface-variant">
                    Tidak ada pengajuan verifikasi yang menunggu saat ini.
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($menunggu as $pemilik)
                        <article class="p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant shadow-sm">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-bold text-title-md flex-shrink-0">
                                        {{ strtoupper(substr($pemilik->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h3 class="text-title-md text-on-surface font-bold">{{ $pemilik->name }}</h3>
                                        <p class="text-body-sm text-on-surface-variant">{{ $pemilik->email }}</p>
                                        <p class="text-[11px] text-outline mt-0.5">Diajukan: {{ $pemilik->updated_at?->translatedFormat('d M Y, H:i') ?? '-' }} WIB</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 flex-wrap">
                                    <a href="{{ route('admin.verifikasi.dokumen', $pemilik) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-outline-variant text-on-surface hover:bg-surface-container text-label-md font-bold transition-colors">
                                        <span class="material-symbols-outlined text-lg">description</span>
                                        Lihat Dokumen
                                    </a>

                                    <form action="{{ route('admin.verifikasi.tinjau', $pemilik) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="keputusan" value="setuju">
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-primary-container text-on-primary hover:bg-primary text-label-md font-bold transition-all shadow-sm active:scale-95">
                                            <span class="material-symbols-outlined text-lg">verified</span>
                                            Setujui
                                        </button>
                                    </form>

                                    <button type="button" onclick="document.getElementById('tolak-modal-{{ $pemilik->id }}').classList.remove('hidden')"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border-[1.5px] border-secondary text-secondary hover:bg-secondary/10 text-label-md font-bold transition-all active:scale-95">
                                        <span class="material-symbols-outlined text-lg">close</span>
                                        Tolak
                                    </button>
                                </div>
                            </div>
                        </article>

                        {{-- Modal tolak --}}
                        <div id="tolak-modal-{{ $pemilik->id }}" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
                            <div class="bg-surface-container-lowest w-full max-w-md rounded-2xl shadow-xl border border-outline-variant overflow-hidden">
                                <div class="p-5 border-b border-outline-variant bg-surface-container-low">
                                    <h3 class="text-title-md text-on-surface font-bold">Tolak Verifikasi {{ $pemilik->name }}</h3>
                                </div>
                                <form action="{{ route('admin.verifikasi.tinjau', $pemilik) }}" method="POST" class="p-5 space-y-3">
                                    @csrf
                                    <input type="hidden" name="keputusan" value="tolak">
                                    <label class="block text-label-md text-on-surface font-semibold">Catatan Penolakan</label>
                                    <textarea name="catatan" rows="3" placeholder="Jelasin apa yang perlu diperbaiki pemilik (opsional)"
                                              class="w-full text-body-sm p-3 rounded-xl bg-surface border border-outline-variant focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/15 resize-none"></textarea>
                                    <div class="flex justify-end gap-2 pt-2">
                                        <button type="button" onclick="document.getElementById('tolak-modal-{{ $pemilik->id }}').classList.add('hidden')"
                                                class="px-4 py-2 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container text-label-md">
                                            Batal
                                        </button>
                                        <button type="submit" class="px-4 py-2 rounded-xl bg-secondary text-white hover:bg-on-secondary-container text-label-md font-bold">
                                            Kirim Penolakan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>
</x-partner-layout>