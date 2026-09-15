<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Pengaturan Jadwal Operasional Lapangan - Booking Lapang Partner</title>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "on-error": "#ffffff","tertiary-container": "#5f4800","on-secondary-container": "#731a04",
            "secondary-container": "#ff8162","surface-container-low": "#f7f3ec","tertiary-fixed-dim": "#e7c268",
            "on-primary-fixed": "#00210c","on-tertiary-container": "#dcb85f","surface": "#fdf9f2",
            "on-primary-container": "#8fca9b","surface-tint": "#326942","on-secondary-fixed-variant": "#83260e",
            "secondary-fixed-dim": "#ffb4a2","primary-fixed-dim": "#99d4a4","on-surface": "#1c1c18",
            "on-surface-variant": "#414941","outline": "#717970","on-tertiary": "#ffffff",
            "on-primary-fixed-variant": "#18512c","primary-fixed": "#b5f1bf","on-tertiary-fixed-variant": "#5a4400",
            "surface-container": "#f1ede6","on-secondary-fixed": "#3c0700","surface-variant": "#e6e2db",
            "inverse-on-surface": "#f4f0e9","tertiary": "#443200","error-container": "#ffdad6",
            "inverse-primary": "#99d4a4","primary-container": "#1e5631","surface-container-high": "#ebe8e1",
            "background": "#fdf9f2","surface-dim": "#dddad3","outline-variant": "#c0c9be","primary": "#003e1c",
            "on-tertiary-fixed": "#251a00","on-primary": "#ffffff","on-secondary": "#ffffff",
            "secondary-fixed": "#ffdad2","on-background": "#1c1c18","surface-container-lowest": "#ffffff",
            "tertiary-fixed": "#ffdf96","error": "#ba1a1a","secondary": "#a33d23","on-error-container": "#93000a",
            "surface-container-highest": "#e6e2db","surface-bright": "#fdf9f2","inverse-surface": "#31302c"
          },
          borderRadius: { DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", full: "9999px" },
        },
      },
    }
</script>
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col">

<aside class="h-screen w-72 flex flex-col fixed left-0 top-0 z-30 bg-primary-container text-on-primary border-r border-outline-variant shadow-sm select-none">
<div class="flex flex-col justify-between h-full p-4">
<div class="flex flex-col gap-6">
<div class="flex items-center gap-3 px-2 pt-2">
<div class="w-11 h-11 rounded-xl bg-surface flex items-center justify-center text-primary shadow-sm border border-outline-variant">
<span class="material-symbols-outlined text-2xl">sports_tennis</span>
</div>
<div class="flex flex-col">
<div class="flex items-center gap-1.5">
<span class="font-bold text-lg text-on-primary">{{ $lapangan->nama ?? 'Booking Lapang Partner' }}</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">verified</span>
</div>
<span class="text-xs text-on-primary-container tracking-wide">{{ $lapangan->lokasi ?? '' }}</span>
</div>
</div>
<div class="h-px bg-on-primary-container/20 mx-2"></div>
<nav class="flex flex-col gap-1.5">
<a class="flex items-center gap-3 px-4 py-3 rounded-lg bg-surface text-primary font-bold shadow-sm" href="{{ route('pemilik.jadwal.edit', $lapangan) }}">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">schedule</span>
<span class="text-sm font-bold">Jadwal Operasional</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-primary-container hover:text-on-primary hover:bg-primary/20 transition-colors" href="{{ route('pemilik.hari-libur.index', $lapangan) }}">
<span class="material-symbols-outlined">event_busy</span>
<span class="text-sm">Hari Libur Khusus</span>
</a>
</nav>
</div>
</div>
</aside>

<header class="fixed top-0 right-0 left-72 z-20 flex justify-between items-center px-8 h-16 bg-surface border-b border-outline-variant shadow-sm">
<nav class="flex items-center gap-2 text-xs text-outline">
<span class="font-bold text-primary">Booking Lapang Partner</span>
<span class="material-symbols-outlined text-sm">chevron_right</span>
<span class="text-on-surface font-semibold">Pengaturan Jadwal Operasional</span>
</nav>
</header>

<main class="ml-72 pt-20 pb-28 px-10 max-w-7xl w-full flex-1 flex flex-col gap-6">

@if (session('success'))
    <div class="p-4 rounded-xl bg-primary-container/10 border border-primary-container text-primary font-semibold">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('pemilik.jadwal.update', $lapangan) }}" id="formJadwal">
@csrf
@method('PUT')

<section class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm mb-6">
<div class="flex flex-col gap-1.5">
<h1 class="text-2xl font-bold text-on-surface">Pengaturan Jadwal Operasional Lapangan</h1>
<p class="text-sm text-on-surface-variant max-w-2xl">
    Jadwal ini menentukan kapan penyewa bisa booking lapangan {{ $lapangan->nama ?? '' }} secara online.
</p>
</div>
</section>

<section class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden flex flex-col">
<div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low flex items-center gap-2">
<span class="material-symbols-outlined text-primary-container">calendar_today</span>
<h2 class="text-base font-semibold text-on-surface">Rincian Hari &amp; Rentang Waktu Buka</h2>
</div>

<div class="divide-y divide-outline-variant/60">
@php
    $urutanHari = [
        1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis',
        5 => 'Jumat', 6 => 'Sabtu', 0 => 'Minggu',
    ];
@endphp
@foreach ($urutanHari as $hari => $namaHari)
    @php
        $j = $jadwal->get($hari);
        $isTutup = old("jadwal.$hari.is_tutup", $j->is_tutup ?? false);
        $jamBuka = old("jadwal.$hari.jam_buka", $j->jam_buka ?? '06:00');
        $jamTutup = old("jadwal.$hari.jam_tutup", $j->jam_tutup ?? '23:00');
    @endphp
    <div class="p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 hover:bg-surface/50 transition-colors" data-hari-row>
        <div class="flex items-center gap-4 min-w-[200px]">
            <div class="w-12 h-12 rounded-xl bg-surface-container flex flex-col items-center justify-center border border-outline-variant">
                <span class="text-xs font-extrabold text-on-surface-variant">{{ strtoupper(substr($namaHari, 0, 3)) }}</span>
            </div>
            <span class="text-base font-semibold text-on-surface">{{ $namaHari }}</span>
        </div>

        <div class="flex items-center gap-3">
            <input type="hidden" name="jadwal[{{ $hari }}][is_tutup]" value="0">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="jadwal[{{ $hari }}][is_tutup]" value="1"
                       class="sr-only peer toggle-tutup" {{ $isTutup ? 'checked' : '' }}>
                <div class="w-11 h-6 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-outline-variant after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"></div>
            </label>
            <span class="text-sm font-bold status-label">{{ $isTutup ? 'Tutup Sepanjang Hari' : 'Buka' }}</span>
        </div>

        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap jam-fields {{ $isTutup ? 'opacity-50' : '' }}">
            <div class="flex items-center gap-2 bg-surface-container-low px-3 py-2 rounded-xl border border-outline-variant">
                <span class="material-symbols-outlined text-outline text-lg">alarm</span>
                <input type="time" name="jadwal[{{ $hari }}][jam_buka]" value="{{ $jamBuka }}"
                       class="bg-transparent border-none text-sm font-bold text-on-surface focus:ring-0 p-0" {{ $isTutup ? 'disabled' : '' }}>
            </div>
            <span class="text-outline font-bold">—</span>
            <div class="flex items-center gap-2 bg-surface-container-low px-3 py-2 rounded-xl border border-outline-variant">
                <span class="material-symbols-outlined text-outline text-lg">bedtime</span>
                <input type="time" name="jadwal[{{ $hari }}][jam_tutup]" value="{{ $jamTutup }}"
                       class="bg-transparent border-none text-sm font-bold text-on-surface focus:ring-0 p-0" {{ $isTutup ? 'disabled' : '' }}>
            </div>
        </div>
    </div>
@endforeach
</div>
</section>

<aside class="fixed bottom-0 right-0 left-72 z-30 px-10 py-4 bg-surface-container-lowest border-t border-outline-variant shadow-[0_-4px_16px_rgba(27,46,31,0.06)] flex items-center justify-end gap-3">
<button type="submit" class="flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-primary-container text-on-primary text-sm font-bold shadow-sm hover:brightness-110 active:scale-98 transition-all">
    <span class="material-symbols-outlined text-lg">check_circle</span>
    <span>Simpan &amp; Terapkan Jadwal Operasional</span>
</button>
</aside>

</form>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toggle-tutup').forEach(box => {
            box.addEventListener('change', (e) => {
                const row = e.target.closest('[data-hari-row]');
                const label = row.querySelector('.status-label');
                const jamFields = row.querySelector('.jam-fields');
                const inputs = jamFields.querySelectorAll('input[type="time"]');

                if (e.target.checked) {
                    label.textContent = 'Tutup Sepanjang Hari';
                    jamFields.classList.add('opacity-50');
                    inputs.forEach(i => i.disabled = true);
                } else {
                    label.textContent = 'Buka';
                    jamFields.classList.remove('opacity-50');
                    inputs.forEach(i => i.disabled = false);
                }
            });
        });
    });
</script>
</body>
</html>