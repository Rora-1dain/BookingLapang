<x-app-layout>
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-xl font-bold mb-4">Daftar Booking Saya</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">{{ session('success') }}</div>
        @endif

        <a href="{{ route('booking.create') }}" class="text-blue-600 underline mb-4 inline-block">+ Booking Baru</a>

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Lapangan</th>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Jam</th>
                    <th class="p-2 border">Total</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td class="p-2 border">{{ $booking->lapangan->nama_lapangan }}</td>
                        <td class="p-2 border">{{ $booking->tanggal_booking->format('d-m-Y') }}</td>
                        <td class="p-2 border">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
                        <td class="p-2 border">Rp{{ number_format($booking->total_harga) }}</td>
                        <td class="p-2 border">{{ $booking->status }}</td>
                        <td class="p-2 border">
                            @if ($booking->status !== 'cancelled')
                                <form method="POST" action="{{ route('booking.cancel', $booking->id) }}">
                                    @csrf
                                    <button type="submit" class="text-red-600 underline">Batalkan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-2 border text-center">Belum ada booking.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>