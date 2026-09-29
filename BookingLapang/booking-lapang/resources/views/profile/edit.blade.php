@extends('layouts.frontend')
@section('title','Profil & Pengaturan Akun - Booking Lapang')
@section('content')
<main class="flex-grow max-w-7xl mx-auto w-full px-6 md:px-12 py-8 md:py-10">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
<!-- ================= LEFT SIDEBAR NAVIGATION ================= -->
<aside class="lg:col-span-4 xl:col-span-3 space-y-6">
<!-- User Snapshot Card -->
<div class="tactile-card rounded-2xl p-5 tactile-shadow">
<div class="flex items-center gap-4">
<img alt="{{ $user->name }} Profil" class="w-14 h-14 rounded-2xl object-cover border border-outline-variant" data-alt="Square portrait of {{ $user->name }} smiling warmly, wearing sports attire in an airy modern badminton club pavilion. Earthy natural sunlight, warm terracotta accents, photorealistic styling." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCmTcUORUJajMhdjI9AXesSHSrcHGpSeDOLNVGKFeMqEFCim9JBSpzD70SGm88hvb5ormp1pnHZASeigUEZuy_ePQqSIooq4RY4V1q4RfpFq21mdvq5WQWtmv-avL4BRuv8_sPXM4Na3BAoH_IsR5pUcZJCSocDwZNPs1oMtVyawLptlEjhTx11m5VO59nIkelS4eDNDhx1vjOKCvvNLPDm6aRwXA04EPYPTKor7SMzqAxrJRi_DbA"/>
<div class="flex-1 min-w-0">
<h2 class="text-title-md font-title-md text-on-surface truncate font-bold">{{ $user->name }}</h2>
<p class="text-body-sm font-body-sm text-on-surface-variant truncate">{{ $user->email }}</p>
<div class="mt-1 flex items-center gap-1.5">
<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-label-sm font-label-sm bg-primary-fixed text-on-primary-fixed">
<span class="material-symbols-outlined text-xs" data-icon="military_tech">military_tech</span>
                  {{ $tier }} Member
                </span>
</div>
</div>
</div>
<div class="mt-4 pt-4 border-t border-surface-container-high grid grid-cols-2 gap-2 text-center">
<div class="p-2 rounded-xl bg-surface-container-low">
<span class="text-label-sm font-label-sm text-on-surface-variant block">Total Match</span>
<span class="text-title-md font-title-md font-bold text-primary-container">48 Kali</span>
</div>
<div class="p-2 rounded-xl bg-surface-container-low">
<span class="text-label-sm font-label-sm text-on-surface-variant block">Poin Lapang</span>
<span class="text-title-md font-title-md font-bold text-tertiary-container">{{ number_format($user->poin ?? 0, 0, ',', '.') }} Pts</span>
</div>
</div>
</div>
<!-- Sidebar Menu Items -->
<div class="tactile-card rounded-2xl p-2 tactile-shadow overflow-hidden">
<nav class="space-y-1">
<!-- Active Tab: Informasi Profil -->
<a class="flex items-center justify-between px-4 py-3 rounded-xl bg-primary-container/10 border-l-4 border-primary text-primary font-bold text-label-lg font-label-lg transition-all" href="#profil">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary" data-icon="person" data-weight="fill" style="font-variation-settings: 'FILL' 1;">person</span>
<span>Informasi Profil</span>
</div>
<span class="material-symbols-outlined text-sm" data-icon="chevron_right">chevron_right</span>
</a>
<!-- Komunitas & Tim Olahraga -->
<a class="flex items-center justify-between px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors text-label-lg font-label-lg" href="#tim">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined" data-icon="groups">groups</span>
<span>Komunitas &amp; Tim Olahraga</span>
</div>
<span class="px-2 py-0.5 rounded-full text-label-sm font-label-sm bg-surface-container-highest text-on-surface-variant">1 Tim</span>
</a>
<!-- Metode Pembayaran & E-Wallet -->
<a class="flex items-center justify-between px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors text-label-lg font-label-lg" href="#pembayaran">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined" data-icon="account_balance_wallet">account_balance_wallet</span>
<span>Metode Pembayaran &amp; E-Wallet</span>
</div>
</a>
<!-- Notifikasi & WhatsApp Alert -->
<a class="flex items-center justify-between px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors text-label-lg font-label-lg" href="#notifikasi">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined" data-icon="notifications_active">notifications_active</span>
<span>Notifikasi &amp; WhatsApp Alert</span>
</div>
<span class="w-2 h-2 rounded-full bg-secondary"></span>
</a>
<!-- Keamanan & Kata Sandi -->
<a class="flex items-center justify-between px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors text-label-lg font-label-lg" href="#keamanan">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined" data-icon="lock">lock</span>
<span>Keamanan &amp; Kata Sandi</span>
</div>
</a>
<!-- Riwayat Booking & Invoice -->
<a class="flex items-center justify-between px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors text-label-lg font-label-lg" href="#riwayat">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined" data-icon="receipt_long">receipt_long</span>
<span>Riwayat Booking &amp; Invoice</span>
</div>
</a>
<!-- Poin Loyalitas -->
<a class="flex items-center justify-between px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors text-label-lg font-label-lg" href="#poin">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-on-tertiary-container" data-icon="stars">stars</span>
<span>Poin Loyalitas</span>
</div>
<span class="text-label-sm font-label-sm font-bold text-on-tertiary-container">{{ number_format($user->poin ?? 0, 0, ',', '.') }} Pts</span>
</a>
<!-- Separator -->
<div class="pt-2 border-t border-surface-container-high my-1"></div>
<!-- Keluar Akun -->
<button class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-secondary hover:bg-error-container/30 transition-colors text-label-lg font-label-lg font-semibold active:opacity-80" type="button">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-secondary" data-icon="logout">logout</span>
<span>Keluar Akun</span>
</div>
</button>
</nav>
</div>
<!-- Venue Operator Quick Banner -->
<div class="tactile-card rounded-2xl p-5 bg-gradient-to-br from-surface to-surface-container-low border border-outline-variant">
<div class="flex items-start gap-3">
<span class="p-2 rounded-xl bg-primary-fixed text-on-primary-fixed">
<span class="material-symbols-outlined" data-icon="stadium">stadium</span>
</span>
<div>
<h4 class="text-title-md font-title-md font-bold text-on-surface">Punya Lapangan Sendiri?</h4>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-1">Daftarkan arena Anda &amp; kelola jadwal otomatis bersama 850+ venue partner.</p>
<a class="inline-flex items-center gap-1 text-primary font-bold text-label-sm font-label-sm mt-3 hover:underline" href="#">
                Daftarkan Lapangan Anda
                <span class="material-symbols-outlined text-xs" data-icon="arrow_forward">arrow_forward</span>
</a>
</div>
</div>
</div>
</aside>
<!-- ================= RIGHT MAIN CONTENT AREA ================= -->
<section class="lg:col-span-8 xl:col-span-9 space-y-8">
<!-- Header Title Banner -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-outline-variant">
<div>
<h1 class="text-headline-md font-headline-md text-on-surface">Profil &amp; Pengaturan Akun</h1>
<p class="text-body-md font-body-md text-on-surface-variant mt-1">Kelola rincian personal, kredensial pembayaran, tim olahraga, dan integrasi WhatsApp booking.</p>
</div>
<div class="flex items-center gap-3">
<span class="text-body-sm font-body-sm text-on-surface-variant flex items-center gap-1.5">
<span class="w-2.5 h-2.5 rounded-full bg-primary-container"></span>
              Sinkronisasi Cloud Aktif
            </span>
</div>
</div>
<!-- ================= SECTION 1: DATA DIRI & PROFIL OLAHRAGA ================= -->
<div class="tactile-card rounded-2xl p-6 md:p-8 tactile-shadow space-y-6" id="profil">
<div class="flex items-center justify-between pb-4 border-b border-surface-container-high">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-xl" data-icon="badge">badge</span>
</div>
<h3 class="text-title-lg font-title-lg text-on-surface">Data Diri &amp; Profil Olahraga</h3>
</div>
<span class="text-body-sm font-body-sm text-on-surface-variant">Terakhir diperbarui: 12 Feb 2025</span>
</div>
<!-- Avatar & Badges Header -->
<div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 p-4 rounded-xl bg-surface-container-low border border-surface-variant">
<div class="relative group">
<img alt="Foto Profil Pengguna {{ $user->name }}" class="w-24 h-24 rounded-2xl object-cover border-2 border-outline-variant shadow-sm" data-alt="Athletic Indonesian male sports captain {{ $user->name }} with friendly expression, outdoor sports complex backdrop with vinyl court and green natural foliage, warm organic sunlight, crisp realistic lighting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBC70oS2U20id-5xqRp4fZoEllY97kIEHq6dgp5sg6Tl76yTQDawR6GbaMA_KMyvMvPUlkUAwAJPfveHipiiEUYkaCi6ihUEyzNkRajhsJz7y3wW0yGxoXNR4cp4c23CrKNV_drtc_BCFSODwmQdAdW4g5WM6FY5HH2rlLMLP_JtDOX2qi_dnyLZQ4SmHY04Kl8O_r8F6sS4NdDXjNEw5QKYf6fYieZcW9s_6CwHaDjSZwzZ52y2RQ"/>
<button class="absolute -bottom-2 -right-2 bg-primary-container text-surface p-2 rounded-xl shadow hover:bg-surface-tint transition-all active:scale-95" title="Ubah Foto Profil" type="button">
<span class="material-symbols-outlined text-base block" data-icon="photo_camera">photo_camera</span>
</button>
</div>
<div class="space-y-2">
<div class="flex items-center gap-2">
<h4 class="text-title-lg font-title-lg font-bold text-on-surface">{{ $user->name }}</h4>
<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed text-label-sm font-label-sm font-semibold">
<span class="material-symbols-outlined text-xs" data-icon="verified" data-weight="fill" style="font-variation-settings: 'FILL' 1;">verified</span>
                  Member Terverifikasi Midtrans
                </span>
</div>
<p class="text-body-sm font-body-sm text-on-surface-variant">Format gambar: JPG, PNG maks 2MB. Disarankan rasio 1:1.</p>
<!-- Badges Cluster -->
<div class="flex flex-wrap gap-2 pt-1">
<span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-surface border border-outline-variant text-label-sm font-label-sm text-on-surface">
<span class="material-symbols-outlined text-sm text-primary" data-icon="sports">sports</span>
                  Penyewa Terverifikasi
                </span>
<span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-surface border border-outline-variant text-label-sm font-label-sm text-on-surface">
<span class="material-symbols-outlined text-sm text-secondary" data-icon="shield">shield</span>
                  Kapten Tim Garuda BC
                </span>
<span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-surface border border-outline-variant text-label-sm font-label-sm text-on-surface">
<span class="material-symbols-outlined text-sm text-on-tertiary-container" data-icon="military_tech">military_tech</span>
                  {{ $tier }} Tier
                </span>
</div>
</div>
</div>
<!-- Form 2-Column Grid -->
<form method="POST" action="{{ route('profile.update') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">@csrf @method('PATCH')
<!-- Nama Lengkap -->
<div class="space-y-1.5">
<label class="block text-label-md font-label-md text-on-surface font-semibold" for="nama_lengkap">
                Nama Lengkap Sesuai KTP <span class="text-error">*</span>
</label>
<input class="custom-input w-full px-4 text-body-md font-body-md text-on-surface" name="name" id="nama_lengkap" placeholder="Masukkan nama lengkap" type="text" value="{{ $user->name }}"/>
</div>
<!-- Email Terverifikasi -->
<div class="space-y-1.5">
<div class="flex justify-between items-center">
<label class="block text-label-md font-label-md text-on-surface font-semibold" for="email">
                  Alamat Email <span class="text-error">*</span>
</label>
<span class="inline-flex items-center gap-1 text-label-sm font-label-sm text-primary-container font-semibold">
<span class="material-symbols-outlined text-xs" data-icon="check_circle">check_circle</span>
                  Terverifikasi
                </span>
</div>
<div class="relative">
<input class="custom-input w-full px-4 text-body-md font-body-md text-on-surface pr-10" name="email" id="email" type="email" value="{{ $user->email }}"/>
<span class="material-symbols-outlined absolute right-3 top-3 text-outline-variant" data-icon="mail">mail</span>
</div>
</div>
<!-- Nomor WhatsApp -->
<div class="space-y-1.5">
<div class="flex justify-between items-center">
<label class="block text-label-md font-label-md text-on-surface font-semibold" for="whatsapp">
                  Nomor WhatsApp Utama <span class="text-error">*</span>
</label>
<span class="text-label-sm font-label-sm text-primary-container font-semibold">
                  Aktif untuk E-Tiket &amp; Waitlist
                </span>
</div>
<div class="relative flex items-center">
<span class="absolute left-3 text-body-md font-body-md text-on-surface-variant font-medium flex items-center gap-1">
<span class="material-symbols-outlined text-base text-primary-container" data-icon="chat">chat</span>
                  🇮🇩
                </span>
<input class="custom-input w-full pl-16 pr-4 text-body-md font-body-md text-on-surface" id="whatsapp" type="tel" value="+62 812-8899-2341"/>
</div>
</div>
<!-- Kota Domisili -->
<div class="space-y-1.5">
<label class="block text-label-md font-label-md text-on-surface font-semibold" for="domisili">
                Kota Domisili Lapangan
              </label>
<div class="relative">
<select class="custom-input w-full px-4 text-body-md font-body-md text-on-surface appearance-none pr-10" id="domisili">
<option selected="" value="jaksel">Jakarta Selatan</option>
<option value="jakbar">Jakarta Barat</option>
<option value="jakpus">Jakarta Pusat</option>
<option value="jaktim">Jakarta Timur</option>
<option value="tangsel">Tangerang Selatan</option>
<option value="bekasi">Bekasi &amp; Sekitarnya</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-3 pointer-events-none text-outline-variant" data-icon="unfold_more">unfold_more</span>
</div>
</div>
<!-- Cabang Olahraga Favorit (Multi-select tags) -->
<div class="md:col-span-2 space-y-2">
<label class="block text-label-md font-label-md text-on-surface font-semibold">
                Cabang Olahraga Favorit (Rekomendasi Algoritma Jadwal)
              </label>
<div class="flex flex-wrap gap-2.5">
<!-- Active Sports Chips -->
<button class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-primary-container text-surface text-label-md font-label-md shadow-sm" type="button">
<span class="material-symbols-outlined text-base" data-icon="sports_tennis">sports_tennis</span>
<span>Badminton</span>
<span class="material-symbols-outlined text-xs hover:opacity-80" data-icon="close">close</span>
</button>
<button class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-primary-container text-surface text-label-md font-label-md shadow-sm" type="button">
<span class="material-symbols-outlined text-base" data-icon="sports_soccer">sports_soccer</span>
<span>Futsal</span>
<span class="material-symbols-outlined text-xs hover:opacity-80" data-icon="close">close</span>
</button>
<button class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-primary-container text-surface text-label-md font-label-md shadow-sm" type="button">
<span class="material-symbols-outlined text-base" data-icon="sports_soccer">sports_soccer</span>
<span>Mini Soccer</span>
<span class="material-symbols-outlined text-xs hover:opacity-80" data-icon="close">close</span>
</button>
<!-- Inactive Chip for addition -->
<button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-surface border border-outline-variant text-on-surface-variant hover:border-primary text-label-md font-label-md transition-colors" type="button">
<span class="material-symbols-outlined text-base" data-icon="sports_basketball">sports_basketball</span>
<span>+ Bola Basket</span>
</button>
<button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-surface border border-outline-variant text-on-surface-variant hover:border-primary text-label-md font-label-md transition-colors" type="button">
<span class="material-symbols-outlined text-base" data-icon="sports_volleyball">sports_volleyball</span>
<span>+ Tenis Meja</span>
</button>
</div>
</div>
<!-- Ukuran Jersey -->
<div class="space-y-1.5">
<label class="block text-label-md font-label-md text-on-surface font-semibold" for="jersey">
                Ukuran Jersey (Merchandise Loyalty &amp; Tournament Pack)
              </label>
<div class="relative">
<select class="custom-input w-full px-4 text-body-md font-body-md text-on-surface appearance-none pr-10" id="jersey">
<option value="S">S (Small - Lebar Dada 48cm)</option>
<option value="M">M (Medium - Lebar Dada 50cm)</option>
<option selected="" value="L">L (Large - Lebar Dada 52cm)</option>
<option value="XL">XL (Extra Large - Lebar Dada 54cm)</option>
<option value="XXL">XXL (Double XL - Lebar Dada 57cm)</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-3 pointer-events-none text-outline-variant" data-icon="expand_more">expand_more</span>
</div>
</div>
<!-- Preferensi Jam Bermain -->
<div class="space-y-1.5">
<label class="block text-label-md font-label-md text-on-surface font-semibold">
                Waktu Favorit Sparring / Main
              </label>
<div class="grid grid-cols-2 gap-2">
<div class="px-3 py-3 rounded-xl border border-primary-container bg-primary/5 flex items-center justify-between">
<span class="text-label-md font-label-md text-primary font-bold">Malam (19.00 - 23.00)</span>
<span class="material-symbols-outlined text-primary text-base" data-icon="check_circle">check_circle</span>
</div>
<div class="px-3 py-3 rounded-xl border border-outline-variant bg-surface text-on-surface-variant flex items-center justify-between">
<span class="text-label-md font-label-md">Akhir Pekan Pagi</span>
<span class="material-symbols-outlined text-outline-variant text-base" data-icon="radio_button_unchecked">radio_button_unchecked</span>
</div>
</div>
</div>
<button type="submit" class="bg-primary-container text-white rounded-xl px-5 py-3 font-bold">Simpan Perubahan</button></form>
</div>
<!-- ================= SECTION 2: PREFERENSI KOMUNITAS & TIM ================= -->
<div class="tactile-card rounded-2xl p-6 md:p-8 tactile-shadow space-y-6" id="tim">
<div class="flex items-center justify-between pb-4 border-b border-surface-container-high">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
<span class="material-symbols-outlined text-xl" data-icon="groups">groups</span>
</div>
<h3 class="text-title-lg font-title-lg text-on-surface">Preferensi Komunitas &amp; Tim Olahraga</h3>
</div>
<span class="px-2.5 py-1 rounded-full text-label-sm font-label-sm bg-surface-container font-semibold text-on-surface-variant">
              Sparring Ready
            </span>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="space-y-1.5">
<label class="block text-label-md font-label-md text-on-surface font-semibold">
                Nama Tim / Komunitas Utama
              </label>
<div class="relative">
<input class="custom-input w-full px-4 text-body-md font-body-md text-on-surface pr-10" type="text" value="Garuda Badminton Club"/>
<span class="material-symbols-outlined absolute right-3 top-3 text-secondary" data-icon="sports_club">sports_and_outdoors</span>
</div>
<p class="text-body-sm font-body-sm text-on-surface-variant">Terdaftar di 14 turnamen internal &amp; liga komunitas Jabodetabek.</p>
</div>
<div class="space-y-1.5">
<label class="block text-label-md font-label-md text-on-surface font-semibold">
                Peran dalam Tim
              </label>
<div class="relative">
<select class="custom-input w-full px-4 text-body-md font-body-md text-on-surface appearance-none pr-10">
<option selected="">Kapten / Manajer Booking</option>
<option>Bendahara Kas Tim</option>
<option>Anggota / Pemain Inti</option>
<option>Pemain Cadangan</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-3 pointer-events-none text-outline-variant" data-icon="expand_more">expand_more</span>
</div>
</div>
</div>
<!-- Digital Scoreboard Arena Toggle -->
<div class="flex items-center justify-between p-4 rounded-xl bg-surface-container-low border border-surface-variant">
<div class="flex items-start gap-3">
<span class="p-2 rounded-lg bg-surface text-primary border border-outline-variant">
<span class="material-symbols-outlined text-xl" data-icon="tv">tv</span>
</span>
<div>
<span class="text-title-md font-title-md text-on-surface font-bold block">Tampilkan nama tim pada scoreboard digital arena</span>
<p class="text-body-sm font-body-sm text-on-surface-variant">Nama "Garuda Badminton Club" akan otomatis ditayangkan di monitor lapangan mitra saat sesi berlangsung.</p>
</div>
</div>
<label class="relative inline-flex items-center cursor-pointer ml-4">
<input checked="" class="sr-only switch-toggle" type="checkbox"/>
<div class="w-12 h-6 bg-outline-variant rounded-full transition-colors duration-200 switch-bg"></div>
<div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 shadow switch-dot"></div>
</label>
</div>
</div>
<!-- ================= SECTION 3: METODE PEMBAYARAN TERSIMPAN ================= -->
<div class="tactile-card rounded-2xl p-6 md:p-8 tactile-shadow space-y-6" id="pembayaran">
<div class="flex items-center justify-between pb-4 border-b border-surface-container-high">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-xl" data-icon="payments">payments</span>
</div>
<h3 class="text-title-lg font-title-lg text-on-surface">Metode Pembayaran Tersimpan (Quick Checkout)</h3>
</div>
<span class="inline-flex items-center gap-1 text-label-sm font-label-sm text-primary font-semibold">
<span class="material-symbols-outlined text-xs" data-icon="lock">lock</span>
              Midtrans 1-Click Vault
            </span>
</div>
<p class="text-body-sm font-body-sm text-on-surface-variant -mt-2">
            Metode ini akan langsung otomatis dipilih untuk penguncian slot lapangan saat "Flash Booking" tanpa perlu input ulang.
          </p>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<!-- Saved Card 1: BCA Virtual Account -->
<div class="p-4 rounded-xl border-2 border-primary bg-surface flex flex-col justify-between relative shadow-sm">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2.5">
<div class="w-12 h-8 rounded-lg bg-surface-container-high flex items-center justify-center font-extrabold text-blue-800 text-xs tracking-wider border border-outline-variant">
                    BCA
                  </div>
<div>
<h5 class="text-title-md font-title-md font-bold text-on-surface">BCA Virtual Account</h5>
<span class="text-body-sm font-body-sm text-on-surface-variant font-mono">**** **** 8829</span>
</div>
</div>
<span class="px-2 py-0.5 rounded text-label-sm font-label-sm bg-primary-container text-surface font-bold">
                  Favorit
                </span>
</div>
<div class="mt-4 pt-3 border-t border-surface-container-high flex items-center justify-between text-body-sm font-body-sm">
<span class="text-primary font-semibold flex items-center gap-1">
<span class="material-symbols-outlined text-sm" data-icon="bolt">bolt</span>
                  Auto Confirmation Active
                </span>
<button class="text-on-surface-variant hover:text-error text-label-sm font-label-sm" type="button">Hapus</button>
</div>
</div>
<!-- Saved Card 2: GoPay -->
<div class="p-4 rounded-xl border border-outline-variant bg-surface flex flex-col justify-between">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2.5">
<div class="w-12 h-8 rounded-lg bg-surface-container-high flex items-center justify-center font-bold text-cyan-700 text-xs tracking-tight border border-outline-variant">
                    GOPAY
                  </div>
<div>
<h5 class="text-title-md font-title-md font-bold text-on-surface">GoPay Wallet</h5>
<span class="text-body-sm font-body-sm text-on-surface-variant">Terhubung: 0812-8899-2341</span>
</div>
</div>
<span class="px-2 py-0.5 rounded text-label-sm font-label-sm bg-surface-container text-on-surface-variant">
                  Terhubung
                </span>
</div>
<div class="mt-4 pt-3 border-t border-surface-container-high flex items-center justify-between text-body-sm font-body-sm">
<span class="text-on-surface-variant">Saldo otomatis terverifikasi</span>
<button class="text-on-surface-variant hover:text-error text-label-sm font-label-sm" type="button">Putuskan</button>
</div>
</div>
</div>
<!-- Add Method Button -->
<button class="w-full py-3 px-4 rounded-xl border-2 border-dashed border-outline-variant hover:border-primary bg-surface-container-low hover:bg-surface transition-all flex items-center justify-center gap-2 text-primary font-bold text-label-lg font-label-lg active:scale-98" type="button">
<span class="material-symbols-outlined text-lg" data-icon="add_circle">add_circle</span>
<span>Tambah Metode Pembayaran Baru (QRIS / Kartu Kredit / E-Wallet)</span>
</button>
</div>
<!-- ================= SECTION 4: PREFERENSI NOTIFIKASI & ALERTS ================= -->
<div class="tactile-card rounded-2xl p-6 md:p-8 tactile-shadow space-y-6" id="notifikasi">
<div class="flex items-center justify-between pb-4 border-b border-surface-container-high">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-lg bg-on-tertiary-container/20 flex items-center justify-center text-tertiary-container">
<span class="material-symbols-outlined text-xl" data-icon="notifications_active">notifications_active</span>
</div>
<h3 class="text-title-lg font-title-lg text-on-surface">Preferensi Notifikasi &amp; Peringatan Booking</h3>
</div>
<span class="text-label-sm font-label-sm text-on-surface-variant">WhatsApp + Email Push</span>
</div>
<div class="space-y-4">
<!-- Item 1: WhatsApp Barcode -->
<label class="flex items-start gap-3.5 p-3.5 rounded-xl hover:bg-surface-container-low transition-colors cursor-pointer border border-transparent hover:border-outline-variant">
<input checked="" class="mt-1 w-5 h-5 rounded text-primary-container focus:ring-primary border-outline-variant" type="checkbox"/>
<div class="flex-1">
<div class="flex items-center gap-2">
<span class="text-title-md font-title-md font-bold text-on-surface">Notifikasi Tiket &amp; QR Barcode via WhatsApp</span>
<span class="px-2 py-0.2 bg-primary-fixed text-on-primary-fixed rounded text-label-sm font-label-sm font-semibold">Rekomendasi</span>
</div>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
                  Dapatkan e-ticket langsung di chat WhatsApp dalam 5 detik setelah pembayaran sukses untuk ditunjukkan ke penjaga arena.
                </p>
</div>
</label>
<!-- Item 2: Pengingat H-2 Jam -->
<label class="flex items-start gap-3.5 p-3.5 rounded-xl hover:bg-surface-container-low transition-colors cursor-pointer border border-transparent hover:border-outline-variant">
<input checked="" class="mt-1 w-5 h-5 rounded text-primary-container focus:ring-primary border-outline-variant" type="checkbox"/>
<div class="flex-1">
<span class="text-title-md font-title-md font-bold text-on-surface">Pengingat H-2 Jam Sebelum Pertandingan Dimulai</span>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
                  Peringatan otomatis berisi nomor lapangan, petunjuk navigasi Maps, dan prakiraan cuaca lapangan outdoor.
                </p>
</div>
</label>
<!-- Item 3: Waitlist Terbuka Kilat -->
<label class="flex items-start gap-3.5 p-3.5 rounded-xl hover:bg-surface-container-low transition-colors cursor-pointer border border-transparent hover:border-outline-variant">
<input checked="" class="mt-1 w-5 h-5 rounded text-primary-container focus:ring-primary border-outline-variant" type="checkbox"/>
<div class="flex-1">
<div class="flex items-center gap-2">
<span class="text-title-md font-title-md font-bold text-on-surface">Peringatan Slot Waitlist Terbuka Kilat (Flash Slot)</span>
<span class="px-2 py-0.2 bg-secondary-fixed text-on-secondary-fixed rounded text-label-sm font-label-sm font-semibold">Prioritas Member</span>
</div>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
                  Kirim sinyal instan saat ada pengguna lain yang membatalkan booking di jam prime-time favorit Anda.
                </p>
</div>
</label>
<!-- Item 4: Promo Khusus -->
<label class="flex items-start gap-3.5 p-3.5 rounded-xl hover:bg-surface-container-low transition-colors cursor-pointer border border-transparent hover:border-outline-variant">
<input checked="" class="mt-1 w-5 h-5 rounded text-primary-container focus:ring-primary border-outline-variant" type="checkbox"/>
<div class="flex-1">
<span class="text-title-md font-title-md font-bold text-on-surface">Info Promo Khusus &amp; Diskon Lapangan Langganan</span>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
                  Voucher diskon sewa bulanan dan info turnamen lokal dari 3 arena yang paling sering Anda pesan.
                </p>
</div>
</label>
</div>
</div>
<!-- ================= SAVE CHANGES ACTION DOCK ================= -->
<div class="tactile-card rounded-2xl p-5 md:p-6 tactile-shadow flex flex-col sm:flex-row items-center justify-between gap-4 bg-surface-container-lowest">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary text-2xl" data-icon="published_with_changes">published_with_changes</span>
<p class="text-body-sm font-body-sm text-on-surface-variant">
              Pastikan nomor WhatsApp dan email aktif untuk kelancaran penerimaan e-tiket digital.
            </p>
</div>
<div class="flex items-center gap-3 w-full sm:w-auto">
<button class="w-1/2 sm:w-auto px-6 py-3 rounded-xl border border-outline-variant text-on-surface-variant hover:text-on-surface hover:bg-surface-container text-label-lg font-label-lg transition-colors active:scale-98" type="button">
              Batal
            </button>
<button class="w-1/2 sm:w-auto px-8 py-3 rounded-xl bg-primary-container hover:bg-surface-tint text-surface font-bold text-label-lg font-label-lg shadow-sm transition-all duration-150 active:scale-98 flex items-center justify-center gap-2" type="button">
<span class="material-symbols-outlined text-base" data-icon="save">save</span>
<span>Simpan Perubahan Profil</span>
</button>
</div>
</div>
<!-- ================= SECURITY CALLOUT FOOTNOTE ================= -->
<div class="rounded-xl p-4 bg-surface-container border border-outline-variant flex items-center gap-3.5 text-on-surface-variant">
<span class="material-symbols-outlined text-primary-container text-2xl" data-icon="verified_user">verified_user</span>
<p class="text-body-sm font-body-sm">
<span class="font-semibold text-on-surface">Keamanan Terjamin:</span> Akun Anda dilindungi enkripsi SSL 256-bit dan terhubung dengan Payment Gateway Midtrans resmi berlisensi Bank Indonesia.
          </p>
</div>
</section>
</div>
</main><div class="max-w-7xl mx-auto px-6 md:px-12 pb-8"><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="px-5 py-3 rounded-xl border border-secondary text-secondary font-bold">Keluar Akun</button></form></div>
@endsection
