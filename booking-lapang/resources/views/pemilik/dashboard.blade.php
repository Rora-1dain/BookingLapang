@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Pemilik</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-sm p-5">
                <p class="text-sm text-gray-500">Lapangan Aktif</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalLapanganAktif }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5">
                <p class="text-sm text-gray-500">Booking Bulan Ini</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $jumlahBookingBulanIni }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5">
                <p class="text-sm text-gray-500">Pendapatan Kotor Bulan Ini</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">Rp{{ number_format($pendapatanBulanIni) }}</p>
            </div>
        </div>

        <a href="{{ route('pemilik.lapangan.index') }}"
            class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg font-medium">
            Kelola Lapangan Saya
        </a>
    </div>
</div>
@endsection