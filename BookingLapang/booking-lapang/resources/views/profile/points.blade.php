@extends('layouts.frontend')
@section('title','Poin Loyalitas - Booking Lapang')
@section('content')
<main class="flex-grow w-full max-w-7xl mx-auto px-6 md:px-12 py-8 space-y-10">
<!-- BREADCRUMB NAVIGATION -->
<nav class="flex items-center gap-2 text-body-sm font-body-sm text-outline">
<a class="hover:text-primary transition-colors flex items-center gap-1" href="#">
<span class="material-symbols-outlined text-sm">home</span>
<span>Beranda</span>
</a>
<span class="material-symbols-outlined text-xs">chevron_right</span>
<a class="hover:text-primary transition-colors" href="#">Akun Saya</a>
<span class="material-symbols-outlined text-xs">chevron_right</span>
<span class="text-on-surface font-semibold text-primary">Poin Loyalitas & Reward</span>
</nav>
<!-- SECTION 1: HERO MEMBERSHIP TIER & POINTS OVERVIEW -->
<section class="space-y-6">
<!-- Top Title Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
<div>
<span class="inline-flex items-center gap-1.5 text-xs font-label-sm uppercase tracking-wider text-primary bg-primary-fixed/40 px-3 py-1 rounded-full mb-2">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">military_tech</span>
            Program Loyalitas Atlet
          </span>
<h1 class="text-headline-lg font-headline-lg text-on-surface tracking-tight">
            Poin Loyalitas & Hadiah Olahraga
          </h1>
<p class="text-body-md font-body-md text-on-surface-variant mt-1">
            Kumpulkan poin dari setiap booking lapangan dan aktivitas olahraga. Tukarkan dengan voucher eksklusif.
          </p>
</div>
<div class="flex items-center gap-3">
<a class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-primary text-primary font-label-lg text-label-lg hover:bg-primary/5 transition-colors" href="#riwayat-poin">
<span class="material-symbols-outlined text-lg">history</span>
            Riwayat Poin
          </a>
<button class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-all active:scale-[0.98] shadow-sm">
<span class="material-symbols-outlined text-lg">redeem</span>
            Tukar Voucher
          </button>
</div>
</div>
<!-- Bento Grid: Main Tier Status + Fast Metrics -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
<!-- Large Tier Card (8 cols) -->
<div class="lg:col-span-8 bg-surface-container-lowest rounded-2xl p-6 md:p-8 tactile-card relative overflow-hidden flex flex-col justify-between border-t-4 border-t-tertiary-fixed-dim">
<!-- Subtle sports court pattern watermark (CSS gradient / layout) -->
<div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-primary/5 to-transparent pointer-events-none"></div>
<div>
<!-- Tier Top Row -->
<div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-surface-variant">
<div class="flex items-center gap-3">
<div class="w-14 h-14 rounded-2xl gold-badge-shimmer flex items-center justify-center text-tertiary shadow-sm">
<span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
</div>
<div>
<div class="flex items-center gap-2">
<span class="text-label-md font-label-md text-outline tracking-wider uppercase">Status Tier Saat Ini</span>
<span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-tertiary-fixed text-tertiary">Aktif Hingga 2026</span>
</div>
<h2 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                    Atlet Regional <span class="text-tertiary-container font-extrabold">(Gold)</span>
</h2>
</div>
</div>
<!-- Quick Info Badge -->
<div class="text-right">
<span class="text-body-sm font-body-sm text-outline block">Estimasi Nilai Poin</span>
<span class="text-title-lg font-title-lg text-primary font-bold">
                  Setara Rp 122.500
                </span>
</div>
</div>
<!-- Big Points Stat Display -->
<div class="pt-6 pb-4 flex flex-col md:flex-row md:items-baseline justify-between gap-2">
<div>
<span class="text-body-sm font-body-sm text-outline uppercase tracking-wider font-semibold">Total Saldo Poin Anda</span>
<div class="flex items-baseline gap-2 mt-1">
<span class="text-headline-xl font-headline-xl text-primary font-extrabold tracking-tight">{{ number_format($user->poin ?? 0, 0, ',', '.') }}</span>
<span class="text-title-lg font-title-lg text-on-surface-variant font-bold">Poin</span>
</div>
</div>
<div class="text-left md:text-right">
<span class="text-label-md font-label-md text-on-surface-variant">Target Berikutnya:</span>
<p class="text-title-md font-title-md text-on-surface font-bold flex items-center md:justify-end gap-1">
<span class="material-symbols-outlined text-primary text-lg">military_tech</span>
                  Atlet Nasional (Platinum)
                </p>
</div>
</div>
<!-- Progress Bar to Next Tier -->
<div class="space-y-2 mt-2">
<div class="flex justify-between text-body-sm font-body-sm">
<span class="text-primary font-semibold">{{ number_format($user->poin ?? 0, 0, ',', '.') }} / 3.000 Poin</span>
<span class="text-secondary font-bold">Kurang 550 Poin Lagi</span>
</div>
<div class="w-full h-3 bg-surface-container rounded-full overflow-hidden p-0.5 border border-outline-variant/60">
<div class="h-full bg-primary-container rounded-full relative transition-all duration-500" style="width: 81.6%;">
<div class="absolute inset-0 bg-white/20 animate-pulse"></div>
</div>
</div>
<div class="flex justify-between items-center text-xs text-outline pt-1">
<span>Gold Tier (1.500 Poin)</span>
<span class="font-medium text-on-surface">Target: Platinum Tier (3.000 Poin)</span>
</div>
</div>
</div>
<!-- Bottom Action Links Inside Hero -->
<div class="mt-6 pt-5 border-t border-surface-variant flex flex-wrap items-center justify-between gap-4">
<div class="flex items-center gap-2 text-body-sm font-body-sm text-on-surface-variant">
<span class="material-symbols-outlined text-primary text-base">verified</span>
<span>Keuntungan Gold: Potongan sewa 5% & booking duluan 3 hari lebih awal.</span>
</div>
<a class="text-label-md font-label-md text-primary font-semibold hover:underline flex items-center gap-1" href="#cara-dapat-poin">
<span>Pelajari Cara Dapat Poin</span>
<span class="material-symbols-outlined text-sm">arrow_forward</span>
</a>
</div>
</div>
<!-- Metric Summary Chips (4 cols) -->
<div class="lg:col-span-4 flex flex-col justify-between gap-4">
<!-- Metric 1: Poin Masuk Bulan Ini -->
<div class="bg-surface-container-lowest p-5 rounded-2xl tactile-card flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-2xl font-bold">trending_up</span>
</div>
<div class="flex-grow">
<span class="text-body-sm font-body-sm text-outline block">Poin Didapat Bulan Ini</span>
<div class="flex items-baseline gap-1.5">
<span class="text-title-lg font-title-lg text-primary font-extrabold">+450</span>
<span class="text-label-sm font-label-sm text-primary font-medium">Poin</span>
</div>
<span class="text-[11px] text-outline">Dari 3 pemesanan & 1 ulasan</span>
</div>
</div>
<!-- Metric 2: Poin Ditukar -->
<div class="bg-surface-container-lowest p-5 rounded-2xl tactile-card flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-2xl font-bold">swap_horiz</span>
</div>
<div class="flex-grow">
<span class="text-body-sm font-body-sm text-outline block">Poin Ditukar</span>
<div class="flex items-baseline gap-1.5">
<span class="text-title-lg font-title-lg text-secondary font-extrabold">-200</span>
<span class="text-label-sm font-label-sm text-secondary font-medium">Poin</span>
</div>
<span class="text-[11px] text-outline">1 voucher sewa raket dipakai</span>
</div>
</div>
<!-- Metric 3: Masa Berlaku Poin -->
<div class="bg-surface-container-lowest p-5 rounded-2xl tactile-card flex items-center gap-4 border-l-4 border-l-primary">
<div class="w-12 h-12 rounded-xl bg-surface-container text-on-surface-variant flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-2xl">hourglass_bottom</span>
</div>
<div class="flex-grow">
<span class="text-body-sm font-body-sm text-outline block">Poin Segera Hangus</span>
<div class="flex items-baseline gap-1.5">
<span class="text-title-lg font-title-lg text-on-surface font-extrabold">0 Poin</span>
</div>
<span class="text-[11px] text-primary font-medium">Aman hingga 31 Des 2025</span>
</div>
</div>
</div>
</div>
</section>
<!-- SECTION 2: KATALOG PENUKARAN VOUCHER & HADIAH OLAHRAGA -->
<section class="space-y-6 pt-4">
<!-- Section Title & Tabs -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-surface-variant pb-4">
<div>
<h2 class="text-headline-sm font-headline-sm text-on-surface font-bold flex items-center gap-2">
<span class="material-symbols-outlined text-primary">redeem</span>
            Katalog Penukaran Voucher & Hadiah Olahraga
          </h2>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
            Pilih reward sesuai kebutuhan sesi latihan atau sparring mingguan Anda.
          </p>
</div>
<div class="text-body-sm font-body-sm text-outline flex items-center gap-1.5">
<span>Saldo Anda:</span>
<span class="font-bold text-primary bg-surface-container px-2 py-0.5 rounded-md border border-outline-variant">2.450 Poin</span>
</div>
</div>
<!-- Filter Category Chips -->
<div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
<button class="px-4 py-2 rounded-xl bg-primary-container text-on-primary text-label-md font-label-md flex items-center gap-1.5 shadow-sm shrink-0">
<span class="material-symbols-outlined text-base">apps</span>
          Semua Hadiah
        </button>
<button class="px-4 py-2 rounded-xl bg-surface-container-lowest border border-outline-variant text-on-surface hover:border-primary text-label-md font-label-md flex items-center gap-1.5 transition-colors shrink-0">
<span class="material-symbols-outlined text-base">sports_tennis</span>
          Diskon Sewa Lapangan
        </button>
<button class="px-4 py-2 rounded-xl bg-surface-container-lowest border border-outline-variant text-on-surface hover:border-primary text-label-md font-label-md flex items-center gap-1.5 transition-colors shrink-0">
<span class="material-symbols-outlined text-base">sports_soccer</span>
          Sewa Alat & Jersey
        </button>
<button class="px-4 py-2 rounded-xl bg-surface-container-lowest border border-outline-variant text-on-surface hover:border-primary text-label-md font-label-md flex items-center gap-1.5 transition-colors shrink-0">
<span class="material-symbols-outlined text-base">local_cafe</span>
          Voucher Minuman/Kantin Arena
        </button>
<button class="px-4 py-2 rounded-xl bg-surface-container-lowest border border-outline-variant text-on-surface hover:border-primary text-label-md font-label-md flex items-center gap-1.5 transition-colors shrink-0">
<span class="material-symbols-outlined text-base">apparel</span>
          Merchandise Resmi
        </button>
</div>
<!-- Rewards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<!-- Reward Card 1: Potongan 25k (Paling Populer) -->
<div class="bg-surface-container-lowest rounded-2xl tactile-card tactile-card-hover p-5 flex flex-col justify-between relative border border-outline-variant">
<!-- Populer Badge -->
<div class="absolute -top-3 left-5">
<span class="bg-secondary text-on-secondary text-label-sm font-label-sm px-3 py-0.5 rounded-full shadow-sm flex items-center gap-1">
<span class="material-symbols-outlined text-xs">local_fire_department</span>
              Paling Populer
            </span>
</div>
<div>
<div class="flex items-start justify-between mt-2">
<div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-2xl">confirmation_number</span>
</div>
<div class="text-right">
<span class="text-headline-sm font-headline-sm font-extrabold text-primary">500</span>
<span class="text-body-sm font-body-sm text-outline block -mt-1">Poin</span>
</div>
</div>
<div class="mt-4">
<span class="text-xs font-label-sm uppercase tracking-wider text-primary font-semibold">Voucher Lapangan</span>
<h3 class="text-title-md font-title-md text-on-surface font-bold mt-0.5">
                Potongan Sewa Lapangan Rp 25.000
              </h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-2 leading-relaxed">
                Berlaku untuk semua cabang lapangan badminton, futsal, dan mini soccer tanpa minimal transaksi.
              </p>
</div>
</div>
<div class="pt-5 mt-5 border-t border-surface-variant flex items-center justify-between">
<div class="text-[11px] text-outline flex items-center gap-1">
<span class="material-symbols-outlined text-sm">alarm</span>
              Masa aktif 30 hari
            </div>
<button class="px-4 py-2 rounded-xl bg-secondary text-on-secondary hover:bg-secondary-container hover:text-on-secondary-container text-label-md font-label-md font-bold transition-all active:scale-95 shadow-sm">
              Tukar Sekarang
            </button>
</div>
</div>
<!-- Reward Card 2: Potongan 50k -->
<div class="bg-surface-container-lowest rounded-2xl tactile-card tactile-card-hover p-5 flex flex-col justify-between border border-outline-variant">
<div>
<div class="flex items-start justify-between">
<div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-2xl">loyalty</span>
</div>
<div class="text-right">
<span class="text-headline-sm font-headline-sm font-extrabold text-primary">950</span>
<span class="text-body-sm font-body-sm text-outline block -mt-1">Poin</span>
</div>
</div>
<div class="mt-4">
<span class="text-xs font-label-sm uppercase tracking-wider text-primary font-semibold">Voucher Lapangan</span>
<h3 class="text-title-md font-title-md text-on-surface font-bold mt-0.5">
                Potongan Sewa Lapangan Rp 50.000
              </h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-2 leading-relaxed">
                Klaim diskon langsung saat booking minimal 2 jam sewa di arena partner Booking Lapang.
              </p>
</div>
</div>
<div class="pt-5 mt-5 border-t border-surface-variant flex items-center justify-between">
<div class="text-[11px] text-outline flex items-center gap-1">
<span class="material-symbols-outlined text-sm">alarm</span>
              Masa aktif 45 hari
            </div>
<button class="px-4 py-2 rounded-xl bg-secondary text-on-secondary hover:bg-secondary-container hover:text-on-secondary-container text-label-md font-label-md font-bold transition-all active:scale-95 shadow-sm">
              Tukar Sekarang
            </button>
</div>
</div>
<!-- Reward Card 3: Gratis Sewa Raket Yonex / Bola Futsal -->
<div class="bg-surface-container-lowest rounded-2xl tactile-card tactile-card-hover p-5 flex flex-col justify-between border border-outline-variant">
<div>
<div class="flex items-start justify-between">
<div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-on-surface">
<span class="material-symbols-outlined text-2xl">sports_tennis</span>
</div>
<div class="text-right">
<span class="text-headline-sm font-headline-sm font-extrabold text-primary">350</span>
<span class="text-body-sm font-body-sm text-outline block -mt-1">Poin</span>
</div>
</div>
<div class="mt-4">
<span class="text-xs font-label-sm uppercase tracking-wider text-outline font-semibold">Peralatan Olahraga</span>
<h3 class="text-title-md font-title-md text-on-surface font-bold mt-0.5">
                Gratis Sewa Raket Yonex / Bola Futsal 1 Sesi
              </h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-2 leading-relaxed">
                Tukarkan langsung di meja registrasi venue. Tersedia raket badminton carbon atau 1 bola futsal standar FIFA.
              </p>
</div>
</div>
<div class="pt-5 mt-5 border-t border-surface-variant flex items-center justify-between">
<div class="text-[11px] text-outline flex items-center gap-1">
<span class="material-symbols-outlined text-sm">storefront</span>
              Redeem di Venue
            </div>
<button class="px-4 py-2 rounded-xl bg-primary text-on-primary hover:bg-primary-container text-label-md font-label-md font-semibold transition-all active:scale-95">
              Tukar
            </button>
</div>
</div>
<!-- Reward Card 4: Voucher Minuman Isotonik & Pocari -->
<div class="bg-surface-container-lowest rounded-2xl tactile-card tactile-card-hover p-5 flex flex-col justify-between border border-outline-variant">
<div>
<div class="flex items-start justify-between">
<div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-on-surface">
<span class="material-symbols-outlined text-2xl">sports_bar</span>
</div>
<div class="text-right">
<span class="text-headline-sm font-headline-sm font-extrabold text-primary">150</span>
<span class="text-body-sm font-body-sm text-outline block -mt-1">Poin</span>
</div>
</div>
<div class="mt-4">
<span class="text-xs font-label-sm uppercase tracking-wider text-outline font-semibold">Kantin Arena</span>
<h3 class="text-title-md font-title-md text-on-surface font-bold mt-0.5">
                Voucher Minuman Isotonik & Pocari di Smash Hub
              </h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-2 leading-relaxed">
                Segarkan tubuh setelah main sengit. Klaim 1 botol dingin minuman isotonik 500ml di kantin arena.
              </p>
</div>
</div>
<div class="pt-5 mt-5 border-t border-surface-variant flex items-center justify-between">
<div class="text-[11px] text-outline flex items-center gap-1">
<span class="material-symbols-outlined text-sm">pin_drop</span>
              Khusus Smash Hub Cilandak
            </div>
<button class="px-4 py-2 rounded-xl bg-primary text-on-primary hover:bg-primary-container text-label-md font-label-md font-semibold transition-all active:scale-95">
              Tukar
            </button>
</div>
</div>
<!-- Reward Card 5: Jersey Dry-Fit Eksklusif -->
<div class="bg-surface-container-lowest rounded-2xl tactile-card tactile-card-hover p-5 flex flex-col justify-between border border-outline-variant">
<div>
<div class="flex items-start justify-between">
<div class="w-12 h-12 rounded-xl bg-tertiary-fixed/40 flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined text-2xl">apparel</span>
</div>
<div class="text-right">
<span class="text-headline-sm font-headline-sm font-extrabold text-primary">1.800</span>
<span class="text-body-sm font-body-sm text-outline block -mt-1">Poin</span>
</div>
</div>
<div class="mt-4">
<span class="text-xs font-label-sm uppercase tracking-wider text-tertiary font-bold">Official Merch</span>
<h3 class="text-title-md font-title-md text-on-surface font-bold mt-0.5">
                Jersey Dry-Fit Eksklusif Booking Lapang 2025
              </h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-2 leading-relaxed">
                Bahan premium berpori cepat kering dengan sablon polyflex logo Booking Lapang. Bebas pilih ukuran (S-XXL).
              </p>
</div>
</div>
<div class="pt-5 mt-5 border-t border-surface-variant flex items-center justify-between">
<div class="text-[11px] text-outline flex items-center gap-1">
<span class="material-symbols-outlined text-sm">local_shipping</span>
              Gratis Ongkir se-Jabodetabek
            </div>
<button class="px-4 py-2 rounded-xl bg-primary text-on-primary hover:bg-primary-container text-label-md font-label-md font-semibold transition-all active:scale-95">
              Tukar
            </button>
</div>
</div>
<!-- Reward Card 6: Free Pass Sparring Weekend 1 Jam -->
<div class="bg-surface-container-lowest rounded-2xl tactile-card tactile-card-hover p-5 flex flex-col justify-between border border-outline-variant">
<div>
<div class="flex items-start justify-between">
<div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-2xl">event_available</span>
</div>
<div class="text-right">
<span class="text-headline-sm font-headline-sm font-extrabold text-primary">2.200</span>
<span class="text-body-sm font-body-sm text-outline block -mt-1">Poin</span>
</div>
</div>
<div class="mt-4">
<span class="text-xs font-label-sm uppercase tracking-wider text-primary font-semibold">Special Pass</span>
<h3 class="text-title-md font-title-md text-on-surface font-bold mt-0.5">
                Free Pass Sparring Weekend 1 Jam
              </h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-2 leading-relaxed">
                Main bebas jam prime-time Sabtu/Minggu di seluruh lapangan mitra badminton dan mini soccer terdaftar.
              </p>
</div>
</div>
<div class="pt-5 mt-5 border-t border-surface-variant flex items-center justify-between">
<div class="text-[11px] text-outline flex items-center gap-1">
<span class="material-symbols-outlined text-sm">stars</span>
              Slot Prime Time Termasuk
            </div>
<button class="px-4 py-2 rounded-xl bg-primary text-on-primary hover:bg-primary-container text-label-md font-label-md font-semibold transition-all active:scale-95">
              Tukar
            </button>
</div>
</div>
</div>
</section>
<!-- SECTION 3: AKTIVITAS & RIWAYAT POIN TRANSPARAN -->
<section class="space-y-6 pt-4" id="riwayat-poin">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-surface-variant pb-4">
<div>
<h2 class="text-headline-sm font-headline-sm text-on-surface font-bold flex items-center gap-2">
<span class="material-symbols-outlined text-primary">manage_history</span>
            Aktivitas & Riwayat Poin Transparan
          </h2>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
            Setiap perolehan dan penukaran poin tercatat otomatis dan transparan.
          </p>
</div>
<!-- Filter Riwayat Tabs -->
<div class="inline-flex p-1 rounded-xl bg-surface-container border border-outline-variant">
<button class="px-3.5 py-1.5 rounded-lg bg-surface-container-lowest text-primary font-bold text-label-md font-label-md shadow-xs">
            Semua Riwayat
          </button>
<button class="px-3.5 py-1.5 rounded-lg text-on-surface-variant hover:text-on-surface text-label-md font-label-md transition-colors">
            Poin Masuk (+)
          </button>
<button class="px-3.5 py-1.5 rounded-lg text-on-surface-variant hover:text-on-surface text-label-md font-label-md transition-colors">
            Poin Keluar (-)
          </button>
</div>
</div>
<!-- Transaction List Container -->
<div class="bg-surface-container-lowest rounded-2xl tactile-card overflow-hidden border border-outline-variant divide-y divide-surface-variant">
<!-- Transaction Item 1 -->
<div class="p-5 flex items-center justify-between gap-4 hover:bg-surface-container/30 transition-colors">
<div class="flex items-center gap-4">
<div class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-2xl font-bold">add_circle</span>
</div>
<div>
<div class="flex items-center gap-2 flex-wrap">
<h4 class="text-title-md font-title-md text-on-surface font-bold">
                  Booking Selesai Smash Hub Cilandak
                </h4>
<span class="text-xs px-2 py-0.5 rounded bg-primary-fixed/50 text-on-primary-fixed font-semibold">
                  ID #BL-20251015-8849
                </span>
</div>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
                Main Badminton Court 3 (2 Jam) • Selesai & Terverifikasi
              </p>
<span class="text-[11px] text-outline block mt-0.5">15 Okt 2025, 20:30 WIB</span>
</div>
</div>
<div class="text-right shrink-0">
<span class="text-title-lg font-title-lg text-primary font-extrabold">+150 Poin</span>
<span class="text-xs text-primary font-medium block">Poin Bertambah</span>
</div>
</div>
<!-- Transaction Item 2 -->
<div class="p-5 flex items-center justify-between gap-4 hover:bg-surface-container/30 transition-colors">
<div class="flex items-center gap-4">
<div class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-2xl font-bold">photo_camera</span>
</div>
<div>
<div class="flex items-center gap-2 flex-wrap">
<h4 class="text-title-md font-title-md text-on-surface font-bold">
                  Ulasan Lapangan dengan Foto
                </h4>
<span class="text-xs px-2 py-0.5 rounded bg-surface-container text-outline font-semibold">
                  Smash Hub Arena
                </span>
</div>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
                Rating 5 Bintang + 3 Foto Kondisi Lapangan Vinyl
              </p>
<span class="text-[11px] text-outline block mt-0.5">16 Okt 2025, 09:12 WIB</span>
</div>
</div>
<div class="text-right shrink-0">
<span class="text-title-lg font-title-lg text-primary font-extrabold">+50 Poin</span>
<span class="text-xs text-primary font-medium block">Bonus Ulasan</span>
</div>
</div>
<!-- Transaction Item 3 -->
<div class="p-5 flex items-center justify-between gap-4 hover:bg-surface-container/30 transition-colors">
<div class="flex items-center gap-4">
<div class="w-11 h-11 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-2xl font-bold">remove_circle</span>
</div>
<div>
<div class="flex items-center gap-2 flex-wrap">
<h4 class="text-title-md font-title-md text-on-surface font-bold">
                  Penukaran Diskon Sewa Rp 25.000
                </h4>
<span class="text-xs px-2 py-0.5 rounded bg-secondary-fixed text-on-secondary-fixed font-semibold">
                  Kupon #VCR-25K-771
                </span>
</div>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
                Voucher Penukaran Berhasil Digunakan saat Checkout
              </p>
<span class="text-[11px] text-outline block mt-0.5">12 Okt 2025, 14:20 WIB</span>
</div>
</div>
<div class="text-right shrink-0">
<span class="text-title-lg font-title-lg text-secondary font-extrabold">-500 Poin</span>
<span class="text-xs text-secondary font-medium block">Penukaran Reward</span>
</div>
</div>
<!-- Transaction Item 4 -->
<div class="p-5 flex items-center justify-between gap-4 hover:bg-surface-container/30 transition-colors">
<div class="flex items-center gap-4">
<div class="w-11 h-11 rounded-xl bg-tertiary-fixed/50 text-tertiary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-2xl font-bold">group_add</span>
</div>
<div>
<div class="flex items-center gap-2 flex-wrap">
<h4 class="text-title-md font-title-md text-on-surface font-bold">
                  Bonus Referral Teman Bergabung
                </h4>
<span class="text-xs px-2 py-0.5 rounded bg-tertiary-fixed/60 text-tertiary font-semibold">
                  Ref: Dimas-Kurnia
                </span>
</div>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
                Kurnia Ramadhan menyelesaikan booking pertama di Booking Lapang
              </p>
<span class="text-[11px] text-outline block mt-0.5">8 Okt 2025, 18:05 WIB</span>
</div>
</div>
<div class="text-right shrink-0">
<span class="text-title-lg font-title-lg text-primary font-extrabold">+200 Poin</span>
<span class="text-xs text-primary font-medium block">Bonus Referral</span>
</div>
</div>
<!-- Transaction Item 5 -->
<div class="p-5 flex items-center justify-between gap-4 hover:bg-surface-container/30 transition-colors">
<div class="flex items-center gap-4">
<div class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-2xl font-bold">add_circle</span>
</div>
<div>
<div class="flex items-center gap-2 flex-wrap">
<h4 class="text-title-md font-title-md text-on-surface font-bold">
                  Booking Selesai Viva Futsal
                </h4>
<span class="text-xs px-2 py-0.5 rounded bg-primary-fixed/50 text-on-primary-fixed font-semibold">
                  ID #BL-20250928-1022
                </span>
</div>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
                Sewa Lapangan Futsal Vinyl Parquet (2 Jam)
              </p>
<span class="text-[11px] text-outline block mt-0.5">28 Sep 2025, 21:00 WIB</span>
</div>
</div>
<div class="text-right shrink-0">
<span class="text-title-lg font-title-lg text-primary font-extrabold">+180 Poin</span>
<span class="text-xs text-primary font-medium block">Poin Bertambah</span>
</div>
</div>
</div>
<!-- Pagination / Load More Footer -->
<div class="flex items-center justify-between pt-2">
<span class="text-body-sm font-body-sm text-outline">Menampilkan 5 dari 32 riwayat transaksi</span>
<button class="px-4 py-2 rounded-xl bg-surface-container-lowest border border-outline-variant text-on-surface hover:border-primary text-label-md font-label-md transition-colors flex items-center gap-1.5">
<span>Lihat Semua Riwayat</span>
<span class="material-symbols-outlined text-base">expand_more</span>
</button>
</div>
</section>
<!-- SECTION 4: INFORMATIVE SIDE/BOTTOM BANNER (CARA MUDAH DAPAT POIN) -->
<section class="bg-gradient-to-r from-primary-container to-primary rounded-3xl p-8 text-on-primary shadow-sm relative overflow-hidden" id="cara-dapat-poin">
<!-- Decorative background court graphics -->
<div class="absolute -right-10 -bottom-10 w-72 h-72 border-8 border-white/10 rounded-full pointer-events-none"></div>
<div class="absolute right-32 top-0 w-48 h-48 border border-white/10 rounded-full pointer-events-none"></div>
<div class="relative z-10 max-w-4xl space-y-6">
<div>
<span class="text-xs font-label-sm uppercase tracking-wider text-tertiary-fixed font-bold bg-white/10 px-3 py-1 rounded-full inline-block mb-2">
            Tips Maksimalkan Reward
          </span>
<h2 class="text-headline-md font-headline-md font-bold tracking-tight text-surface-container-lowest">
            Cara Mudah Mengumpulkan Poin Setiap Minggu
          </h2>
<p class="text-body-md font-body-md text-on-primary/90 mt-1 max-w-2xl">
            Tingkatkan tier Anda ke Atlet Nasional (Platinum) lebih cepat dengan berpartisipasi aktif dalam ekosistem Booking Lapang.
          </p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
<!-- Step 1 -->
<div class="bg-white/10 backdrop-blur-xs p-5 rounded-2xl border border-white/15">
<div class="w-10 h-10 rounded-xl bg-white text-primary flex items-center justify-center font-bold text-lg mb-3">
              1
            </div>
<h4 class="text-title-md font-title-md font-bold text-white">Main Rutin</h4>
<p class="text-body-sm font-body-sm text-white/80 mt-1.5 leading-relaxed">
              Dapatkan 10 Poin per kelipatan transaksi Rp 10.000 setiap booking lapangan badminton, futsal, atau tenis.
            </p>
</div>
<!-- Step 2 -->
<div class="bg-white/10 backdrop-blur-xs p-5 rounded-2xl border border-white/15">
<div class="w-10 h-10 rounded-xl bg-white text-primary flex items-center justify-center font-bold text-lg mb-3">
              2
            </div>
<h4 class="text-title-md font-title-md font-bold text-white">Tulis Ulasan Foto</h4>
<p class="text-body-sm font-body-sm text-white/80 mt-1.5 leading-relaxed">
              Bantu pemain lain dengan review jujur kondisi lantai lapangan, lampu, dan kamar ganti. Dapatkan bonus +50 Poin.
            </p>
</div>
<!-- Step 3 -->
<div class="bg-white/10 backdrop-blur-xs p-5 rounded-2xl border border-white/15">
<div class="w-10 h-10 rounded-xl bg-white text-primary flex items-center justify-center font-bold text-lg mb-3">
              3
            </div>
<h4 class="text-title-md font-title-md font-bold text-white">Ajak Teman Main</h4>
<p class="text-body-sm font-body-sm text-white/80 mt-1.5 leading-relaxed">
              Bagikan kode referral pribadi. Anda dan teman masing-masing menerima bonus instan +200 Poin saat sesi pertama usai.
            </p>
</div>
</div>
<!-- Referral Link Copier Widget -->
<div class="bg-white/15 p-4 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 border border-white/20">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-2xl text-tertiary-fixed">share</span>
<div>
<span class="text-xs text-white/80 block">Kode Referral Unik Anda:</span>
<span class="text-title-md font-title-md font-bold text-white tracking-wider">DIMAS-LAPANG25</span>
</div>
</div>
<button class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-white text-primary hover:bg-surface text-label-lg font-label-lg font-bold transition-all active:scale-95 shadow-xs flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-lg">content_copy</span>
            Salin Kode Referral
          </button>
</div>
</div>
</section>
<div class="max-w-7xl mx-auto px-6 md:px-12 pb-12">
<div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 shadow-sm">
<h3 class="text-title-lg font-bold text-on-surface mb-3">Tukar Poin jadi Voucher</h3>
<p class="text-body-sm text-on-surface-variant mb-4">100 poin = Rp10.000 diskon. Gunakan kelipatan 100 poin.</p>
<form method="POST" action="{{ route('poin.redeem') }}" class="flex flex-col sm:flex-row gap-3">@csrf
<input type="number" name="jumlah_poin" min="100" step="100" max="{{ $user->poin ?? 0 }}" required class="flex-1 rounded-xl border border-outline-variant p-3" placeholder="Jumlah poin">
<button class="px-5 py-3 bg-primary-container text-white rounded-xl font-bold">Tukar Voucher</button>
</form>
@if(session('success'))<p class="mt-3 text-sm text-primary">{{ session('success') }}</p>@endif
@if(session('error'))<p class="mt-3 text-sm text-secondary">{{ session('error') }}</p>@endif
</div></div></main>
@endsection
