<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Booking Lapang')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }
        .material-symbols-outlined.fill-icon {
            font-variation-settings: 'FILL' 1, 'wght' 600, 'GRAD' 0, 'opsz' 24;
        }
        [x-cloak] { display: none !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-background text-on-surface font-body-md antialiased selection:bg-primary selection:text-on-primary" x-data="{ open: false }">
<div class="min-h-screen flex flex-col">

    {{-- ==================== TOP NAV BAR (Shared Component) ==================== --}}
    <header class="bg-surface sticky top-0 z-50 shadow-sm">
        <div class="flex justify-between items-center w-full px-6 md:px-12 max-w-7xl mx-auto h-20 gap-4">
            <div class="flex items-center gap-5 shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="Booking Lapang - Beranda">
                    <span class="w-9 h-9 rounded-xl bg-primary-container flex items-center justify-center text-on-primary">
                        <span class="material-symbols-outlined text-[20px]">sports_soccer</span>
                    </span>
                    <span class="hidden sm:block font-title-lg text-title-lg text-primary font-bold leading-tight">Booking<br class="hidden xl:block">Lapang</span>
                </a>

                {{-- Location switcher chip. Decorative for now — no city-switching backend yet. --}}
                <button type="button" class="hidden md:inline-flex items-center gap-1.5 px-3 py-2 rounded-lg hover:bg-surface-container-low transition-colors text-left" title="Ganti area (segera hadir)">
                    <span class="material-symbols-outlined text-primary text-[18px]">location_on</span>
                    <span class="font-label-md text-label-md text-on-surface leading-tight">Jabodetabek &amp;<br>Bandung</span>
                </button>
            </div>

            <nav class="hidden lg:flex items-center gap-1" aria-label="Navigasi utama">
                <a href="{{ route('lapangan.publik.index') }}" class="px-3.5 py-2 rounded-lg font-label-lg text-label-lg transition-colors {{ request()->routeIs('lapangan.*') ? 'text-primary font-bold underline underline-offset-4 decoration-2' : 'text-on-surface-variant hover:text-primary hover:bg-primary/5' }}">Cari Lapangan</a>
                <a href="{{ route('referral.leaderboard') }}" class="px-3.5 py-2 rounded-lg font-label-lg text-label-lg transition-colors {{ request()->routeIs('referral.leaderboard') ? 'text-primary bg-primary/10' : 'text-on-surface-variant hover:text-primary hover:bg-primary/5' }}">Komunitas</a>
                <a href="#" class="px-3.5 py-2 rounded-lg font-label-lg text-label-lg text-on-surface-variant hover:text-primary hover:bg-primary/5 transition-colors">Promo &amp; Turnamen</a>
                <a href="{{ auth()->check() ? route('pemilik.lapangan.create') : route('login') }}" class="px-3.5 py-2 rounded-lg font-label-lg text-label-lg text-on-surface-variant hover:text-primary hover:bg-primary/5 transition-colors">Daftarkan Lapangan</a>
            </nav>

            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                @auth
                    <button type="button" title="Notifikasi" aria-label="Notifikasi" class="hidden sm:inline-flex w-10 h-10 rounded-full items-center justify-center text-on-surface-variant hover:bg-surface-container-high hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                @endauth

                <a href="https://wa.me/6281234567890" target="_blank" class="hidden md:inline-flex font-label-lg text-label-lg text-on-surface-variant hover:text-primary px-3 py-2 transition-colors">Bantuan</a>

                @auth
                    <div class="relative" x-data="{ userMenu: false }" @click.outside="userMenu = false">
                        <button type="button" @click="userMenu = !userMenu" class="flex items-center gap-2 pl-1 pr-2 sm:pr-3 py-1 rounded-full border border-outline-variant hover:border-primary transition-colors">
                            <span class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-label-md text-label-md font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            <span class="hidden xl:block max-w-[120px] truncate font-label-md text-label-md text-on-surface">{{ auth()->user()->name }}</span>
                            <span class="material-symbols-outlined hidden xl:block text-[18px] text-on-surface-variant">expand_more</span>
                        </button>
                        <div x-cloak x-show="userMenu" x-transition class="absolute right-0 mt-2 w-56 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-grass-floating overflow-hidden py-1.5">
                            <a href="{{ route('booking.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 font-body-md text-body-md text-on-surface hover:bg-surface-container-low"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">confirmation_number</span>Booking Saya</a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 font-body-md text-body-md text-on-surface hover:bg-surface-container-low"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">person</span>Profil &amp; Pengaturan</a>
                            <a href="{{ route('poin.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 font-body-md text-body-md text-on-surface hover:bg-surface-container-low"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">stars</span>Poin Loyalitas</a>
                            <a href="{{ route('waitlist.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 font-body-md text-body-md text-on-surface hover:bg-surface-container-low"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">hourglass_top</span>Waitlist</a>
                            <a href="{{ route('referral.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 font-body-md text-body-md text-on-surface hover:bg-surface-container-low"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">share</span>Ajak Teman</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-outline-variant mt-1 pt-1">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 font-body-md text-body-md text-secondary hover:bg-surface-container-low text-left"><span class="material-symbols-outlined text-[18px]">logout</span>Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-container text-on-primary px-4 py-2.5 rounded-xl font-label-lg text-label-lg font-bold shadow-grass-resting active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-[18px]">login</span>
                        <span class="hidden sm:inline">Masuk / Daftar</span>
                    </a>
                @endauth

                <button type="button" @click="open = !open" class="lg:hidden w-10 h-10 rounded-full flex items-center justify-center text-on-surface hover:bg-surface-container-high" :aria-expanded="open.toString()" aria-label="Buka menu">
                    <span class="material-symbols-outlined" x-text="open ? 'close' : 'menu'"></span>
                </button>
            </div>
        </div>

        <div x-cloak x-show="open" x-transition class="lg:hidden border-t border-outline-variant bg-surface-container-lowest px-6 py-4 flex flex-col gap-1">
            <form action="{{ route('lapangan.publik.index') }}" method="GET" class="flex items-center bg-surface-container-low border border-outline-variant rounded-xl h-11 px-3 gap-2 mb-2">
                <span class="material-symbols-outlined text-on-surface-variant text-[20px]">search</span>
                <input name="kata_kunci" type="search" value="{{ request('kata_kunci') }}" placeholder="Cari venue atau area..." class="bg-transparent border-0 focus:ring-0 w-full p-0 font-body-md text-body-md">
            </form>
            <a href="{{ route('home') }}" class="px-3 py-2.5 rounded-lg font-label-lg text-label-lg text-on-surface hover:bg-surface-container-low">Beranda</a>
            <a href="{{ route('lapangan.publik.index') }}" class="px-3 py-2.5 rounded-lg font-label-lg text-label-lg text-on-surface hover:bg-surface-container-low">Cari Lapangan</a>
            <a href="{{ route('referral.leaderboard') }}" class="px-3 py-2.5 rounded-lg font-label-lg text-label-lg text-on-surface hover:bg-surface-container-low">Komunitas</a>
            @auth
                <a href="{{ route('booking.index') }}" class="px-3 py-2.5 rounded-lg font-label-lg text-label-lg text-on-surface hover:bg-surface-container-low">Booking Saya</a>
                <a href="{{ route('poin.index') }}" class="px-3 py-2.5 rounded-lg font-label-lg text-label-lg text-on-surface hover:bg-surface-container-low">Poin Loyalitas</a>
                <a href="{{ route('waitlist.index') }}" class="px-3 py-2.5 rounded-lg font-label-lg text-label-lg text-on-surface hover:bg-surface-container-low">Waitlist</a>
                <a href="{{ route('profile.edit') }}" class="px-3 py-2.5 rounded-lg font-label-lg text-label-lg text-on-surface hover:bg-surface-container-low">Profil &amp; Pengaturan</a>
                <a href="{{ route('pemilik.lapangan.create') }}" class="px-3 py-2.5 rounded-lg font-label-lg text-label-lg text-on-surface hover:bg-surface-container-low">Daftarkan Lapangan</a>
                <form method="POST" action="{{ route('logout') }}" class="pt-2 mt-1 border-t border-outline-variant">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2.5 rounded-lg font-label-lg text-label-lg text-secondary hover:bg-surface-container-low">Keluar</button>
                </form>
            @else
                <div class="flex gap-2 pt-3 mt-1 border-t border-outline-variant">
                    <a href="{{ route('login') }}" class="flex-1 text-center border border-outline-variant rounded-xl py-2.5 font-label-lg text-label-lg text-on-surface">Masuk</a>
                    <a href="{{ route('register') }}" class="flex-1 text-center bg-primary text-on-primary rounded-xl py-2.5 font-label-lg text-label-lg font-bold">Daftar</a>
                </div>
            @endauth
        </div>
    </header>

    <main class="site-main flex-1">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-6 md:px-12 pt-5">
                <div class="flex items-center gap-2.5 bg-primary/10 border border-primary/30 text-on-primary-fixed-variant px-4 py-3 rounded-xl font-body-md text-body-md">
                    <span class="material-symbols-outlined text-primary">check_circle</span><span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-7xl mx-auto px-6 md:px-12 pt-5">
                <div class="flex items-center gap-2.5 bg-error-container border border-error/30 text-on-error-container px-4 py-3 rounded-xl font-body-md text-body-md">
                    <span class="material-symbols-outlined">error</span><span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    {{-- ==================== FOOTER (Shared Component) ==================== --}}
    <footer class="bg-surface-container-high border-t border-outline-variant">
        <div class="w-full px-6 md:px-12 py-12 max-w-7xl mx-auto flex flex-col gap-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                <div class="lg:col-span-2">
                    <a class="flex items-center gap-2 text-title-lg font-title-lg text-primary font-bold" href="{{ route('home') }}">
                        <span class="w-9 h-9 rounded-xl bg-primary-container flex items-center justify-center text-on-primary">
                            <span class="material-symbols-outlined text-[20px]">sports_soccer</span>
                        </span>
                        <span>Booking Lapang</span>
                    </a>
                    <p class="mt-4 font-body-md text-body-md text-on-surface-variant max-w-sm">
                        Platform agregator reservasi lapangan olahraga terlengkap dan terpercaya di Indonesia. Memudahkan komunitas bermain dengan sistem pembayaran instan.
                    </p>
                    <div class="mt-6 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined">support_agent</span>
                        </div>
                        <div>
                            <span class="block font-label-sm text-label-sm text-on-surface-variant">Bantuan CS WhatsApp 24/7</span>
                            <a class="font-label-lg text-label-lg text-primary font-bold hover:underline" href="https://wa.me/6281234567890" target="_blank">+62 812-3456-7890</a>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-title-md text-title-md text-primary font-bold mb-4">Jelajahi</h4>
                    <ul class="space-y-2.5 font-body-md text-body-md text-on-surface-variant">
                        <li><a class="hover:text-primary transition-colors" href="{{ route('lapangan.publik.index') }}">Cari Lapangan</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('referral.leaderboard') }}">Komunitas</a></li>
                        @auth
                            <li><a class="hover:text-primary transition-colors" href="{{ route('booking.index') }}">Booking Saya</a></li>
                            <li><a class="hover:text-primary transition-colors" href="{{ route('poin.index') }}">Poin Loyalitas</a></li>
                            <li><a class="hover:text-primary transition-colors" href="{{ route('waitlist.index') }}">Waitlist</a></li>
                        @endauth
                    </ul>
                </div>

                <div>
                    <h4 class="font-title-md text-title-md text-primary font-bold mb-4">Untuk Mitra</h4>
                    <ul class="space-y-2.5 font-body-md text-body-md text-on-surface-variant">
                        <li><a class="hover:text-primary transition-colors" href="{{ auth()->check() ? route('pemilik.lapangan.create') : route('login') }}">Daftarkan Lapangan</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('login') }}">Masuk sebagai Mitra</a></li>
                        @auth
                            <li><a class="hover:text-primary transition-colors" href="{{ route('pemilik.payout.index') }}">Payout Saya</a></li>
                        @endauth
                    </ul>
                </div>

                <div>
                    <h4 class="font-title-md text-title-md text-primary font-bold mb-4">Bantuan</h4>
                    <ul class="space-y-2.5 font-body-md text-body-md text-on-surface-variant">
                        <li><a class="hover:text-primary transition-colors" href="https://wa.me/6281234567890" target="_blank">Hubungi CS</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Syarat &amp; Ketentuan</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-6 border-t border-outline-variant flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="font-label-sm text-label-sm text-on-surface-variant font-medium">Pembayaran Resmi &amp; Terlindungi:</span>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="bg-surface-container px-2.5 py-1 rounded border border-outline-variant font-label-sm text-label-sm font-bold text-on-surface">QRIS</span>
                        <span class="bg-surface-container px-2.5 py-1 rounded border border-outline-variant font-label-sm text-label-sm font-bold text-on-surface">Virtual Account</span>
                        <span class="bg-surface-container px-2.5 py-1 rounded border border-outline-variant font-label-sm text-label-sm font-bold text-on-surface">GoPay</span>
                        <span class="bg-surface-container px-2.5 py-1 rounded border border-outline-variant font-label-sm text-label-sm font-bold text-on-surface">Midtrans Payment</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-on-surface-variant font-body-sm text-body-sm">
                    <span class="material-symbols-outlined text-primary text-[18px]">lock</span>
                    <span>Enkripsi 256-Bit SSL Bank Grade</span>
                </div>
            </div>

            <div class="pt-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4 font-body-sm text-body-sm text-on-surface-variant">
                <p>© {{ date('Y') }} Booking Lapang. Seluruh hak cipta dilindungi.</p>
                <p class="text-right">Dibuat untuk ekosistem olahraga Indonesia yang lebih sehat &amp; terorganisir.</p>
            </div>
        </div>
    </footer>
</div>
@stack('scripts')
</body>
</html>