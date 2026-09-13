@extends('layouts.frontend')
@section('title','{{ $lapangan->nama_lapangan }} - Booking Lapang')
@section('content')
<main class="w-full max-w-7xl mx-auto px-6 md:px-12 py-6">
<!-- BREADCRUMB -->
<nav class="flex items-center gap-2 text-body-sm font-body-sm text-outline mb-5">
<a class="hover:text-primary transition-colors flex items-center gap-1" href="#">
<span class="material-symbols-outlined text-base">home</span>
        Beranda
      </a>
<span class="material-symbols-outlined text-xs">chevron_right</span>
<a class="hover:text-primary transition-colors" href="#">{{ $lapangan->kota ?? 'Lokasi belum diisi' }}</a>
<span class="material-symbols-outlined text-xs">chevron_right</span>
<a class="hover:text-primary transition-colors" href="#">{{ $lapangan->jenis }}</a>
<span class="material-symbols-outlined text-xs">chevron_right</span>
<span class="text-on-surface font-semibold">Smash Hub Arena Cilandak</span>
</nav>
<!-- HERO PHOTO GALLERY: ASYMMETRIC BENTO GRID -->
<section class="mb-8">
<div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 h-[460px] rounded-2xl overflow-hidden relative">
<!-- Main Large Court Photo (Left 7 Cols) -->
<div class="md:col-span-8 relative group overflow-hidden bg-surface-container-high">
<img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" data-alt="A brightly lit professional indoor badminton court with vivid green Li-Ning tournament vinyl mat floor marked with sharp white boundary chalk lines. High industrial ceilings with anti-glare 600 lux LED light fixtures create an athletic, clean sports atmosphere. The perspective is a wide angle capturing the taut net, pristine vinyl grain, and polished dark green court surrounds in realistic natural lighting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAoa3AIfzkIZxKYSdCgRUADZlD20x6Mpiaiv4HdLvyink18QGuVxCTpOku8BF62x0FcQj_VlV17JpuvtlHawBPhAr9F6fyn4uW6EslpPIDx3plpRZdQas1LGC0zbW_f93oa6jp3eZgOranDLF_Zhrrs_fi-Nf_KMVnae6Qmnq25wnSzlPScMJ8Ol-T6M5SJjllmLb4eFprcltoe1qDqyjpIUAnMzIK8p-CD0sFQ2ZfL8UfSLxftBVo"/>
<div class="absolute top-4 left-4 bg-surface-container-lowest/90 backdrop-blur px-3 py-1.5 rounded-lg flex items-center gap-2 border border-outline-variant/60 shadow-sm">
<span class="material-symbols-outlined text-secondary-container" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-label-md font-label-md text-on-surface font-bold">{{ number_format($lapangan->rataRataRating(),1) }}</span>
<span class="text-body-sm font-body-sm text-outline">(214 Ulasan)</span>
</div>
<div class="absolute bottom-4 left-4 bg-inverse-surface/80 text-inverse-on-surface backdrop-blur px-3 py-1.5 rounded-lg flex items-center gap-2 text-label-md font-label-md">
<span class="material-symbols-outlined text-base text-primary-fixed">sports_tennis</span>
<span>Court 1 • Standar Turnamen BWF</span>
</div>
</div>
<!-- 4 Sub Thumbnails (Right 4 Cols - 2x2 Grid) -->
<div class="hidden md:grid md:col-span-4 grid-cols-2 grid-rows-2 gap-3.5">
<!-- Sub 1: Shower & Lockers -->
<div class="relative group overflow-hidden bg-surface-container-high rounded-lg">
<img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" data-alt="A clean, minimalist modern sports locker room and shower facility with warm wooden benches, sleek brass hooks, and stone-colored ceramic wall tiles. Warm natural lighting highlights the dry spotless floor, folded white towels, and contemporary interior design fitting for a premium athletic club." src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_4VlfwXkQ6qn_9qNjGpi3gGMQ9e1znVR7ZSfLw_gqsy22UghpBu8rbZEIhkgSBOYhgcrEvCC_reDOLvJJEuQ8ITzEfqQseJ1PnycrtSCQUnKkHyNMxInkOCB3-xFHfV0iFSWbIbY1ascVhXar_en8jDy-D_CYuGv3FdQFs3XS_8VRIp_kNObCY3SodBkRvjlu5BjYwAZsOlZfxgBNL-MzWhPWAA6w6KI1lxJyyeuQF6fDdR63peE"/>
<span class="absolute bottom-2 left-2 bg-inverse-surface/75 text-inverse-on-surface text-[11px] font-semibold px-2 py-0.5 rounded">Ruang Ganti</span>
</div>
<!-- Sub 2: Pro Shop & Stringing -->
<div class="relative group overflow-hidden bg-surface-container-high rounded-lg">
<img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" data-alt="A pro badminton shop inside a sports complex showcasing premium Yonex and Victor graphite rackets mounted on wood panel walls, specialized electronic stringing machines, and stacked tubes of shuttlecocks under warm accent spotlights." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCh0y4fqXhVeeP1atx764qE_8-CnZVyaIdMvd6RSLjLWpPLxR3h1M4QsqxchgO5URZGqF1VZiWqWXxSp6bgE8qjN5nHci2MeMdwy1XTx-pLNRjeNOP34lJHWYx9KV5cOU8bWQ5OlWb309zLJOScuJt6p8c4szRbWU3h_Fj46a1cAq2Bv2q1nSGmtMA-_Ox-q__C2IvaRPXQ-Olzwq1ziaZzqeZNJy7cbFIZb3pudmDVWR8ao9f9QVc"/>
<span class="absolute bottom-2 left-2 bg-inverse-surface/75 text-inverse-on-surface text-[11px] font-semibold px-2 py-0.5 rounded">Pro Shop</span>
</div>
<!-- Sub 3: Spectator Tribune & Cafe -->
<div class="relative group overflow-hidden bg-surface-container-high rounded-lg">
<img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" data-alt="An elevated wooden spectator tribune and mezzanine cafe overlooking indoor badminton courts, equipped with comfortable seating, glass railings, and natural timber benches for waiting players enjoying cold beverages." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZx2FDDVPja3i9nfI0el2IJ65pWv9eBRbL8ICW1hYbeCgxHe9SyLin2CXVt7QDo-w88HhmX37PrdlhPCFan8TIZl3Qf_yEJDjj6x4kUF_R_bVa6iScH19U3kyKSju4dmwV_ZwM94lW_KvewD-nmw492yNVCWR661wQBrl7JzqvHLEU3Yj32wIeYvm0YBs-gv5GZZLpcFWOFhZhoOqcL_eyfd6U9HFTg51_IBne0f04ev-hztX1jSA"/>
<span class="absolute bottom-2 left-2 bg-inverse-surface/75 text-inverse-on-surface text-[11px] font-semibold px-2 py-0.5 rounded">Tribun &amp; Cafe</span>
</div>
<!-- Sub 4: Parking & Lobby -->
<div class="relative group overflow-hidden bg-surface-container-high rounded-lg">
<img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" data-alt="Exterior ground level view of a modern sports arena facade at dusk with warm exterior architecture lighting, wide paved parking lots with painted vehicle spaces, and clean glass entryway." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBBDaGUQSPPHKC9dH1hUNuRn0I37rqa2S3IuJjNrJc2IVu7WYBG5aRVI1-BWSHAduHc55ivF10ym8_-fYMLa7mcHSDYD4cdYun7Aye1uKd7_PMw1i1ZharBqeaSp2dIDGFJptgSBQaQBCBs0v_iktdFtuC9OMbzgFrZXcHhafiarXt7IGBTzAq-yLZUiXKvzEXnQE3Hsxg3IeMa7cwLVc4EUduueXf3_JQKcWQtde7Cx727ytq1ZD8"/>
<span class="absolute bottom-2 left-2 bg-inverse-surface/75 text-inverse-on-surface text-[11px] font-semibold px-2 py-0.5 rounded">Parkir Luas</span>
</div>
</div>
<!-- Float CTA 'Lihat Semua Foto' -->
<button class="absolute bottom-4 right-4 bg-surface-container-lowest/95 hover:bg-surface-container-lowest text-on-surface text-label-md font-label-md px-4 py-2 rounded-xl shadow-md border border-outline-variant flex items-center gap-2 transition-all active:scale-95">
<span class="material-symbols-outlined text-base">photo_library</span>
          Lihat Semua Foto (18)
        </button>
</div>
</section>
<!-- VENUE TITLE & META SUMMARY BAR -->
<section class="mb-8 pb-6 border-b border-outline-variant flex flex-col md:flex-row md:items-start md:justify-between gap-6">
<div class="space-y-3 max-w-3xl">
<!-- Badges Cluster -->
<div class="flex flex-wrap items-center gap-2.5">
<span class="bg-primary-container text-on-primary text-label-sm font-label-sm px-2.5 py-1 rounded-md flex items-center gap-1.5 uppercase tracking-wider">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
            Official Arena Partner
          </span>
<span class="bg-tertiary-fixed text-on-tertiary-fixed text-label-sm font-label-sm px-2.5 py-1 rounded-md flex items-center gap-1">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
            4.9 / 5.0 (214 Ulasan)
          </span>
<span class="bg-surface-container text-on-surface-variant text-label-sm font-label-sm px-2.5 py-1 rounded-md flex items-center gap-1">
<span class="material-symbols-outlined text-sm">schedule</span>
            Buka 07.00 - 24.00 WIB
          </span>
<span class="bg-surface-container text-on-surface-variant text-label-sm font-label-sm px-2.5 py-1 rounded-md">
            4 Lapangan Li-Ning
          </span>
</div>
<!-- Venue Name -->
<h1 class="text-headline-xl font-headline-xl text-on-surface tracking-tight">
          {{ $lapangan->nama_lapangan }}
        </h1>
<!-- Address & Geo -->
<div class="flex items-center flex-wrap gap-2 text-body-md font-body-md text-on-surface-variant">
<span class="material-symbols-outlined text-primary text-lg">pin_drop</span>
<span>Jl. Cilandak Barat No. 88, RT.02/RW.05, Jakarta Selatan</span>
<span class="text-outline">•</span>
<span class="font-semibold text-primary">2.4 km dari lokasi Anda</span>
<span class="text-outline">•</span>
<a class="text-secondary hover:underline font-semibold flex items-center gap-0.5" href="#peta">
            Lihat di Peta
            <span class="material-symbols-outlined text-sm">open_in_new</span>
</a>
</div>
</div>
<!-- Quick Action Share & Wishlist -->
<div class="flex items-center gap-3 self-start md:self-auto">
<button class="h-11 px-4 rounded-xl border border-outline-variant bg-surface-container-lowest text-on-surface text-label-md font-label-md hover:bg-surface-container transition-colors flex items-center gap-2">
<span class="material-symbols-outlined text-base">share</span>
          Bagikan
        </button>
<button class="h-11 px-4 rounded-xl border border-outline-variant bg-surface-container-lowest text-on-surface text-label-md font-label-md hover:bg-surface-container transition-colors flex items-center gap-2">
<span class="material-symbols-outlined text-base text-secondary">favorite_border</span>
          Simpan
        </button>
</div>
</section>
<!-- TWO-COLUMN MAIN CONTENT (65% DETAIL / 35% STICKY RESERVATION) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
<!-- LEFT COLUMN: DETAIL CANVAS (8 OF 12 COLS ~ 67%) -->
<div class="lg:col-span-8 space-y-10">
<!-- IN-PAGE NAVIGATION TABS -->
<div class="sticky top-20 bg-surface/95 backdrop-blur z-30 border-b border-outline-variant pt-2">
<nav class="flex space-x-8 text-title-md font-title-md overflow-x-auto">
<a class="border-b-2 border-primary-container text-primary font-bold pb-3 whitespace-nowrap flex items-center gap-2" href="{{ route('booking.create', ['lapangan_id' => $lapangan->id]) }}">
<span class="material-symbols-outlined text-lg">calendar_month</span>
              Pilih Jadwal &amp; Slot
            </a>
<a class="text-on-surface-variant hover:text-on-surface pb-3 whitespace-nowrap transition-colors flex items-center gap-2" href="#fasilitas">
<span class="material-symbols-outlined text-lg">sports_tennis</span>
              Fasilitas Arena
            </a>
<a class="text-on-surface-variant hover:text-on-surface pb-3 whitespace-nowrap transition-colors flex items-center gap-2" href="#peraturan">
<span class="material-symbols-outlined text-lg">gavel</span>
              Peraturan &amp; Kebijakan
            </a>
<a class="text-on-surface-variant hover:text-on-surface pb-3 whitespace-nowrap transition-colors flex items-center gap-2" href="#ulasan">
<span class="material-symbols-outlined text-lg">reviews</span>
              Ulasan Pengguna ({{ $lapangan->ulasans()->count() }})
            </a>
</nav>
</div>
<!-- SECTION 1: INTERACTIVE SCHEDULE & SLOT SELECTOR -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 md:p-8 grass-shadow space-y-6" id="jadwal">
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
<div>
<h2 class="text-headline-sm font-headline-sm text-on-surface">Pilih Lapangan &amp; Waktu Main</h2>
<p class="text-body-md font-body-md text-on-surface-variant">Pilih nomor court, tentukan tanggal, dan klik slot jam yang masih tersedia.</p>
</div>
<!-- Legend Indicators -->
<div class="flex items-center gap-4 text-body-sm font-body-sm">
<div class="flex items-center gap-1.5">
<span class="w-3.5 h-3.5 rounded border border-outline-variant bg-surface-container-lowest"></span>
<span>Tersedia</span>
</div>
<div class="flex items-center gap-1.5">
<span class="w-3.5 h-3.5 rounded bg-primary-container"></span>
<span class="font-semibold text-primary-container">Terpilih</span>
</div>
<div class="flex items-center gap-1.5">
<span class="w-3.5 h-3.5 rounded slot-pattern-booked border border-outline-variant"></span>
<span class="text-outline">Dipesan</span>
</div>
</div>
</div>
<!-- Step 1: Court Filter Chips -->
<div>
<span class="text-label-md font-label-md text-on-surface-variant block mb-2.5">1. Pilih Nomor Lapangan:</span>
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
<button class="flex flex-col text-left p-3.5 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary transition-all">
<span class="text-label-lg font-label-lg text-on-surface font-bold">Court 1</span>
<span class="text-body-sm font-body-sm text-on-surface-variant">Karpet Li-Ning</span>
<span class="text-label-sm font-label-sm text-primary mt-1">Mulai Rp 80k</span>
</button>
<button class="flex flex-col text-left p-3.5 rounded-xl border-2 border-primary-container bg-primary-container/5 text-primary transition-all relative">
<span class="absolute top-2 right-2 material-symbols-outlined text-sm text-primary-container" style="font-variation-settings: 'FILL' 1;">check_circle</span>
<span class="text-label-lg font-label-lg font-bold">Court 2</span>
<span class="text-body-sm font-body-sm text-primary">Karpet Li-Ning</span>
<span class="text-label-sm font-label-sm text-primary font-bold mt-1">Mulai Rp 95k</span>
</button>
<button class="flex flex-col text-left p-3.5 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary transition-all">
<span class="text-label-lg font-label-lg text-on-surface font-bold">Court 3</span>
<span class="text-body-sm font-body-sm text-on-surface-variant">Karpet Li-Ning</span>
<span class="text-label-sm font-label-sm text-primary mt-1">Mulai Rp 80k</span>
</button>
<button class="flex flex-col text-left p-3.5 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary transition-all">
<div class="flex items-center justify-between">
<span class="text-label-lg font-label-lg text-on-surface font-bold">Court VIP</span>
<span class="bg-secondary-fixed text-on-secondary-fixed text-[10px] font-bold px-1.5 py-0.5 rounded">AC</span>
</div>
<span class="text-body-sm font-body-sm text-on-surface-variant">Full AC + Lounge</span>
<span class="text-label-sm font-label-sm text-primary mt-1">Mulai Rp 140k</span>
</button>
</div>
</div>
<!-- Step 2: Horizontal 7-Day Date Picker Strip -->
<div>
<div class="flex items-center justify-between mb-2.5">
<span class="text-label-md font-label-md text-on-surface-variant">2. Pilih Tanggal (Oktober 2025):</span>
<button class="text-primary text-label-md font-label-md font-semibold hover:underline flex items-center gap-1">
<span class="material-symbols-outlined text-sm">calendar_month</span>
                Buka Kalender Penuh
              </button>
</div>
<div class="grid grid-cols-7 gap-2.5 overflow-x-auto pb-1">
<!-- Day 1 (Selected) -->
<button class="flex flex-col items-center justify-center p-3 rounded-xl border-2 border-primary-container bg-primary-container text-on-primary shadow-sm text-center">
<span class="text-label-sm font-label-sm uppercase opacity-90">Hari Ini</span>
<span class="text-headline-sm font-headline-sm font-bold">15</span>
<span class="text-body-sm font-body-sm">Rabu</span>
<div class="w-1.5 h-1.5 rounded-full bg-tertiary-fixed mt-1"></div>
</button>
<!-- Day 2 -->
<button class="flex flex-col items-center justify-center p-3 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary text-on-surface text-center transition-all">
<span class="text-label-sm font-label-sm uppercase text-outline">Kam</span>
<span class="text-headline-sm font-headline-sm font-bold text-on-surface">16</span>
<span class="text-body-sm font-body-sm text-on-surface-variant">Okt</span>
<div class="w-1.5 h-1.5 rounded-full bg-primary-container mt-1"></div>
</button>
<!-- Day 3 -->
<button class="flex flex-col items-center justify-center p-3 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary text-on-surface text-center transition-all">
<span class="text-label-sm font-label-sm uppercase text-outline">Jum</span>
<span class="text-headline-sm font-headline-sm font-bold text-on-surface">17</span>
<span class="text-body-sm font-body-sm text-on-surface-variant">Okt</span>
<div class="w-1.5 h-1.5 rounded-full bg-primary-container mt-1"></div>
</button>
<!-- Day 4 (Weekend) -->
<button class="flex flex-col items-center justify-center p-3 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary text-on-surface text-center transition-all">
<span class="text-label-sm font-label-sm uppercase text-secondary font-bold">Sab</span>
<span class="text-headline-sm font-headline-sm font-bold text-secondary">18</span>
<span class="text-body-sm font-body-sm text-on-surface-variant">Okt</span>
<div class="w-1.5 h-1.5 rounded-full bg-secondary mt-1"></div>
</button>
<!-- Day 5 (Weekend) -->
<button class="flex flex-col items-center justify-center p-3 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary text-on-surface text-center transition-all">
<span class="text-label-sm font-label-sm uppercase text-secondary font-bold">Min</span>
<span class="text-headline-sm font-headline-sm font-bold text-secondary">19</span>
<span class="text-body-sm font-body-sm text-on-surface-variant">Okt</span>
<div class="w-1.5 h-1.5 rounded-full bg-secondary mt-1"></div>
</button>
<!-- Day 6 -->
<button class="flex flex-col items-center justify-center p-3 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary text-on-surface text-center transition-all">
<span class="text-label-sm font-label-sm uppercase text-outline">Sen</span>
<span class="text-headline-sm font-headline-sm font-bold text-on-surface">20</span>
<span class="text-body-sm font-body-sm text-on-surface-variant">Okt</span>
<div class="w-1.5 h-1.5 rounded-full bg-primary-container mt-1"></div>
</button>
<!-- Day 7 -->
<button class="flex flex-col items-center justify-center p-3 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary text-on-surface text-center transition-all">
<span class="text-label-sm font-label-sm uppercase text-outline">Sel</span>
<span class="text-headline-sm font-headline-sm font-bold text-on-surface">21</span>
<span class="text-body-sm font-body-sm text-on-surface-variant">Okt</span>
<div class="w-1.5 h-1.5 rounded-full bg-primary-container mt-1"></div>
</button>
</div>
</div>
<!-- Step 3: Slot Matrix Grid (08:00 - 23:00) -->
<div>
<div class="flex items-center justify-between mb-3">
<span class="text-label-md font-label-md text-on-surface-variant">3. Pilih Jam Main (Slot 1 Jam):</span>
<span class="text-body-sm font-body-sm text-outline">Harga disesuaikan jam prime time malam</span>
</div>
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
<!-- 08.00 - 09.00 (Available) -->
<button class="p-3 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary hover:bg-surface-container-low transition-all text-left group">
<div class="flex justify-between items-center">
<span class="text-title-md font-title-md font-bold text-on-surface group-hover:text-primary">08.00 - 09.00</span>
<span class="text-outline text-xs">Pagi</span>
</div>
<div class="text-body-sm font-body-sm font-semibold text-primary mt-1">Rp 80.000</div>
</button>
<!-- 09.00 - 10.00 (Booked) -->
<div class="p-3 rounded-xl border border-outline-variant/60 slot-pattern-booked opacity-70 cursor-not-allowed text-left select-none relative">
<div class="flex justify-between items-center">
<span class="text-title-md font-title-md font-bold text-outline line-through">09.00 - 10.00</span>
<span class="material-symbols-outlined text-sm text-outline">lock</span>
</div>
<div class="text-label-sm font-label-sm text-outline mt-1 font-semibold">Sudah Dipesan</div>
</div>
<!-- 10.00 - 11.00 (Available) -->
<button class="p-3 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary hover:bg-surface-container-low transition-all text-left group">
<div class="flex justify-between items-center">
<span class="text-title-md font-title-md font-bold text-on-surface group-hover:text-primary">10.00 - 11.00</span>
<span class="text-outline text-xs">Pagi</span>
</div>
<div class="text-body-sm font-body-sm font-semibold text-primary mt-1">Rp 80.000</div>
</button>
<!-- 11.00 - 12.00 (Booked) -->
<div class="p-3 rounded-xl border border-outline-variant/60 slot-pattern-booked opacity-70 cursor-not-allowed text-left select-none">
<div class="flex justify-between items-center">
<span class="text-title-md font-title-md font-bold text-outline line-through">11.00 - 12.00</span>
<span class="material-symbols-outlined text-sm text-outline">lock</span>
</div>
<div class="text-label-sm font-label-sm text-outline mt-1 font-semibold">Sudah Dipesan</div>
</div>
<!-- 14.00 - 15.00 (Promo Diskon 15%) -->
<button class="p-3 rounded-xl border border-secondary bg-surface-container-lowest hover:bg-secondary-fixed/20 transition-all text-left relative overflow-hidden group">
<span class="absolute -top-1 -right-1 bg-secondary text-on-secondary text-[10px] font-bold px-2 py-0.5 rounded-bl-lg">Hemat 15%</span>
<div class="flex justify-between items-center">
<span class="text-title-md font-title-md font-bold text-on-surface">14.00 - 15.00</span>
</div>
<div class="flex items-center gap-1.5 mt-1">
<span class="text-body-sm font-body-sm font-bold text-secondary">Rp 68.000</span>
<span class="text-[11px] text-outline line-through">Rp 80.000</span>
</div>
</button>
<!-- 15.00 - 16.00 (Available) -->
<button class="p-3 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary hover:bg-surface-container-low transition-all text-left group">
<div class="flex justify-between items-center">
<span class="text-title-md font-title-md font-bold text-on-surface group-hover:text-primary">15.00 - 16.00</span>
<span class="text-outline text-xs">Sore</span>
</div>
<div class="text-body-sm font-body-sm font-semibold text-primary mt-1">Rp 80.000</div>
</button>
<!-- 16.00 - 17.00 (Booked) -->
<div class="p-3 rounded-xl border border-outline-variant/60 slot-pattern-booked opacity-70 cursor-not-allowed text-left select-none">
<div class="flex justify-between items-center">
<span class="text-title-md font-title-md font-bold text-outline line-through">16.00 - 17.00</span>
<span class="material-symbols-outlined text-sm text-outline">lock</span>
</div>
<div class="text-label-sm font-label-sm text-outline mt-1 font-semibold">Sudah Dipesan</div>
</div>
<!-- 17.00 - 18.00 (Booked) -->
<div class="p-3 rounded-xl border border-outline-variant/60 slot-pattern-booked opacity-70 cursor-not-allowed text-left select-none">
<div class="flex justify-between items-center">
<span class="text-title-md font-title-md font-bold text-outline line-through">17.00 - 18.00</span>
<span class="material-symbols-outlined text-sm text-outline">lock</span>
</div>
<div class="text-label-sm font-label-sm text-outline mt-1 font-semibold">Sudah Dipesan</div>
</div>
<!-- 18.00 - 19.00 (Booked) -->
<div class="p-3 rounded-xl border border-outline-variant/60 slot-pattern-booked opacity-70 cursor-not-allowed text-left select-none">
<div class="flex justify-between items-center">
<span class="text-title-md font-title-md font-bold text-outline line-through">18.00 - 19.00</span>
<span class="material-symbols-outlined text-sm text-outline">lock</span>
</div>
<div class="text-label-sm font-label-sm text-outline mt-1 font-semibold">Sudah Dipesan</div>
</div>
<!-- 19.00 - 20.00 (SELECTED 1) -->
<button class="p-3 rounded-xl border-2 border-primary-container bg-primary-container text-on-primary text-left shadow-sm relative transition-all">
<div class="flex justify-between items-center">
<span class="text-title-md font-title-md font-bold">19.00 - 20.00</span>
<span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<div class="flex items-center justify-between mt-1">
<span class="text-body-sm font-body-sm font-bold text-primary-fixed">Rp {{ number_format($lapangan->harga_per_jam,0,',','.') }}</span>
<span class="text-[10px] bg-white/20 px-1.5 py-0.5 rounded font-semibold uppercase">Slot Terpilih</span>
</div>
</button>
<!-- 20.00 - 21.00 (SELECTED 2) -->
<button class="p-3 rounded-xl border-2 border-primary-container bg-primary-container text-on-primary text-left shadow-sm relative transition-all">
<div class="flex justify-between items-center">
<span class="text-title-md font-title-md font-bold">20.00 - 21.00</span>
<span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<div class="flex items-center justify-between mt-1">
<span class="text-body-sm font-body-sm font-bold text-primary-fixed">Rp {{ number_format($lapangan->harga_per_jam,0,',','.') }}</span>
<span class="text-[10px] bg-white/20 px-1.5 py-0.5 rounded font-semibold uppercase">Slot Terpilih</span>
</div>
</button>
<!-- 21.00 - 22.00 (Available - Prime) -->
<button class="p-3 rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary hover:bg-surface-container-low transition-all text-left relative group">
<span class="absolute top-1.5 right-2 text-[10px] bg-tertiary-fixed text-on-tertiary-fixed px-1.5 py-0.2 rounded font-bold">Prime</span>
<div class="flex justify-between items-center">
<span class="text-title-md font-title-md font-bold text-on-surface group-hover:text-primary">21.00 - 22.00</span>
</div>
<div class="text-body-sm font-body-sm font-semibold text-primary mt-1">Rp {{ number_format($lapangan->harga_per_jam,0,',','.') }}</div>
</button>
</div>
</div>
</section>
<!-- SECTION 2: FASILITAS LENGKAP VENUE -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 md:p-8 grass-shadow space-y-6" id="fasilitas">
<div>
<h2 class="text-headline-sm font-headline-sm text-on-surface">Fasilitas &amp; Standar Lapangan</h2>
<p class="text-body-md font-body-md text-on-surface-variant">Didesain khusus untuk performa permainan maksimal dan kenyamanan pemain pro maupun komunitas.</p>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
<!-- Fac 1 -->
<div class="p-4 rounded-xl border border-outline-variant bg-surface-container-low flex items-start gap-3.5">
<div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-xl">layers</span>
</div>
<div>
<h3 class="text-title-md font-title-md text-on-surface font-bold">Karpet Li-Ning BWF</h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">Vinyl taraflex 5mm dengan shock-absorption anti cidera lutut.</p>
</div>
</div>
<!-- Fac 2 -->
<div class="p-4 rounded-xl border border-outline-variant bg-surface-container-low flex items-start gap-3.5">
<div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-xl">light_mode</span>
</div>
<div>
<h3 class="text-title-md font-title-md text-on-surface font-bold">Lampu LED 600 Lux</h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">Overhead anti-silau terarah, kok shuttlecock jelas terlihat tinggi.</p>
</div>
</div>
<!-- Fac 3 -->
<div class="p-4 rounded-xl border border-outline-variant bg-surface-container-low flex items-start gap-3.5">
<div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-xl">shower</span>
</div>
<div>
<h3 class="text-title-md font-title-md text-on-surface font-bold">Shower Panas &amp; Loker</h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">Kamar mandi bersih terpisah pria/wanita dengan pemanas air.</p>
</div>
</div>
<!-- Fac 4 -->
<div class="p-4 rounded-xl border border-outline-variant bg-surface-container-low flex items-start gap-3.5">
<div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-xl">local_parking</span>
</div>
<div>
<h3 class="text-title-md font-title-md text-on-surface font-bold">Parkir 40+ Mobil</h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">Kapasitas parkir kendaraan roda 4 &amp; 2 dengan petugas keamanan.</p>
</div>
</div>
<!-- Fac 5 -->
<div class="p-4 rounded-xl border border-outline-variant bg-surface-container-low flex items-start gap-3.5">
<div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-xl">storefront</span>
</div>
<div>
<h3 class="text-title-md font-title-md text-on-surface font-bold">Kantin &amp; Pro Shop</h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">Sedia minuman isotonik dingin, stringing raket, dan grip Yonex.</p>
</div>
</div>
<!-- Fac 6 -->
<div class="p-4 rounded-xl border border-outline-variant bg-surface-container-low flex items-start gap-3.5">
<div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-xl">mosque</span>
</div>
<div>
<h3 class="text-title-md font-title-md text-on-surface font-bold">Musholla AC</h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">Area wudhu bersih, sarung &amp; mukena wangi tersedia lengkap.</p>
</div>
</div>
</div>
</section>
<!-- SECTION 3: DESKRIPSI & KEBIJAKAN ARENA -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 md:p-8 grass-shadow space-y-6" id="peraturan">
<div>
<h2 class="text-headline-sm font-headline-sm text-on-surface">Deskripsi &amp; Tata Tertib Arena</h2>
<p class="text-body-md font-body-md text-on-surface-variant">Harap membaca ketentuan berikut demi menjaga keselamatan dan ketertiban bersama.</p>
</div>
<div class="space-y-4 text-body-md font-body-md text-on-surface leading-relaxed">
<p>
<strong>Smash Hub Arena Cilandak</strong> merupakan pusat kebugaran badminton modern dengan 4 line lapangan berstandar internasional. Menggunakan atap setinggi 9 meter dengan 6 unit exhaust fan bertenaga tinggi untuk memastikan sirkulasi udara tetap sejuk dan tidak pengap di jam-jam padat malam hari.
            </p>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
<div class="border border-outline-variant rounded-xl p-4 bg-surface-container">
<h3 class="text-title-md font-title-md text-primary font-bold flex items-center gap-2 mb-2">
<span class="material-symbols-outlined text-xl">sports_gymnastics</span>
                  Kebijakan Sepatu Non-Marking
                </h3>
<p class="text-body-sm font-body-sm text-on-surface-variant">
                  Wajib menggunakan sepatu khusus badminton sol karet mentah (non-marking shoe). Dilarang keras memakai sepatu lari outdoor, sandal, atau tanpa alas kaki di atas karpet lapangan.
                </p>
</div>
<div class="border border-outline-variant rounded-xl p-4 bg-surface-container">
<h3 class="text-title-md font-title-md text-primary font-bold flex items-center gap-2 mb-2">
<span class="material-symbols-outlined text-xl">update</span>
                  Reschedule &amp; Pembatalan
                </h3>
<p class="text-body-sm font-body-sm text-on-surface-variant">
                  Jadwal dapat di-reschedule maksimal <strong>6 jam sebelum sesi main dimulai</strong> melalui menu Pesanan Saya di aplikasi. Tiket yang sudah dipesan tidak dapat di-refund tunai.
                </p>
</div>
</div>
</div>
</section>
<!-- SECTION 4: ULASAN PEMAIN REAL -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 md:p-8 grass-shadow space-y-6" id="ulasan">
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
<div>
<h2 class="text-headline-sm font-headline-sm text-on-surface">Ulasan Pemain Asli</h2>
<p class="text-body-md font-body-md text-on-surface-variant">Ditinjau oleh penyewa yang telah menyelesaikan sesi bermain di Smash Hub Arena.</p>
</div>
<button class="px-4 py-2 border border-primary text-primary hover:bg-primary-container/10 rounded-xl text-label-md font-label-md font-semibold transition-colors self-start sm:self-auto">
              Tulis Ulasan
            </button>
</div>
<!-- Rating Breakdown Header -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-6 rounded-xl bg-surface-container-low border border-outline-variant items-center">
<div class="md:col-span-4 text-center md:border-r md:border-outline-variant pr-0 md:pr-4">
<span class="text-headline-xl font-headline-xl text-on-surface font-extrabold">4.9</span>
<div class="flex items-center justify-center gap-1 text-tertiary-fixed-dim my-1.5">
<span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-body-sm font-body-sm text-outline">Berdasarkan 214 ulasan terverifikasi</p>
</div>
<div class="md:col-span-8 space-y-2">
<div class="flex items-center gap-3 text-body-sm font-body-sm">
<span class="w-14 text-right font-medium">5 Bintang</span>
<div class="w-full bg-surface-container-high h-2.5 rounded-full overflow-hidden">
<div class="bg-primary-container h-full rounded-full w-[88%]"></div>
</div>
<span class="w-8 text-right font-semibold text-outline">88%</span>
</div>
<div class="flex items-center gap-3 text-body-sm font-body-sm">
<span class="w-14 text-right font-medium">4 Bintang</span>
<div class="w-full bg-surface-container-high h-2.5 rounded-full overflow-hidden">
<div class="bg-primary-container h-full rounded-full w-[9%]"></div>
</div>
<span class="w-8 text-right font-semibold text-outline">9%</span>
</div>
<div class="flex items-center gap-3 text-body-sm font-body-sm">
<span class="w-14 text-right font-medium">3 Bintang</span>
<div class="w-full bg-surface-container-high h-2.5 rounded-full overflow-hidden">
<div class="bg-primary-container h-full rounded-full w-[2%]"></div>
</div>
<span class="w-8 text-right font-semibold text-outline">2%</span>
</div>
<div class="flex items-center gap-3 text-body-sm font-body-sm">
<span class="w-14 text-right font-medium">2 Bintang</span>
<div class="w-full bg-surface-container-high h-2.5 rounded-full overflow-hidden">
<div class="bg-primary-container h-full rounded-full w-[1%]"></div>
</div>
<span class="w-8 text-right font-semibold text-outline">1%</span>
</div>
</div>
</div>
<!-- Review Card List -->
<div class="space-y-4">
<!-- Review 1 -->
<div class="p-5 rounded-xl border border-outline-variant bg-surface-container-lowest space-y-3">
<div class="flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold">
                    RA
                  </div>
<div>
<h4 class="text-title-md font-title-md font-bold text-on-surface">Rian Ardiansyah</h4>
<span class="text-body-sm font-body-sm text-outline">Bermain di Court 2 • Kemarin</span>
</div>
</div>
<div class="flex items-center gap-0.5 text-tertiary-fixed-dim">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
</div>
<p class="text-body-md font-body-md text-on-surface leading-normal">
                "Karpet Li-Ning-nya luar biasa kesat dan empuk, lutut ga gampang pegal walau main 2 jam nonstop! Lampunya beneran ga silau waktu smash atau ambil lob belakang. Jam lampu nyala dan mati sangat on-time sesuai tiket aplikasi. Rekomended buat sparring rutin mingguan."
              </p>
<div class="flex items-center gap-2 text-label-sm font-label-sm text-primary">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
                Penyewa Terverifikasi Midtrans
              </div>
</div>
<!-- Review 2 -->
<div class="p-5 rounded-xl border border-outline-variant bg-surface-container-lowest space-y-3">
<div class="flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold">
                    DS
                  </div>
<div>
<h4 class="text-title-md font-title-md font-bold text-on-surface">Dimas Setiawan</h4>
<span class="text-body-sm font-body-sm text-outline">Bermain di Court VIP • 3 hari lalu</span>
</div>
</div>
<div class="flex items-center gap-0.5 text-tertiary-fixed-dim">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
</div>
<p class="text-body-md font-body-md text-on-surface leading-normal">
                "Parkir mobil luas ga ribet vallet, shower air panasnya kenceng dan wangi. Kantin di lantai 2 jualan pisang rebus sama Pocari dingin. Worth every penny booking di sini!"
              </p>
<div class="flex items-center gap-2 text-label-sm font-label-sm text-primary">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
                Penyewa Terverifikasi Midtrans
              </div>
</div>
</div>
</section>
</div>
<!-- RIGHT COLUMN: STICKY BOOKING SUMMARY MODULE (4 OF 12 COLS ~ 33%) -->
<aside class="lg:col-span-4 sticky top-24">
<div class="bg-surface-container-lowest border-2 border-primary-container rounded-2xl p-6 grass-shadow-lg space-y-6">
<!-- Header Sticky Card -->
<div class="pb-4 border-b border-outline-variant flex items-center justify-between">
<div>
<span class="text-label-sm font-label-sm text-outline uppercase font-bold tracking-wider">Ringkasan Reservasi</span>
<h3 class="text-title-lg font-title-lg text-on-surface">Smash Hub Cilandak</h3>
</div>
<span class="bg-primary-fixed text-on-primary-fixed-variant text-label-sm font-label-sm px-2.5 py-1 rounded-full font-bold">
              2 Jam Sesi
            </span>
</div>
<!-- Booking Specs Details -->
<div class="space-y-3 text-body-md font-body-md">
<!-- Date item -->
<div class="flex items-center justify-between">
<div class="flex items-center gap-2 text-on-surface-variant">
<span class="material-symbols-outlined text-base text-primary">calendar_today</span>
<span>Tanggal</span>
</div>
<span class="font-bold text-on-surface">Rabu, 15 Okt 2025</span>
</div>
<!-- Court item -->
<div class="flex items-center justify-between">
<div class="flex items-center gap-2 text-on-surface-variant">
<span class="material-symbols-outlined text-base text-primary">sports_tennis</span>
<span>Lapangan</span>
</div>
<span class="font-bold text-on-surface">Court 2 (Li-Ning)</span>
</div>
<!-- Time slot item -->
<div class="flex items-center justify-between">
<div class="flex items-center gap-2 text-on-surface-variant">
<span class="material-symbols-outlined text-base text-primary">alarm</span>
<span>Jam Main</span>
</div>
<span class="font-bold text-secondary">19.00 - 21.00 WIB</span>
</div>
</div>
<!-- Promo Coupon Applied -->
<div class="p-3 bg-surface-container rounded-xl border border-dashed border-primary/40 flex items-center justify-between">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-lg">local_activity</span>
<div>
<span class="text-label-md font-label-md font-bold text-primary block">SORESEHAT Diterapkan</span>
<span class="text-body-sm font-body-sm text-outline">Diskon Weekday Spesial</span>
</div>
</div>
<span class="text-label-md font-label-md font-bold text-secondary">-Rp 20.000</span>
</div>
<!-- Price Calculation Breakdown -->
<div class="pt-3 border-t border-outline-variant space-y-2 text-body-sm font-body-sm">
<div class="flex justify-between text-on-surface-variant">
<span>Sewa Lapangan (Rp {{ number_format($lapangan->harga_per_jam,0,',','.') }} x 2 jam)</span>
<span>Rp 190.000</span>
</div>
<div class="flex justify-between text-on-surface-variant">
<span>Biaya Layanan &amp; Jaminan Slot</span>
<span>Rp 2.000</span>
</div>
<div class="flex justify-between text-secondary font-semibold">
<span>Diskon Kupon</span>
<span>-Rp 20.000</span>
</div>
<!-- Final Grand Total -->
<div class="pt-3 border-t border-outline-variant flex justify-between items-baseline">
<div>
<span class="text-label-md font-label-md text-outline block">Total Pembayaran</span>
<span class="text-[11px] text-on-surface-variant">Termasuk pajak &amp; asuransi venue</span>
</div>
<span class="text-headline-md font-headline-md font-extrabold text-primary">
                Rp 172.000
              </span>
</div>
</div>
<!-- PRIMARY CTA BUTTON (Express Midtrans) -->
<div class="space-y-2.5">
<button class="w-full bg-primary-container hover:bg-primary text-on-primary py-4 px-5 rounded-xl font-title-md text-title-md font-bold shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2.5">
<span class="material-symbols-outlined text-xl">lock</span>
              Lanjut ke Pembayaran Instan
            </button>
<!-- Trust Badge & Payment Guarantee -->
<div class="bg-surface-container-low p-3 rounded-lg border border-outline-variant/60 text-center space-y-1">
<div class="flex items-center justify-center gap-1.5 text-label-sm font-label-sm text-primary font-bold">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">shield</span>
                Garansi Slot 100% Anti-Bentrok
              </div>
<p class="text-[11px] text-on-surface-variant">
                Verifikasi Otomatis QRIS, BCA VA, Mandiri, &amp; GoPay via Midtrans
              </p>
</div>
</div>
<!-- Help Hotline Contact -->
<div class="text-center">
<a class="text-body-sm font-body-sm text-outline hover:text-primary transition-colors inline-flex items-center gap-1" href="#">
<span class="material-symbols-outlined text-sm">support_agent</span>
              Ada kendala pemesanan? Hubungi CS Arena
            </a>
</div>
</div>
</aside>
</div>
</main>
@endsection
