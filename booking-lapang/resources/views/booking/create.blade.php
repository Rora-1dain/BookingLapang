@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8 text-white">
    <h2 class="text-xl font-bold mb-4">Form Booking Lapang</h2>

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">{{ session('error') }}</div>
    @endif

    <form action="{{ route('booking.store') }}" method="POST">
        @csrf

        <label class="block mb-1">Pilih Lapangan</label>
        <select name="lapangan_id" required class="w-full border rounded p-2 mb-4 text-gray-900 bg-white">
            @foreach ($lapangans as $lapangan)
                <option value="{{ $lapangan->id }}">
                    {{ $lapangan->nama_lapangan }} - Rp{{ number_format($lapangan->harga_per_jam) }}/jam
                </option>
            @endforeach
        </select>

        <label class="block mb-1">Tanggal Booking</label>
        <input type="date" name="tanggal_booking" required class="w-full border rounded p-2 mb-4 text-gray-900 bg-white">

        <label class="block mb-1">Jam Mulai</label>
        <input type="time" name="jam_mulai" required class="w-full border rounded p-2 mb-4 text-gray-900 bg-white">

        <label class="block mb-1">Jam Selesai</label>
        <input type="time" name="jam_selesai" required class="w-full border rounded p-2 mb-4 text-gray-900 bg-white">

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Booking Sekarang</button>
    </form>
</div>
@endsection