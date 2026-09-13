@extends('layouts.frontend')
@section('title','Cari Lapangan Olahraga - Booking Lapang')
@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-6 md:px-12 py-8">
<div class="flex flex-col lg:flex-row gap-8 items-start">
<!-- ========================================================== -->
<!-- A. LEFT FILTER SIDEBAR (STICKY ~290px)                     -->
<!-- ========================================================== -->
<aside class="w-full lg:w-[290px] shrink-0 bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 shadow-sm lg:sticky lg:top-24 space-y-6">
<form action="{{ route('lapangan.publik.index') }}" method="GET">
<div class="flex items-center justify-between pb-4 border-b border-outline-variant">
<div class="flex items-center gap-2"><span class="material-symbols-outlined text-primary-container text-[22px]">tune</span><h2 class="text-title-lg font-title-lg text-on-surface">Filter Pencarian</h2></div>
<a class="text-secondary hover:underline text-label-sm font-label-sm font-bold" href="{{ route('lapangan.publik.index') }}">Reset Semua</a>
</div>
<div class="space-y-2"><h3 class="text-title-md font-title-md text-on-surface">Cabang Olahraga</h3>
<select class="w-full rounded-xl border border-outline-variant bg-surface-container-low p-2.5 text-body-md text-on-surface" name="jenis">
<option value="">Semua</option>@foreach($daftarJenis as $jenis)<option value="{{ $jenis }}" @selected(($kriteria['jenis'] ?? '') === $jenis)>{{ $jenis }}</option>@endforeach
</select></div>
<div class="space-y-2"><h3 class="text-title-md font-title-md text-on-surface">Area & Lokasi</h3>
<select class="w-full rounded-xl border border-outline-variant bg-surface-container-low p-2.5 text-body-md text-on-surface" name="kota">
<option value="">Semua Kota</option>@foreach($daftarKota as $kota)<option value="{{ $kota }}" @selected(($kriteria['kota'] ?? '') === $kota)>{{ $kota }}</option>@endforeach
</select></div>
<div class="space-y-2"><h3 class="text-title-md font-title-md text-on-surface">Rentang Harga</h3>
<div class="grid grid-cols-2 gap-2"><input class="w-full rounded-xl border border-outline-variant bg-surface-container-low p-2.5 text-body-sm" name="harga_min" placeholder="Min" type="number" value="{{ $kriteria['harga_min'] ?? '' }}"/><input class="w-full rounded-xl border border-outline-variant bg-surface-container-low p-2.5 text-body-sm" name="harga_max" placeholder="Max" type="number" value="{{ $kriteria['harga_max'] ?? '' }}"/></div></div>
<div class="space-y-2"><h3 class="text-title-md font-title-md text-on-surface">Rating Minimal</h3>
<select class="w-full rounded-xl border border-outline-variant bg-surface-container-low p-2.5 text-body-md text-on-surface" name="rating_min"><option value="">Semua Rating</option>@foreach([4,3,2,1] as $r)<option value="{{ $r }}" @selected((string) ($kriteria['rating_min'] ?? '') === (string) $r)>{{ $r }}+ ★</option>@endforeach</select></div>
<input class="w-full rounded-xl border border-outline-variant bg-surface-container-low p-2.5 text-body-sm" name="kata_kunci" placeholder="Cari nama venue..." type="text" value="{{ $kriteria['kata_kunci'] ?? '' }}"/>
<button class="w-full bg-primary-container text-white rounded-xl py-2.5 font-bold" type="submit">Terapkan Filter</button>
</form></aside>
<!-- ========================================================== -->
<!-- B. RIGHT COLUMN: SEARCH RESULTS & ARENA CARDS             -->
<!-- ========================================================== -->
<section class="flex-1 w-full space-y-6">
<!-- Header Results & Active Removable Chips -->
<div class="space-y-3">
<div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-2">
<h1 class="text-headline-sm font-headline-sm text-on-surface">
              Menampilkan <span class="text-primary-container font-bold">48 Lapangan</span> di Jakarta Selatan & Sekitarnya
            </h1>
<span class="text-body-sm text-on-surface-variant">Update ketersediaan: 2 menit lalu</span>
</div>
<!-- Active Filter Chips Row -->
<div class="flex flex-wrap items-center gap-2">
<span class="text-label-sm font-label-sm text-on-surface-variant">Filter aktif:</span>
<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-surface-container border border-outline-variant text-body-sm font-medium text-on-surface">
              Badminton
              <button aria-label="Hapus filter Badminton" class="hover:text-secondary flex items-center">
<span class="material-symbols-outlined text-[16px]" data-icon="close">close</span>
</button>
</span>
<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-surface-container border border-outline-variant text-body-sm font-medium text-on-surface">
              Futsal
              <button aria-label="Hapus filter Futsal" class="hover:text-secondary flex items-center">
<span class="material-symbols-outlined text-[16px]" data-icon="close">close</span>
</button>
</span>
<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant border border-secondary-container/40 text-body-sm font-medium">
              Slot Sore & Malam
              <button aria-label="Hapus filter Slot Jam" class="hover:text-secondary flex items-center">
<span class="material-symbols-outlined text-[16px]" data-icon="close">close</span>
</button>
</span>
<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-surface-container border border-outline-variant text-body-sm font-medium text-on-surface">
              Area Cilandak & Sekitar
              <button aria-label="Hapus filter Lokasi" class="hover:text-secondary flex items-center">
<span class="material-symbols-outlined text-[16px]" data-icon="close">close</span>
</button>
</span>
<button class="text-secondary hover:underline text-label-sm font-label-sm font-bold ml-1">
              Bersihkan
            </button>
</div>
</div>
<!-- ========================================================== -->
<!-- VENUE CARDS GRID (2 COLUMNS RESPONSIVE)                   -->
<!-- ========================================================== -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">@forelse($lapangans as $lapangan)<a class="tactile-card group bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden block" href="{{ route('lapangan.show', $lapangan) }}">
<div class="relative aspect-[16/10] overflow-hidden bg-surface-container">
@php($foto = $lapangan->fotoUtama())
@if($foto)
<img alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" src="{{ asset('storage/'.$foto->path) }}"/>
@else
<div class="w-full h-full flex items-center justify-center bg-surface-container-high text-outline"><span class="material-symbols-outlined text-5xl">sports_soccer</span></div>
@endif
<div class="absolute top-3 left-3 flex items-center gap-1.5 bg-surface-container-lowest/95 backdrop-blur-xs px-2.5 py-1 rounded-lg shadow-sm">
<span class="material-symbols-outlined text-tertiary-fixed-dim text-[16px]" style="font-variation-settings:'FILL' 1;">star</span>
<span class="text-label-md font-bold text-on-surface">{{ number_format($lapangan->rataRataRating(),1) }}</span>
<span class="text-body-sm text-on-surface-variant">({{ $lapangan->ulasans()->count() }})</span>
</div>
</div>
<div class="p-5 space-y-3">
<div><span class="text-label-sm text-primary-container font-bold uppercase tracking-wider">{{ $lapangan->jenis }}</span>
<h3 class="text-title-lg font-title-lg text-on-surface mt-0.5 leading-tight">{{ $lapangan->nama_lapangan }}</h3></div>
<div class="flex items-center gap-1 text-body-sm text-on-surface-variant"><span class="material-symbols-outlined text-[16px] text-outline">location_on</span><span>{{ $lapangan->kota ?? 'Kota belum diisi' }}</span></div>
<div class="flex items-center justify-between pt-2 border-t border-[#E8E0D3]">
<span class="text-label-md font-label-md text-primary-container font-bold">Rp {{ number_format($lapangan->harga_per_jam,0,',','.') }}<span class="text-[11px] font-normal text-outline">/jam</span></span>
<span class="text-label-sm text-primary font-semibold">Lihat detail →</span>
</div>
</div>
</a>@empty<div class="md:col-span-2 text-center py-12 text-on-surface-variant">Belum ada lapangan yang tersedia.</div>@endforelse</div>
<!-- ========================================================== -->
<!-- PROMO MINI BANNER (TACTILE TERRACOTTA / MIDTRANS)          -->
<!-- ========================================================== -->
<div class="bg-[#1E5631] text-on-primary rounded-2xl p-6 relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6 shadow-md">
<div class="space-y-1.5 z-10 text-center sm:text-left">
<div class="inline-flex items-center gap-1.5 bg-[#E76F51] text-on-primary px-2.5 py-0.5 rounded-full text-label-sm font-bold">
<span class="material-symbols-outlined text-[14px]" data-icon="redeem">redeem</span>
              Promo Main Sore
            </div>
<h4 class="text-headline-sm font-headline-sm text-on-primary font-bold">
              Diskon Langsung Rp 25.000 dengan QRIS Midtrans
            </h4>
<p class="text-body-md text-surface-container-highest max-w-xl">
              Gunakan kode voucher <span class="font-bold underline decoration-secondary">MAINSORERAMAI</span> untuk seluruh pemesanan slot sore (15.00 - 18.00) di area Jakarta Selatan.
            </p>
</div>
<div class="shrink-0 z-10">
<button class="bg-surface-container-lowest text-primary hover:bg-surface-bright px-6 py-3 rounded-xl font-bold text-label-lg font-label-lg active:scale-95 transition-transform shadow-sm">
              Klaim Voucher
            </button>
</div>
<!-- Subtle field boundary pattern background overlay -->
<div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-10 pointer-events-none flex items-center justify-end pr-6">
<span class="material-symbols-outlined text-[180px]" data-icon="sports_soccer">sports_soccer</span>
</div>
</div>
<!-- ========================================================== -->
<!-- PAGINATION BAR                                             -->
<!-- ========================================================== -->
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-outline-variant">
<p class="text-body-sm text-on-surface-variant">
            Menampilkan <span class="font-bold text-on-surface">1 - 6</span> dari <span class="font-bold text-on-surface">48</span> venue olahraga
          </p>
<nav aria-label="Navigasi Halaman" class="flex items-center gap-1.5">
<button class="w-10 h-10 rounded-xl border border-outline-variant flex items-center justify-center text-on-surface-variant hover:bg-surface-container disabled:opacity-40" disabled="">
<span class="material-symbols-outlined text-[20px]" data-icon="chevron_left">chevron_left</span>
</button>
<button class="w-10 h-10 rounded-xl bg-primary-container text-on-primary font-bold text-label-md">
              1
            </button>
<button class="w-10 h-10 rounded-xl border border-outline-variant text-on-surface hover:bg-surface-container text-label-md transition-colors">
              2
            </button>
<button class="w-10 h-10 rounded-xl border border-outline-variant text-on-surface hover:bg-surface-container text-label-md transition-colors">
              3
            </button>
<span class="w-8 text-center text-on-surface-variant text-body-sm">...</span>
<button class="w-10 h-10 rounded-xl border border-outline-variant text-on-surface hover:bg-surface-container text-label-md transition-colors">
              8
            </button>
<button class="w-10 h-10 rounded-xl border border-outline-variant flex items-center justify-center text-on-surface hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined text-[20px]" data-icon="chevron_right">chevron_right</span>
</button>
</nav>
</div>
</section>
</div>
</main>
@endsection
