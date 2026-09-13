@extends('layouts.frontend')
@section('title', 'Leaderboard Referral - Booking Lapang')
@section('content')

<section class="py-12 md:py-16 bg-surface-container-low border-b border-outline-variant">
    <div class="max-w-3xl mx-auto px-6 md:px-12 text-center">
        <div class="inline-flex items-center gap-2 bg-surface-container-lowest border border-outline-variant px-3.5 py-1.5 rounded-full mb-5 shadow-sm">
            <span class="material-symbols-outlined text-tertiary-fixed-dim text-[18px] fill-icon">trophy</span>
            <span class="font-label-sm text-label-sm text-primary tracking-wide">LEADERBOARD REFERRAL BULAN INI</span>
        </div>
        <h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-background tracking-tight">
            Komunitas Paling Aktif Mengajak Bermain
        </h1>
        <p class="mt-3 font-body-lg text-body-lg text-on-surface-variant max-w-xl mx-auto">
            Peringkat pengguna dengan jumlah teman terbanyak yang berhasil diajak bergabung ke Booking Lapang bulan ini.
        </p>
    </div>
</section>

<section class="py-12 md:py-16">
    <div class="max-w-2xl mx-auto px-6 md:px-12">
        <div class="bg-surface-container-lowest p-6 md:p-8 rounded-2xl border border-outline-variant shadow-grass-resting flex flex-col gap-5">

            <div class="flex items-center justify-between">
                <h2 class="text-title-lg font-title-lg text-primary tracking-tight">Peringkat Referral</h2>
                <span class="material-symbols-outlined text-tertiary-fixed-dim text-2xl fill-icon">trophy</span>
            </div>

            @if($topReferrer->isEmpty())
                <div class="text-center py-10 text-on-surface-variant font-body-md text-body-md">
                    Belum ada data referral bulan ini. Jadilah yang pertama mengajak teman bermain!
                </div>
            @else
                <div class="flex flex-col gap-2.5">
                    @foreach($topReferrer as $index => $user)
                        @php
                            $rank = $index + 1;
                            $isMe = auth()->check() && auth()->id() === $user->id;
                        @endphp
                        <div class="p-3.5 rounded-xl flex items-center justify-between
                            @if($rank === 1) bg-surface-container-low border border-tertiary-fixed
                            @elseif($isMe) bg-surface-container border-2 border-primary-container shadow-sm
                            @else bg-surface-container-lowest border border-outline-variant @endif">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-label-md shrink-0
                                    @if($rank === 1) bg-tertiary-fixed text-tertiary-container shadow-sm
                                    @elseif($isMe) bg-primary-container text-white
                                    @else bg-surface-container-high text-on-surface @endif">
                                    {{ $rank }}
                                </div>
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-title-md font-title-md font-bold {{ $rank === 1 || $isMe ? 'text-primary' : 'text-on-surface' }}">
                                            {{ $user->name }}
                                        </span>
                                        @if($isMe)
                                            <span class="px-2 py-0.5 bg-primary-container text-white rounded text-label-sm font-label-sm">Kamu</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end shrink-0">
                                <span class="text-title-lg font-title-lg font-extrabold {{ $rank === 1 || $isMe ? 'text-primary' : 'text-on-surface' }}">{{ $user->referrals_count }}</span>
                                <span class="text-label-sm font-label-sm text-on-surface-variant">Teman</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="bg-surface-container-low p-3.5 rounded-xl border border-outline-variant text-center">
                <p class="text-body-sm font-body-sm text-on-surface-variant">
                    @auth
                        Ajak lebih banyak teman lewat halaman <a href="{{ route('referral.index') }}" class="text-primary font-bold hover:underline">Ajak Teman</a> untuk naik peringkat.
                    @else
                        <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">Daftar sekarang</a> dan mulai ajak teman untuk masuk ke leaderboard.
                    @endauth
                </p>
            </div>
        </div>
    </div>
</section>

@endsection