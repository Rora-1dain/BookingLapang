@extends('layouts.app')

@section('content')
<h2>Form Booking Lapang</h2>

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('booking.store') }}" method="POST">
    @csrf

    <label>Pilih Lapangan</label>
    <select name="lapangan_id" required>
        @foreach ($lapangans as $lapangan)
            <option value="{{ $lapangan->id }}">
                {{ $lapangan->nama_lapangan }} - Rp{{ number_format($lapangan->harga_per_jam) }}/jam
            </option>
        @endforeach
    </select>

    <label>Tanggal Booking</label>
    <input type="date" name="tanggal_booking" required>

    <label>Jam Mulai</label>
    <input type="time" name="jam_mulai" required>

    <label>Jam Selesai</label>
    <input type="time" name="jam_selesai" required>

    <button type="submit">Booking Sekarang</button>
</form>
@endsection
