<x-partner-layout :title="'Edit Lapangan - Booking Lapang Partner'">

    <x-partner-sidebar active="lapangan" />

    <div class="flex-1 ml-72 flex flex-col min-h-screen">
        <header class="fixed top-0 right-0 left-72 z-20 flex justify-between items-center px-8 h-16 bg-surface-container-lowest border-b border-outline-variant shadow-sm">
            <div class="flex items-center gap-2">
                <span class="font-title-lg text-title-lg text-primary tracking-tight font-extrabold">Booking Lapang Partner</span>
                <span class="text-outline-variant">/</span>
                <span class="font-label-md text-label-md text-on-surface-variant bg-surface-container px-2.5 py-1 rounded-md">Edit Lapangan</span>
            </div>
        </header>

        <main class="mt-16 p-8 flex-1">
            <div class="max-w-2xl">
                <div class="flex items-center gap-2 mb-1">
                    <span class="bg-primary-fixed text-primary font-bold text-xs px-2.5 py-1 rounded-md">Mode Edit</span>
                    @php
                        $badge = match($lapangan->status_approval) {
                            'disetujui' => ['Disetujui', 'bg-primary-container text-on-primary'],
                            'ditolak' => ['Ditolak', 'bg-red-100 text-red-800'],
                            default => ['Menunggu Persetujuan', 'bg-amber-100 text-amber-900'],
                        };
                    @endphp
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-md {{ $badge[1] }}">{{ $badge[0] }}</span>
                </div>
                <h2 class="font-headline-md text-headline-md text-primary tracking-tight mb-1">Edit: {{ $lapangan->nama_lapangan }}</h2>
                <p class="text-body-md text-on-surface-variant mb-6">Perbarui detail lapangan lu di bawah ini.</p>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 p-3 mb-6 rounded-xl text-sm">
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($lapangan->fotos->isNotEmpty())
                    <div class="mb-6">
                        <p class="text-xs font-semibold text-on-surface mb-2">Foto Lapangan</p>
                        <div class="grid grid-cols-4 gap-3">
                            @foreach ($lapangan->fotos as $foto)
                                <div class="aspect-square rounded-xl overflow-hidden border border-outline-variant bg-surface-container">
                                    <img src="{{ Storage::url($foto->path_file) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-outline mt-2">Upload/ganti foto belum tersedia di halaman ini.</p>
                    </div>
                @endif

                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl grass-shadow-lg p-6 lg:p-8">
                    <form method="POST" action="{{ route('pemilik.lapangan.update', $lapangan) }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-on-surface mb-1.5" for="nama_lapangan">Nama Lapangan *</label>
                            <input id="nama_lapangan" type="text" name="nama_lapangan" value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}"
                                   class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-2.5 text-body-md text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary/10" required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-on-surface mb-1.5" for="jenis">Cabang Olahraga *</label>
                                <input id="jenis" type="text" name="jenis" value="{{ old('jenis', $lapangan->jenis) }}" list="daftar-jenis"
                                       class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-2.5 text-body-md text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary/10" required>
                                <datalist id="daftar-jenis">
                                    <option value="Badminton">
                                    <option value="Futsal">
                                    <option value="Mini Soccer">
                                    <option value="Tenis Lapangan">
                                    <option value="Bola Basket">
                                </datalist>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-on-surface mb-1.5" for="kota">Kota</label>
                                <input id="kota" type="text" name="kota" value="{{ old('kota', $lapangan->kota) }}"
                                       class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-2.5 text-body-md text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary/10">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-on-surface mb-1.5" for="harga_per_jam">Harga per Jam *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-xs text-on-surface-variant">Rp</span>
                                <input id="harga_per_jam" type="number" name="harga_per_jam" value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}" min="0"
                                       class="w-full bg-surface border border-outline-variant rounded-xl pl-10 pr-4 py-2.5 text-body-md text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary/10" required>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit" class="px-6 py-2.5 bg-primary-container hover:bg-primary text-on-primary rounded-xl font-label-md text-label-md flex items-center gap-2 shadow-sm transition-all active:scale-98">
                                <span class="material-symbols-outlined text-lg">check_circle</span>
                                <span>Simpan Perubahan</span>
                            </button>
                            <a href="{{ route('pemilik.lapangan.index') }}" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:bg-surface-container rounded-xl font-label-md text-label-md transition-colors">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</x-partner-layout>