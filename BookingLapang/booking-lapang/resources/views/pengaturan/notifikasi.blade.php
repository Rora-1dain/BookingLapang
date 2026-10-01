@extends('layouts.frontend')
@section('title','Pengaturan Notifikasi - Booking Lapang')
@section('content')
<main class="flex-grow w-full max-w-3xl mx-auto px-6 md:px-12 py-8">
    <h1 class="text-headline-lg font-headline-lg text-on-surface tracking-tight">Pengaturan Notifikasi</h1>
    <p class="text-body-md font-body-md text-on-surface-variant mt-1 mb-6">
        Pilih lewat mana kamu mau menerima tiap jenis notifikasi. Notifikasi keamanan dan kegagalan refund
        selalu dikirim lewat email dan tidak bisa dimatikan.
    </p>

    @if (session('status') === 'preferensi-disimpan')
        <p class="mb-4 px-4 py-3 rounded-lg bg-primary-fixed text-on-primary-fixed text-sm font-semibold">Preferensi tersimpan.</p>
    @endif

    <form method="POST" action="{{ route('pengaturan.notifikasi.update') }}" class="tactile-card rounded-2xl p-5 tactile-shadow">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-[1fr_auto_auto] gap-x-6 gap-y-3 items-center">
            <span class="text-label-sm font-label-sm text-on-surface-variant uppercase">Jenis</span>
            <span class="text-label-sm font-label-sm text-on-surface-variant uppercase text-center">Email</span>
            <span class="text-label-sm font-label-sm text-on-surface-variant uppercase text-center">In-app</span>

            @foreach ($jenis as $kunci => [$label, , $transaksional])
                <div>
                    <p class="font-semibold text-on-surface">{{ $label }}</p>
                    @if ($transaksional)
                        <p class="text-body-sm text-on-surface-variant">Penting: minimal tetap masuk in-app.</p>
                    @elseif ($kunci === 'promo')
                        <p class="text-body-sm text-on-surface-variant">Nonaktif sampai kamu aktifkan.</p>
                    @endif
                </div>
                @foreach (['mail', 'database'] as $channel)
                    <div class="text-center">
                        <input type="checkbox"
                               name="notifikasi[{{ $kunci }}][]"
                               value="{{ $channel }}"
                               class="w-5 h-5 rounded"
                               @checked(in_array($channel, $preferensi[$kunci]))>
                    </div>
                @endforeach
            @endforeach
        </div>

        <button type="submit" class="mt-6 bg-primary text-on-primary font-bold px-5 py-2.5 rounded-xl">Simpan</button>
    </form>
</main>
@endsection