@extends('layouts.frontend')
@section('title', 'Booking Berulang - '.$lapangan->nama_lapangan)
@section('content')

<main class="max-w-7xl mx-auto px-6 md:px-12 py-6 space-y-6">

    @if(session('error'))
        <div class="mb-4 p-4 rounded-xl bg-error-container text-on-error-container">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-4 rounded-xl bg-error-container text-on-error-container">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-label-md font-label-md text-outline">
        <a class="hover:text-primary transition-colors" href="{{ route('booking.index') }}">Beranda</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-bold">{{ $lapangan->nama_lapangan }} - Booking Berulang</span>
    </nav>

    <!-- Header Lapangan -->
    <section class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2">
                <h1 class="text-headline-sm md:text-headline-md font-headline-md text-on-surface">
                    {{ $lapangan->nama_lapangan }}
                </h1>
                <div class="flex items-center gap-1.5 text-body-sm font-body-sm text-outline">
                    <span class="material-symbols-outlined text-[16px] text-primary">location_on</span>
                    <span>{{ $lapangan->kota ?? '-' }}</span>
                </div>
            </div>
            <div class="flex items-baseline lg:items-end flex-col bg-surface-container-low p-4 rounded-xl border border-outline-variant">
                <span class="text-label-sm font-label-sm text-outline uppercase tracking-wider font-semibold">Tarif Reguler</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-headline-md font-headline-md text-primary font-bold" id="hargaPerJamText">Rp {{ number_format($lapangan->harga_per_jam,0,',','.') }}</span>
                    <span class="text-body-sm font-body-sm text-on-surface-variant font-medium">/ jam</span>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-outline-variant flex items-center gap-3 bg-surface-container-low p-3.5 rounded-lg">
            <span class="w-8 h-8 rounded-full bg-primary-container text-on-primary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[18px]">event_repeat</span>
            </span>
            <p class="text-body-md font-body-md text-on-surface">
                <span class="font-bold text-primary">Program Booking Rutin Mingguan</span> — Amankan slot favorit Anda setiap minggu, otomatis dicek tiap sesi.
            </p>
        </div>
    </section>

    <form action="{{ route('booking.berulang.store') }}" method="POST" id="formBerulang">
        @csrf
        <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 space-y-6">

                <!-- Bagian 1: Hari -->
                <section class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-primary text-on-primary text-label-sm font-bold flex items-center justify-center">1</span>
                        <h2 class="text-title-lg font-title-lg text-on-surface">Pilih Hari Bermain Rutin</h2>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2.5 pt-1" id="hariSelector">
                        @php $namaHari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; @endphp
                        @foreach($namaHari as $idx => $nama)
                            <button type="button" data-hari="{{ $idx }}"
                                class="hari-chip flex flex-col items-center justify-center py-3 px-2 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary transition-all text-center">
                                <span class="text-label-sm font-label-sm text-outline">{{ substr($nama,0,3) }}</span>
                                <span class="text-title-md font-title-md text-on-surface font-semibold">{{ $nama }}</span>
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="hari" id="hariInput" value="" required>
                </section>

                <!-- Bagian 2: Jam -->
                <section class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-primary text-on-primary text-label-sm font-bold flex items-center justify-center">2</span>
                        <h2 class="text-title-lg font-title-lg text-on-surface">Waktu & Durasi Sewa</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-label-lg font-label-lg text-on-surface mb-1.5">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jamMulai" required
                                class="w-full h-12 bg-surface-container-lowest border-1.5 border-outline-variant rounded-xl px-4 text-body-lg font-bold text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-label-lg font-label-lg text-on-surface mb-1.5">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="jamSelesai" required
                                class="w-full h-12 bg-surface-container-lowest border-1.5 border-outline-variant rounded-xl px-4 text-body-lg font-bold text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>
                </section>

                <!-- Bagian 3: Tanggal & Jumlah Sesi -->
                <section class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-primary text-on-primary text-label-sm font-bold flex items-center justify-center">3</span>
                        <h2 class="text-title-lg font-title-lg text-on-surface">Tanggal Mulai & Frekuensi Sesi</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-label-lg font-label-lg text-on-surface mb-1.5">Tanggal Mulai Sesi Pertama</label>
                            <input type="date" name="tanggal_mulai" id="tanggalMulai" required min="{{ now()->format('Y-m-d') }}"
                                class="w-full h-12 bg-surface-container-lowest border-1.5 border-outline-variant rounded-xl px-4 text-body-md font-bold text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20">
                            <p class="text-label-sm font-label-sm text-outline mt-1.5" id="hariCocokInfo"></p>
                        </div>
                        <div>
                            <label class="block text-label-lg font-label-lg text-on-surface mb-1.5">Jumlah Sesi Berulang</label>
                            <select name="jumlah_sesi" id="jumlahSesi" required
                                class="w-full h-12 bg-surface-container-lowest border-1.5 border-outline-variant rounded-xl px-4 text-body-md font-semibold text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 appearance-none">
                                @for($i = 2; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == 4 ? 'selected' : '' }}>{{ $i }} Sesi</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </section>

                <!-- Info Box -->
                <section class="bg-[#FBF7E8] border border-tertiary-fixed-dim rounded-xl p-5 shadow-sm">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-tertiary text-[22px]">verified_user</span>
                        <p class="text-body-md font-body-md text-on-surface">
                            Sistem otomatis membuat booking untuk tiap sesi minggu berturut-turut. Kalau satu tanggal bentrok, sesi itu dilewati dan sisanya tetap diproses — kau bisa lihat rinciannya di halaman ringkasan setelah submit.
                        </p>
                    </div>
                </section>
            </div>

            <!-- Ringkasan kanan -->
            <aside class="lg:col-span-4 lg:sticky lg:top-28 space-y-4">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm space-y-5">
                    <h2 class="text-title-lg font-title-lg text-on-surface font-bold pb-4 border-b border-outline-variant">Rincian Paket</h2>

                    <div class="space-y-2.5" id="previewSesi">
                        <p class="text-body-sm text-outline">Lengkapi hari, jam, tanggal mulai & jumlah sesi untuk lihat preview jadwal.</p>
                    </div>

                    <div class="border-t border-outline-variant pt-4 space-y-2 text-body-sm font-body-sm">
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Estimasi Total (<span id="jumlahSesiText">0</span> sesi)</span>
                            <span class="font-semibold text-on-surface" id="totalHargaText">Rp 0</span>
                        </div>
                        <p class="text-label-sm text-outline">Harga final & sesi yang bentrok baru dipastikan setelah submit.</p>
                    </div>

                    <button type="submit" class="w-full py-4 px-6 rounded-xl bg-primary-container hover:bg-primary text-on-primary text-label-lg font-bold shadow-md transition-all flex items-center justify-center gap-2">
                        <span>Buat Paket Booking Berulang</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </div>
            </aside>
        </div>
    </form>
</main>

<script>
(function() {
    const hargaPerJam = {{ (int) $lapangan->harga_per_jam }};
    const namaHari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    const hariInput = document.getElementById('hariInput');
    const chips = document.querySelectorAll('.hari-chip');
    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            chips.forEach(c => c.classList.remove('bg-primary-container','text-on-primary','border-primary','border-2'));
            chip.classList.add('bg-primary-container','text-on-primary','border-primary','border-2');
            hariInput.value = chip.dataset.hari;
            renderPreview();
        });
    });

    const jamMulai = document.getElementById('jamMulai');
    const jamSelesai = document.getElementById('jamSelesai');
    const tanggalMulai = document.getElementById('tanggalMulai');
    const jumlahSesi = document.getElementById('jumlahSesi');
    const hariCocokInfo = document.getElementById('hariCocokInfo');

    [jamMulai, jamSelesai, tanggalMulai, jumlahSesi].forEach(el => el.addEventListener('input', renderPreview));

    function durasiJam() {
        if (!jamMulai.value || !jamSelesai.value) return 0;
        const [h1, m1] = jamMulai.value.split(':').map(Number);
        const [h2, m2] = jamSelesai.value.split(':').map(Number);
        const menit = (h2 * 60 + m2) - (h1 * 60 + m1);
        return menit > 0 ? menit / 60 : 0;
    }

    function renderPreview() {
        const previewEl = document.getElementById('previewSesi');
        const jumlahSesiText = document.getElementById('jumlahSesiText');
        const totalHargaText = document.getElementById('totalHargaText');

        if (!hariInput.value || !tanggalMulai.value || !jumlahSesi.value) {
            previewEl.innerHTML = '<p class="text-body-sm text-outline">Lengkapi hari, jam, tanggal mulai & jumlah sesi untuk lihat preview jadwal.</p>';
            jumlahSesiText.textContent = '0';
            totalHargaText.textContent = 'Rp 0';
            return;
        }

        const hariTarget = parseInt(hariInput.value);
        let tgl = new Date(tanggalMulai.value + 'T00:00:00');
        while (tgl.getDay() !== hariTarget) {
            tgl.setDate(tgl.getDate() + 1);
        }
        if (tgl.getDay() === hariTarget && tanggalMulai.value) {
            const tglDipilih = new Date(tanggalMulai.value + 'T00:00:00');
            hariCocokInfo.textContent = tglDipilih.getDay() === hariTarget
                ? 'Tanggal mulai sudah sesuai hari ' + namaHari[hariTarget] + '.'
                : 'Sesi pertama akan mulai ' + namaHari[hariTarget] + ' terdekat sejak tanggal ini.';
        }

        const n = parseInt(jumlahSesi.value);
        let html = '';
        for (let i = 0; i < n; i++) {
            const tanggalSesi = new Date(tgl);
            tanggalSesi.setDate(tanggalSesi.getDate() + (i * 7));
            const label = tanggalSesi.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            html += `<div class="p-2.5 rounded-lg bg-surface-container-low border border-outline-variant text-body-sm">
                        <div class="font-bold text-on-surface">Sesi ${i+1}: ${label}</div>
                     </div>`;
        }
        previewEl.innerHTML = html;
        jumlahSesiText.textContent = n;

        const durasi = durasiJam();
        const total = hargaPerJam * durasi * n;
        totalHargaText.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
})();
</script>
@endsection