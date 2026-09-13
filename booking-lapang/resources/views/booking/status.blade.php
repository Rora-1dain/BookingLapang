@extends('layouts.frontend')
@section('title','Status Pembayaran - Booking Lapang')
@section('content')
<main class="flex-grow max-w-4xl w-full mx-auto px-6 md:px-12 py-10">
<div class="mb-8"><p class="text-label-sm text-primary-container font-bold uppercase tracking-wider">Transaksi</p><h1 class="text-headline-lg font-headline-lg text-on-surface font-bold">Status Pembayaran</h1><p class="text-body-md text-on-surface-variant">Booking #{{ $booking->id }} · {{ $booking->lapangan->nama_lapangan }}</p></div>
@if(session('info'))<div class="mb-6 p-4 rounded-xl bg-surface-container border border-outline-variant text-on-surface">{{ session('info') }}</div>@endif
<div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 shadow-sm">
<div class="grid sm:grid-cols-2 gap-4">
<div class="p-4 rounded-xl bg-surface-container-low"><p class="text-label-sm text-outline">Lapangan</p><p class="text-title-md font-bold text-on-surface">{{ $booking->lapangan->nama_lapangan }}</p></div>
<div class="p-4 rounded-xl bg-surface-container-low"><p class="text-label-sm text-outline">Tanggal</p><p class="text-title-md font-bold text-on-surface">{{ $booking->tanggal_booking->translatedFormat('l, d F Y') }}</p></div>
<div class="p-4 rounded-xl bg-surface-container-low"><p class="text-label-sm text-outline">Jam</p><p class="text-title-md font-bold text-on-surface">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }} WIB</p></div>
<div class="p-4 rounded-xl bg-surface-container-low"><p class="text-label-sm text-outline">Total</p><p class="text-title-md font-bold text-primary">Rp {{ number_format($booking->total_harga,0,',','.') }}</p></div>
<div class="p-4 rounded-xl bg-surface-container-low"><p class="text-label-sm text-outline">Status Booking</p><p class="text-title-md font-bold text-on-surface">{{ ucfirst($booking->status) }}</p></div>
<div class="p-4 rounded-xl bg-surface-container-low"><p class="text-label-sm text-outline">Status Pembayaran</p><p class="text-title-md font-bold text-on-surface">{{ ucfirst($booking->status_pembayaran ?? 'belum') }}</p></div>
</div>
<div class="mt-6 flex flex-wrap gap-3">
<form method="POST" action="{{ route('booking.cek-status',$booking) }}">@csrf<button class="px-5 py-3 bg-primary-container text-white rounded-xl font-bold">Cek Ulang Status</button></form>
<a href="{{ route('booking.invoice',$booking) }}" class="px-5 py-3 bg-surface-container border border-outline-variant text-primary rounded-xl font-bold">Unduh Invoice PDF</a>
<a href="{{ route('booking.index') }}" class="px-5 py-3 border border-outline-variant rounded-xl font-bold text-on-surface">Kembali</a>
</div></div></main>
@endsection
