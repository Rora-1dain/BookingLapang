@extends('layouts.app')

@section('content')
<h2>Status Pembayaran Booking #{{ $booking->id }}</h2>

@if (session('info'))
    <div class="alert" style="background:#d1ecf1; padding:10px; margin-bottom:16px;">
        {{ session('info') }}
    </div>
@endif

<table border="1" cellpadding="8">
    <tr>
        <th>Lapangan</th>
        <td>{{ $booking->lapangan->nama_lapangan ?? '-' }}</td>
    </tr>
    <tr>
        <th>Tanggal</th>
        <td>{{ $booking->tanggal_booking }}</td>
    </tr>
    <tr>
        <th>Total Harga</th>
        <td>Rp{{ number_format($booking->total_harga) }}</td>
    </tr>
    <tr>
        <th>Status Booking</th>
        <td>{{ $booking->status }}</td>
    </tr>
    <tr>
        <th>Status Pembayaran</th>
        <td>{{ $booking->status_pembayaran }}</td>
    </tr>
</table>

<form method="POST" action="{{ route('booking.cek-status', $booking) }}" style="margin-top:16px;">
    @csrf
    <button type="submit">Cek Ulang Status</button>
</form>
@endsection