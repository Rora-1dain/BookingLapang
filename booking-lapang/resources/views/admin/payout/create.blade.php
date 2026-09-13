<x-partner-layout :title="'Buat Payout - Booking Lapang Admin'">

    <x-admin-sidebar active="payout" />

    <div class="flex-1 flex flex-col min-h-screen ml-64 bg-background">
        <header class="sticky top-0 right-0 h-16 w-full bg-surface-container-lowest border-b border-outline-variant/60 shadow-sm z-30 flex items-center px-8">
            <nav class="flex items-center gap-2 text-label-md text-on-surface-variant">
                <span>Booking Lapang Admin</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <a href="{{ route('admin.payout.index') }}" class="hover:text-primary">Payout Mitra</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-primary font-bold">Buat Payout</span>
            </nav>
        </header>

        <main class="flex-1 p-8 max-w-xl mx-auto w-full">
            <h1 class="text-headline-md text-on-surface font-bold tracking-tight mb-1">Buat Payout Baru</h1>
            <p class="text-body-md text-on-surface-variant mb-6">
                Sistem otomatis ngumpulin semua booking lunas milik pemilik ini dalam periode yang dipilih, yang belum pernah masuk payout lain.
            </p>

            @if (session('error'))
                <div class="bg-error-container border border-error/30 text-error p-4 rounded-xl text-sm font-semibold mb-4">{{ session('error') }}</div>
            @endif

            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-sm p-6">
                <form action="{{ route('admin.payout.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-label-md font-semibold text-on-surface mb-1.5">Pemilik Lapangan</label>
                        <select name="pemilik_id" required
                                class="w-full h-11 px-3 rounded-xl border border-outline-variant bg-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/15">
                            <option value="">-- Pilih Pemilik --</option>
                            @foreach ($pemilikList as $pemilik)
                                <option value="{{ $pemilik->id }}" @selected(old('pemilik_id') == $pemilik->id)>{{ $pemilik->name }}</option>
                            @endforeach
                        </select>
                        @error('pemilik_id') <p class="text-secondary text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-label-md font-semibold text-on-surface mb-1.5">Periode Mulai</label>
                            <input type="date" name="periode_mulai" required value="{{ old('periode_mulai') }}"
                                   class="w-full h-11 px-3 rounded-xl border border-outline-variant bg-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/15">
                            @error('periode_mulai') <p class="text-secondary text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-label-md font-semibold text-on-surface mb-1.5">Periode Selesai</label>
                            <input type="date" name="periode_selesai" required value="{{ old('periode_selesai') }}"
                                   class="w-full h-11 px-3 rounded-xl border border-outline-variant bg-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/15">
                            @error('periode_selesai') <p class="text-secondary text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary text-on-primary hover:bg-primary-container font-bold text-label-lg shadow-sm transition-all active:scale-95">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                            Buat Payout
                        </button>
                        <a href="{{ route('admin.payout.index') }}" class="px-5 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container font-label-lg">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-partner-layout>