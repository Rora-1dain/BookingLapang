<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Kelola Hari Libur Khusus - Booking Lapang Partner</title>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "on-tertiary-container": "#dcb85f","on-background": "#1c1c18","surface-container-highest": "#e6e2db",
            "on-surface-variant": "#414941","tertiary-fixed": "#ffdf96","on-tertiary": "#ffffff",
            "error-container": "#ffdad6","on-secondary": "#ffffff","error": "#ba1a1a","primary-fixed": "#b5f1bf",
            "background": "#fdf9f2","secondary-container": "#ff8162","on-primary": "#ffffff",
            "secondary-fixed": "#ffdad2","tertiary-container": "#5f4800","surface-variant": "#e6e2db",
            "surface-container": "#f1ede6","surface-container-lowest": "#ffffff","on-error-container": "#93000a",
            "primary-container": "#1e5631","outline-variant": "#c0c9be","primary": "#003e1c",
            "on-surface": "#1c1c18","on-primary-container": "#8fca9b","surface": "#fdf9f2",
            "secondary": "#a33d23","outline": "#717970","surface-container-low": "#f7f3ec","on-error": "#ffffff"
          },
          borderRadius: { DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", full: "9999px" },
        }
      }
    }
</script>
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .material-symbols-outlined.fill-icon { font-variation-settings: 'FILL' 1, 'wght' 500, 'GRAD' 0, 'opsz' 24; }
    body { background-color: #fdf9f2; color: #1c1c18; font-family: 'Plus Jakarta Sans', sans-serif; }
</style>
</head>
<body class="bg-background text-on-background antialiased flex min-h-screen">

<aside class="h-screen w-72 flex flex-col fixed left-0 top-0 z-30 bg-primary-container text-on-primary border-r border-outline-variant/30 shadow-sm">
<div class="flex flex-col justify-between h-full p-4">
<div>
<div class="flex items-center gap-3 px-3 py-3 rounded-xl bg-primary/40 border border-primary-fixed/20 mb-6">
<div class="w-11 h-11 rounded-lg bg-surface-container-lowest text-primary flex items-center justify-center font-bold shadow-sm overflow-hidden flex-shrink-0">
<span class="material-symbols-outlined fill-icon text-primary text-2xl">sports_tennis</span>
</div>
<div class="min-w-0 flex-1">
<div class="flex items-center gap-1.5">
<span class="font-bold text-on-primary truncate">{{ $lapangan->nama ?? 'Booking Lapang Partner' }}</span>
<span class="material-symbols-outlined fill-icon text-[#e9c46a] text-sm">verified</span>
</div>
<p class="text-xs text-on-primary-container truncate">{{ $lapangan->lokasi ?? '' }}</p>
</div>
</div>
<nav class="space-y-1.5">
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-primary-container hover:text-on-primary hover:bg-primary/20 transition-colors" href="{{ route('pemilik.jadwal.edit', $lapangan) }}">
<span class="material-symbols-outlined text-xl">schedule</span>
<span class="text-sm">Jadwal Operasional</span>
</a>
<a class="flex items-center justify-between px-4 py-3 rounded-lg bg-surface text-primary font-bold shadow-sm" href="{{ route('pemilik.hari-libur.index', $lapangan) }}">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined fill-icon text-primary text-xl">event_busy</span>
<span class="text-sm">Hari Libur</span>
</div>
<span class="bg-primary/10 text-primary font-bold px-2 py-0.5 rounded-full text-xs">Aktif</span>
</a>
</nav>
</div>
</div>
</aside>

<header class="fixed top-0 right-0 left-72 z-20 flex justify-between items-center px-8 h-16 bg-surface-container-lowest border-b border-surface-container-highest">
<nav class="flex items-center gap-2 text-on-surface-variant text-sm">
<span>Booking Lapang Partner</span>
<span class="material-symbols-outlined text-xs text-outline">chevron_right</span>
<span class="text-primary font-bold">Kelola Hari Libur Khusus</span>
</nav>
</header>

<main class="ml-72 pt-20 pb-16 px-10 w-[calc(100%-18rem)] max-w-7xl">

@if (session('success'))
    <div class="mb-6 p-4 rounded-xl bg-primary-container/10 border border-primary-container text-primary font-semibold">
        {{ session('success') }}
    </div>
@endif

<section class="mb-8">
<div class="flex items-center gap-2.5 mb-1">
<span class="p-1.5 rounded-md bg-primary/10 text-primary inline-flex">
<span class="material-symbols-outlined fill-icon text-xl">event_busy</span>
</span>
<h1 class="text-2xl text-on-surface font-extrabold tracking-tight">
    Kelola Hari Libur Khusus — {{ $lapangan->nama ?? '' }}
</h1>
</div>
<p class="text-sm text-on-surface-variant max-w-4xl">
    Atur tanggal merah, turnamen internal, atau renovasi mendadak. Pada tanggal yang dipilih, seluruh slot lapangan ini otomatis ditutup dan tidak dapat dipesan.
</p>

<div class="mt-5 bg-[#fff8e7] border border-[#e9c46a]/60 rounded-xl p-4 flex items-start gap-3.5">
<span class="material-symbols-outlined text-[#a67c00] text-2xl flex-shrink-0 mt-0.5">notification_important</span>
<div class="text-on-surface">
<p class="text-base font-semibold text-[#5f4800]">Pemberitahuan Otomatis &amp; Proteksi Slot</p>
<p class="text-sm text-on-surface-variant mt-0.5">
    Menambah hari libur tidak membatalkan reservasi pelanggan yang sudah dibayar secara otomatis. Periksa daftar booking masuk untuk reschedule atau refund bila ada jadwal bertabrakan.
</p>
</div>
</div>
</section>

<section class="bg-surface-container-lowest border border-[#E8E0D3] rounded-2xl p-6 shadow-sm mb-10">
<div class="flex items-center gap-2.5 pb-4 mb-5 border-b border-[#E8E0D3]">
<div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
<span class="material-symbols-outlined fill-icon text-lg">event_available</span>
</div>
<div>
<h2 class="text-lg font-bold text-on-surface">+ Tambah Tanggal Libur</h2>
<p class="text-sm text-on-surface-variant">Tanggal ini akan tertutup total untuk lapangan {{ $lapangan->nama ?? 'ini' }}.</p>
</div>
</div>

<form method="POST" action="{{ route('pemilik.hari-libur.store', $lapangan) }}" class="space-y-4">
@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
<label class="block text-sm font-semibold text-on-surface mb-1.5">Tanggal Libur <span class="text-secondary">*</span></label>
<div class="relative">
<input type="date" name="tanggal" required min="{{ now()->toDateString() }}"
       class="w-full bg-surface-container-lowest border border-[#E8E0D3] rounded-xl px-3.5 py-2.5 text-sm text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
</div>
@error('tanggal')<p class="text-xs text-secondary mt-1">{{ $message }}</p>@enderror
</div>

<div>
<label class="block text-sm font-semibold text-on-surface mb-1.5">Keterangan Alasan Penutupan</label>
<input type="text" name="keterangan" id="inputKeterangan" maxlength="255"
       placeholder="Contoh: Libur Nasional / Turnamen Internal / Perawatan Rutin"
       class="w-full bg-surface-container-lowest border border-[#E8E0D3] rounded-xl px-3.5 py-2.5 text-sm text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
<div class="flex flex-wrap gap-1.5 mt-2">
<span class="text-xs text-on-surface-variant">Saran cepat:</span>
@foreach (['Libur Nasional', 'Perawatan Rutin', 'Turnamen Internal'] as $saran)
    <button type="button" class="saran-cepat text-xs bg-surface-container hover:bg-surface-container-high px-2 py-0.5 rounded text-on-surface transition" data-value="{{ $saran }}">{{ $saran }}</button>
@endforeach
</div>
</div>
</div>

<div class="pt-2 flex justify-end">
<button type="submit" class="bg-[#1e5631] hover:bg-[#3a7d44] text-white px-6 py-2.5 rounded-xl text-sm font-bold flex items-center justify-center gap-2 shadow-md transition-all active:scale-98">
<span class="material-symbols-outlined text-xl">add_circle</span>
<span>Tambah Tanggal Libur</span>
</button>
</div>
</form>
</section>

<section class="bg-surface-container-lowest border border-[#E8E0D3] rounded-2xl p-6 shadow-sm mb-12">
<div class="pb-5 border-b border-[#E8E0D3]">
<h2 class="text-lg font-bold text-on-surface">Daftar Tanggal Libur Terjadwal</h2>
<p class="text-sm text-on-surface-variant mt-0.5">Jadwal penutupan yang aktif di sistem pencarian penyewa untuk {{ $lapangan->nama ?? 'lapangan ini' }}.</p>
</div>

<div class="divide-y divide-[#E8E0D3]/80 mt-2">
@forelse ($hariLiburs as $h)
    @php $tgl = \Carbon\Carbon::parse($h->tanggal); @endphp
    <article class="py-4 hover:bg-surface-container-low/50 px-2 rounded-xl transition-colors flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5 min-w-0">
            <div class="w-12 h-12 rounded-xl bg-[#fff8e7] border border-[#e9c46a]/40 text-[#5f4800] flex flex-col items-center justify-center flex-shrink-0 shadow-sm">
                <span class="text-lg leading-none font-bold">{{ $tgl->format('d') }}</span>
                <span class="text-[10px] font-bold uppercase tracking-wider">{{ $tgl->translatedFormat('M') }}</span>
            </div>
            <div class="space-y-1 min-w-0">
                <h3 class="text-base text-on-surface font-bold truncate">{{ $h->keterangan ?: 'Tanpa keterangan' }}</h3>
                <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-on-surface-variant text-sm">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-base text-outline">date_range</span>
                        {{ $tgl->translatedFormat('d F Y') }}
                    </span>
                    <span class="flex items-center gap-1 text-primary">
                        <span class="material-symbols-outlined text-base">lock</span>
                        Tutup Sepanjang Hari
                    </span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 self-end lg:self-center flex-shrink-0">
            <form method="POST" action="{{ route('pemilik.hari-libur.destroy', [$lapangan, $h]) }}"
                  onsubmit="return confirm('Hapus tanggal libur ini?')">
                @csrf
                @method('DELETE')
                <button class="border border-[#e76f51] text-[#e76f51] hover:bg-[#ffdad2]/40 active:scale-95 px-3 py-1.5 rounded-lg text-sm font-semibold flex items-center gap-1.5 transition">
                    <span class="material-symbols-outlined text-base">delete</span>
                    <span>Hapus</span>
                </button>
            </form>
        </div>
    </article>
@empty
    <div class="max-w-md mx-auto py-10 text-center flex flex-col items-center">
        <div class="w-20 h-20 rounded-full bg-[#ffdad2]/40 text-[#e76f51] flex items-center justify-center mb-4 border border-[#e76f51]/20">
            <span class="material-symbols-outlined text-4xl">calendar_month</span>
        </div>
        <h3 class="text-lg text-on-surface font-bold">Belum ada tanggal libur khusus ditambahkan</h3>
        <p class="text-sm text-on-surface-variant mt-2 leading-relaxed">
            Lapangan ini saat ini buka normal mengikuti jam operasional mingguan. Tambahkan tanggal libur lewat form di atas.
        </p>
    </div>
@endforelse
</div>
</section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.saran-cepat').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('inputKeterangan').value = btn.dataset.value;
            });
        });
    });
</script>
</body>
</html>