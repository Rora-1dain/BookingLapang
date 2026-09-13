@extends('layouts.frontend')
@section('title','Waitlist - Booking Lapang')
@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-6 md:px-12 py-8">
<div class="mb-8"><p class="text-label-sm text-primary-container font-bold uppercase tracking-wider">Akun Saya</p><h1 class="text-headline-lg font-headline-lg text-on-surface font-bold">Waitlist & Status Antrian</h1><p class="text-body-md text-on-surface-variant">Pantau slot yang sedang kamu tunggu.</p></div>
<div class="space-y-4">
@forelse($waitlists as $waitlist)
<article class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 shadow-sm">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
<div><div class="flex items-center gap-2 mb-2"><span class="px-2.5 py-1 rounded-full text-label-sm font-bold {{ $waitlist->status === 'ditawarkan' ? 'bg-primary-fixed text-primary' : 'bg-tertiary-fixed text-tertiary-container' }}">{{ ucfirst($waitlist->status) }}</span></div>
<h3 class="text-title-lg font-bold text-on-surface">{{ $waitlist->lapangan->nama_lapangan }}</h3>
<p class="text-body-sm text-on-surface-variant">{{ $waitlist->lapangan->jenis }} · {{ $waitlist->lapangan->kota ?? '-' }}</p>
<p class="text-body-sm text-outline mt-2"><span class="material-symbols-outlined text-sm align-middle">calendar_today</span> {{ $waitlist->tanggal_booking->translatedFormat('l, d F Y') }} · {{ $waitlist->jam_mulai }} - {{ $waitlist->jam_selesai }}</p>
</div>
<div class="flex flex-wrap gap-2">
<a href="{{ route('lapangan.show',$waitlist->lapangan) }}" class="px-4 py-2 bg-surface-container border border-outline-variant text-primary rounded-xl text-label-sm font-bold">Cek Detail Lapangan</a>
@if($waitlist->status === 'menunggu')
<form method="POST" action="{{ route('waitlist.cancel',$waitlist) }}">@csrf<button class="px-4 py-2 border border-secondary text-secondary rounded-xl text-label-sm font-bold">Batalkan Antrian</button></form>
@endif
</div>
</div>
</article>
@empty
<div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-12 text-center text-on-surface-variant">Belum ada antrian waitlist.</div>
@endforelse
</div></main>
@endsection
