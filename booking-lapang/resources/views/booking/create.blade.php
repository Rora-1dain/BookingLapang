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
                <select name="lapangan_id" required
                    class="w-full border border-gray-300 rounded-lg p-3 mb-4 text-gray-900">
                    @foreach ($lapangans as $lapangan)
                        <option value="{{ $lapangan->id }}">
                            {{ $lapangan->nama_lapangan }} - {{ $lapangan->jenis }} - Rp{{ number_format($lapangan->harga_per_jam) }}/jam
                        </option>
                    @endforeach
                </select>

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
@endsection