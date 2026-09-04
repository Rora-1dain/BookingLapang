@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Kelola Booking (Admin)</h2>
        <a href="{{ route('admin.booking.export') }}"
           class="inline-flex items-center px-4 py-2 rounded-lg bg-teal-700 hover:bg-teal-800 text-white text-sm font-bold transition">
            Export Excel
        </a>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show"
             class="mb-6 flex items-center justify-between rounded-xl bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25L15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" @click="show = false" class="text-green-500 hover:text-green-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div x-data="{ activeId: {{ $bookings->first()->id ?? 'null' }} }" class="flex flex-col md:flex-row gap-6 items-start">

        {{-- LEFT: LIST PANEL --}}
        <div class="w-full md:w-[38%] bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                    </svg>
                    <input type="text" placeholder="Cari pemesan..."
                        class="w-full text-sm pl-9 pr-3 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/30 focus:border-teal-500">
                </div>
            </div>

            <div class="divide-y divide-gray-100 max-h-[560px] overflow-y-auto">
                @forelse ($bookings as $booking)
                <button type="button" @click="activeId = {{ $booking->id }}"
                    class="w-full text-left px-5 py-4 flex items-start justify-between gap-3 transition"
                    :class="activeId === {{ $booking->id }}
                        ? 'bg-teal-50 border-l-4 border-teal-600'
                        : 'border-l-4 border-transparent hover:bg-gray-50'">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ $booking->user->name }}</p>
                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ $booking->lapangan->nama_lapangan }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $booking->tanggal_booking->format('d M Y') }} · {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</p>
                    </div>
                    @if ($booking->status === 'pending')
                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">PENDING</span>
                    @elseif ($booking->status === 'confirmed')
                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700">CONFIRMED</span>
                    @else
                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700">CANCELLED</span>
                    @endif
                </button>
                @empty
                <div class="px-5 py-10 text-center text-gray-400 text-sm">Belum ada data booking.</div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT: DETAIL PANEL --}}
        <div class="w-full flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-8 min-h-[400px]">

            @forelse ($bookings as $booking)
            <div x-show="activeId === {{ $booking->id }}" x-cloak>

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $booking->user->name }}</h3>
                    @if ($booking->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">PENDING</span>
                    @elseif ($booking->status === 'confirmed')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-700">CONFIRMED</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700">CANCELLED</span>
                    @endif
                </div>

                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="grid grid-cols-2 gap-x-10 gap-y-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Lapangan</p>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $booking->lapangan->nama_lapangan }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total Harga</p>
                            <p class="text-base font-bold text-gray-900 mt-1">Rp{{ number_format($booking->total_harga) }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Tanggal</p>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $booking->tanggal_booking->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Tanggal Dibuat</p>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $booking->created_at->format('d F Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Jam Mulai</p>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $booking->jam_mulai }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Jam Selesai</p>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $booking->jam_selesai }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    @if ($booking->status === 'pending')
                        <div class="flex gap-4">
                            <form action="{{ route('admin.booking.confirm', $booking) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-teal-700 hover:bg-teal-800 text-white text-sm font-bold px-5 py-3 rounded-lg transition">
                                    Konfirmasi Booking
                                </button>
                            </form>
                            <form action="{{ route('admin.booking.cancel', $booking) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full border border-red-300 text-red-600 hover:bg-red-50 text-sm font-bold px-5 py-3 rounded-lg transition">
                                    Tolak / Batalkan
                                </button>
                            </form>
                        </div>
                    @elseif ($booking->status === 'confirmed')
                        <div class="rounded-lg bg-green-50 text-green-700 text-sm px-4 py-3 mb-4">
                            Booking ini sudah dikonfirmasi.
                        </div>

                        @if (!$booking->status_refund || $booking->status_refund === 'belum_refund')
                            <form action="{{ route('admin.refund.store', $booking) }}" method="POST"
                                  onsubmit="return confirm('Yakin ajukan refund untuk booking ini?');">
                                @csrf
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Alasan Pembatalan</label>
                                <textarea name="alasan" rows="2" required
                                    class="w-full mt-1 mb-3 text-sm rounded-lg border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500"
                                    placeholder="Contoh: Lapangan bermasalah, permintaan user, dll."></textarea>
                                <button type="submit"
                                    class="w-full border border-red-300 text-red-600 hover:bg-red-50 text-sm font-bold px-5 py-3 rounded-lg transition">
                                    Batalkan & Ajukan Refund
                                </button>
                            </form>
                        @elseif ($booking->status_refund === 'diproses')
                            <div class="rounded-lg bg-amber-50 text-amber-700 text-sm px-4 py-3">
                                Refund sedang diproses.
                            </div>
                        @elseif ($booking->status_refund === 'selesai')
                            <div class="rounded-lg bg-blue-50 text-blue-700 text-sm px-4 py-3">
                                Refund sudah selesai diproses.
                            </div>
                        @elseif ($booking->status_refund === 'ditolak')
                            <div class="rounded-lg bg-red-50 text-red-700 text-sm px-4 py-3">
                                Refund ditolak. {{ $booking->catatan_refund }}
                            </div>
                        @endif
                    @else
                        <div class="rounded-lg bg-gray-100 text-gray-500 text-sm px-4 py-3">
                            Booking ini telah dibatalkan.
                        </div>
                    @endif
                </div>

            </div>
            @empty
            <div class="h-full flex flex-col items-center justify-center text-center text-gray-400 py-24">
                <svg class="w-10 h-10 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-sm">Pilih booking di sebelah kiri untuk melihat detail</p>
            </div>
            @endforelse

        </div>
    </div>

</div>
@endsection