@extends('layouts.frontend')
@section('title','Booking Lapang - Sewa Lapangan Olahraga')
@section('content')


<section class="relative pt-8 pb-16 md:pt-14 md:pb-24 overflow-hidden">
<!-- Subtle Sports Field Ambient Accents -->
<div class="absolute inset-0 pointer-events-none opacity-40">
<div class="absolute top-10 left-1/2 -translate-x-1/2 w-[900px] h-[500px] bg-gradient-to-b from-primary-fixed-dim/20 via-transparent to-transparent blur-3xl rounded-full"></div>
</div>
<div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
<!-- Headings Cluster -->
<div class="max-w-3xl mx-auto text-center mb-10 md:mb-12">
<div class="inline-flex items-center gap-2 bg-surface-container-high border border-outline-variant px-3.5 py-1.5 rounded-full mb-5 shadow-sm">
<span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
<span class="font-label-sm text-label-sm text-primary tracking-wide">500+ ARENA OLAHRAGA TERVERIFIKASI</span>
</div>
<h1 class="font-headline-xl-mobile md:font-headline-xl text-headline-xl-mobile md:text-headline-xl text-on-background tracking-tight text-balance">
          Cari, Bandingkan &amp; Booking Lapangan Olahraga Tanpa Ribet
        </h1>
<p class="mt-4 font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto text-balance">
          Akses 500+ arena futsal, badminton, mini soccer, basket, hingga tenis di Jabodetabek &amp; Bandung. Jadwal real-time, anti-bentrok, bayar instan via Midtrans.
        </p>
</div>
<!-- Quick Sport Filter Chips -->
<div class="flex items-center justify-center gap-2.5 overflow-x-auto pb-4 max-w-4xl mx-auto no-scrollbar">
<button class="inline-flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded-xl font-label-md text-label-md shadow-grass-resting active:scale-95 transition-transform" type="button">
<span class="text-base">🏸</span>
<span>Badminton</span>
</button>
<button class="inline-flex items-center gap-2 bg-surface-container-lowest text-on-surface border border-outline-variant hover:border-primary px-4 py-2 rounded-xl font-label-md text-label-md hover:bg-surface-container transition-all" type="button">
<span class="text-base">⚽</span>
<span>Futsal</span>
</button>
<button class="inline-flex items-center gap-2 bg-surface-container-lowest text-on-surface border border-outline-variant hover:border-primary px-4 py-2 rounded-xl font-label-md text-label-md hover:bg-surface-container transition-all" type="button">
<span class="text-base">🥅</span>
<span>Mini Soccer</span>
</button>
<button class="inline-flex items-center gap-2 bg-surface-container-lowest text-on-surface border border-outline-variant hover:border-primary px-4 py-2 rounded-xl font-label-md text-label-md hover:bg-surface-container transition-all" type="button">
<span class="text-base">🏀</span>
<span>Basket</span>
</button>
<button class="inline-flex items-center gap-2 bg-surface-container-lowest text-on-surface border border-outline-variant hover:border-primary px-4 py-2 rounded-xl font-label-md text-label-md hover:bg-surface-container transition-all" type="button">
<span class="text-base">🎾</span>
<span>Tenis</span>
</button>
<button class="inline-flex items-center gap-2 bg-surface-container-lowest text-on-surface border border-outline-variant hover:border-primary px-4 py-2 rounded-xl font-label-md text-label-md hover:bg-surface-container transition-all" type="button">
<span class="text-base">🏓</span>
<span>Padel</span>
</button>
</div>
<!-- Floating Booking Matrix / Search Box -->
<div class="mt-6 bg-surface-container-lowest rounded-2xl p-4 md:p-6 border border-outline-variant shadow-grass-floating max-w-5xl mx-auto">
<form method="GET" action="{{ route('lapangan.publik.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
<!-- Dropdown 1: Cabang Olahraga -->
<div class="md:col-span-3">
<label class="block font-label-md text-label-md text-on-surface-variant mb-1.5">Cabang Olahraga</label>
<div class="relative">
<span class="absolute left-3 top-1/2 -translate-y-1/2 text-primary material-symbols-outlined" data-icon="sports_tennis">sports_tennis</span>
<select name="jenis" class="w-full h-12 pl-10 pr-8 bg-surface-container-low border border-outline-variant rounded-xl font-body-md text-body-md text-on-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container focus:outline-none appearance-none cursor-pointer">
<option selected="">Badminton</option>
<option>Futsal Interlock</option>
<option>Mini Soccer Turf</option>
<option>Basket FIBA</option>
<option>Tenis Lapangan</option>
<option>Padel Court</option>
</select>
<span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant material-symbols-outlined text-[18px]" data-icon="expand_more">expand_more</span>
</div>
</div>
<!-- Dropdown 2: Lokasi / Kota -->
<div class="md:col-span-3">
<label class="block font-label-md text-label-md text-on-surface-variant mb-1.5">Lokasi / Kota</label>
<div class="relative">
<span class="absolute left-3 top-1/2 -translate-y-1/2 text-primary material-symbols-outlined" data-icon="location_on">location_on</span>
<select name="kota" class="w-full h-12 pl-10 pr-8 bg-surface-container-low border border-outline-variant rounded-xl font-body-md text-body-md text-on-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container focus:outline-none appearance-none cursor-pointer">
<option selected="">Jakarta Selatan</option>
<option>Jakarta Barat</option>
<option>BSD, Tangerang Selatan</option>
<option>Kota Bandung</option>
<option>Bekasi Kota</option>
<option>Depok Margonda</option>
</select>
<span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant material-symbols-outlined text-[18px]" data-icon="expand_more">expand_more</span>
</div>
</div>
<!-- Dropdown 3: Tanggal Main & Jam -->
<div class="md:col-span-3">
<label class="block font-label-md text-label-md text-on-surface-variant mb-1.5">Waktu Main</label>
<div class="relative">
<span class="absolute left-3 top-1/2 -translate-y-1/2 text-primary material-symbols-outlined" data-icon="calendar_month">calendar_month</span>
<select class="w-full h-12 pl-10 pr-8 bg-surface-container-low border border-outline-variant rounded-xl font-body-md text-body-md text-on-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container focus:outline-none appearance-none cursor-pointer">
<option selected="">Hari Ini (Sore: 16.00 - 19.00)</option>
<option>Hari Ini (Malam: 19.00 - 23.00)</option>
<option>Besok Pagi (06.00 - 10.00)</option>
<option>Sabtu Weekend (Slot Bebas)</option>
<option>Minggu Weekend (Slot Bebas)</option>
</select>
<span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant material-symbols-outlined text-[18px]" data-icon="expand_more">expand_more</span>
</div>
</div>
<!-- Action Button: Cari Jadwal (Oranye Terakota #E76F51 tegas) -->
<div class="md:col-span-3 pt-2 md:pt-6">
<button class="w-full h-12 bg-secondary hover:bg-on-secondary-container text-on-secondary rounded-xl font-label-lg text-label-lg flex items-center justify-center gap-2 shadow-grass-interactive active:scale-[0.98] transition-all duration-150" type="submit">
<span class="material-symbols-outlined" data-icon="search">search</span>
<span>Cari Jadwal Lapangan</span>
</button>
</div>
</form>
<!-- Live Slot Indicator bar below search -->
<div class="mt-4 pt-3 border-t border-outline-variant/60 flex flex-wrap items-center justify-between gap-3 text-on-surface-variant font-body-sm text-body-sm">
<div class="flex items-center gap-2">
<span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
<span>Update real-time: <strong class="text-on-surface">312 slot kosong</strong> untuk malam ini</span>
</div>
<div class="flex items-center gap-4">
<span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-primary" data-icon="verified_user">verified_user</span> Instant Confirmation</span>
<span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-primary" data-icon="payments">payments</span> QRIS &amp; VA Midtrans</span>
</div>
</div>
</div>
</div>
</section>
<section class="py-14 md:py-20 bg-surface-container-low border-t border-outline-variant" id="cari-lapangan">
<div class="max-w-7xl mx-auto px-6 md:px-12">
<!-- Header Row -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
<div>
<span class="font-label-md text-label-md text-primary font-bold tracking-wider uppercase">Pilihan Komunitas Aktif</span>
<h2 class="mt-1 font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-background">
            Lapangan Populer &amp; Rekomendasi Terdekat
          </h2>
<p class="mt-1 font-body-md text-body-md text-on-surface-variant">
            Standar arena resmi dengan fasilitas bersih, pencahayaan pro, dan review terpercaya pemain.
          </p>
</div>
<div class="flex items-center gap-3">
<button aria-label="Lihat Semua Rekomendasi" class="inline-flex items-center gap-1.5 font-label-lg text-label-lg text-primary hover:text-on-primary-fixed-variant group transition-colors" type="button">
<span>Lihat 48 Arena di Sekitarmu</span>
<span class="material-symbols-outlined transition-transform group-hover:translate-x-1 text-[18px]" data-icon="arrow_forward">arrow_forward</span>
</button>
</div>
</div>
<!-- Venue Cards Grid (Asymmetric Bento Highlight + 4 Main Cards) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
@forelse($lapangans as $lapangan)
<a class="bg-surface-container-lowest rounded-2xl border border-outline-variant overflow-hidden shadow-grass-resting hover:shadow-grass-interactive hover:-translate-y-1 transition-all duration-200 flex flex-col" href="{{ route('lapangan.show',$lapangan) }}">
<div class="aspect-[4/3] bg-surface-container flex items-center justify-center overflow-hidden">
@php($foto=$lapangan->fotoUtama())
@if($foto)<img alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover" src="{{ asset('storage/'.$foto->path) }}"/>@else<span class="material-symbols-outlined text-5xl text-outline">sports_soccer</span>@endif
</div>
<div class="p-5 flex flex-col flex-1"><span class="text-label-sm text-primary-container font-bold uppercase">{{ $lapangan->jenis }}</span><h3 class="font-title-lg text-title-lg text-on-surface font-bold line-clamp-1 mt-1">{{ $lapangan->nama_lapangan }}</h3><p class="text-body-sm text-on-surface-variant mt-2">{{ $lapangan->kota ?? 'Lokasi belum diisi' }}</p><div class="mt-auto pt-4 flex items-center justify-between"><span class="font-bold text-primary">Rp {{ number_format($lapangan->harga_per_jam,0,',','.') }}/jam</span><span class="text-label-sm text-primary font-bold">{{ number_format($lapangan->rataRataRating(),1) }} ★</span></div></div>
</a>
@empty
<div class="lg:col-span-4 text-center py-10 text-on-surface-variant">Belum ada lapangan aktif.</div>
@endforelse
</div>
</div>
</section>
<section class="py-16 md:py-24 bg-surface relative">
<div class="max-w-7xl mx-auto px-6 md:px-12">
<div class="text-center max-w-2xl mx-auto mb-14">
<span class="font-label-md text-label-md text-primary font-bold tracking-wider uppercase">Standar Kenyamanan Pemain</span>
<h2 class="mt-2 font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-background">
          Kenapa Booking di Booking Lapang?
        </h2>
<p class="mt-2 font-body-md text-body-md text-on-surface-variant">
          Didesain khusus untuk membebaskan kapten tim dan pemain dari urusan rumit koordinasi lapangan manual.
        </p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<!-- Fitur 1 -->
<div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant shadow-grass-resting hover:shadow-grass-interactive transition-all">
<div class="w-14 h-14 rounded-2xl bg-surface-container-low border border-outline-variant flex items-center justify-center text-primary mb-6">
<span class="material-symbols-outlined text-3xl" data-icon="sync_saved_locally">sync_saved_locally</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface font-bold mb-2.5">
            Jadwal Real-Time Anti Bentrok
          </h3>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
            Kalender terintegrasi langsung dengan komputer kasir arena venue. Slot yang Anda kunci tidak akan terdobel atau diambil penyewa offline.
          </p>
<div class="mt-5 inline-flex items-center gap-1.5 font-label-md text-label-md text-primary font-semibold">
<span class="material-symbols-outlined text-[16px]" data-icon="check_circle">check_circle</span>
<span>Sinkronisasi otomatis &lt; 2 detik</span>
</div>
</div>
<!-- Fitur 2 -->
<div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant shadow-grass-resting hover:shadow-grass-interactive transition-all">
<div class="w-14 h-14 rounded-2xl bg-surface-container-low border border-outline-variant flex items-center justify-center text-primary mb-6">
<span class="material-symbols-outlined text-3xl" data-icon="security">security</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface font-bold mb-2.5">
            Bayar Instan &amp; Aman via Midtrans
          </h3>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
            Kupon bukti booking resmi diterbitkan otomatis setelah verifikasi pembayaran via QRIS, Virtual Account BCA/Mandiri/BRI, GoPay, atau ShopeePay.
          </p>
<div class="mt-5 inline-flex items-center gap-1.5 font-label-md text-label-md text-primary font-semibold">
<span class="material-symbols-outlined text-[16px]" data-icon="check_circle">check_circle</span>
<span>Kuitansi PDF langsung ke WhatsApp</span>
</div>
</div>
<!-- Fitur 3 -->
<div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant shadow-grass-resting hover:shadow-grass-interactive transition-all">
<div class="w-14 h-14 rounded-2xl bg-surface-container-low border border-outline-variant flex items-center justify-center text-primary mb-6">
<span class="material-symbols-outlined text-3xl" data-icon="groups">groups</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface font-bold mb-2.5">
            Garansi Refund &amp; Komunitas Sparring
          </h3>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
            Hujan deras pada lapangan outdoor? Ajukan reschedule instan. Tim kekurangan lawan tanding? Lempar slot ke forum sparring komunitas kami.
          </p>
<div class="mt-5 inline-flex items-center gap-1.5 font-label-md text-label-md text-primary font-semibold">
<span class="material-symbols-outlined text-[16px]" data-icon="check_circle">check_circle</span>
<span>Garansi proteksi cuaca buruk</span>
</div>
</div>
</div>
</div>
</section>
<section class="py-6 max-w-7xl mx-auto px-6 md:px-12" id="promo">
<div class="relative bg-gradient-to-r from-primary-container via-primary to-primary-container rounded-3xl p-8 md:p-12 text-on-primary overflow-hidden shadow-grass-interactive">
<!-- Subtle Visual Elements -->
<div class="absolute -right-16 -bottom-16 w-80 h-80 rounded-full bg-surface-tint/30 blur-2xl pointer-events-none"></div>
<div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
<div class="lg:col-span-8">
<div class="inline-flex items-center gap-2 bg-secondary text-on-secondary px-3.5 py-1 rounded-full font-label-sm text-label-sm mb-4 font-bold tracking-wide">
            ⚡ PROMO WEEKDAY SORE
          </div>
<h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-surface-bright tracking-tight">
            Promo Main Sore Spesial Weekday — Cashback hingga 20% + Poin Loyalitas
          </h2>
<p class="mt-3 font-body-lg text-body-lg text-on-primary-container max-w-2xl">
            Gunakan kode promo <code class="bg-on-primary/10 border border-on-primary/20 px-2 py-0.5 rounded font-mono text-surface-bright font-bold">SORESEHAT</code> untuk sewa jam 15.00 - 18.00 WIB setiap Senin sampai Kamis di semua venue mitra.
          </p>
</div>
<div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-3 justify-end items-start lg:items-end">
<button class="w-full sm:w-auto bg-surface-bright text-primary hover:bg-surface-container-low px-6 py-3.5 rounded-xl font-label-lg text-label-lg font-bold shadow-grass-resting active:scale-95 transition-all" type="button">
            Klaim Voucher Sekarang
          </button>
<span class="font-label-sm text-label-sm text-on-primary-container">Berlaku sampai 30 November 2025</span>
</div>
</div>
</div>
</section>
<section class="py-16 md:py-20 bg-surface-container" id="daftarkan-lapangan">
<div class="max-w-7xl mx-auto px-6 md:px-12">
<div class="bg-surface-container-lowest rounded-3xl border border-outline-variant p-8 md:p-14 shadow-grass-resting">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
<!-- Left Column: Copy & Value Proposition -->
<div class="lg:col-span-7">
<span class="font-label-md text-label-md text-secondary font-bold tracking-wider uppercase">Mitra Arena Olahraga</span>
<h2 class="mt-2 font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-background">
              Punya Lapangan Olahraga? Daftarkan &amp; Otomatiskan Jadwal Anda
            </h2>
<p class="mt-3 font-body-lg text-body-lg text-on-surface-variant">
              Tingkatkan okupansi lapangan kosong di jam kerja, hilangkan kerepotan catat manual di buku catatan, dan nikmati sistem kasir digital tanpa biaya langganan bulanan di awal.
            </p>
<!-- Mitra Key Stats -->
<div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
<div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant">
<span class="block font-headline-md text-headline-md text-primary font-bold">+45%</span>
<span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5 block">Peningkatan Okupansi</span>
</div>
<div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant">
<span class="block font-headline-md text-headline-md text-primary font-bold">H+1</span>
<span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5 block">Payout Otomatis Bank</span>
</div>
<div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant">
<span class="block font-headline-md text-headline-md text-primary font-bold">24/7</span>
<span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5 block">Dashboard Manajemen</span>
</div>
</div>
<!-- Action Button -->
<div class="mt-8 flex flex-wrap items-center gap-4">
<button class="bg-primary hover:bg-primary-container text-on-primary px-7 py-3.5 rounded-xl font-label-lg text-label-lg font-bold shadow-grass-interactive active:scale-95 transition-all" type="button">
                Gabung Jadi Mitra Arena
              </button>
<a class="inline-flex items-center gap-2 font-label-lg text-label-lg text-on-surface-variant hover:text-primary transition-colors py-2 px-3" href="#demo">
<span class="material-symbols-outlined" data-icon="smart_display">smart_display</span>
<span>Lihat Demo Dashboard Pengelola</span>
</a>
</div>
</div>
<!-- Right Column: Interactive Highlight Card / Graphic Preview -->
<div class="lg:col-span-5">
<div class="bg-surface-container-low rounded-2xl p-6 border border-outline-variant shadow-grass-resting">
<div class="flex items-center justify-between pb-4 border-b border-outline-variant">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-lg bg-primary text-on-primary flex items-center justify-center font-bold">
                    BL
                  </div>
<div>
<h4 class="font-label-lg text-label-lg text-on-surface">Arena Manager Pro</h4>
<span class="font-body-sm text-body-sm text-primary font-semibold">● 14 Slot Terisi Hari Ini</span>
</div>
</div>
<span class="bg-primary/10 text-primary font-label-sm text-label-sm px-2.5 py-1 rounded-md font-bold">Real-time</span>
</div>
<!-- Mini Slot Preview Grid -->
<div class="mt-4 space-y-2.5">
<div class="p-3 bg-surface-container-lowest rounded-xl border border-outline-variant flex items-center justify-between">
<div>
<span class="font-label-md text-label-md text-on-surface block">Court 1 - Badminton Vinyl</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">19.00 - 21.00 • Tim Garuda BSD</span>
</div>
<span class="bg-primary-fixed-dim text-on-primary-fixed-variant px-2 py-0.5 rounded text-[11px] font-bold">Lunas Midtrans</span>
</div>
<div class="p-3 bg-surface-container-lowest rounded-xl border border-outline-variant flex items-center justify-between">
<div>
<span class="font-label-md text-label-md text-on-surface block">Court 2 - Mini Soccer</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">20.00 - 22.00 • Sparring FC</span>
</div>
<span class="bg-primary-fixed-dim text-on-primary-fixed-variant px-2 py-0.5 rounded text-[11px] font-bold">Lunas QRIS</span>
</div>
<div class="p-3 bg-surface-container border border-dashed border-outline-variant rounded-xl flex items-center justify-between opacity-80">
<div>
<span class="font-label-md text-label-md text-on-surface block">Court 3 - Futsal Interlock</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">22.00 - 23.00 • Slot Tersedia</span>
</div>
<span class="text-secondary font-label-sm text-label-sm font-bold">Open Booking</span>
</div>
</div>
<div class="mt-4 pt-3 border-t border-outline-variant flex items-center justify-between font-body-sm text-body-sm text-on-surface-variant">
<span>Pencairan dana berikutnya:</span>
<strong class="text-on-surface font-semibold">Besok, 09:00 WIB</strong>
</div>
</div>
</div>
</div>
</div>
</div>
</section>


@endsection
