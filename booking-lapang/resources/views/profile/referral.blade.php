@extends('layouts.frontend')
@section('title','Program Referral - Booking Lapang')
@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-6 md:px-12 py-6 flex flex-col gap-8">
<!-- HERO SECTION & REFERRAL INVITATION CARD -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 lg:p-8 shadow-court-card relative overflow-hidden">
<!-- Decorative court line vector simulation -->
<div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-5 pointer-events-none overflow-hidden hidden md:block">
<div class="border-4 border-primary rounded-full w-96 h-96 absolute -top-16 -right-20"></div>
<div class="border-2 border-primary w-full h-full absolute top-0 left-12"></div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
<!-- Left Hero Copy -->
<div class="lg:col-span-7 flex flex-col gap-4">
<div class="inline-flex items-center gap-2 px-3 py-1 bg-surface-container-low border border-outline-variant rounded-full w-fit">
<span class="material-symbols-outlined text-secondary text-base" data-icon="redeem">redeem</span>
<span class="text-label-sm font-label-sm text-primary uppercase tracking-wider">Program Komunitas 2025</span>
</div>
<h1 class="text-headline-lg font-headline-lg text-primary tracking-tight">
            Ajak Teman & Komunitas Mabar, Dapatkan Saldo Sewa & Poin Loyalitas!
          </h1>
<p class="text-body-lg font-body-lg text-on-surface-variant leading-relaxed">
            Temanmu dapat diskon <strong class="text-primary font-bold">Rp 25.000</strong> untuk booking pertama, kamu dapat bonus <strong class="text-primary font-bold">Rp 25.000 + 200 Poin</strong> setelah mereka selesai main di lapangan pilihan.
          </p>
<!-- Highlights Pills -->
<div class="flex flex-wrap items-center gap-3 pt-2">
<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container text-primary font-label-md text-label-md">
<span class="material-symbols-outlined text-primary text-base" data-icon="verified">verified</span>
              Tanpa Batas Undangan
            </span>
<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container text-primary font-label-md text-label-md">
<span class="material-symbols-outlined text-primary text-base" data-icon="bolt">bolt</span>
              Kredit Reward Instan
            </span>
<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container text-primary font-label-md text-label-md">
<span class="material-symbols-outlined text-primary text-base" data-icon="emoji_events">emoji_events</span>
              Piala Musim Komunitas
            </span>
</div>
</div>
<!-- Right Referral Action Box -->
<div class="lg:col-span-5 bg-surface-container-low p-6 rounded-2xl border border-outline-variant flex flex-col gap-5">
<div class="flex items-center justify-between">
<div class="flex flex-col">
<span class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider">Kode Referral Kamu</span>
<span class="text-label-md font-label-md text-primary font-bold">Aktif & Siap Dibagikan</span>
</div>
<span class="material-symbols-outlined text-on-primary-fixed-variant" data-icon="share">share</span>
</div>
<!-- Code Box -->
<div class="bg-surface-container-lowest border-2 border-dashed border-primary-container p-4 rounded-xl flex items-center justify-between">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-secondary text-2xl" data-icon="loyalty">loyalty</span>
<span class="text-headline-sm font-headline-sm text-primary tracking-wider font-extrabold" id="referralCode">{{ $kodeReferral }}</span>
</div>
<button class="bg-primary-container text-white px-4 py-2 rounded-xl text-label-md font-label-lg flex items-center gap-1.5 hover:bg-on-primary-fixed-variant active:scale-95 transition-all" id="copyBtn" onclick="copyCode()">
<span class="material-symbols-outlined text-sm" data-icon="content_copy">content_copy</span>
<span>Salin</span>
</button>
</div>
<!-- Action Buttons & QR Code integration -->
<div class="flex items-center gap-4">
<!-- Share WhatsApp Direct -->
<a class="flex-1 bg-[#25D366] text-white py-3 px-4 rounded-xl font-label-lg text-label-lg flex items-center justify-center gap-2 hover:opacity-95 shadow-sm active:scale-98 transition-all" href="https://wa.me/?text=Yuk%20mabar%20bareng%20aku%20di%20Booking%20Lapang!%20Gunakan%20kode%20referral%20{{ $kodeReferral }}%20untuk%20dapat%20diskon%20Rp%2025.000%20booking%20pertama" target="_blank">
<span class="material-symbols-outlined text-lg" data-icon="chat">chat</span>
<span>Bagikan ke WhatsApp</span>
</a>
<!-- QR Code Mini Trigger Box -->
<div class="relative group cursor-pointer">
<div class="w-12 h-12 rounded-xl bg-surface-container-lowest border border-outline-variant flex items-center justify-center hover:border-primary transition-colors">
<span class="material-symbols-outlined text-primary text-2xl" data-icon="qr_code_2">qr_code_2</span>
</div>
<!-- Tooltip QR popover -->
<div class="absolute right-0 bottom-14 hidden group-hover:flex flex-col items-center bg-white p-3 rounded-xl border border-outline-variant shadow-court-floating z-30 w-44">
<img class="w-32 h-32 object-contain rounded-lg border border-outline-variant" data-alt="Digital square QR code graphic formatted for quick mobile camera scan on modern paper textured background with subtle green corner targeting brackets." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDq_hG0T5dggsGuCADzsOnQ-Sy6doG1JaO0ckHiqE9_s71CVQ4G37rpbhikA7hl3t0zx2BZObs2klFgRL8x8Oty7zWnn9fxFNASW1gg0D5RWxsJk00MZu1jSdI_3lofqRgcsKekmjXP0kQ45Fnlzf3f4XIyzImhgHoJPgQq9tEX649mN8Uy7gNXK12xWQPir2QOZ-Dnfljx_X0JqzaQkDW6pnV3_Y7bLBwO2dNEsn-1E25txOh9qvA"/>
<span class="text-label-sm font-label-sm text-primary mt-2 font-bold text-center">Scan untuk Gabung Mabar</span>
</div>
</div>
</div>
<!-- Direct Link Preview -->
<div class="flex items-center justify-between text-body-sm font-body-sm text-on-surface-variant pt-1 border-t border-outline-variant">
<span class="truncate max-w-[240px]">bookinglapang.id/ref/{{ $kodeReferral }}</span>
<button class="text-primary font-semibold hover:underline text-label-md font-label-md" onclick="copyLink()">Salin Tautan</button>
</div>
</div>
</div>
</section>
<!-- ==================== 4 METRICS STATS CARDS ==================== -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
<!-- Stat 1: Teman Diundang -->
<div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant shadow-court-card flex items-start gap-4">
<div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary shrink-0">
<span class="material-symbols-outlined text-2xl" data-icon="group_add">group_add</span>
</div>
<div class="flex flex-col">
<span class="text-body-sm font-body-sm text-on-surface-variant">Total Teman Diundang</span>
<span class="text-headline-md font-headline-md text-on-surface font-extrabold">18 <span class="text-title-md font-title-md font-medium text-on-surface-variant">Orang</span></span>
<span class="text-body-sm font-body-sm text-primary mt-1 flex items-center gap-1 font-medium">
<span class="material-symbols-outlined text-xs" data-icon="trending_up">trending_up</span> +3 minggu ini
          </span>
</div>
</div>
<!-- Stat 2: Berhasil Booking Pertama -->
<div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant shadow-court-card flex items-start gap-4">
<div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary-container shrink-0">
<span class="material-symbols-outlined text-2xl" data-icon="event_available">event_available</span>
</div>
<div class="flex flex-col">
<span class="text-body-sm font-body-sm text-on-surface-variant">Berhasil Booking Pertama</span>
<span class="text-headline-md font-headline-md text-on-surface font-extrabold">12 <span class="text-title-md font-title-md font-medium text-on-surface-variant">Teman</span></span>
<span class="text-body-sm font-body-sm text-on-surface-variant mt-1">Konversi 66.7% mabar</span>
</div>
</div>
<!-- Stat 3: Total Reward Terkumpul -->
<div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant shadow-court-card flex items-start gap-4">
<div class="w-12 h-12 rounded-xl bg-secondary-fixed flex items-center justify-center text-secondary shrink-0">
<span class="material-symbols-outlined text-2xl" data-icon="account_balance_wallet">account_balance_wallet</span>
</div>
<div class="flex flex-col">
<span class="text-body-sm font-body-sm text-on-surface-variant">Total Reward Terkumpul</span>
<span class="text-headline-md font-headline-md text-secondary font-extrabold">Rp 300.000</span>
<span class="text-body-sm font-body-sm text-on-surface-variant mt-1">Setara saldo voucher aktif</span>
</div>
</div>
<!-- Stat 4: Poin Referral -->
<div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant shadow-court-card flex items-start gap-4">
<div class="w-12 h-12 rounded-xl bg-tertiary-fixed flex items-center justify-center text-tertiary-container shrink-0">
<span class="material-symbols-outlined text-2xl" data-icon="stars">stars</span>
</div>
<div class="flex flex-col">
<span class="text-body-sm font-body-sm text-on-surface-variant">Poin Referral</span>
<span class="text-headline-md font-headline-md text-on-surface font-extrabold">+2.400 <span class="text-title-md font-title-md font-medium text-on-surface-variant">Poin</span></span>
<span class="text-body-sm font-body-sm text-on-surface-variant mt-1">Siap ditukar merch / slot</span>
</div>
</div>
</section>
<!-- ==================== TWO-COLUMN CORE CONTENT ==================== -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
<!-- LEFT COLUMN: Status Undangan & Aktivitas Terbaru (7 Cols) -->
<section class="lg:col-span-7 flex flex-col gap-6">
<div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-court-card flex flex-col gap-5">
<div class="flex items-center justify-between border-b border-outline-variant pb-4">
<div>
<h2 class="text-title-lg font-title-lg text-primary tracking-tight">Status Undangan Teman & Aktivitas Terbaru</h2>
<p class="text-body-sm font-body-sm text-on-surface-variant">Pantau progres temanmu mulai dari mendaftar hingga bermain di venue.</p>
</div>
<span class="material-symbols-outlined text-outline" data-icon="history">history</span>
</div>
<!-- Invited Friends List -->
<div class="flex flex-col divide-y divide-outline-variant">
<!-- Friend 1: Rian Ardiansyah -->
<div class="py-4 flex items-center justify-between gap-4 first:pt-1">
<div class="flex items-center gap-3.5">
<div class="w-11 h-11 rounded-full bg-primary-container text-white font-bold flex items-center justify-center text-label-lg shrink-0">
                  RA
                </div>
<div class="flex flex-col">
<div class="flex items-center gap-2">
<span class="text-title-md font-title-md text-on-surface">Rian Ardiansyah</span>
<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container text-on-primary-fixed-variant text-label-sm font-label-sm">
<span class="material-symbols-outlined text-xs" data-icon="sports_tennis">sports_tennis</span> Badminton
                    </span>
</div>
<span class="text-body-sm font-body-sm text-on-surface-variant">Selesai Main di Smash Hub Arena, BSD</span>
<span class="text-body-sm font-body-sm text-on-surface-variant text-xs">26 Okt 2025 • 19:30 WIB</span>
</div>
</div>
<div class="flex flex-col items-end shrink-0">
<span class="inline-flex items-center gap-1 text-label-md font-label-md text-primary bg-surface-container px-2.5 py-1 rounded-lg">
<span class="material-symbols-outlined text-sm text-primary" data-icon="check_circle">check_circle</span>
                  Bonus Dikreditkan
                </span>
<span class="text-label-md font-label-md text-primary font-bold mt-1">+Rp 25.000 • +200 Poin</span>
</div>
</div>
<!-- Friend 2: Bima Putra -->
<div class="py-4 flex items-center justify-between gap-4">
<div class="flex items-center gap-3.5">
<div class="w-11 h-11 rounded-full bg-surface-container text-primary font-bold flex items-center justify-center text-label-lg shrink-0">
                  BP
                </div>
<div class="flex flex-col">
<div class="flex items-center gap-2">
<span class="text-title-md font-title-md text-on-surface">Bima Putra</span>
<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container text-on-primary-fixed-variant text-label-sm font-label-sm">
<span class="material-symbols-outlined text-xs" data-icon="sports_soccer">sports_soccer</span> Futsal
                    </span>
</div>
<span class="text-body-sm font-body-sm text-on-surface-variant">Selesai Main di Viva Futsal Senayan</span>
<span class="text-body-sm font-body-sm text-on-surface-variant text-xs">25 Okt 2025 • 21:00 WIB</span>
</div>
</div>
<div class="flex flex-col items-end shrink-0">
<span class="inline-flex items-center gap-1 text-label-md font-label-md text-primary bg-surface-container px-2.5 py-1 rounded-lg">
<span class="material-symbols-outlined text-sm text-primary" data-icon="check_circle">check_circle</span>
                  Bonus Dikreditkan
                </span>
<span class="text-label-md font-label-md text-primary font-bold mt-1">+Rp 25.000 • +200 Poin</span>
</div>
</div>
<!-- Friend 3: Farhan Maulana -->
<div class="py-4 flex items-center justify-between gap-4">
<div class="flex items-center gap-3.5">
<div class="w-11 h-11 rounded-full bg-surface-variant text-on-surface-variant font-bold flex items-center justify-center text-label-lg shrink-0">
                  FM
                </div>
<div class="flex flex-col">
<div class="flex items-center gap-2">
<span class="text-title-md font-title-md text-on-surface">Farhan Maulana</span>
</div>
<span class="text-body-sm font-body-sm text-on-surface-variant">Sudah Daftar Akun Booking Lapang</span>
<span class="text-body-sm font-body-sm text-on-surface-variant text-xs">Menunggu booking perdana untuk klaim reward</span>
</div>
</div>
<div class="flex flex-col items-end shrink-0">
<span class="inline-flex items-center gap-1 text-label-md font-label-md text-on-surface-variant bg-surface-container-high px-2.5 py-1 rounded-lg">
<span class="material-symbols-outlined text-sm" data-icon="hourglass_empty">hourglass_empty</span>
                  Menunggu Booking
                </span>
<button class="text-body-sm font-body-sm text-primary font-semibold hover:underline mt-1">Ingatkan Main</button>
</div>
</div>
<!-- Friend 4: Kevin Sanjaya -->
<div class="py-4 flex items-center justify-between gap-4">
<div class="flex items-center gap-3.5">
<div class="w-11 h-11 rounded-full bg-surface-variant text-on-surface-variant font-bold flex items-center justify-center text-label-lg shrink-0">
                  KS
                </div>
<div class="flex flex-col">
<div class="flex items-center gap-2">
<span class="text-title-md font-title-md text-on-surface">Kevin Sanjaya</span>
<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container text-on-primary-fixed-variant text-label-sm font-label-sm">
<span class="material-symbols-outlined text-xs" data-icon="sports_tennis">sports_tennis</span> Badminton
                    </span>
</div>
<span class="text-body-sm font-body-sm text-on-surface-variant">Kode Digunakan • Lapangan Champion Grogol</span>
<span class="text-body-sm font-body-sm text-secondary font-semibold text-xs">Jadwal Main Besok (28 Okt, 20:00 WIB)</span>
</div>
</div>
<div class="flex flex-col items-end shrink-0">
<span class="inline-flex items-center gap-1 text-label-md font-label-md text-secondary bg-secondary-fixed px-2.5 py-1 rounded-lg">
<span class="material-symbols-outlined text-sm" data-icon="schedule">schedule</span>
                  Terjadwal Main
                </span>
<span class="text-body-sm font-body-sm text-on-surface-variant mt-1">Pending reward Rp 25k</span>
</div>
</div>
<!-- Friend 5: Tomi Kurniawan -->
<div class="py-4 flex items-center justify-between gap-4 last:pb-1">
<div class="flex items-center gap-3.5">
<div class="w-11 h-11 rounded-full bg-surface-container-high text-on-surface-variant font-bold flex items-center justify-center text-label-lg shrink-0">
                  TK
                </div>
<div class="flex flex-col">
<span class="text-title-md font-title-md text-on-surface">Tomi Kurniawan</span>
<span class="text-body-sm font-body-sm text-on-surface-variant">Undangan Terkirim via WhatsApp</span>
<span class="text-body-sm font-body-sm text-on-surface-variant text-xs">Belum menyelesaikan pendaftaran</span>
</div>
</div>
<div class="flex flex-col items-end shrink-0">
<span class="inline-flex items-center gap-1 text-label-md font-label-md text-outline bg-surface-container px-2.5 py-1 rounded-lg">
<span class="material-symbols-outlined text-sm" data-icon="outgoing_mail">outgoing_mail</span>
                  Terkirim
                </span>
<button class="text-body-sm font-body-sm text-primary font-semibold hover:underline mt-1">Kirim Ulang</button>
</div>
</div>
</div>
<!-- Bottom Pagination / CTA inside Card -->
<div class="pt-3 border-t border-outline-variant flex items-center justify-between text-body-sm font-body-sm">
<span class="text-on-surface-variant">Menampilkan 5 dari 18 teman diundang</span>
<button class="text-primary font-bold hover:underline inline-flex items-center gap-1">
              Lihat Seluruh Aktivitas
              <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
</button>
</div>
</div>
</section>
<!-- RIGHT COLUMN: Leaderboard Komunitas & Top Referrer (5 Cols) -->
<section class="lg:col-span-5 flex flex-col gap-6">
<div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-court-card flex flex-col gap-5">
<!-- Leaderboard Header & Gamification Banner -->
<div>
<div class="flex items-center justify-between mb-2">
<h2 class="text-title-lg font-title-lg text-primary tracking-tight">Leaderboard Komunitas</h2>
<span class="material-symbols-outlined text-tertiary-fixed-dim text-2xl" data-icon="trophy">trophy</span>
</div>
<!-- Grand Prize Announcement Box -->
<div class="bg-gradient-to-r from-primary-container to-primary text-white p-4 rounded-xl flex items-center gap-3">
<span class="material-symbols-outlined text-3xl text-tertiary-fixed shrink-0" data-icon="workspace_premium">workspace_premium</span>
<div class="flex flex-col">
<span class="text-label-sm font-label-sm text-on-primary-container uppercase tracking-wider">Musim Oktober 2025</span>
<span class="text-body-sm font-body-sm font-bold text-white leading-tight">Hadiah Grand Prize: Sewa Lapangan Gratis 1 Bulan untuk Top Squad!</span>
</div>
</div>
</div>
<!-- Leaderboard Ranked List -->
<div class="flex flex-col gap-2.5">
<!-- Rank #1 -->
<div class="p-3.5 rounded-xl bg-surface-container-low border border-tertiary-fixed flex items-center justify-between relative overflow-hidden">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-tertiary-fixed text-tertiary-container flex items-center justify-center font-bold text-label-md shrink-0 shadow-sm">
                  1
                </div>
<div class="flex flex-col">
<div class="flex items-center gap-1.5">
<span class="text-title-md font-title-md text-on-surface font-bold">Garuda BC Jakarta</span>
<span class="material-symbols-outlined text-tertiary-container text-base" data-icon="verified">verified</span>
</div>
<span class="text-body-sm font-body-sm text-primary font-semibold">Hadiah: Voucher Rp 1.500.000 + Piala</span>
</div>
</div>
<div class="flex flex-col items-end shrink-0">
<span class="text-title-lg font-title-lg text-primary font-extrabold">42</span>
<span class="text-label-sm font-label-sm text-on-surface-variant">Teman</span>
</div>
</div>
<!-- Rank #2 -->
<div class="p-3.5 rounded-xl bg-surface-container-lowest border border-outline-variant flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-surface-container-high text-on-surface font-bold flex items-center justify-center text-label-md shrink-0">
                  2
                </div>
<div class="flex flex-col">
<span class="text-title-md font-title-md text-on-surface font-bold">Futsal Kuy Community</span>
<span class="text-body-sm font-body-sm text-on-surface-variant">Hadiah: Voucher Rp 1.000.000</span>
</div>
</div>
<div class="flex flex-col items-end shrink-0">
<span class="text-title-lg font-title-lg text-on-surface font-extrabold">35</span>
<span class="text-label-sm font-label-sm text-on-surface-variant">Teman</span>
</div>
</div>
<!-- Rank #3 (Current User Highlighted) -->
<div class="p-3.5 rounded-xl bg-surface-container border-2 border-primary-container flex items-center justify-between relative shadow-sm">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-primary-container text-white flex items-center justify-center font-bold text-label-md shrink-0">
                  3
                </div>
<div class="flex flex-col">
<div class="flex items-center gap-2">
<span class="text-title-md font-title-md text-primary font-bold">Dimas Satria (Kamu)</span>
<span class="px-2 py-0.5 bg-primary-container text-white rounded text-label-sm font-label-sm">Posisi Kamu</span>
</div>
<span class="text-body-sm font-body-sm text-primary font-semibold">Hadiah: Voucher Rp 500.000 + Top Captain</span>
</div>
</div>
<div class="flex flex-col items-end shrink-0">
<span class="text-title-lg font-title-lg text-primary font-extrabold">18</span>
<span class="text-label-sm font-label-sm text-on-surface-variant">Teman</span>
</div>
</div>
<!-- Rank #4 -->
<div class="p-3.5 rounded-xl bg-surface-container-lowest border border-outline-variant flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-surface-container-low text-on-surface font-bold flex items-center justify-center text-label-md shrink-0">
                  4
                </div>
<div class="flex flex-col">
<span class="text-body-md font-body-md text-on-surface font-bold">Smash Squad Tangerang</span>
<span class="text-body-sm font-body-sm text-on-surface-variant">Komunitas Bulutangkis</span>
</div>
</div>
<div class="flex flex-col items-end shrink-0">
<span class="text-title-md font-title-md text-on-surface font-bold">15</span>
<span class="text-label-sm font-label-sm text-on-surface-variant">Teman</span>
</div>
</div>
<!-- Rank #5 -->
<div class="p-3.5 rounded-xl bg-surface-container-lowest border border-outline-variant flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-surface-container-low text-on-surface font-bold flex items-center justify-center text-label-md shrink-0">
                  5
                </div>
<div class="flex flex-col">
<span class="text-body-md font-body-md text-on-surface font-bold">Basket Rawamangun</span>
<span class="text-body-sm font-body-sm text-on-surface-variant">Klub 3x3 Jakarta</span>
</div>
</div>
<div class="flex flex-col items-end shrink-0">
<span class="text-title-md font-title-md text-on-surface font-bold">11</span>
<span class="text-label-sm font-label-sm text-on-surface-variant">Teman</span>
</div>
</div>
</div>
<!-- Bottom Motivational Note -->
<div class="bg-surface-container-low p-3.5 rounded-xl border border-outline-variant text-center">
<p class="text-body-sm font-body-sm text-on-surface-variant">
              Butuh <strong class="text-primary">17 teman lagi</strong> untuk menyalip posisi #2 dan merebut voucher Rp 1.000.000! Reset leaderboard dalam 4 hari.
            </p>
</div>
</div>
</section>
</div>
<!-- ==================== CARA KERJA PROGRAM REFERRAL (3-STEP) ==================== -->
<section class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant shadow-court-card flex flex-col gap-8">
<div class="text-center max-w-2xl mx-auto flex flex-col gap-2">
<span class="text-label-sm font-label-sm text-secondary uppercase tracking-wider font-bold">Langkah Mudah & Transparan</span>
<h2 class="text-headline-md font-headline-md text-primary tracking-tight">Cara Kerja Program Referral Booking Lapang</h2>
<p class="text-body-md font-body-md text-on-surface-variant">Mulai ajak rekan sparring sekarang dan nikmati hematnya sewa lapangan bersama.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
<!-- Step 1 -->
<div class="bg-surface-container-low p-6 rounded-2xl border border-outline-variant flex flex-col items-start gap-4 relative">
<div class="w-12 h-12 rounded-xl bg-primary-container text-white flex items-center justify-center font-bold text-headline-sm">
            1
          </div>
<div class="flex flex-col gap-2">
<h3 class="text-title-lg font-title-lg text-on-surface font-bold">Bagikan Kode atau Link</h3>
<p class="text-body-md font-body-md text-on-surface-variant">
              Kirimkan kode unik <strong class="text-primary">{{ $kodeReferral }}</strong> atau tautan langsung ke grup WhatsApp tim, teman kantor, atau squad mabar Anda.
            </p>
</div>
<div class="mt-auto pt-2 flex items-center gap-1.5 text-primary text-label-md font-label-md font-semibold">
<span class="material-symbols-outlined text-base" data-icon="send">send</span>
            Kirim Sekali Klik
          </div>
</div>
<!-- Step 2 -->
<div class="bg-surface-container-low p-6 rounded-2xl border border-outline-variant flex flex-col items-start gap-4 relative">
<div class="w-12 h-12 rounded-xl bg-secondary text-white flex items-center justify-center font-bold text-headline-sm">
            2
          </div>
<div class="flex flex-col gap-2">
<h3 class="text-title-lg font-title-lg text-on-surface font-bold">Teman Booking & Main</h3>
<p class="text-body-md font-body-md text-on-surface-variant">
              Teman memasukkan kode saat registrasi dan langsung menikmati potongan <strong class="text-secondary font-semibold">Rp 25.000</strong> pada reservasi jadwal perdana mereka.
            </p>
</div>
<div class="mt-auto pt-2 flex items-center gap-1.5 text-secondary text-label-md font-label-md font-semibold">
<span class="material-symbols-outlined text-base" data-icon="sports">sports</span>
            Check-in di Lapangan
          </div>
</div>
<!-- Step 3 -->
<div class="bg-surface-container-low p-6 rounded-2xl border border-outline-variant flex flex-col items-start gap-4 relative">
<div class="w-12 h-12 rounded-xl bg-tertiary-container text-tertiary-fixed flex items-center justify-center font-bold text-headline-sm">
            3
          </div>
<div class="flex flex-col gap-2">
<h3 class="text-title-lg font-title-lg text-on-surface font-bold">Cairkan Reward Otomatis</h3>
<p class="text-body-md font-body-md text-on-surface-variant">
              Begitu teman selesai main, saldo sewa <strong class="text-primary">Rp 25.000</strong> plus <strong class="text-primary">200 Poin</strong> langsung masuk otomatis ke dompet akun Anda.
            </p>
</div>
<div class="mt-auto pt-2 flex items-center gap-1.5 text-tertiary-container text-label-md font-label-md font-semibold">
<span class="material-symbols-outlined text-base" data-icon="payments">payments</span>
            Potong Langsung Tagihan
          </div>
</div>
</div>
<!-- FAQ Teaser strip -->
<div class="bg-surface-container rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary text-2xl" data-icon="help">help</span>
<span class="text-body-sm font-body-sm text-on-surface">Punya pertanyaan seputar masa berlaku voucher saldo atau syarat klaim komunitas?</span>
</div>
<a class="text-primary font-bold text-label-md font-label-md hover:underline whitespace-nowrap" href="#">Baca FAQ Referral →</a>
</div>
</section>
<div class="max-w-7xl mx-auto px-6 md:px-12 pb-10"><div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 shadow-sm">
<p class="text-label-sm text-primary-container font-bold uppercase tracking-wider">Kode Referral Kamu</p>
<div class="flex flex-col sm:flex-row gap-3 mt-2"><input readonly value="{{ $kodeReferral }}" class="flex-1 rounded-xl border border-outline-variant bg-surface-container-low p-3 font-bold text-primary"><button type="button" onclick="navigator.clipboard.writeText('{{ $linkReferral }}')" class="px-5 py-3 bg-primary-container text-white rounded-xl font-bold">Salin Link</button></div>
<p class="text-body-sm text-outline mt-2">{{ $linkReferral }}</p>
<div class="grid grid-cols-2 gap-3 mt-5"><div class="p-4 rounded-xl bg-surface-container-low"><p class="text-label-sm text-outline">Teman Terdaftar</p><p class="text-2xl font-bold text-primary">{{ $jumlahTemanDaftar }}</p></div><div class="p-4 rounded-xl bg-surface-container-low"><p class="text-label-sm text-outline">Reward Diterima</p><p class="text-2xl font-bold text-primary">{{ $jumlahRewardDiterima }}</p></div></div>
</div></div></main>
@endsection
