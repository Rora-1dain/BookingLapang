@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Selamat datang, {{ auth()->user()->name }}!</h2>
        <p class="text-gray-600 mb-6">Kelola booking lapangan Anda di sini.</p>
        <a href="{{ route('booking.index') }}"
            class="inline-block bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium">
            Lihat Daftar Booking
        </a>
    </div>
</div>
@endsection