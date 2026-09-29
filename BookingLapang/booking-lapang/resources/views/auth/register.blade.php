@extends('layouts.auth-split')
@section('title', 'Daftar - Booking Lapang')
@section('content')

<header class="w-full px-6 md:px-12 py-3 flex justify-between items-center bg-surface border-b border-surface-variant shadow-sm sticky top-0 z-50">
    <div class="flex items-center gap-8">
        <a class="text-headline-md font-headline-md text-primary tracking-tight flex items-center gap-2" href="{{ route('home') }}">
            <span class="w-8 h-8 rounded-lg bg-primary-container text-surface flex items-center justify-center">
                <span class="material-symbols-outlined fill-icon text-title-lg">sports_tennis</span>
            </span>
            Booking Lapang
        </a>
        <nav class="hidden md:flex items-center gap-6">
            <a class="text-label-md font-label-md text-on-surface-variant hover:text-on-surface transition-colors duration-150" href="https://wa.me/6281234567890" target="_blank">Bantuan</a>
        </nav>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-body-sm font-body-sm text-outline hidden sm:inline">Kelola gelanggang Anda?</span>
        <a class="px-4 py-2 rounded-xl border border-primary text-primary hover:bg-surface-container transition-colors duration-150 text-label-md font-label-md active:scale-[0.98]" href="{{ route('login') }}">
            Masuk sebagai Mitra
        </a>
    </div>
</header>

<main class="flex-1 w-full max-w-[1440px] mx-auto grid grid-cols-1 lg:grid-cols-12 min-h-[calc(100vh-125px)]">
    {{-- LEFT SIDE: Hero Visual --}}
    <section class="lg:col-span-5 relative hidden lg:flex flex-col justify-between p-10 bg-primary-container overflow-hidden text-surface">
        <div class="absolute inset-0 z-0">
            <img class="w-full h-full object-cover object-center opacity-30 mix-blend-luminosity" alt="Lapangan olahraga" src="https://images.unsplash.com/photo-1626248801379-51a0748a5f96?q=80&w=1600&auto=format&fit=crop">
            <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary-container/85 to-primary-container/95"></div>
        </div>

        <div class="relative z-10 space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-surface/10 backdrop-blur-md border border-surface/20 text-tertiary-fixed-dim text-label-sm font-label-sm tracking-wide">
                <span class="material-symbols-outlined text-sm fill-icon">verified</span>
                Ekosistem Olahraga Terpadu Booking Lapang
            </div>
            <h2 class="text-headline-lg font-headline-lg text-surface tracking-tight leading-tight max-w-sm">
                Akses Gelanggang Terbaik, Tanpa Hambatan.
            </h2>
            <p class="text-body-md font-body-md text-surface-variant max-w-sm">
                Hubungkan gairah berolahraga dengan ratusan gelanggang terverifikasi di seluruh Nusantara.
            </p>
        </div>

        <div class="relative z-10 my-8 space-y-3.5">
            <div class="p-4 rounded-xl bg-surface/10 border border-surface/15 backdrop-blur-md transition-all duration-300" id="left-perk-penyewa">
                <div class="flex items-start gap-3">
                    <div class="p-2 rounded-lg bg-surface/15 text-tertiary-fixed">
                        <span class="material-symbols-outlined text-title-md">sports_score</span>
                    </div>
                    <div>
                        <h3 class="text-title-md font-title-md text-surface">Bagi Pemain &amp; Squad</h3>
                        <p class="text-body-sm font-body-sm text-surface-variant mt-0.5">Booking instan hitungan detik, garansi refund cuaca ekstrem, dan reward poin setia di setiap match.</p>
                    </div>
                </div>
            </div>
            <div class="p-4 rounded-xl bg-surface/10 border border-surface/15 backdrop-blur-md transition-all duration-300 opacity-70" id="left-perk-pemilik">
                <div class="flex items-start gap-3">
                    <div class="p-2 rounded-lg bg-surface/15 text-secondary-container">
                        <span class="material-symbols-outlined text-title-md">real_estate_agent</span>
                    </div>
                    <div>
                        <h3 class="text-title-md font-title-md text-surface">Bagi Mitra Pemilik Arena</h3>
                        <p class="text-body-sm font-body-sm text-surface-variant mt-0.5">Terima reservasi 24/7 tanpa bentrok jadwal, pembukuan rapi, dan pencairan dana otomatis via Midtrans.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-10 pt-6 border-t border-surface/15 flex items-center justify-between">
            <div class="flex items-center gap-2 text-tertiary-fixed-dim">
                <span class="material-symbols-outlined text-title-lg fill-icon">workspace_premium</span>
                <span class="text-label-sm font-label-sm tracking-wide">Standar Kualitas Arena Terverifikasi</span>
            </div>
        </div>
    </section>

    {{-- RIGHT SIDE: Registration Form --}}
    <section class="lg:col-span-7 p-6 sm:p-10 md:p-12 lg:p-16 flex flex-col justify-center bg-surface">
        <div class="max-w-xl mx-auto w-full">
            <div class="mb-8">
                <div class="inline-flex lg:hidden items-center gap-2 px-3 py-1 rounded-full bg-surface-container-high border border-outline-variant text-primary text-label-sm font-label-sm mb-3">
                    <span class="material-symbols-outlined text-sm fill-icon">sports_tennis</span>
                    Booking Lapang Platform
                </div>
                <h1 class="text-headline-lg font-headline-lg text-on-surface tracking-tight">Mulai Bergabung di Booking Lapang</h1>
                <p class="text-body-md font-body-md text-on-surface-variant mt-1.5">Daftar akun untuk mulai booking lapangan. Ingin mendaftarkan arena sebagai mitra? Anda bisa mengajukannya lewat menu "Daftarkan Lapangan" setelah masuk.</p>
            </div>

            <form class="space-y-4" method="POST" action="{{ route('register') }}">
                @csrf
                @if ($errors->any())
                    <div class="mb-4 rounded-xl bg-error-container border border-error/30 p-4 text-body-sm font-body-sm text-on-error-container">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-1.5" for="name">Nama Lengkap (Sesuai KTP)</label>
                    <div class="relative">
                        <input class="w-full h-12 px-4 rounded-xl border border-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest text-body-md font-body-md text-on-surface transition-colors @error('name') border-error @enderror"
                               id="name" name="name" placeholder="Contoh: Rian Ardianto" required type="text" value="{{ old('name') }}" autofocus>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-outline">
                            <span class="material-symbols-outlined text-title-md">person</span>
                        </div>
                    </div>
                    @error('name')<p class="mt-1.5 text-body-sm font-body-sm text-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-1.5" for="email">Email Aktif</label>
                    <div class="relative">
                        <input class="w-full h-12 px-4 rounded-xl border border-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest text-body-md font-body-md text-on-surface transition-colors @error('email') border-error @enderror"
                               id="email" name="email" placeholder="nama@domain.com" required type="email" value="{{ old('email') }}">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-outline">
                            <span class="material-symbols-outlined text-title-md">mail</span>
                        </div>
                    </div>
                    <span class="text-body-sm font-body-sm text-outline mt-1 block">Untuk tiket &amp; konfirmasi booking</span>
                    @error('email')<p class="mt-1.5 text-body-sm font-body-sm text-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md font-label-md text-on-surface mb-1.5" for="password">Kata Sandi</label>
                        <div class="relative">
                            <input class="w-full h-12 px-4 rounded-xl border border-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest text-body-md font-body-md text-on-surface transition-colors @error('password') border-error @enderror"
                                   id="password" name="password" oninput="checkPasswordStrength(this.value)" placeholder="Minimal 8 karakter" required type="password">
                            <button class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-outline hover:text-on-surface" onclick="togglePasswordVisibility('password', this)" type="button">
                                <span class="material-symbols-outlined text-title-md">visibility</span>
                            </button>
                        </div>
                        @error('password')<p class="mt-1.5 text-body-sm font-body-sm text-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-label-md font-label-md text-on-surface mb-1.5" for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <input class="w-full h-12 px-4 rounded-xl border border-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest text-body-md font-body-md text-on-surface transition-colors"
                                   id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi" required type="password">
                            <button class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-outline hover:text-on-surface" onclick="togglePasswordVisibility('password_confirmation', this)" type="button">
                                <span class="material-symbols-outlined text-title-md">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5 pt-1">
                    <div class="flex justify-between items-center text-label-sm font-label-sm">
                        <span class="text-on-surface-variant">Kekuatan Sandi:</span>
                        <span class="text-outline font-semibold" id="strength-text">Belum dimasukkan</span>
                    </div>
                    <div class="w-full h-1.5 bg-surface-variant rounded-full overflow-hidden flex gap-1">
                        <div class="h-full w-1/3 bg-outline-variant transition-colors duration-200" id="bar-1"></div>
                        <div class="h-full w-1/3 bg-outline-variant transition-colors duration-200" id="bar-2"></div>
                        <div class="h-full w-1/3 bg-outline-variant transition-colors duration-200" id="bar-3"></div>
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input class="mt-1 w-4 h-4 rounded text-primary focus:ring-primary border-surface-variant" required type="checkbox">
                        <span class="text-body-sm font-body-sm text-on-surface-variant">
                            Saya menyetujui <a class="text-primary font-semibold underline underline-offset-2 hover:text-primary-container" href="#">Syarat &amp; Ketentuan</a> serta <a class="text-primary font-semibold underline underline-offset-2 hover:text-primary-container" href="#">Kebijakan Privasi</a> yang berlaku di Booking Lapang.
                        </span>
                    </label>
                </div>

                <div class="pt-3">
                    <button class="w-full h-12 bg-primary hover:bg-primary-container text-on-primary rounded-xl font-label-lg text-label-lg transition-all duration-150 active:scale-[0.98] grass-shadow flex items-center justify-center gap-2 font-bold" type="submit">
                        <span>Daftar Akun Sekarang</span>
                        <span class="material-symbols-outlined text-title-md">arrow_forward</span>
                    </button>
                </div>
            </form>

            <p class="text-center text-body-md font-body-md text-on-surface-variant mt-8">
                Sudah memiliki akun?
                <a class="text-primary font-bold hover:underline underline-offset-4 ml-1" href="{{ route('login') }}">Masuk di sini</a>
            </p>
        </div>
    </section>
</main>

<footer class="w-full px-6 md:px-12 py-4 flex flex-col md:flex-row justify-between items-center gap-4 bg-surface border-t border-surface-variant">
    <div class="flex items-center gap-2">
        <span class="text-title-md font-title-lg text-primary font-bold">Booking Lapang</span>
        <span class="text-body-sm font-body-sm text-on-surface-variant">© {{ date('Y') }} Booking Lapang. Platform reservasi gelanggang olahraga terpercaya.</span>
    </div>
    <div class="flex flex-wrap items-center gap-6">
        <a class="text-label-sm font-label-sm text-on-surface-variant hover:text-primary transition-colors duration-150" href="#">Syarat &amp; Ketentuan</a>
        <a class="text-label-sm font-label-sm text-on-surface-variant hover:text-primary transition-colors duration-150" href="#">Kebijakan Privasi</a>
        <a class="text-label-sm font-label-sm text-on-surface-variant hover:text-primary transition-colors duration-150" href="https://wa.me/6281234567890" target="_blank">Hubungi CS</a>
    </div>
</footer>

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('.material-symbols-outlined');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    function checkPasswordStrength(val) {
        const b1 = document.getElementById('bar-1');
        const b2 = document.getElementById('bar-2');
        const b3 = document.getElementById('bar-3');
        const label = document.getElementById('strength-text');

        if (!val || val.length === 0) {
            b1.className = 'h-full w-1/3 bg-outline-variant transition-colors duration-200';
            b2.className = 'h-full w-1/3 bg-outline-variant transition-colors duration-200';
            b3.className = 'h-full w-1/3 bg-outline-variant transition-colors duration-200';
            label.textContent = 'Belum dimasukkan';
            label.className = 'text-outline font-semibold';
        } else if (val.length < 6) {
            b1.className = 'h-full w-1/3 bg-secondary transition-colors duration-200';
            b2.className = 'h-full w-1/3 bg-outline-variant transition-colors duration-200';
            b3.className = 'h-full w-1/3 bg-outline-variant transition-colors duration-200';
            label.textContent = 'Lemah';
            label.className = 'text-secondary font-semibold';
        } else if (val.length < 10) {
            b1.className = 'h-full w-1/3 bg-tertiary-fixed-dim transition-colors duration-200';
            b2.className = 'h-full w-1/3 bg-tertiary-fixed-dim transition-colors duration-200';
            b3.className = 'h-full w-1/3 bg-outline-variant transition-colors duration-200';
            label.textContent = 'Sedang';
            label.className = 'text-tertiary font-semibold';
        } else {
            b1.className = 'h-full w-1/3 bg-primary transition-colors duration-200';
            b2.className = 'h-full w-1/3 bg-primary transition-colors duration-200';
            b3.className = 'h-full w-1/3 bg-primary transition-colors duration-200';
            label.textContent = 'Kuat & Aman';
            label.className = 'text-primary font-semibold';
        }
    }
</script>
@endpush
@endsection