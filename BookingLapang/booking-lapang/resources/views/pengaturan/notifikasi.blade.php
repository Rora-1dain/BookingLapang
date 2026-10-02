@extends('layouts.frontend')
@section('title','Pengaturan Notifikasi - Booking Lapang')
@section('content')
<main class="flex-grow w-full max-w-3xl mx-auto px-6 md:px-12 py-8">
    <h1 class="text-headline-lg font-headline-lg text-on-surface tracking-tight">Pengaturan Notifikasi</h1>
    <p class="text-body-md font-body-md text-on-surface-variant mt-1 mb-6">
        Pilih lewat mana kamu mau menerima tiap jenis notifikasi. Kalau semuanya dimatikan, notifikasi tetap
        masuk in-app. Notifikasi penting (hasil verifikasi, ulasan buruk, refund gagal) selalu dikirim lewat email
        dan tidak bisa dimatikan.
    </p>

    @if (session('success'))
        <p class="mb-4 px-4 py-3 rounded-lg bg-primary-fixed text-on-primary-fixed text-sm font-semibold">{{ session('success') }}</p>
    @endif
    @if ($errors->any())
        <p class="mb-4 px-4 py-3 rounded-lg bg-error-container text-on-error-container text-sm font-semibold">Preferensi gagal disimpan. Coba lagi.</p>
    @endif

    <form method="POST" action="{{ route('notifikasi.preferensi.update') }}" class="tactile-card rounded-2xl p-5 tactile-shadow">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-[1fr_auto_auto] gap-x-6 gap-y-4 items-center">
            <span class="text-label-sm font-label-sm text-on-surface-variant uppercase">Jenis</span>
            <span class="text-label-sm font-label-sm text-on-surface-variant uppercase text-center">Email</span>
            <span class="text-label-sm font-label-sm text-on-surface-variant uppercase text-center">In-app</span>

            @foreach ($tipe as $kunci => [$label, $deskripsi])
                <div>
                    <p class="font-semibold text-on-surface">{{ $label }}</p>
                    <p class="text-body-sm text-on-surface-variant">{{ $deskripsi }}</p>
                </div>
                @foreach (['email', 'database'] as $channel)
                    <div class="text-center">
                        {{-- hidden 0: checkbox yang tidak dicentang tidak terkirim, padahal validator master mewajibkan boolean --}}
                        <input type="hidden" name="preferensi[{{ $kunci }}][{{ $channel }}]" value="0">
                        <input type="checkbox"
                               name="preferensi[{{ $kunci }}][{{ $channel }}]"
                               value="1"
                               class="w-5 h-5 rounded"
                               @checked($preferensi[$kunci][$channel])>
                    </div>
                @endforeach
            @endforeach
        </div>

        <button type="submit" class="mt-6 bg-primary text-on-primary font-bold px-5 py-2.5 rounded-xl">Simpan</button>
    </form>
</main>
@endsection