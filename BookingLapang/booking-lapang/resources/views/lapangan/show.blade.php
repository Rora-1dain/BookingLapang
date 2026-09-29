@extends('layouts.frontend')
@section('title', $lapangan->nama_lapangan . ' - Booking Lapang')
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
<span class="text-on-surface font-semibold">{{ $lapangan->nama_lapangan }}</span>
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
<span class="text-body-sm font-body-sm text-outline">({{ $lapangan->ulasans()->count() }} Ulasan)</span>
</div>
<div class="absolute bottom-4 left-4 bg-inverse-surface/80 text-inverse-on-surface backdrop-blur px-3 py-1.5 rounded-lg flex items-center gap-2 text-label-md font-label-md">
<span class="material-symbols-outlined text-base text-primary-fixed">sports_tennis</span>
<span>{{ $lapangan->jenis }}</span>
</div>
</div>
<!-- 4 Sub Thumbnails (Right 4 Cols - 2x2 Grid) -->
<div class="hidden md:grid md:col-span-4 grid-cols-2 grid-rows-2 gap-3.5">
<div class="relative group overflow-hidden bg-surface-container-high rounded-lg">
<img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" data-alt="Ruang ganti" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_4VlfwXkQ6qn_9qNjGpi3gGMQ9e1znVR7ZSfLw_gqsy22UghpBu8rbZEIhkgSBOYhgcrEvCC_reDOLvJJEuQ8ITzEfqQseJ1PnycrtSCQUnKkHyNMxInkOCB3-xFHfV0iFSWbIbY1ascVhXar_en8jDy-D_CYuGv3FdQFs3XS_8VRIp_kNObCY3SodBkRvjlu5BjYwAZsOlZfxgBNL-MzWhPWAA6w6KI1lxJyyeuQF6fDdR63peE"/>
<span class="absolute bottom-2 left-2 bg-inverse-surface/75 text-inverse-on-surface text-[11px] font-semibold px-2 py-0.5 rounded">Ruang Ganti</span>
</div>
<div class="relative group overflow-hidden bg-surface-container-high rounded-lg">
<img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" data-alt="Pro shop" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCh0y4fqXhVeeP1atx764qE_8-CnZVyaIdMvd6RSLjLWpPLxR3h1M4QsqxchgO5URZGqF1VZiWqWXxSp6bgE8qjN5nHci2MeMdwy1XTx-pLNRjeNOP34lJHWYx9KV5cOU8bWQ5OlWb309zLJOScuJt6p8c4szRbWU3h_Fj46a1cAq2Bv2q1nSGmtMA-_Ox-q__C2IvaRPXQ-Olzwq1ziaZzqeZNJy7cbFIZb3pudmDVWR8ao9f9QVc"/>
<span class="absolute bottom-2 left-2 bg-inverse-surface/75 text-inverse-on-surface text-[11px] font-semibold px-2 py-0.5 rounded">Pro Shop</span>
</div>
<div class="relative group overflow-hidden bg-surface-container-high rounded-lg">
<img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" data-alt="Tribun dan cafe" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZx2FDDVPja3i9nfI0el2IJ65pWv9eBRbL8ICW1hYbeCgxHe9SyLin2CXVt7QDo-w88HhmX37PrdlhPCFan8TIZl3Qf_yEJDjj6x4kUF_R_bVa6iScH19U3kyKSju4dmwV_ZwM94lW_KvewD-nmw492yNVCWR661wQBrl7JzqvHLEU3Yj32wIeYvm0YBs-gv5GZZLpcFWOFhZhoOqcL_eyfd6U9HFTg51_IBne0f04ev-hztX1jSA"/>
<span class="absolute bottom-2 left-2 bg-inverse-surface/75 text-inverse-on-surface text-[11px] font-semibold px-2 py-0.5 rounded">Tribun &amp; Cafe</span>
</div>
<div class="relative group overflow-hidden bg-surface-container-high rounded-lg">
<img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" data-alt="Parkir" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBBDaGUQSPPHKC9dH1hUNuRn0I37rqa2S3IuJjNrJc2IVu7WYBG5aRVI1-BWSHAduHc55ivF10ym8_-fYMLa7mcHSDYD4cdYun7Aye1uKd7_PMw1i1ZharBqeaSp2dIDGFJptgSBQaQBCBs0v_iktdFtuC9OMbzgFrZXcHhafiarXt7IGBTzAq-yLZUiXKvzEXnQE3Hsxg3IeMa7cwLVc4EUduueXf3_JQKcWQtde7Cx727ytq1ZD8"/>
<span class="absolute bottom-2 left-2 bg-inverse-surface/75 text-inverse-on-surface text-[11px] font-semibold px-2 py-0.5 rounded">Parkir Luas</span>
</div>
</div>
<button class="absolute bottom-4 right-4 bg-surface-container-lowest/95 hover:bg-surface-container-lowest text-on-surface text-label-md font-label-md px-4 py-2 rounded-xl shadow-md border border-outline-variant flex items-center gap-2 transition-all active:scale-95">
<span class="material-symbols-outlined text-base">photo_library</span>
          Lihat Semua Foto
        </button>
</div>
</section>
<!-- VENUE TITLE & META SUMMARY BAR -->
<section class="mb-8 pb-6 border-b border-outline-variant flex flex-col md:flex-row md:items-start md:justify-between gap-6">
<div class="space-y-3 max-w-3xl">
<div class="flex flex-wrap items-center gap-2.5">
<span class="bg-primary-container text-on-primary text-label-sm font-label-sm px-2.5 py-1 rounded-md flex items-center gap-1.5 uppercase tracking-wider">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
            Official Arena Partner
          </span>
<span class="bg-tertiary-fixed text-on-tertiary-fixed text-label-sm font-label-sm px-2.5 py-1 rounded-md flex items-center gap-1">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
            {{ number_format($lapangan->rataRataRating(),1) }} / 5.0 ({{ $lapangan->ulasans()->count() }} Ulasan)
          </span>
<span class="bg-surface-container text-on-surface-variant text-label-sm font-label-sm px-2.5 py-1 rounded-md">
            {{ $lapangan->jenis }}
          </span>
</div>
<h1 class="text-headline-xl font-headline-xl text-on-surface tracking-tight">
          {{ $lapangan->nama_lapangan }}
        </h1>
<div class="flex items-center flex-wrap gap-2 text-body-md font-body-md text-on-surface-variant">
<span class="material-symbols-outlined text-primary text-lg">pin_drop</span>
<span>{{ $lapangan->kota ?? 'Lokasi belum diisi' }}</span>
</div>
</div>
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
<!-- TWO-COLUMN MAIN CONTENT -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
<div class="lg:col-span-8 space-y-10">
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

<!-- SECTION 1: RINGKASAN JADWAL -> ARAHKAN KE FORM BOOKING ASLI -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 md:p-8 grass-shadow space-y-5" id="jadwal">
    <div>
        <h2 class="text-headline-sm font-headline-sm text-on-surface">Pilih Jadwal & Slot</h2>
        <p class="text-body-md font-body-md text-on-surface-variant">Cek jam operasional, ketersediaan slot, dan booking langsung di halaman reservasi.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-body-sm font-body-sm">
        <div class="p-3 rounded-xl border border-outline-variant bg-surface-container-low">
            <span class="text-outline block">Mulai dari</span>
            <span class="font-bold text-on-surface">Rp {{ number_format($lapangan->harga_per_jam,0,',','.') }}/jam</span>
        </div>
        <div class="p-3 rounded-xl border border-outline-variant bg-surface-container-low">
            <span class="text-outline block">Rating</span>
            <span class="font-bold text-on-surface">{{ number_format($lapangan->rataRataRating(),1) }} ({{ $lapangan->ulasans()->count() }} ulasan)</span>
        </div>
        <div class="p-3 rounded-xl border border-outline-variant bg-surface-container-low">
            <span class="text-outline block">Lokasi</span>
            <span class="font-bold text-on-surface">{{ $lapangan->kota ?? '-' }}</span>
        </div>
    </div>

    <a href="{{ route('booking.create', ['lapangan_id' => $lapangan->id]) }}"
        class="w-full bg-primary-container hover:bg-primary text-on-primary py-4 px-5 rounded-xl font-title-md text-title-md font-bold shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2.5">
        <span class="material-symbols-outlined text-xl">calendar_month</span>
        Lanjut Pilih Jadwal & Slot
    </a>
</section>

<!-- SECTION 2: FASILITAS LENGKAP VENUE -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 md:p-8 grass-shadow space-y-6" id="fasilitas">
<div>
<h2 class="text-headline-sm font-headline-sm text-on-surface">Fasilitas &amp; Standar Lapangan</h2>
<p class="text-body-md font-body-md text-on-surface-variant">Didesain khusus untuk performa permainan maksimal dan kenyamanan pemain pro maupun komunitas.</p>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
<div class="p-4 rounded-xl border border-outline-variant bg-surface-container-low flex items-start gap-3.5">
<div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-xl">layers</span>
</div>
<div>
<h3 class="text-title-md font-title-md text-on-surface font-bold">Karpet Li-Ning BWF</h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">Vinyl taraflex 5mm dengan shock-absorption anti cidera lutut.</p>
</div>
</div>
<div class="p-4 rounded-xl border border-outline-variant bg-surface-container-low flex items-start gap-3.5">
<div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-xl">light_mode</span>
</div>
<div>
<h3 class="text-title-md font-title-md text-on-surface font-bold">Lampu LED 600 Lux</h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">Overhead anti-silau terarah, kok shuttlecock jelas terlihat tinggi.</p>
</div>
</div>
<div class="p-4 rounded-xl border border-outline-variant bg-surface-container-low flex items-start gap-3.5">
<div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-xl">shower</span>
</div>
<div>
<h3 class="text-title-md font-title-md text-on-surface font-bold">Shower Panas &amp; Loker</h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">Kamar mandi bersih terpisah pria/wanita dengan pemanas air.</p>
</div>
</div>
<div class="p-4 rounded-xl border border-outline-variant bg-surface-container-low flex items-start gap-3.5">
<div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-xl">local_parking</span>
</div>
<div>
<h3 class="text-title-md font-title-md text-on-surface font-bold">Parkir 40+ Mobil</h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">Kapasitas parkir kendaraan roda 4 &amp; 2 dengan petugas keamanan.</p>
</div>
</div>
<div class="p-4 rounded-xl border border-outline-variant bg-surface-container-low flex items-start gap-3.5">
<div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-xl">storefront</span>
</div>
<div>
<h3 class="text-title-md font-title-md text-on-surface font-bold">Kantin &amp; Pro Shop</h3>
<p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">Sedia minuman isotonik dingin, stringing raket, dan grip Yonex.</p>
</div>
</div>
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
<!-- SECTION 4: ULASAN PEMAIN -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 md:p-8 grass-shadow space-y-6" id="ulasan">
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
<div>
<h2 class="text-headline-sm font-headline-sm text-on-surface">Ulasan Pemain</h2>
<p class="text-body-md font-body-md text-on-surface-variant">Ditinjau oleh penyewa yang telah menyelesaikan sesi bermain.</p>
</div>
<button class="px-4 py-2 border border-primary text-primary hover:bg-primary-container/10 rounded-xl text-label-md font-label-md font-semibold transition-colors self-start sm:self-auto">
              Tulis Ulasan
            </button>
</div>
<div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-6 rounded-xl bg-surface-container-low border border-outline-variant items-center">
<div class="md:col-span-4 text-center md:border-r md:border-outline-variant pr-0 md:pr-4">
<span class="text-headline-xl font-headline-xl text-on-surface font-extrabold">{{ number_format($lapangan->rataRataRating(),1) }}</span>
<p class="text-body-sm font-body-sm text-outline mt-1.5">Berdasarkan {{ $lapangan->ulasans()->count() }} ulasan</p>
</div>
</div>
@forelse ($ulasan as $item)
<div class="p-5 rounded-xl border border-outline-variant bg-surface-container-lowest space-y-3">
<div class="flex items-center justify-between">
<h4 class="text-title-md font-title-md font-bold text-on-surface">{{ $item->user->name ?? 'Pengguna' }}</h4>
<span class="font-bold">{{ $item->rating }}/5</span>
</div>
<p class="text-body-md font-body-md text-on-surface leading-normal">{{ $item->komentar }}</p>
</div>
@empty
<p class="text-body-md text-on-surface-variant">Belum ada ulasan untuk lapangan ini.</p>
@endforelse
</section>
</div>

<!-- RIGHT COLUMN: STICKY INFO CARD -->
<aside class="lg:col-span-4 sticky top-24">
    <div class="bg-surface-container-lowest border-2 border-primary-container rounded-2xl p-6 grass-shadow-lg space-y-5">
        <div class="pb-4 border-b border-outline-variant">
            <span class="text-label-sm font-label-sm text-outline uppercase font-bold tracking-wider">Venue</span>
            <h3 class="text-title-lg font-title-lg text-on-surface">{{ $lapangan->nama_lapangan }}</h3>
        </div>

        <div class="space-y-3 text-body-md font-body-md">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-on-surface-variant">
                    <span class="material-symbols-outlined text-base text-primary">payments</span>
                    <span>Harga Mulai</span>
                </div>
                <span class="font-bold text-on-surface">Rp {{ number_format($lapangan->harga_per_jam,0,',','.') }}/jam</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-on-surface-variant">
                    <span class="material-symbols-outlined text-base text-primary">sports_tennis</span>
                    <span>Jenis</span>
                </div>
                <span class="font-bold text-on-surface">{{ $lapangan->jenis }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-on-surface-variant">
                    <span class="material-symbols-outlined text-base text-primary">location_on</span>
                    <span>Kota</span>
                </div>
                <span class="font-bold text-on-surface">{{ $lapangan->kota ?? '-' }}</span>
            </div>
        </div>

        <a href="{{ route('booking.create', ['lapangan_id' => $lapangan->id]) }}"
            class="w-full bg-primary-container hover:bg-primary text-on-primary py-4 px-5 rounded-xl font-title-md text-title-md font-bold shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2.5">
            <span class="material-symbols-outlined text-xl">lock</span>
            Booking Sekarang
        </a>

        <div class="bg-surface-container-low p-3 rounded-lg border border-outline-variant/60 text-center space-y-1">
            <div class="flex items-center justify-center gap-1.5 text-label-sm font-label-sm text-primary font-bold">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">shield</span>
                Garansi Slot 100% Anti-Bentrok
            </div>
            <p class="text-[11px] text-on-surface-variant">
                Verifikasi Otomatis QRIS, BCA VA, Mandiri, &amp; GoPay via Midtrans
            </p>
        </div>

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