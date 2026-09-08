@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Pengajuan Refund</h2>

        <form method="GET" action="{{ route('admin.refund.index') }}">
            <select name="status" onchange="this.form.submit()"
                class="text-sm rounded-lg border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500/30 focus:border-teal-500">
                <option value="">Semua Status</option>
                <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </form>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">
            {{ session('success') }}
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
                        <p class="text-xs text-gray-400 mt-1">{{ $booking->tanggal_booking->format('d M Y') }} · Rp{{ number_format($booking->total_harga) }}</p>
                    </div>
                    @if ($booking->status_refund === 'diproses')
                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">DIPROSES</span>
                    @elseif ($booking->status_refund === 'selesai')
                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700">SELESAI</span>
                    @else
                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700">DITOLAK</span>
                    @endif
                </button>
                @empty
                <div class="px-5 py-10 text-center text-gray-400 text-sm">Belum ada pengajuan refund.</div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT: DETAIL PANEL --}}
        <div class="w-full flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-8 min-h-[400px]">

            @forelse ($bookings as $booking)
            <div x-show="activeId === {{ $booking->id }}" x-cloak>

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $booking->user->name }}</h3>
                    @if ($booking->status_refund === 'diproses')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">DIPROSES</span>
                    @elseif ($booking->status_refund === 'selesai')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-700">SELESAI</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700">DITOLAK</span>
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
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Tanggal Booking</p>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $booking->tanggal_booking->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Payment Reference</p>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $booking->payment_reference }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Alasan Pembatalan</p>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $booking->alasan_pembatalan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Catatan Refund</p>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $booking->catatan_refund ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    @php
                        $log = $booking->refundLogs->last() ?? null;
                    @endphp

                    @if ($log)
                        <div class="rounded-lg bg-gray-50 border border-gray-100 text-sm px-4 py-3 text-gray-700">
                            <p>Diajukan oleh: <span class="font-bold">{{ $log->admin->name ?? '-' }}</span></p>
                            <p>Nominal refund: <span class="font-bold">Rp{{ number_format($log->nominal) }}</span> ({{ $log->persentase }}%)</p>
                            <p>Hasil: <span class="font-bold">{{ $log->hasil }}</span></p>
                        </div>
                    @endif

                    @if ($booking->status_refund === 'diproses')
                        <div class="mt-4 rounded-lg bg-amber-50 text-amber-700 text-sm px-4 py-3">
                            Refund sedang diproses oleh Midtrans.
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

    <div class="mt-6">
        {{ $bookings->links() }}
    </div>

</div>
@endsection