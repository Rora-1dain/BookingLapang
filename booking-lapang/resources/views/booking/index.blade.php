@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-3xl mx-auto">

        @if (session('success'))
            <div class="bg-teal-100 text-teal-800 p-3 mb-6 rounded-lg flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-3 mb-6 rounded-lg flex items-center gap-2">
                ⚠️ {{ session('error') }}
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
                <div class="bg-white rounded-2xl shadow-sm p-5
                    {{ $booking->status === 'cancelled' ? 'opacity-60' : '' }}">
                    <div class="flex justify-between items-center">
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

                        {{-- TODO(merge Ardan): $booking->ulasan (hasOne) baru valid pas relasi Ardan udah masuk --}}
                        @if ($booking->status === 'confirmed' && $booking->tanggal_booking->isPast() && !$booking->ulasan()->exists())
                            <button type="button"
                                onclick="document.getElementById('form-ulasan-{{ $booking->id }}').classList.toggle('hidden')"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg px-4 py-2 text-sm font-medium">
                                ⭐ Beri Ulasan
                            </button>
                        @elseif ($booking->status === 'confirmed' && $booking->tanggal_booking->isPast())
                            <span class="text-xs text-gray-400">Sudah diulas</span>
                        @endif
                    </div>

                    @if ($booking->status === 'confirmed' && $booking->tanggal_booking->isPast() && !$booking->ulasan()->exists())
                        <div id="form-ulasan-{{ $booking->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
                            <form action="{{ route('ulasan.store', $booking) }}" method="POST">
                                @csrf
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                <select name="rating" required class="border border-gray-300 rounded-lg p-2 mb-3 text-gray-900">
                                    <option value="">Pilih rating</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ str_repeat('★', $i) }} ({{ $i }})</option>
                                    @endfor
                                </select>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Komentar (opsional)</label>
                                <textarea name="komentar" rows="3" maxlength="500"
                                    class="w-full border border-gray-300 rounded-lg p-2 mb-3 text-gray-900"
                                    placeholder="Bagaimana pengalaman Anda?"></textarea>
                                <button type="submit"
                                    class="bg-teal-600 hover:bg-teal-700 text-white rounded-lg px-4 py-2 text-sm font-medium">
                                    Kirim Ulasan
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