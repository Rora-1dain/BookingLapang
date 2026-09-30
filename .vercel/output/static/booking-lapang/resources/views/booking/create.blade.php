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

            <form action="{{ route('booking.store') }}" method="POST">
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
                <input type="date" name="tanggal_booking" required
                    class="w-full border border-gray-300 rounded-lg p-3 mb-4 text-gray-900">

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" required
                            class="w-full border border-gray-300 rounded-lg p-3 text-gray-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" required
                            class="w-full border border-gray-300 rounded-lg p-3 text-gray-900">
                    </div>
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
                    <button type="submit"
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
</script>
@endsection