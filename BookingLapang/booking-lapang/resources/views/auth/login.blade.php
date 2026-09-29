@extends('layouts.auth-split')
@section('title', 'Masuk - Booking Lapang')
@section('content')

<main class="flex-1 w-full min-h-screen grid grid-cols-1 lg:grid-cols-12 overflow-hidden bg-background">
    {{-- LEFT COLUMN: Immersive Visual Showcase --}}
    <div class="hidden lg:relative lg:flex lg:col-span-7 flex-col justify-between p-12 overflow-hidden bg-primary-container text-white">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1626248801379-51a0748a5f96?q=80&w=1600&auto=format&fit=crop')"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-primary/95 via-primary-container/70 to-primary/40 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary/60 to-transparent"></div>

        <div class="relative z-10 flex items-center justify-between">
            <a class="flex items-center gap-3 group" href="{{ route('home') }}">
                <div class="w-11 h-11 rounded-xl bg-surface-bright/95 shadow-md flex items-center justify-center p-2 text-primary group-hover:bg-primary-fixed transition-colors duration-200">
                    <span class="material-symbols-outlined fill-icon">sports_tennis</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-headline-md font-headline-md text-white tracking-tight flex items-center gap-1.5">
                        Booking Lapang
                        <span class="inline-block w-2 h-2 rounded-full bg-tertiary-fixed-dim"></span>
                    </span>
                    <span class="text-label-sm font-label-sm text-surface-variant/80 tracking-wide">ARENA RESERVATION</span>
                </div>
            </a>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-label-md font-label-md">
                <span class="w-2 h-2 rounded-full bg-tertiary-fixed-dim animate-pulse"></span>
                <span>Sistem Live Slot Otomatis</span>
            </div>
        </div>

        <div class="relative z-10 max-w-lg my-auto pt-8">
            <h1 class="text-headline-xl font-headline-xl text-white leading-tight mb-4 tracking-tight drop-shadow-sm">
                Kembali ke Lapangan.<br>
                <span class="text-tertiary-fixed-dim">Reservasi Cepat,</span> Jadwal Akurat, Tanpa Ribet.
            </h1>
            <p class="text-body-lg font-body-lg text-surface-container-high/90 max-w-md mb-8">
                Akses ratusan gelanggang bulutangkis, futsal, mini soccer, dan tenis dengan kepastian jadwal tanpa tumpang tindih.
            </p>

            <div class="p-5 rounded-2xl bg-surface-container-lowest/15 backdrop-blur-md border border-white/20 shadow-lg text-white">
                <div class="flex items-center gap-1 mb-2 text-tertiary-fixed-dim">
                    @for ($i = 0; $i < 5; $i++)
                        <span class="material-symbols-outlined text-[18px] fill-icon">star</span>
                    @endfor
                    <span class="text-label-md font-label-md text-white ml-2 font-bold">5.0 / 5.0</span>
                </div>
                <p class="text-body-md font-body-md text-white/95 italic mb-3">
                    “Penyewa terverifikasi main tiap minggu, tinggal tap jam yang kosong, bayar QRIS, langsung dapat kode booking arena.”
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-secondary-container text-white flex items-center justify-center font-bold text-label-md">RA</div>
                    <div>
                        <p class="text-label-md font-label-md font-bold text-white">Raka Andhika</p>
                        <p class="text-label-sm font-label-sm text-surface-variant/80">Kapten Tim Badminton Sudirman Squad</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-3 pt-6 border-t border-white/15">
            <div class="flex items-center gap-2.5 px-4 py-2 rounded-xl bg-white/10 backdrop-blur-sm border border-white/15">
                <span class="material-symbols-outlined text-tertiary-fixed-dim text-[20px]">stadium</span>
                <span class="text-label-md font-label-md text-white">Gelanggang Resmi Terverifikasi</span>
            </div>
            <div class="flex items-center gap-2.5 px-4 py-2 rounded-xl bg-white/10 backdrop-blur-sm border border-white/15">
                <span class="material-symbols-outlined text-primary-fixed text-[20px]">verified_user</span>
                <span class="text-label-md font-label-md text-white">Transaksi Bergaransi Midtrans</span>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: Authentication Form Panel --}}
    <div class="col-span-1 lg:col-span-5 flex flex-col justify-between px-6 sm:px-12 md:px-16 py-8 md:py-10 bg-background min-h-screen overflow-y-auto">
        <header class="w-full flex items-center justify-between pb-6">
            <div class="flex lg:hidden items-center gap-2">
                <div class="w-9 h-9 rounded-lg bg-primary-container text-white flex items-center justify-center">
                    <span class="material-symbols-outlined fill-icon text-[20px]">sports_tennis</span>
                </div>
                <span class="text-title-lg font-title-lg text-primary tracking-tight">Booking Lapang</span>
            </div>
            <a class="inline-flex items-center gap-1.5 text-label-md font-label-md text-on-surface-variant hover:text-primary transition-colors duration-150 group" href="{{ route('home') }}">
                <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-0.5 transition-transform duration-150">arrow_back</span>
                <span>Kembali ke Beranda</span>
            </a>
            <a class="inline-flex items-center gap-1 text-label-md font-label-md text-on-surface-variant hover:text-primary transition-colors" href="https://wa.me/6281234567890" target="_blank">
                <span class="material-symbols-outlined text-[18px]">help</span>
                <span class="hidden sm:inline">Bantuan</span>
            </a>
        </header>

        <div class="w-full max-w-md mx-auto my-auto py-4">
            <div class="mb-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-surface-container text-primary text-label-sm font-label-sm uppercase tracking-wider mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    Portal Pemain &amp; Mitra
                </div>
                <h2 class="text-headline-lg font-headline-lg text-primary tracking-tight mb-2">Selamat Datang Kembali</h2>
                <p class="text-body-md font-body-md text-on-surface-variant">Masuk ke akun Booking Lapang untuk kelola jadwal main, e-tiket, atau arena olahraga Anda.</p>
            </div>

            <form class="space-y-4" method="POST" action="{{ route('login') }}">
                @csrf
                @if ($errors->any())
                    <div class="mb-4 rounded-xl bg-error-container border border-error/30 p-4 text-body-sm font-body-sm text-on-error-container">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-1.5" for="email">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                            <span class="material-symbols-outlined text-[20px]">alternate_email</span>
                        </div>
                        <input class="w-full pl-11 pr-4 py-3 bg-surface-container-lowest border border-surface-dim rounded-xl text-body-md font-body-md text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all duration-150 @error('email') border-error @enderror"
                               id="email" name="email" type="email" value="{{ old('email') }}" placeholder="dimas.satria@example.com" required autofocus>
                    </div>
                    @error('email')<p class="mt-1.5 text-body-sm font-body-sm text-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-1.5" for="password">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                            <span class="material-symbols-outlined text-[20px]">lock</span>
                        </div>
                        <input class="w-full pl-11 pr-11 py-3 bg-surface-container-lowest border border-surface-dim rounded-xl text-body-md font-body-md text-on-surface placeholder:text-outline/60 focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all duration-150 @error('password') border-error @enderror"
                               id="password" name="password" placeholder="Masukkan kombinasi sandi akun" required type="password">
                        <button aria-label="Toggle Password Visibility" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-outline hover:text-primary transition-colors focus:outline-none" id="togglePasswordBtn" type="button">
                            <span class="material-symbols-outlined text-[20px]" id="toggleIcon">visibility</span>
                        </button>
                    </div>
                    @error('password')<p class="mt-1.5 text-body-sm font-body-sm text-error">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input class="w-4 h-4 text-primary-container rounded border-surface-dim focus:ring-primary-container/30 focus:ring-offset-0 transition" type="checkbox" name="remember">
                        <span class="text-body-md font-body-md text-on-surface select-none">Ingat saya</span>
                    </label>
                    <a class="text-label-md font-label-md text-secondary hover:text-on-secondary-container hover:underline transition-colors" href="{{ route('password.request') }}">
                        Lupa Kata Sandi?
                    </a>
                </div>

                <div class="pt-2">
                    <button class="w-full h-12 flex items-center justify-center gap-2 rounded-xl bg-primary-container text-white text-title-md font-title-md hover:bg-[#164325] active:scale-[0.98] transition-all duration-150 shadow-sm" type="submit">
                        <span>Masuk ke Akun</span>
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </button>
                </div>
            </form>

            <div class="text-center mt-6">
                <p class="text-body-md font-body-md text-on-surface-variant">
                    Belum punya akun Booking Lapang?
                    <a class="font-title-md text-primary hover:text-secondary underline decoration-primary/40 underline-offset-4 ml-1 transition-colors" href="{{ route('register') }}">
                        Daftar Sekarang
                    </a>
                </p>
            </div>

            <div class="mt-4 p-3 rounded-xl bg-surface-container/60 border border-surface-dim flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-primary text-[20px]">domain</span>
                    <span class="text-label-sm font-label-sm text-on-surface">Kelola venue atau pasang lapangan?</span>
                </div>
                <a class="text-label-sm font-label-sm text-secondary font-bold hover:underline" href="{{ route('register') }}">
                    Daftar Venue →
                </a>
            </div>
        </div>

        <footer class="w-full pt-6 border-t border-surface-variant/70 flex flex-col sm:flex-row items-center justify-between gap-3 text-body-sm font-body-sm text-outline">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px] text-primary">lock</span>
                <span>Dilindungi enkripsi SSL 256-bit &amp; Midtrans Payment Partner</span>
            </div>
            <div class="flex items-center gap-4 text-label-sm font-label-sm">
                <a class="hover:text-primary transition-colors" href="#">Syarat &amp; Ketentuan</a>
                <span>•</span>
                <a class="hover:text-primary transition-colors" href="#">Kebijakan Privasi</a>
            </div>
        </footer>
    </div>
</main>

@push('scripts')
<script>
    document.getElementById('togglePasswordBtn')?.addEventListener('click', function () {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            pwd.type = 'password';
            icon.textContent = 'visibility';
        }
    });
</script>
@endpush
@endsection