@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-lg mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Lapangan</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 mb-6 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form method="POST" action="{{ route('pemilik.lapangan.update', $lapangan) }}">
                @csrf
                @method('PUT')

                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lapangan</label>
                <input type="text" name="nama_lapangan" value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}"
                    class="w-full border border-gray-300 rounded-lg p-2 mb-4 text-gray-900" required>

                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                <input type="text" name="jenis" value="{{ old('jenis', $lapangan->jenis) }}"
                    class="w-full border border-gray-300 rounded-lg p-2 mb-4 text-gray-900" required>

                <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Jam</label>
                <input type="number" name="harga_per_jam" value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}" min="0"
                    class="w-full border border-gray-300 rounded-lg p-2 mb-6 text-gray-900" required>

                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-teal-600 hover:bg-teal-700 text-white rounded-lg px-4 py-2 text-sm font-medium">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('pemilik.lapangan.index') }}"
                        class="border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection