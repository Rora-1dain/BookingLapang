@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 text-white">
    <h2 class="text-xl font-bold mb-4">Daftar Booking Saya</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">{{ session('success') }}</div>
    @endif

    <table class="w-full border border-gray-600">
        <tr class="bg-gray-800">
            <th class="p-2 border border-gray-600">Lapangan</th>
            <th class="p-2 border border-gray-600">Tanggal</th>
            <th class="p-2 border border-gray-600">Jam</th>
            <th class="p-2 border border-gray-600">Total</th>
            <th class="p-2 border border-gray-600">Status</th>
            <th class="p-2 border border-gray-600">Aksi</th>
        </tr>
        @foreach ($bookings as $booking)
        <tr>
            <td class="p-2 border border-gray-600">{{ $booking->lapangan->nama_lapangan }}</td>
            <td class="p-2 border border-gray-600">{{ $booking->tanggal_booking->format('d-m-Y') }}</td>
            <td class="p-2 border border-gray-600">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
            <td class="p-2 border border-gray-600">Rp{{ number_format($booking->total_harga) }}</td>
            <td class="p-2 border border-gray-600">
                @if ($booking->status === 'pending')
                    <span style="color:orange">Pending</span>
                @elseif ($booking->status === 'confirmed')
                    <span style="color:green">Confirmed</span>
                @else
                    <span style="color:red">Cancelled</span>
                @endif
            </td>
            <td class="p-2 border border-gray-600">
                @if ($booking->status === 'pending')
                    <form action="{{ route('booking.cancel', $booking) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-400 underline">Batalkan</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection