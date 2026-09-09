@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
            Cari Lapangan
        </h2>

        {{-- Form Filter --}}
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <form method="GET" action="{{ route('lapangan.publik.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">

                {{-- Kata Kunci --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Nama Lapangan</label>
                    <input type="text" name="kata_kunci" value="{{ $kriteria['kata_kunci'] ?? '' }}"
                           placeholder="misal: Lapangan A"
                           class="w-full rounded-md border-gray-300 shadow-sm">
                </div>

                {{-- Jenis --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                    <select name="jenis" class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Semua Jenis</option>
                        @foreach ($daftarJenis as $jenis)
                            <option value="{{ $jenis }}" @selected(($kriteria['jenis'] ?? '') === $jenis)>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kota --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                    <select name="kota" class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Semua Kota</option>
                        @foreach ($daftarKota as $kota)
                            <option value="{{ $kota }}" @selected(($kriteria['kota'] ?? '') === $kota)>
                                {{ $kota }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Harga Min --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Min</label>
                    <input type="number" name="harga_min" value="{{ $kriteria['harga_min'] ?? '' }}"
                           placeholder="Rp 0"
                           class="w-full rounded-md border-gray-300 shadow-sm">
                </div>

                {{-- Harga Max --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Max</label>
                    <input type="number" name="harga_max" value="{{ $kriteria['harga_max'] ?? '' }}"
                           placeholder="Tanpa batas"
                           class="w-full rounded-md border-gray-300 shadow-sm">
                </div>

                {{-- Rating Min --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating Minimal</label>
                    <select name="rating_min" class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Semua Rating</option>
                        @foreach ([4, 3, 2, 1] as $r)
                            <option value="{{ $r }}" @selected(($kriteria['rating_min'] ?? '') == $r)>
                                {{ $r }}+ ★
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="lg:col-span-6 flex gap-3">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                        Cari
                    </button>
                    <a href="{{ route('lapangan.publik.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300">
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>

        {{-- Hasil Pencarian --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($lapangans as $lapangan)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg">{{ $lapangan->nama_lapangan }}</h3>
                        <p class="text-sm text-gray-500">{{ $lapangan->jenis }} · {{ $lapangan->kota ?? 'Kota belum diisi' }}</p>
                        <p class="text-indigo-600 font-medium mt-2">
                            Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }} / jam
                        </p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 py-12">
                    Tidak ada lapangan yang cocok dengan filter kamu.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $lapangans->withQueryString()->links() }}
        </div>

    </div>
</div>
@endsection