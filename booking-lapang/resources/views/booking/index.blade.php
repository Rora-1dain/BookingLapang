@extends('layouts.frontend')
@section('title','Riwayat Booking & Invoice - Booking Lapang')
@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-6 md:px-12 py-8">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
<div><p class="text-label-sm text-primary-container font-bold uppercase tracking-wider mb-1">Akun Saya</p><h1 class="text-headline-lg font-headline-lg text-on-surface font-bold">Riwayat Booking & Invoice</h1><p class="text-body-md text-on-surface-variant mt-1">Semua pesanan lapangan olahraga kamu ada di sini.</p></div>
<a href="{{ route('booking.create') }}" class="bg-primary-container text-white px-5 py-3 rounded-xl font-bold flex items-center gap-2"><span class="material-symbols-outlined">add_circle</span>Booking Lapangan Baru</a>
</div>
@if(session('success'))<div class="mb-6 p-4 rounded-xl bg-primary-fixed text-on-primary-fixed">{{ session('success') }}</div>@endif
@if(session('error'))<div class="mb-6 p-4 rounded-xl bg-error-container text-error">{{ session('error') }}</div>@endif
<div class="space-y-4">
@forelse($bookings as $booking)
<article class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-5 shadow-sm">
<div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-4 border-b border-outline-variant/60">
<div class="flex gap-4">
<div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 border border-outline-variant bg-surface-container flex items-center justify-center">
<span class="material-symbols-outlined text-2xl text-primary">sports_soccer</span>
</div>
<div>
<div class="flex items-center gap-2 mb-1">
<span class="px-2 py-0.5 rounded-full text-label-sm font-bold {{ $booking->status === 'confirmed' ? 'bg-primary-fixed text-primary' : ($booking->status === 'pending' ? 'bg-tertiary-fixed text-tertiary-container' : 'bg-surface-container-high text-on-surface-variant') }}">{{ ucfirst($booking->status) }}</span>
@if($booking->status_pembayaran)<span class="px-2 py-0.5 rounded-full text-label-sm font-bold bg-surface-container text-on-surface-variant">{{ ucfirst($booking->status_pembayaran) }}</span>@endif
</div>
<h3 class="text-title-lg font-title-lg text-on-surface font-bold">{{ $booking->lapangan->nama_lapangan }}</h3>
<p class="text-body-sm text-on-surface-variant">{{ $booking->lapangan->jenis }} · {{ $booking->lapangan->kota ?? '-' }}</p>
<p class="text-body-sm text-outline font-mono mt-1">Booking ID: <strong class="text-on-surface">#{{ $booking->id }}</strong></p>
</div></div>
<div class="text-right"><p class="text-label-sm text-outline">Total Pembayaran</p><p class="text-title-lg text-primary font-bold">Rp {{ number_format($booking->total_harga,0,',','.') }}</p></div>
</div>
<div class="pt-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
<div class="flex flex-wrap items-center gap-4 text-body-sm text-on-surface-variant">
<span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">calendar_today</span>{{ $booking->tanggal_booking->translatedFormat('l, d F Y') }}</span>
<span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span>{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }} WIB</span>
</div>
<div class="flex flex-wrap gap-2">
@if($booking->status === 'pending')<a href="{{ route('booking.bayar',$booking) }}" class="px-4 py-2 bg-primary-container text-white rounded-xl text-label-sm font-bold">Bayar Sekarang</a><form method="POST" action="{{ route('booking.cancel',$booking) }}">@csrf<button class="px-4 py-2 border border-outline-variant rounded-xl text-label-sm font-bold">Batalkan</button></form>@endif
<a href="{{ route('booking.status',$booking) }}" class="px-4 py-2 bg-surface-container border border-outline-variant text-primary rounded-xl text-label-sm font-bold">Status</a>
<a href="{{ route('booking.invoice',$booking) }}" class="px-4 py-2 bg-surface-container border border-outline-variant text-primary rounded-xl text-label-sm font-bold">Invoice PDF</a>
@if($booking->status === 'confirmed' && $booking->tanggal_booking->isPast() && !$booking->ulasan()->exists())<a href="{{ route('booking.rating',$booking) }}" class="px-4 py-2 bg-secondary text-white rounded-xl text-label-sm font-bold">Beri Ulasan</a>@endif
</div></div>
</article>
@empty
<div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-12 text-center text-on-surface-variant">Belum ada booking.</div>
@endforelse
</div>
</main>
@endsection
