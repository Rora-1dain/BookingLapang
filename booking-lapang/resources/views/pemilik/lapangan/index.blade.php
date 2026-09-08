@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-4xl mx-auto">

        @if (session('success'))
            <div class="bg-teal-100 text-teal-800 p-3 mb-6 rounded-lg flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Lapangan Saya</h2>
            <a href="{{ route('pemilik.lapangan.create') }}"
                class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg font-medium">
                + Ajukan Lapangan Baru
            </a>
        </div>

        <div class="space-y-4">
            @forelse ($lapangans as $lapangan)
                <div class="bg-white rounded-2xl shadow-sm p-5 flex justify-between items-center">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            @if ($lapangan->status_approval === 'disetujui')
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Disetujui</span>
                            @elseif ($lapangan->status_approval === 'pending')
                                <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">Menunggu</span>
                            @else
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Ditolak</span>
                            @endif
                            <span class="text-xs text-gray-400">{{ ucfirst($lapangan->status) }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-900">{{ $lapangan->nama_lapangan }}</h3>
                        <p class="text-sm text-gray-500">{{ $lapangan->jenis }}</p>
                        <p class="text-sm text-gray-700 mt-1">Rp{{ number_format($lapangan->harga_per_jam) }}/jam</p>
                    </div>

                    <a href="{{ route('pemilik.lapangan.edit', $lapangan) }}"
                        class="border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        Edit
                    </a>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm p-8 text-center text-gray-400">
                    Belum ada lapangan yang diajukan.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection