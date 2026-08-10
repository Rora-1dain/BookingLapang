<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 text-white">
        <h1 class="text-xl font-bold mb-4">Daftar Booking Saya</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">{{ session('success') }}</div>
        @endif

        <a href="{{ route('booking.create') }}" class="underline mb-4 inline-block">+ Booking Baru</a>

        <table class="w-full border border-gray-600">
            <thead>
                <tr class="bg-gray-800">
                    <th class="p-2 border border-gray-600">Lapangan</th>
                    <th class="p-2 border border-gray-600">Tanggal</th>
                    <th class="p-2 border border-gray-600">Jam</th>
                    <th class="p-2 border border-gray-600">Total</th>
                    <th class="p-2 border border-gray-600">Status</th>
                    <th class="p-2 border border-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td class="p-2 border border-gray-600">{{ $booking->lapangan->nama_lapangan }}</td>
                        <td class="p-2 border border-gray-600">{{ $booking->tanggal_booking->format('d-m-Y') }}</td>
                        <td class="p-2 border border-gray-600">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
                        <td class="p-2 border border-gray-600">Rp{{ number_format($booking->total_harga) }}</td>
                        <td class="p-2 border border-gray-600">{{ $booking->status }}</td>
                        <td class="p-2 border border-gray-600">
                            @if ($booking->status !== 'cancelled')
                                <form method="POST" action="{{ route('booking.cancel', $booking->id) }}">
                                    @csrf
                                    <button type="submit" class="text-red-400 underline">Batalkan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-2 border border-gray-600 text-center">Belum ada booking.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>