<x-partner-layout :title="'Ajukan Lapangan Baru - Booking Lapang Partner'">

    <x-partner-sidebar active="lapangan" />

    <div class="flex-1 ml-72 flex flex-col min-h-screen">
        <header class="fixed top-0 right-0 left-72 z-20 flex justify-between items-center px-8 h-16 bg-surface-container-lowest border-b border-outline-variant shadow-sm">
            <div class="flex items-center gap-2">
                <span class="font-title-lg text-title-lg text-primary tracking-tight font-extrabold">Booking Lapang Partner</span>
                <span class="text-outline-variant">/</span>
                <span class="font-label-md text-label-md text-on-surface-variant bg-surface-container px-2.5 py-1 rounded-md">Ajukan Lapangan Baru</span>
            </div>
        </header>

        <main class="mt-16 p-8 flex-1">
            <div class="max-w-2xl">
                <h2 class="font-headline-md text-headline-md text-primary tracking-tight mb-1">Ajukan Lapangan Baru</h2>
                <p class="text-body-md text-on-surface-variant mb-6">Lapangan yang lu ajukan bakal ditinjau admin sebelum tayang ke publik.</p>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 p-3 mb-6 rounded-xl text-sm">
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl grass-shadow-lg p-6 lg:p-8">
                    <form method="POST" action="{{ route('pemilik.lapangan.store') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-xs font-semibold text-on-surface mb-1.5" for="nama_lapangan">Nama Lapangan *</label>
                            <input id="nama_lapangan" type="text" name="nama_lapangan" value="{{ old('nama_lapangan') }}"
                                   class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-2.5 text-body-md text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary/10"
                                   placeholder="Contoh: Court 1 - Lapangan Badminton" required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-on-surface mb-1.5" for="jenis">Cabang Olahraga *</label>
                                <input id="jenis" type="text" name="jenis" value="{{ old('jenis') }}" list="daftar-jenis"
                                       class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-2.5 text-body-md text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary/10"
                                       placeholder="Badminton, Futsal, dll" required>
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
                                <input id="kota" type="text" name="kota" value="{{ old('kota') }}"
                                       class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-2.5 text-body-md text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary/10"
                                       placeholder="Contoh: Jakarta Selatan">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-on-surface mb-1.5" for="harga_per_jam">Harga per Jam *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-xs text-on-surface-variant">Rp</span>
                                <input id="harga_per_jam" type="number" name="harga_per_jam" value="{{ old('harga_per_jam') }}" min="0"
                                       class="w-full bg-surface border border-outline-variant rounded-xl pl-10 pr-4 py-2.5 text-body-md text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary/10"
                                       required>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit" class="px-6 py-2.5 bg-primary-container hover:bg-primary text-on-primary rounded-xl font-label-md text-label-md flex items-center gap-2 shadow-sm transition-all active:scale-98">
                                <span class="material-symbols-outlined text-lg">check_circle</span>
                                <span>Ajukan Lapangan</span>
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