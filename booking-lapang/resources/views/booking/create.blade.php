@extends('layouts.frontend')

@section('title', 'Booking Lapangan - Booking Lapang')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10 md:py-14">
    <div class="mb-7">
        <p class="text-label-sm text-primary font-bold uppercase tracking-wider">Booking Lapangan</p>
        <h1 class="mt-2 text-headline-lg-mobile md:text-headline-lg text-on-surface font-extrabold tracking-tight">Amankan jadwal main kamu</h1>
        <p class="mt-2 text-body-md text-on-surface-variant">Pilih venue, tanggal, dan jam bermain. Kami cek ketersediaannya sebelum booking dikirim.</p>
    </div>
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-soft-md p-5 sm:p-7">
                        @if (session('error'))
                <div class="flex items-center gap-2 bg-error-container text-on-error-container p-3 mb-6 rounded-xl border border-red-200">
                    <span>⚠️</span> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('booking.store') }}" method="POST" id="form-booking">
                @csrf

                <label class="block text-label-md text-on-surface-variant mb-1.5">Pilih Lapangan</label>
                <select name="lapangan_id" id="lapangan_id" required
                    class="w-full h-12 border border-outline-variant rounded-xl px-4 bg-surface-container-low text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/10">
                    @foreach ($lapangans as $lapangan)
                        <option value="{{ $lapangan->id }}"
                            data-rating="{{ $lapangan->rataRataRating() }}"
                            data-jumlah-ulasan="{{ $lapangan->ulasans()->count() }}"
                            data-harga="{{ $lapangan->harga_per_jam }}">
                            {{ $lapangan->nama_lapangan }} - {{ $lapangan->jenis }} - Rp{{ number_format($lapangan->harga_per_jam) }}/jam
                        </option>
                    @endforeach
                </select>

                <div id="badge-rating" class="flex items-center gap-2 text-body-sm mb-6 text-on-surface-variant">
                    <span class="text-tertiary text-lg tracking-[2px]" id="badge-bintang">☆☆☆☆☆</span>
                    <span id="badge-teks">Pilih lapangan untuk lihat rating</span>
                </div>

                <label class="block text-label-md text-on-surface-variant mb-1.5">Tanggal Booking</label>
                <input type="date" name="tanggal_booking" id="tanggal_booking" required
                    class="w-full h-12 border border-outline-variant rounded-xl px-4 bg-surface-container-low text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/10">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-label-md text-on-surface-variant mb-1.5">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" required
                            class="w-full h-12 border border-outline-variant rounded-xl px-4 bg-surface-container-low text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/10">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface-variant mb-1.5">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" required
                            class="w-full h-12 border border-outline-variant rounded-xl px-4 bg-surface-container-low text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/10">
                    </div>
                </div>

                {{-- Status ketersediaan + tombol Daftar Tunggu, muncul otomatis kalau slot penuh --}}
                <p id="ketersediaan-info" class="text-body-sm mb-2 hidden font-semibold"></p>
                <div id="waitlist-box" class="hidden bg-tertiary-fixed/40 border border-tertiary-fixed rounded-xl p-4 mb-5">
                    <p class="text-body-sm text-on-surface mb-3">
                        Jadwal ini sedang penuh. Daftar tunggu untuk dapat notifikasi kalau ada yang batal.
                    </p>
                    <button type="button" id="btn-daftar-tunggu"
                        class="btn btn-secondary !border-tertiary !text-tertiary-container !bg-white">
                        Daftar Tunggu
                    </button>
                    <p id="waitlist-info" class="text-body-sm mt-2"></p>
                </div>

                <label class="block text-label-md text-on-surface-variant mb-1.5">Kode Voucher (opsional)</label>
                <div class="flex flex-col sm:flex-row gap-2 mb-1">
                    <input type="text" name="kode_voucher" id="kode_voucher"
                        class="flex-1 h-12 border border-outline-variant rounded-xl px-4 bg-surface-container-low text-on-surface uppercase focus:border-primary focus:ring-2 focus:ring-primary/10"
                        placeholder="PROMO-XXXXXX">
                    <button type="button" id="btn-cek-voucher"
                        class="btn btn-secondary">
                        Cek Voucher
                    </button>
                </div>
                <p id="voucher-info" class="text-body-sm mb-6"></p>

                <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2 border-t border-outline-variant">
                    <a href="{{ route('booking.index') }}"
                        class="btn btn-secondary flex-1">
                        Batal
                    </a>
                    <button type="submit" id="btn-submit-booking"
                        class="btn btn-primary flex-1">
                        Booking Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const selectLapangan = document.getElementById('lapangan_id');
    const badgeBintang = document.getElementById('badge-bintang');
    const badgeTeks = document.getElementById('badge-teks');

    function updateBadgeRating() {
        const selected = selectLapangan.options[selectLapangan.selectedIndex];
        const rating = parseFloat(selected.dataset.rating || 0);
        const jumlahUlasan = selected.dataset.jumlahUlasan || 0;

        let bintang = '';
        for (let i = 1; i <= 5; i++) {
            bintang += i <= Math.round(rating) ? '★' : '☆';
        }
        badgeBintang.textContent = bintang;
        badgeTeks.textContent = rating > 0
            ? `${rating} (${jumlahUlasan} ulasan)`
            : 'Belum ada ulasan';
    }

    selectLapangan.addEventListener('change', updateBadgeRating);
    updateBadgeRating();

    document.getElementById('btn-cek-voucher').addEventListener('click', function () {
        const kode = document.getElementById('kode_voucher').value.trim();
        const info = document.getElementById('voucher-info');
        const selected = selectLapangan.options[selectLapangan.selectedIndex];
        const totalHarga = parseFloat(selected.dataset.harga || 0);

        if (!kode) {
            info.textContent = 'Masukkan kode voucher dulu.';
            info.className = 'text-sm mb-6 text-red-600';
            return;
        }

        info.textContent = 'Mengecek voucher...';
        info.className = 'text-sm mb-6 text-gray-500';

        fetch("{{ route('voucher.cek') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ kode_voucher: kode, total_harga: totalHarga })
        })
        .then(res => res.json())
        .then(data => {
            if (data.valid) {
                info.textContent = `✅ Voucher valid — diskon ${data.jenis_diskon === 'persen' ? data.nilai + '%' : 'Rp' + data.nilai}`;
                info.className = 'text-sm mb-6 text-teal-600';
            } else {
                info.textContent = `❌ ${data.message}`;
                info.className = 'text-sm mb-6 text-red-600';
            }
        })
        .catch(() => {
            info.textContent = 'Gagal mengecek voucher, coba lagi.';
            info.className = 'text-sm mb-6 text-red-600';
        });
    });

    // === Bagian 4 Revano: cek ketersediaan slot + tombol Daftar Tunggu ===
    const tanggalInput = document.getElementById('tanggal_booking');
    const jamMulaiInput = document.getElementById('jam_mulai');
    const jamSelesaiInput = document.getElementById('jam_selesai');
    const ketersediaanInfo = document.getElementById('ketersediaan-info');
    const waitlistBox = document.getElementById('waitlist-box');
    const btnDaftarTunggu = document.getElementById('btn-daftar-tunggu');
    const btnSubmitBooking = document.getElementById('btn-submit-booking');
    const waitlistInfo = document.getElementById('waitlist-info');

    function slotLengkapDiisi() {
        return selectLapangan.value && tanggalInput.value && jamMulaiInput.value && jamSelesaiInput.value;
    }

    function resetStatusSlot() {
        ketersediaanInfo.classList.add('hidden');
        waitlistBox.classList.add('hidden');
        waitlistInfo.textContent = '';
        btnDaftarTunggu.disabled = false;
        btnDaftarTunggu.textContent = 'Daftar Tunggu';
        btnSubmitBooking.disabled = false;
        btnSubmitBooking.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    function cekKetersediaanSlot() {
        if (!slotLengkapDiisi()) {
            resetStatusSlot();
            return;
        }

        const payload = {
            lapangan_id: selectLapangan.value,
            tanggal_booking: tanggalInput.value,
            jam_mulai: jamMulaiInput.value,
            jam_selesai: jamSelesaiInput.value,
        };

        ketersediaanInfo.classList.remove('hidden');
        ketersediaanInfo.textContent = 'Mengecek ketersediaan slot...';
        ketersediaanInfo.className = 'text-sm mb-2 text-gray-500';

        fetch("{{ route('booking.cekKetersediaan') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify(payload),
        })
        .then(res => res.json())
        .then(data => {
            if (data.tersedia) {
                ketersediaanInfo.textContent = '✅ Slot tersedia.';
                ketersediaanInfo.className = 'text-sm mb-2 text-teal-600';
                waitlistBox.classList.add('hidden');
                btnSubmitBooking.disabled = false;
                btnSubmitBooking.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                ketersediaanInfo.textContent = '❌ Slot ini sudah penuh.';
                ketersediaanInfo.className = 'text-sm mb-2 text-red-600';
                waitlistBox.classList.remove('hidden');
                // slot penuh: booking langsung dimatikan dulu, arahkan ke waitlist
                btnSubmitBooking.disabled = true;
                btnSubmitBooking.classList.add('opacity-50', 'cursor-not-allowed');
            }
        })
        .catch(() => {
            ketersediaanInfo.textContent = 'Gagal mengecek ketersediaan, coba lagi.';
            ketersediaanInfo.className = 'text-sm mb-2 text-red-600';
        });
    }

    [selectLapangan, tanggalInput, jamMulaiInput, jamSelesaiInput].forEach(el => {
        el.addEventListener('change', cekKetersediaanSlot);
    });

    btnDaftarTunggu.addEventListener('click', function () {
        if (!slotLengkapDiisi()) return;

        const payload = {
            lapangan_id: selectLapangan.value,
            tanggal_booking: tanggalInput.value,
            jam_mulai: jamMulaiInput.value,
            jam_selesai: jamSelesaiInput.value,
        };

        btnDaftarTunggu.disabled = true;
        btnDaftarTunggu.textContent = 'Mendaftarkan...';
        waitlistInfo.textContent = '';

        fetch("{{ route('waitlist.daftar') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify(payload),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btnDaftarTunggu.textContent = 'Terdaftar di Antrian';
                waitlistInfo.textContent = '✅ Kamu masuk antrian waitlist. Kami kabari kalau ada slot kosong.';
                waitlistInfo.className = 'text-sm mt-2 text-teal-700';
            } else {
                btnDaftarTunggu.disabled = false;
                btnDaftarTunggu.textContent = 'Daftar Tunggu';
                waitlistInfo.textContent = `❌ ${data.message}`;
                waitlistInfo.className = 'text-sm mt-2 text-red-600';
            }
        })
        .catch(() => {
            btnDaftarTunggu.disabled = false;
            btnDaftarTunggu.textContent = 'Daftar Tunggu';
            waitlistInfo.textContent = 'Gagal mendaftar waitlist, coba lagi.';
            waitlistInfo.className = 'text-sm mt-2 text-red-600';
        });
    });
</script>
@endsection