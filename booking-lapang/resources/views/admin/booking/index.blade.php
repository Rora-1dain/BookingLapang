@extends('layouts.booking')

@section('content')
<h2>Kelola Booking (Admin)</h2>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<table border="1" cellpadding="8">
    <tr>
        <th>Pemesan</th><th>Lapangan</th><th>Tanggal</th><th>Jam</th><th>Total</th><th>Status</th><th>Aksi</th>
    </tr>
    @foreach ($bookings as $booking)
    <tr>
        <td>{{ $booking->user->name }}</td>
        <td>{{ $booking->lapangan->nama_lapangan }}</td>
        <td>{{ $booking->tanggal_booking->format('d-m-Y') }}</td>
        <td>{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
        <td>Rp{{ number_format($booking->total_harga) }}</td>
        <td>
            @if ($booking->status === 'pending')
                <span style="color:orange">Pending</span>
            @elseif ($booking->status === 'confirmed')
                <span style="color:green">Confirmed</span>
            @else
                <span style="color:red">Cancelled</span>
            @endif
        </td>
        <td>
            @if ($booking->status === 'pending')
                <form action="{{ route('admin.booking.confirm', $booking) }}" method="POST">
                    @csrf
                    <button type="submit">Konfirmasi</button>
                </form>
            @endif
        </td>
    </tr>
    @endforeach
</table>
@endsection