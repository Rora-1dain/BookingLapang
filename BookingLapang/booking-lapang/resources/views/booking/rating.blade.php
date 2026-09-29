@extends('layouts.frontend')
@section('title','Beri Ulasan - Booking Lapang')
@section('content')
<main class="flex-grow w-full max-w-5xl mx-auto px-4 md:px-6 py-8">
<!-- Breadcrumbs -->
<nav class="flex items-center gap-2 text-body-sm font-body-sm text-outline mb-6">
<a class="hover:text-primary transition-colors" href="#">Beranda</a>
<span class="material-symbols-outlined text-sm" data-icon="chevron_right">chevron_right</span>
<a class="hover:text-primary transition-colors" href="#">Riwayat Booking</a>
<span class="material-symbols-outlined text-sm" data-icon="chevron_right">chevron_right</span>
<span class="text-primary-container font-semibold">Ulasan & Penilaian (#BL-20251015-8849)</span>
</nav>
<!-- Page Header -->
<div class="mb-8">
<h1 class="text-headline-lg font-headline-lg text-primary tracking-tight mb-2">Beri Ulasan & Nilai Pengalaman Bermain</h1>
<p class="text-body-lg font-body-lg text-on-surface-variant">Bantu komunitas dan pemain lain mengetahui kualitas lapangan, fasilitas, dan pelayanan di arena ini.</p>
</div>
<!-- Layout Grid: 8 Cols Form / 4 Cols Guidance Box -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
<!-- FORM CONTAINER (Col 1-8) -->
<div class="lg:col-span-8 flex flex-col gap-8">
<!-- 1. Match & Venue Context Banner -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 shadow-sm flex flex-col sm:flex-row gap-5 items-center">
<div class="w-full sm:w-36 h-28 rounded-xl overflow-hidden flex-shrink-0 bg-surface-container relative">
<img class="w-full h-full object-cover" data-alt="Professional indoor badminton court featuring crisp dark green Li-Ning synthetic vinyl mat with stark white boundary chalk lines under sharp high-bay LED ceiling lights, captured at eye-level." src="https://lh3.googleusercontent.com/aida-public/AB6AXuB-gpJs196SQwrmTXcKDT3mcDgA05tM-u_9bEMHMFaSwiCrNem6kvPOHSODwW3kuvRl90VKKNulkRSXFBZGaQNMUoOhu1-j8AnZdAnVAUocMYkw2DZ4irNjvkSmhxoesrMy_qEVrLLQV1qgqL2IdrBBf9WITP17qH6Z5-gVpBbqrSEo589med09XcbDldBW_6JMOTwRVOyuBBoUluUx9-Q_d6zS-9M0fH5tzY_lwkeGA0E1tLfGRUk"/>
<span class="absolute bottom-1.5 left-1.5 bg-on-background/70 backdrop-blur-sm text-surface text-[10px] font-semibold px-2 py-0.5 rounded">Court 2</span>
</div>
<div class="flex-grow flex flex-col justify-center gap-1.5 w-full">
<div class="flex items-center justify-between flex-wrap gap-2">
<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-container bg-primary-fixed/40 px-2.5 py-0.5 rounded-full">
<span class="material-symbols-outlined filled text-sm" data-icon="verified">verified</span>
                Terverifikasi Selesai Bermain
              </span>
<span class="text-label-sm font-label-sm text-outline">Kode: #BL-20251015-8849</span>
</div>
<h2 class="text-title-lg font-title-lg text-on-surface">{{ $booking->lapangan->nama_lapangan }}</h2>
<p class="text-body-md font-body-md text-on-surface-variant flex items-center gap-1.5">
<span class="material-symbols-outlined text-base text-secondary" data-icon="schedule">schedule</span>
              Rabu, 15 Okt 2025 • 19.00 - 21.00 WIB (2 Jam)
            </p>
<div class="flex items-center gap-2 text-body-sm font-body-sm text-outline mt-0.5">
<span class="material-symbols-outlined text-base" data-icon="sports">sports</span>
              Bulu Tangkis • Vinyl Li-Ning Mats • Indoor Arena
            </div>
</div>
</div>
<!-- FORM CARD -->
<form method="POST" action="{{ route('ulasan.store',$booking) }}" class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 md:p-8 shadow-sm flex flex-col gap-10">
@csrf
<!-- Section 1: Rating Bintang Keseluruhan -->
<section class="flex flex-col items-center text-center p-6 bg-surface-container-low rounded-xl border border-outline-variant/60">
<label class="text-title-md font-title-md text-on-surface mb-1">Rating Bintang Keseluruhan</label>
<p class="text-body-sm font-body-sm text-on-surface-variant mb-4">Bagaimana kepuasan Anda bermain di {{ $booking->lapangan->nama_lapangan }} secara umum?</p>
<!-- 5 Large Stars Interactive -->
<div class="flex items-center gap-2 mb-3">
<button type="button" data-rating="1" class="text-tertiary-fixed-dim hover:scale-110 transition-transform" type="submit">
<button type="button" data-rating="2" class="text-tertiary-fixed-dim hover:scale-110 transition-transform" type="button">
<button type="button" data-rating="3" class="text-tertiary-fixed-dim hover:scale-110 transition-transform" type="button">
<button type="button" data-rating="4" class="text-tertiary-fixed-dim hover:scale-110 transition-transform" type="button">
<button type="button" data-rating="5" class="text-tertiary-fixed-dim hover:scale-110 transition-transform" type="button">
</div>
<!-- Dynamic Feedback Text -->
<div class="inline-flex items-center gap-2 bg-surface-container-lowest px-4 py-1.5 rounded-full border border-outline-variant shadow-xs">
<span class="w-2.5 h-2.5 rounded-full bg-primary-container"></span>
<span class="text-label-lg font-label-lg text-primary-container">5/5 - Luar Biasa! Sangat Puas</span>
</div>
</section>
<!-- Section 2: Penilaian Aspek Kunci Lapangan -->
<section class="flex flex-col gap-4">
<div class="border-b border-outline-variant pb-2">
<h3 class="text-title-lg font-title-lg text-on-surface">Penilaian Aspek Kunci Lapangan</h3>
<p class="text-body-sm font-body-sm text-on-surface-variant">Beri penilaian detail untuk setiap sarana penunjang kenyamanan olahraga.</p>
</div>
<div class="space-y-4">
<!-- Item 1 -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 rounded-xl bg-surface-container-lowest hover:bg-surface-container-low transition-colors border border-outline-variant/60 gap-3">
<div>
<div class="text-title-md font-title-md text-on-surface">Kualitas Lantai / Karpet</div>
<div class="text-body-sm font-body-sm text-on-surface-variant">Grip kesat, pantulan kokoh, tidak licin</div>
</div>
<div class="flex items-center gap-1 text-tertiary-fixed-dim flex-shrink-0">
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-label-md font-label-md text-on-surface font-bold ml-2">5.0</span>
</div>
</div>
<!-- Item 2 -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 rounded-xl bg-surface-container-lowest hover:bg-surface-container-low transition-colors border border-outline-variant/60 gap-3">
<div>
<div class="text-title-md font-title-md text-on-surface">Pencahayaan Lampu Arena</div>
<div class="text-body-sm font-body-sm text-on-surface-variant">Anti-silau, merata, terang jelas</div>
</div>
<div class="flex items-center gap-1 text-tertiary-fixed-dim flex-shrink-0">
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-label-md font-label-md text-on-surface font-bold ml-2">5.0</span>
</div>
</div>
<!-- Item 3 -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 rounded-xl bg-surface-container-lowest hover:bg-surface-container-low transition-colors border border-outline-variant/60 gap-3">
<div>
<div class="text-title-md font-title-md text-on-surface">Kebersihan Kamar Mandi & Ruang Ganti</div>
<div class="text-body-sm font-body-sm text-on-surface-variant">Shower hangat, loker rapi, wangi</div>
</div>
<div class="flex items-center gap-1 text-tertiary-fixed-dim flex-shrink-0">
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-2xl text-outline-variant" data-icon="star">star</span>
<span class="text-label-md font-label-md text-on-surface font-bold ml-2">4.0</span>
</div>
</div>
<!-- Item 4 -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 rounded-xl bg-surface-container-lowest hover:bg-surface-container-low transition-colors border border-outline-variant/60 gap-3">
<div>
<div class="text-title-md font-title-md text-on-surface">Keramahan Petugas & Resepsionis</div>
<div class="text-body-sm font-body-sm text-on-surface-variant">Cepat, tanggap, ramah proses check-in</div>
</div>
<div class="flex items-center gap-1 text-tertiary-fixed-dim flex-shrink-0">
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-label-md font-label-md text-on-surface font-bold ml-2">5.0</span>
</div>
</div>
<!-- Item 5 -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 rounded-xl bg-surface-container-lowest hover:bg-surface-container-low transition-colors border border-outline-variant/60 gap-3">
<div>
<div class="text-title-md font-title-md text-on-surface">Area Parkir & Akses Lokasi</div>
<div class="text-body-sm font-body-sm text-on-surface-variant">Mudah dijangkau, parkir memadai & aman</div>
</div>
<div class="flex items-center gap-1 text-tertiary-fixed-dim flex-shrink-0">
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined filled text-2xl" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-2xl text-outline-variant" data-icon="star">star</span>
<span class="text-label-md font-label-md text-on-surface font-bold ml-2">4.0</span>
</div>
</div>
</div>
</section>
<!-- Section 3: Ulasan Tertulis -->
<section class="flex flex-col gap-3">
<div class="flex items-center justify-between">
<label class="text-title-lg font-title-lg text-on-surface" for="review-content">Ceritakan Pengalaman Bermain Anda</label>
<span class="text-body-sm font-body-sm text-outline">Minimal 30 karakter</span>
</div>
<textarea name="komentar" class="w-full p-4 rounded-xl border-1.5 border-outline-variant bg-surface-container-lowest text-body-md font-body-md text-on-surface placeholder:text-outline focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all resize-none" id="review-content" placeholder="Contoh: Karpet Li-Ning terasa empuk dan tidak bikin lutut cepat lelah. Pencahayaan terang tanpa bikin silau waktu smash lob belakang. Kantin sedia minuman dingin lengkap..." rows="4">Karpet Li-Ning terasa sangat empuk dan grip sepatunya mantap, tidak licin meski sudah main intens 2 jam. Lampu arena merata dan tidak menyilaukan mata saat mengambil kok atas. Fasilitas loker dan shower air panas berfungsi sangat baik. Pasti booking lagi untuk latihan minggu depan!</textarea>
<!-- Quick tags / Chips bantuan -->
<div>
<p class="text-label-md font-label-md text-on-surface-variant mb-2">Tag cepat untuk melengkapi ulasan:</p>
<div class="flex flex-wrap gap-2">
<button class="px-3 py-1.5 rounded-full bg-primary-container text-on-primary text-label-md font-label-md inline-flex items-center gap-1 shadow-2xs" type="button">
<span class="material-symbols-outlined text-sm" data-icon="check">check</span>
                  Karpet Kesat Anti-Selip
                </button>
<button class="px-3 py-1.5 rounded-full bg-primary-container text-on-primary text-label-md font-label-md inline-flex items-center gap-1 shadow-2xs" type="button">
<span class="material-symbols-outlined text-sm" data-icon="check">check</span>
                  Lampu Sangat Terang
                </button>
<button class="px-3 py-1.5 rounded-full bg-surface-container-lowest hover:bg-surface-container border border-outline-variant text-on-surface text-label-md font-label-md inline-flex items-center gap-1 transition-colors" type="button">
<span class="material-symbols-outlined text-sm text-outline" data-icon="add">add</span>
                  Shower Hangat Berfungsi
                </button>
<button class="px-3 py-1.5 rounded-full bg-surface-container-lowest hover:bg-surface-container border border-outline-variant text-on-surface text-label-md font-label-md inline-flex items-center gap-1 transition-colors" type="button">
<span class="material-symbols-outlined text-sm text-outline" data-icon="add">add</span>
                  Parkir Mobil Luas
                </button>
<button class="px-3 py-1.5 rounded-full bg-surface-container-lowest hover:bg-surface-container border border-outline-variant text-on-surface text-label-md font-label-md inline-flex items-center gap-1 transition-colors" type="button">
<span class="material-symbols-outlined text-sm text-outline" data-icon="add">add</span>
                  Lokasi Strategis
                </button>
<button class="px-3 py-1.5 rounded-full bg-surface-container-lowest hover:bg-surface-container border border-outline-variant text-on-surface text-label-md font-label-md inline-flex items-center gap-1 transition-colors" type="button">
<span class="material-symbols-outlined text-sm text-outline" data-icon="add">add</span>
                  Admin Tanggap
                </button>
</div>
</div>
</section>
<!-- Section 4: Unggah Foto / Video Lapangan -->
<section class="flex flex-col gap-4">
<div class="flex items-center justify-between">
<div>
<h3 class="text-title-lg font-title-lg text-on-surface">Unggah Foto / Video Lapangan</h3>
<p class="text-body-sm font-body-sm text-on-surface-variant">Tunjukkan visual asli kondisi lapangan terkini saat Anda bermain.</p>
</div>
<span class="text-label-md font-label-md text-outline">2 / 5 Foto</span>
</div>
<!-- Incentive Callout Badge -->
<div class="flex items-center gap-3 p-3.5 bg-tertiary-fixed/20 border border-tertiary-fixed-dim/40 rounded-xl">
<span class="material-symbols-outlined filled text-tertiary-fixed-dim text-2xl" data-icon="stars" style="font-variation-settings: 'FILL' 1;">stars</span>
<p class="text-body-sm font-body-sm text-tertiary">
<strong>Bonus Poin Review:</strong> Dapatkan <span class="font-bold text-primary-container">+50 Poin Loyalitas</span> & Kupon Diskon Rp 15.000 setelah foto ulasan Anda diverifikasi admin!
              </p>
</div>
<!-- Upload Grid & Preview Thumbnails -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
<!-- Drag & Drop Trigger Box -->
<div class="border-2 border-dashed border-outline-variant hover:border-primary-container rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer transition-colors bg-surface-container-low/50 h-32">
<span class="material-symbols-outlined text-3xl text-primary-container mb-1" data-icon="add_a_photo">add_a_photo</span>
<span class="text-label-md font-label-md text-on-surface font-medium">Unggah Foto</span>
<span class="text-[11px] text-outline mt-0.5">Maks 5 Foto (JPEG/PNG)</span>
</div>
<!-- Uploaded Thumbnail 1 -->
<div class="relative rounded-xl overflow-hidden border border-outline-variant h-32 group shadow-2xs">
<img class="w-full h-full object-cover" data-alt="High angle view of warm glow arena LED lights shining overhead across clean indoor badminton court lines, sports photography style." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBZ9jVA4xSTc6mJJW10XvZ_NrbBBSPNlNc8579LK2z7gp3FgnrLepAcb6kgIzgA0uibO7pnbyUPoHSlBCpmaDA1ThfiRFzA39eGbIKzag4DzKjStaCtXIJVrzxLmhadRC6QGPaDpQZKrarpYkZQbYIrNARb5Yf05wHAHUaBjQYjZcFcZCmRT9zTpFDsRexBxJX6W3ElMVbXgtAAXhVJSfd2Xb9ABFh3dDJqgkXFLI13QG9Kx8t1Mmo"/>
<button class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-on-background/70 text-surface flex items-center justify-center hover:bg-secondary transition-colors" title="Hapus foto" type="button">
<span class="material-symbols-outlined text-sm" data-icon="close">close</span>
</button>
<span class="absolute bottom-1.5 left-1.5 bg-on-background/70 text-surface text-[10px] px-1.5 py-0.5 rounded">Lampu Arena</span>
</div>
<!-- Uploaded Thumbnail 2 -->
<div class="relative rounded-xl overflow-hidden border border-outline-variant h-32 group shadow-2xs">
<img class="w-full h-full object-cover" data-alt="Close up view of taught dark red badminton net tautly stretched over polished court lines with shuttles in natural lighting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBmdI7ZIOhUM59cQt1hWMQe9Vas6iUhSE17kJ95oDilL9NXbGtxPlOtN1QzTM20lmakpiuufPq0AOR-j3YLbzKrGAPQiwM9ZmmgaImnuLzIz7qts-gszkuJMWtbVtPm4SWDFIzRm43NJ2T5uus-1tpzeaFOouto9S_kOrjjkmwhLjEvtqAEjYCA_zUJu08Ac462sdmpc92cqURRgagoBneMScS5fcdQEYdDAvQsgx8G-1lIoriY7hs"/>
<button class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-on-background/70 text-surface flex items-center justify-center hover:bg-secondary transition-colors" title="Hapus foto" type="button">
<span class="material-symbols-outlined text-sm" data-icon="close">close</span>
</button>
<span class="absolute bottom-1.5 left-1.5 bg-on-background/70 text-surface text-[10px] px-1.5 py-0.5 rounded">Net & Karpet</span>
</div>
<!-- Empty Slot placeholder -->
<div class="border border-dashed border-outline-variant/60 rounded-xl flex items-center justify-center text-outline-variant h-32">
<span class="material-symbols-outlined text-2xl" data-icon="image">image</span>
</div>
</div>
</section>
<!-- Section 5: Privasi & Rekomendasi -->
<section class="flex flex-col gap-6 pt-2 border-t border-outline-variant">
<!-- Question: Recommendation Toggle -->
<div>
<label class="text-title-md font-title-md text-on-surface block mb-2.5">
                Apakah Anda merekomendasikan venue ini ke teman/komunitas?
              </label>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
<label class="flex items-center gap-3 p-3.5 rounded-xl border-2 border-primary-container bg-primary-fixed/20 cursor-pointer transition-all">
<input checked="" class="text-primary-container focus:ring-primary-container w-4 h-4" name="recommendation" type="radio"/>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary-container filled" data-icon="thumb_up">thumb_up</span>
<span class="text-label-lg font-label-lg text-primary-container">Ya, Sangat Merekomendasikan</span>
</div>
</label>
<label class="flex items-center gap-3 p-3.5 rounded-xl border border-outline-variant bg-surface-container-lowest hover:bg-surface-container-low cursor-pointer transition-all">
<input class="text-primary-container focus:ring-primary-container w-4 h-4" name="recommendation" type="radio"/>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-outline" data-icon="thumb_down">thumb_down</span>
<span class="text-label-lg font-label-lg text-on-surface">Kurang Merekomendasikan</span>
</div>
</label>
</div>
</div>
<!-- Verified Name Checkbox -->
<label class="flex items-start gap-3 cursor-pointer">
<input checked="" class="rounded border-outline-variant text-primary-container focus:ring-primary-container w-5 h-5 mt-0.5" type="checkbox"/>
<div class="text-body-md font-body-md text-on-surface">
                Tampilkan nama saya sebagai <span class="font-semibold text-primary-container">Penyewa Terverifikasi ({{ $booking->user->name }})</span>
<p class="text-body-sm font-body-sm text-outline">Ulasan dengan nama asli terverifikasi mendapatkan bobot kepercayaan lebih tinggi di komunitas pemain.</p>
</div>
</label>
</section>
<!-- Actions Footer Buttons -->
<div class="pt-6 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4">
<button class="w-full sm:w-auto px-6 py-3 rounded-xl border-1.5 border-primary-container text-primary-container font-label-lg hover:bg-primary-container/5 active:scale-95 transition-all text-center" type="button">
              Simpan Draf
            </button>
<div class="flex items-center gap-3 w-full sm:w-auto">
<a class="w-full sm:w-auto px-5 py-3 rounded-xl text-on-surface-variant hover:text-on-surface font-label-lg text-center transition-colors" href="#">
                Kembali ke Riwayat
              </a>
<button class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-primary-container hover:bg-[#3A7D44] text-on-primary font-label-lg shadow-sm flex items-center justify-center gap-2 active:scale-95 transition-all" type="submit">
<span>Kirim Ulasan & Klaim Poin</span>
<span class="material-symbols-outlined text-lg" data-icon="arrow_forward">arrow_forward</span>
</button>
</div>
</div>
<input type="hidden" name="rating" id="rating-value" value="5">
<script>
document.querySelectorAll('[data-rating]').forEach(el => el.addEventListener('click', () => {
document.getElementById('rating-value').value = el.dataset.rating;
}));
</script></form>
</div>
<!-- RIGHT SIDEBAR (Col 9-12): Community Review Guidelines -->
<aside class="lg:col-span-4 flex flex-col gap-6 sticky top-28">
<!-- Guidance Box -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 shadow-sm">
<div class="flex items-center gap-2 text-primary-container mb-4">
<span class="material-symbols-outlined text-2xl" data-icon="policy">policy</span>
<h3 class="text-title-lg font-title-lg text-on-surface">Panduan Ulasan Komunitas</h3>
</div>
<p class="text-body-sm font-body-sm text-on-surface-variant mb-4">
            Booking Lapang menghadirkan ulasan jujur dari atlet dan penggemar olahraga nyata. Mohon perhatikan panduan berikut:
          </p>
<ul class="space-y-4 text-body-sm font-body-sm text-on-surface">
<li class="flex items-start gap-3">
<div class="w-6 h-6 rounded-full bg-primary-fixed/40 text-primary-container flex items-center justify-center flex-shrink-0 mt-0.5">
<span class="material-symbols-outlined text-base" data-icon="verified_user">verified_user</span>
</div>
<div>
<strong>Jujur & Otentik</strong>
<p class="text-on-surface-variant text-xs mt-0.5">Berikan penilaian yang obyektif berdasarkan pengalaman langsung saat menggunakan sarana lapangan.</p>
</div>
</li>
<li class="flex items-start gap-3">
<div class="w-6 h-6 rounded-full bg-primary-fixed/40 text-primary-container flex items-center justify-center flex-shrink-0 mt-0.5">
<span class="material-symbols-outlined text-base" data-icon="sports_score">sports_score</span>
</div>
<div>
<strong>Fokus pada Aspek Lapangan</strong>
<p class="text-on-surface-variant text-xs mt-0.5">Bahas kondisi karpet, pencahayaan, ventilasi/AC, serta kesiapan fasilitas sanitasi dan staf.</p>
</div>
</li>
<li class="flex items-start gap-3">
<div class="w-6 h-6 rounded-full bg-secondary-fixed/40 text-secondary flex items-center justify-center flex-shrink-0 mt-0.5">
<span class="material-symbols-outlined text-base" data-icon="gavel">gavel</span>
</div>
<div>
<strong>Bebas Ujaran Negatif & Spam</strong>
<p class="text-on-surface-variant text-xs mt-0.5">Hindari kata kasar, promosi eksternal tanpa izin, maupun informasi pribadi orang lain.</p>
</div>
</li>
</ul>
<div class="mt-6 pt-4 border-t border-outline-variant text-center">
<a class="text-label-md font-label-md text-primary-container hover:underline inline-flex items-center gap-1" href="#">
              Baca Syarat & Ketentuan Ulasan
              <span class="material-symbols-outlined text-xs" data-icon="open_in_new">open_in_new</span>
</a>
</div>
</div>
<!-- Venue Summary Small Card -->
<div class="bg-surface-container-low border border-outline-variant rounded-2xl p-5 shadow-2xs">
<div class="flex items-center gap-2 mb-2">
<span class="material-symbols-outlined text-secondary text-xl" data-icon="contact_support">contact_support</span>
<h4 class="text-title-md font-title-md text-on-surface">Punya Masalah Booking?</h4>
</div>
<p class="text-body-sm font-body-sm text-on-surface-variant mb-3">
            Jika ada komplain terkait selisih jam main atau pengembalian dana deposit sewa, silakan hubungi tim CS kami.
          </p>
<a class="w-full py-2 px-4 rounded-xl border border-outline-variant bg-surface-container-lowest text-center text-label-md font-label-md text-on-surface hover:bg-surface-container block transition-colors" href="#">
            Pusat Resolusi Booking
          </a>
</div>
</aside>
</div>
</main>
@endsection
