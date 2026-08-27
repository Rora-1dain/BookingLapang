@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-3xl mx-auto">

        @if (session('success'))
            <div class="bg-teal-100 text-teal-800 p-3 mb-6 rounded-lg flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Daftar Booking Saya</h2>
            <a href="{{ route('booking.create') }}"
                class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg font-medium">
                + Booking Baru
            </a>
        </div>

        <div class="space-y-4">
            @foreach ($bookings as $booking)
                <div class="bg-white rounded-2xl shadow-sm p-5 flex justify-between items-center
                    {{ $booking->status === 'cancelled' ? 'opacity-60' : '' }}">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            @if ($booking->status === 'pending')
                                <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">Pending</span>
                            @elseif ($booking->status === 'confirmed')
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Confirmed</span>
                            @else
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Cancelled</span>
                            @endif
                        </div>
                        <h3 class="font-semibold text-gray-900 {{ $booking->status === 'cancelled' ? 'line-through' : '' }}">
                            {{ $booking->lapangan->nama_lapangan }}
                        </h3>
                        <p class="text-sm text-gray-500">📅 {{ $booking->tanggal_booking->format('d M Y') }}</p>
                        <p class="text-sm text-gray-500">🕐 {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</p>
                        <p class="text-sm text-gray-700 mt-1">Total: Rp{{ number_format($booking->total_harga) }}</p>
                    </div>

                    @if ($booking->status === 'pending')
    <div class="flex gap-2">
        <a href="{{ route('booking.bayar', $booking) }}"
            class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2 text-sm font-medium">
            Bayar
        </a>
        <form action="{{ route('booking.cancel', $booking) }}" method="POST">
            @csrf
            <button type="submit"
                class="border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                Batalkan
            </button>
        </form>
    </div>
@endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection