@extends('layouts.frontend')
@section('title','Checkout & Pembayaran - Booking Lapang')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 md:px-12 py-8 space-y-6">
<!-- TOP SECTION: STEPPER & TIMER BAR -->
<section class="flex flex-col md:flex-row items-center justify-between gap-4 bg-surface-container-lowest p-5 rounded-xl border border-outline-variant custom-shadow-resting">
<!-- Stepper Progress Bar -->
<div class="flex items-center gap-3 w-full md:w-auto">
<!-- Step 1 (Done) -->
<div class="flex items-center gap-2">
<div class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold">
<span class="material-symbols-outlined text-[18px]" data-icon="check">check</span>
</div>
<span class="font-label-md text-label-md text-on-surface-variant font-medium hidden sm:inline">1. Pilih Slot</span>
</div>
<div class="w-8 md:w-12 h-0.5 bg-primary-container"></div>
<!-- Step 2 (Active) -->
<div class="flex items-center gap-2">
<div class="w-8 h-8 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold text-label-md">
            2
          </div>
<span class="font-label-lg text-label-lg text-primary font-bold">Review & Checkout</span>
</div>
<div class="w-8 md:w-12 h-0.5 bg-surface-variant"></div>
<!-- Step 3 (Pending) -->
<div class="flex items-center gap-2">
<div class="w-8 h-8 rounded-full bg-surface-variant text-on-surface-variant flex items-center justify-center font-bold text-label-md">
            3
          </div>
<span class="font-label-md text-label-md text-on-surface-variant hidden sm:inline">Bayar</span>
</div>
</div>
<!-- Live Session Countdown Timer -->
<div class="flex items-center gap-3 bg-secondary/10 border border-secondary/20 px-4 py-2.5 rounded-xl w-full md:w-auto justify-center">
<span class="material-symbols-outlined text-secondary text-[22px] animate-pulse" data-icon="timer">timer</span>
<div class="text-left">
<span class="font-label-sm text-label-sm text-on-surface-variant block leading-tight">Amankan Slot Anda</span>
<span class="font-title-md text-title-md text-secondary font-bold">
            Sisa Waktu: <span id="countdown">14:32</span>
</span>
</div>
</div>
</section>
<!-- 2-COLUMN CHECKOUT GRID (7 : 5) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
<!-- LEFT COLUMN: FORM, VOUCHER, & MIDTRANS GATEWAY (7 COLS) -->
<section class="lg:col-span-7 space-y-6">
<!-- 1. INFORMASI KONTAK PENYEWA -->
<div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant custom-shadow-resting space-y-5">
<div class="flex items-center justify-between border-b border-outline-variant pb-4">
<div class="flex items-center gap-2">
<span class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-primary">
<span class="material-symbols-outlined" data-icon="person">person</span>
</span>
<h2 class="font-title-lg text-title-lg text-on-surface">Informasi Pemesan</h2>
</div>
<span class="font-label-sm text-label-sm bg-primary-fixed/50 text-primary px-2.5 py-1 rounded-full font-semibold">Data Akun Terverifikasi</span>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="space-y-1 md:col-span-2">
<label class="font-label-md text-label-md text-on-surface-variant block">Nama Lengkap Sesuai ID</label>
<input class="w-full h-12 px-4 rounded-xl border border-outline-variant bg-surface-container-lowest text-on-surface font-body-md focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all outline-none" type="text" value="{{ $booking->user->name }}"/>
</div>
<div class="space-y-1">
<label class="font-label-md text-label-md text-on-surface-variant block">Email Konfirmasi & E-Tiket</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3.5 top-3 text-outline text-[20px]" data-icon="mail">mail</span>
<input class="w-full h-12 pl-10 pr-4 rounded-xl border border-outline-variant bg-surface-container-lowest text-on-surface font-body-md focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all outline-none" type="email" value="{{ $booking->user->email }}"/>
</div>
</div>
<div class="space-y-1">
<label class="font-label-md text-label-md text-on-surface-variant block">Nomor WhatsApp (Akses Gerbang Arena)</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3.5 top-3 text-outline text-[20px]" data-icon="chat">chat</span>
<input class="w-full h-12 pl-10 pr-4 rounded-xl border border-outline-variant bg-surface-container-lowest text-on-surface font-body-md focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all outline-none" type="tel" value="{{ $booking->user->phone ?? '-' }}"/>
</div>
</div>
</div>
<!-- Community Toggle Switch -->
<div class="pt-2">
<label class="flex items-start gap-3 cursor-pointer p-3.5 rounded-xl border border-outline-variant/70 bg-surface hover:bg-surface-container transition-colors">
<input checked="" class="mt-1 w-5 h-5 rounded text-primary-container focus:ring-primary-container border-outline-variant" type="checkbox"/>
<div class="flex-1">
<div class="flex items-center justify-between">
<span class="font-label-lg text-label-lg text-on-surface font-semibold">Booking atas nama Komunitas / Tim?</span>
<span class="font-label-sm text-label-sm text-primary font-bold">Terpilih</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Nama tim akan otomatis tercetak di jadwal live scoreboard arena venue.</p>
<div class="mt-3">
<input class="w-full h-10 px-3 rounded-lg border border-outline-variant bg-surface-container-lowest text-on-surface font-body-md focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none" placeholder="Nama Komunitas / Klub Anda" type="text" value="Garuda Badminton Club"/>
</div>
</div>
</label>
</div>
</div>
<!-- 2. VOUCHER & PROMO DISKON -->
<div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant custom-shadow-resting space-y-4">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2">
<span class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-secondary">
<span class="material-symbols-outlined" data-icon="sell">sell</span>
</span>
<h2 class="font-title-lg text-title-lg text-on-surface">Voucher Diskon & Promo</h2>
</div>
<span class="font-label-sm text-label-sm text-secondary font-bold flex items-center gap-1">
<span class="material-symbols-outlined text-[16px]" data-icon="local_fire_department">local_fire_department</span>
              Hemat Rp 20.000
            </span>
</div>
<!-- Input Code Box -->
<div class="flex items-center gap-2">
<div class="relative flex-1">
<span class="material-symbols-outlined absolute left-3.5 top-3 text-outline text-[20px]" data-icon="confirmation_number">confirmation_number</span>
<input class="w-full h-12 pl-10 pr-4 rounded-xl border border-primary-container bg-surface-container-lowest text-primary font-title-md font-bold tracking-wider focus:outline-none" type="text" uppercase="" value="SORESEHAT"/>
<span class="absolute right-3 top-3 text-primary-container font-label-sm text-label-sm flex items-center gap-1 bg-primary-fixed px-2 py-0.5 rounded">
<span class="material-symbols-outlined text-[14px]" data-icon="check_circle">check_circle</span>
                Terpasang
              </span>
</div>
<button class="h-12 px-6 rounded-xl bg-primary-container text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary transition-colors active:scale-95">
              Terapkan
            </button>
</div>
<!-- Quick Promo Recommendation Badges -->
<div class="space-y-2 pt-1">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase font-semibold tracking-wider block">Kupon Rekomendasi Tersedia</span>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
<!-- Active Voucher Card -->
<div class="p-3.5 rounded-xl border-2 border-primary-container bg-primary-fixed/20 flex items-start gap-3 relative">
<div class="w-7 h-7 rounded-full bg-primary-container text-on-primary flex items-center justify-center shrink-0 mt-0.5">
<span class="material-symbols-outlined text-[16px]" data-icon="done">done</span>
</div>
<div class="flex-1">
<div class="flex items-center justify-between">
<span class="font-label-lg text-label-lg font-bold text-primary">SORESEHAT</span>
<span class="font-label-sm text-label-sm text-secondary font-bold">-Rp 20.000</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant">Potongan jam main sore & malam badminton.</p>
</div>
</div>
<!-- Alternative Clickable Voucher -->
<div class="p-3.5 rounded-xl border border-outline-variant bg-surface hover:bg-surface-container-high transition-colors flex items-start gap-3 cursor-pointer group">
<div class="w-7 h-7 rounded-full bg-surface-variant text-on-surface-variant flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-primary group-hover:text-on-primary transition-colors">
<span class="material-symbols-outlined text-[16px]" data-icon="add">add</span>
</div>
<div class="flex-1">
<div class="flex items-center justify-between">
<span class="font-label-lg text-label-lg font-bold text-on-surface">WEEKENDSERU</span>
<span class="font-label-sm text-label-sm text-on-surface-variant font-bold">-10% Max 25K</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant">Min. booking 3 jam multi-slot hari ini.</p>
</div>
</div>
</div>
</div>
</div>
<!-- 3. PEMILIHAN METODE PEMBAYARAN (MIDTRANS INTEGRATED) -->
<div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant custom-shadow-resting space-y-5">
<div class="flex items-center justify-between border-b border-outline-variant pb-4">
<div class="flex items-center gap-2">
<span class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-primary">
<span class="material-symbols-outlined" data-icon="account_balance_wallet">account_balance_wallet</span>
</span>
<h2 class="font-title-lg text-title-lg text-on-surface">Metode Pembayaran Midtrans</h2>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1">
<span class="material-symbols-outlined text-[16px] text-primary" data-icon="flash_on">flash_on</span>
              Verifikasi Instan &lt; 5 Detik
            </span>
</div>
<!-- Payment Options Accordion Groups -->
<div class="space-y-3">
<!-- Category A: Virtual Account (Active Expanded) -->
<div class="border-2 border-primary-container rounded-xl overflow-hidden">
<div class="p-4 bg-primary-fixed/20 flex items-center justify-between cursor-pointer">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary-container" data-icon="account_balance">account_balance</span>
<div>
<h3 class="font-label-lg text-label-lg font-bold text-on-surface">Virtual Account (Otomatis)</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">BCA, Mandiri, BNI, BRI, Permata Bank</p>
</div>
</div>
<span class="material-symbols-outlined text-primary-container" data-icon="expand_less">expand_less</span>
</div>
<!-- Virtual Account Bank List -->
<div class="p-4 bg-surface-container-lowest space-y-3">
<!-- BCA VA (Selected) -->
<label class="flex items-center justify-between p-3.5 rounded-xl border-2 border-primary-container bg-surface cursor-pointer">
<div class="flex items-center gap-3">
<input checked="" class="w-5 h-5 text-primary-container focus:ring-primary-container border-outline-variant" name="payment_method" type="radio"/>
<div class="w-12 h-7 bg-surface-container-high rounded flex items-center justify-center font-bold text-label-md text-[#0060AF] border border-outline-variant">
                      BCA
                    </div>
<div>
<span class="font-label-lg text-label-lg font-bold text-on-surface block">BCA Virtual Account</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">Bebas antre, verifikasi langsung otomatis</span>
</div>
</div>
<div class="text-right">
<span class="font-label-md text-label-md text-primary font-bold">Bebas Biaya</span>
<span class="block font-label-sm text-label-sm text-on-surface-variant line-through">Rp 4.000</span>
</div>
</label>
<!-- Mandiri Bill -->
<label class="flex items-center justify-between p-3.5 rounded-xl border border-outline-variant hover:bg-surface transition-colors cursor-pointer">
<div class="flex items-center gap-3">
<input class="w-5 h-5 text-primary-container focus:ring-primary-container border-outline-variant" name="payment_method" type="radio"/>
<div class="w-12 h-7 bg-surface-container-high rounded flex items-center justify-center font-bold text-label-md text-[#003B70] border border-outline-variant">
                      MDR
                    </div>
<div>
<span class="font-label-lg text-label-lg font-medium text-on-surface block">Mandiri Bill Payment</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">Nomor Perusahaan & Kode Bayar</span>
</div>
</div>
<span class="font-label-md text-label-md text-primary font-bold">Bebas Biaya</span>
</label>
<!-- BNI VA -->
<label class="flex items-center justify-between p-3.5 rounded-xl border border-outline-variant hover:bg-surface transition-colors cursor-pointer">
<div class="flex items-center gap-3">
<input class="w-5 h-5 text-primary-container focus:ring-primary-container border-outline-variant" name="payment_method" type="radio"/>
<div class="w-12 h-7 bg-surface-container-high rounded flex items-center justify-center font-bold text-label-md text-[#F15A24] border border-outline-variant">
                      BNI
                    </div>
<div>
<span class="font-label-lg text-label-lg font-medium text-on-surface block">BNI Virtual Account</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">ATM, Mobile Banking, Internet Banking</span>
</div>
</div>
<span class="font-label-md text-label-md text-primary font-bold">Bebas Biaya</span>
</label>
</div>
</div>
<!-- Category B: E-Wallet & QRIS -->
<div class="border border-outline-variant rounded-xl overflow-hidden">
<div class="p-4 bg-surface hover:bg-surface-container transition-colors flex items-center justify-between cursor-pointer">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-on-surface-variant" data-icon="qr_code_scanner">qr_code_scanner</span>
<div>
<h3 class="font-label-lg text-label-lg font-bold text-on-surface">GoPay, QRIS, & Dompet Digital</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">Scan instan semua aplikasi pembayaran Indonesia</p>
</div>
</div>
<div class="flex items-center gap-2">
<span class="font-label-sm text-label-sm bg-primary-fixed text-primary font-bold px-2 py-0.5 rounded">QRIS Ready</span>
<span class="material-symbols-outlined text-outline" data-icon="expand_more">expand_more</span>
</div>
</div>
</div>
<!-- Category C: Kartu Kredit / Debit (3D Secure) -->
<div class="border border-outline-variant rounded-xl overflow-hidden">
<div class="p-4 bg-surface hover:bg-surface-container transition-colors flex items-center justify-between cursor-pointer">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-on-surface-variant" data-icon="credit_card">credit_card</span>
<div>
<h3 class="font-label-lg text-label-lg font-bold text-on-surface">Kartu Kredit / Debit Online</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">Visa, Mastercard, JCB berproteksi 3D Secure OTP</p>
</div>
</div>
<div class="flex items-center gap-2">
<span class="font-label-sm text-label-sm text-on-surface-variant">3D Secure</span>
<span class="material-symbols-outlined text-outline" data-icon="expand_more">expand_more</span>
</div>
</div>
</div>
</div>
<!-- Midtrans Notice Strip -->
<div class="p-3 bg-surface rounded-xl border border-outline-variant flex items-center gap-3">
<span class="material-symbols-outlined text-primary-container text-[20px]" data-icon="security">security</span>
<p class="font-body-sm text-body-sm text-on-surface-variant">
              Pembayaran diproses secara aman oleh gateway Midtrans. Tiket QR barcode langsung aktif begitu pembayaran dikonfirmasi.
            </p>
</div>
</div>
</section>
<!-- RIGHT COLUMN: STICKY ORDER SUMMARY (5 COLS) -->
<aside class="lg:col-span-5 lg:sticky lg:top-28 space-y-6">
<div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant custom-shadow-floating space-y-6">
<!-- Venue & Sport Header -->
<div class="flex items-start gap-4">
<div class="w-24 h-24 rounded-xl overflow-hidden border border-outline-variant shrink-0 bg-surface-container relative">
<img class="w-full h-full object-cover" data-alt="A brightly illuminated professional indoor badminton court with emerald green Li-Ning synthetic turf mats, crisp white boundary lines, high ceiling industrial stadium lighting, warm polished wood spectator benches, and clean architectural lines evoking athletic vigor and premium sporting ambiance." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDWIpR04ZidMGoz_Vuoph_FWuGItiec1RitW0qjqOJTls2sEFg14NqyT0fhn2BXyx83rBkdF5viOTUlAw5YXGvgHWnGzfT5peRR-88PO3Aeuy88JDkUq6oIzp7t3POksZA-EsKRC198OkS43JgPS86iisvIxXufAbcm5W1vYWCMw3j1HZZSHRIGbBDhUNAUx5nR5b6OOF_BqBAJqaneLGc8GUgKHBcKqSA5LLY6yfkUkwHDNysF31c"/>
<span class="absolute bottom-1 right-1 bg-on-surface/80 text-surface-container-lowest text-[10px] font-bold px-1.5 py-0.5 rounded">Indoor</span>
</div>
<div class="flex-1 min-w-0">
<div class="flex items-center gap-1 text-tertiary-fixed-dim">
<span class="material-symbols-outlined text-[16px] text-tertiary-fixed-dim" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-sm text-label-sm text-on-surface font-bold">4.9</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">(128 review)</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface font-bold truncate mt-0.5">Smash Hub Arena</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
<span class="material-symbols-outlined text-[16px] text-secondary" data-icon="location_on">location_on</span>
                Cilandak Barat, Jakarta Selatan
              </p>
<div class="flex items-center gap-1.5 mt-2">
<span class="font-label-sm text-label-sm bg-surface-container-high px-2 py-0.5 rounded-full border border-outline-variant text-on-surface-variant font-medium">BWF Mat Li-Ning</span>
<span class="font-label-sm text-label-sm bg-surface-container-high px-2 py-0.5 rounded-full border border-outline-variant text-on-surface-variant font-medium">AC Arena</span>
</div>
</div>
</div>
<!-- Reservation Specifics Badge Box -->
<div class="bg-surface rounded-xl p-4 border border-outline-variant/80 space-y-3">
<div class="flex items-center justify-between pb-2 border-b border-outline-variant/60">
<span class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-2">
<span class="material-symbols-outlined text-[18px] text-primary" data-icon="sports_score">sports_score</span>
                Lapangan
              </span>
<span class="font-label-lg text-label-lg text-on-surface font-bold">Court 2 (Karpet Li-Ning BWF)</span>
</div>
<div class="flex items-center justify-between pb-2 border-b border-outline-variant/60">
<span class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-2">
<span class="material-symbols-outlined text-[18px] text-primary" data-icon="calendar_today">calendar_today</span>
                Tanggal Main
              </span>
<span class="font-label-lg text-label-lg text-on-surface font-bold">Rabu, 15 Okt 2025</span>
</div>
<div class="flex items-center justify-between">
<span class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-2">
<span class="material-symbols-outlined text-[18px] text-primary" data-icon="schedule">schedule</span>
                Jam Sesi
              </span>
<div class="text-right">
<span class="font-label-lg text-label-lg text-on-surface font-bold block">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }} WIB</span>
<span class="font-label-sm text-label-sm text-primary font-semibold">2 Jam Durasi</span>
</div>
</div>
</div>
<!-- Transparent Price Breakdown -->
<div class="space-y-2.5 pt-1">
<h4 class="font-label-lg text-label-lg font-bold text-on-surface">Rincian Pembayaran</h4>
<div class="flex items-center justify-between font-body-md text-body-md text-on-surface-variant">
<span>Sewa Lapangan (2 Jam x Rp 95.000)</span>
<span class="text-on-surface font-medium">Rp 190.000</span>
</div>
<div class="flex items-center justify-between font-body-md text-body-md text-on-surface-variant">
<span class="flex items-center gap-1">
                Biaya Layanan Platform
                <span class="material-symbols-outlined text-[14px] text-outline" data-icon="info" title="Biaya pemeliharaan sistem & jaminan slot">info</span>
</span>
<span class="text-on-surface font-medium">Rp 2.000</span>
</div>
<div class="flex items-center justify-between font-body-md text-body-md text-secondary">
<span class="flex items-center gap-1 font-semibold">
<span class="material-symbols-outlined text-[16px]" data-icon="loyalty">loyalty</span>
                Diskon Voucher (SORESEHAT)
              </span>
<span class="font-bold text-secondary">-Rp 20.000</span>
</div>
<div class="h-[1px] bg-outline-variant my-2"></div>
<div class="flex items-baseline justify-between pt-1">
<div>
<span class="font-title-md text-title-md font-bold text-on-surface block">Total Tagihan</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">Termasuk pajak & asuransi atlet</span>
</div>
<div class="text-right">
<span class="font-headline-md text-headline-md text-primary font-extrabold tracking-tight">Rp {{ number_format($booking->total_harga,0,',','.') }}</span>
</div>
</div>
</div>
<!-- Terms & Agreement Checkbox -->
<div class="pt-1">
<label class="flex items-start gap-2.5 cursor-pointer">
<input checked="" class="mt-0.5 w-4 h-4 rounded text-primary-container focus:ring-primary-container border-outline-variant" type="checkbox"/>
<span class="font-body-sm text-body-sm text-on-surface-variant leading-snug">
                Saya menyetujui <span class="text-primary font-semibold underline">Kebijakan Sepatu Non-Marking</span> serta batas aturan reschedule maks. 6 jam sebelum sesi bermain.
              </span>
</label>
</div>
<!-- Primary CTA Button -->
<button class="w-full h-14 bg-primary-container text-on-primary rounded-xl font-title-lg text-title-lg font-bold flex items-center justify-center gap-2 hover:bg-[#3A7D44] active:scale-[0.98] transition-all shadow-md">
<span class="material-symbols-outlined text-[20px]" data-icon="lock" data-weight="fill" style="font-variation-settings: 'FILL' 1;">lock</span>
            Bayar Sekarang Rp {{ number_format($booking->total_harga,0,',','.') }}
          </button>
<!-- Security Badges Footer -->
<div class="grid grid-cols-3 gap-2 pt-2 border-t border-outline-variant/70 text-center">
<div class="p-2 rounded-lg bg-surface flex flex-col items-center">
<span class="material-symbols-outlined text-primary text-[20px]" data-icon="verified">verified</span>
<span class="font-label-sm text-label-sm text-on-surface font-semibold mt-1">Midtrans Verified</span>
</div>
<div class="p-2 rounded-lg bg-surface flex flex-col items-center">
<span class="material-symbols-outlined text-primary text-[20px]" data-icon="encrypted">encrypted</span>
<span class="font-label-sm text-label-sm text-on-surface font-semibold mt-1">PCI-DSS L1</span>
</div>
<div class="p-2 rounded-lg bg-surface flex flex-col items-center">
<span class="material-symbols-outlined text-primary text-[20px]" data-icon="event_available">event_available</span>
<span class="font-label-sm text-label-sm text-on-surface font-semibold mt-1">Bebas Bentrok</span>
</div>
</div>
</div>
<!-- Help Desk Banner Card -->
<div class="bg-surface-container rounded-xl p-4 border border-outline-variant flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-primary-fixed text-primary flex items-center justify-center">
<span class="material-symbols-outlined" data-icon="support_agent">support_agent</span>
</div>
<div>
<h5 class="font-label-md text-label-md font-bold text-on-surface">Butuh bantuan pembayaran?</h5>
<p class="font-body-sm text-body-sm text-on-surface-variant">Tim Customer Care Booking Lapang siap 24/7</p>
</div>
</div>
<button class="px-3 py-1.5 rounded-lg border border-primary text-primary font-label-sm text-label-sm font-bold hover:bg-primary/5 transition-colors">
            Chat CS
          </button>
</div>
</aside>
</div>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
document.getElementById('pay-button')?.addEventListener('click', function () {
    snap.pay(@json($snapToken), {
        onSuccess: () => window.location.href = @json(route('booking.status', $booking)),
        onPending: () => window.location.href = @json(route('booking.status', $booking)),
        onError: () => alert('Pembayaran gagal, coba lagi.'),
        onClose: () => {}
    });
});
</script></main>
@endsection
