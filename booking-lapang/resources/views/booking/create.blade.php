@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900">Form Booking Lapangan</h2>
            <p class="text-gray-500 mb-6">Isi detail di bawah untuk mengamankan jadwal Anda.</p>

            @if (session('error'))
                <div class="flex items-center gap-2 bg-red-100 text-red-700 p-3 mb-6 rounded-lg">
                    <span>⚠️</span> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('booking.store') }}" method="POST" id="form-booking">
                @csrf

                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Lapangan</label>
                <select name="lapangan_id" id="lapangan_id" required
                    class="w-full border border-gray-300 rounded-lg p-3 mb-1 text-gray-900">
                    @foreach ($lapangans as $lapangan)
                        <option value="{{ $lapangan->id }}"
                            data-rating="{{ $lapangan->rataRataRating() }}"
                            data-jumlah-ulasan="{{ $lapangan->ulasans()->count() }}"
                            data-harga="{{ $lapangan->harga_per_jam }}">
                            {{ $lapangan->nama_lapangan }} - {{ $lapangan->jenis }} - Rp{{ number_format($lapangan->harga_per_jam) }}/jam
                        </option>
                    @endforeach
                </select>

                <div id="badge-rating" class="flex items-center gap-1 text-sm mb-4 text-gray-500">
                    <span class="text-yellow-400" id="badge-bintang">☆☆☆☆☆</span>
                    <span id="badge-teks">Pilih lapangan untuk lihat rating</span>
                </div>

                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Booking</label>
                <input type="date" name="tanggal_booking" id="tanggal_booking" required
                    class="w-full border border-gray-300 rounded-lg p-3 mb-4 text-gray-900">

                <div class="grid grid-cols-2 gap-4 mb-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" required
                            class="w-full border border-gray-300 rounded-lg p-3 text-gray-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" required
                            class="w-full border border-gray-300 rounded-lg p-3 text-gray-900">
                    </div>
                </div>

                {{-- Status ketersediaan + tombol Daftar Tunggu, muncul otomatis kalau slot penuh --}}
                <p id="ketersediaan-info" class="text-sm mb-2 hidden"></p>
                <div id="waitlist-box" class="hidden bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-amber-800 mb-2">
                        Jadwal ini sedang penuh. Daftar tunggu untuk dapat notifikasi kalau ada yang batal.
                    </p>
                    <button type="button" id="btn-daftar-tunggu"
                        class="bg-amber-500 hover:bg-amber-600 text-white rounded-lg px-4 py-2 text-sm font-medium">
                        Daftar Tunggu
                    </button>
                    <p id="waitlist-info" class="text-sm mt-2"></p>
                </div>

                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Voucher (opsional)</label>
                <div class="flex gap-2 mb-1">
                    <input type="text" name="kode_voucher" id="kode_voucher"
                        class="flex-1 border border-gray-300 rounded-lg p-3 text-gray-900 uppercase"
                        placeholder="PROMO-XXXXXX">
                    <button type="button" id="btn-cek-voucher"
                        class="bg-gray-800 hover:bg-gray-900 text-white rounded-lg px-4 font-medium">
                        Cek Voucher
                    </button>
                </div>
                <p id="voucher-info" class="text-sm mb-6"></p>

                <div class="flex gap-3">
                    <a href="{{ route('booking.index') }}"
                        class="flex-1 text-center border border-gray-300 rounded-lg py-3 text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" id="btn-submit-booking"
                        class="flex-1 bg-teal-600 hover:bg-teal-700 text-white rounded-lg py-3 font-medium">
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