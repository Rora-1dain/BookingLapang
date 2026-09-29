@props(['active' => 'dashboard'])

<aside class="h-screen w-72 flex flex-col fixed left-0 top-0 z-30 bg-[#FBF7F0] border-r border-surface-container-high text-on-surface shadow-sm">
    <div class="flex flex-col justify-between h-full p-4">
        <div>
            <div class="flex items-center gap-3 px-3 py-4 border-b border-surface-container-highest mb-4">
                <div class="w-11 h-11 rounded-2xl bg-primary text-white flex items-center justify-center shadow-md">
                    <span class="material-symbols-outlined text-2xl">sports_tennis</span>
                </div>
                <div class="overflow-hidden">
                    <div class="flex items-center gap-1.5">
                        <h1 class="text-title-md font-bold text-on-surface truncate leading-tight">{{ auth()->user()->name }}</h1>
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded-full">MITRA</span>
                    </div>
                </div>
            </div>

            <nav aria-label="Menu Pengelola" class="space-y-1.5">
                <a class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ $active === 'dashboard' ? 'bg-primary/10 text-primary font-bold shadow-sm' : 'text-on-surface-variant hover:text-primary hover:bg-white' }}"
                   href="{{ route('pemilik.dashboard') }}">
                    <span class="material-symbols-outlined text-xl {{ $active === 'dashboard' ? 'text-primary' : 'text-outline' }}">dashboard</span>
                    <span class="text-label-md">Dashboard Ringkasan</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ $active === 'lapangan' ? 'bg-primary/10 text-primary font-bold shadow-sm' : 'text-on-surface-variant hover:text-primary hover:bg-white' }}"
                   href="{{ route('pemilik.lapangan.index') }}">
                    <span class="material-symbols-outlined text-xl {{ $active === 'lapangan' ? 'text-primary' : 'text-outline' }}">sports_tennis</span>
                    <span class="text-label-md font-semibold">Kelola Listing Lapangan</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all justify-between {{ $active === 'booking' ? 'bg-primary/10 text-primary font-bold shadow-sm' : 'text-on-surface-variant hover:text-primary hover:bg-white' }}"
                   href="{{ route('pemilik.booking.index') }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-xl {{ $active === 'booking' ? 'text-primary' : 'text-outline' }}">calendar_month</span>
                        <span class="text-label-md font-semibold">Daftar Booking Masuk</span>
                    </div>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ $active === 'payout' ? 'bg-primary/10 text-primary font-bold shadow-sm' : 'text-on-surface-variant hover:text-primary hover:bg-white' }}"
                   href="{{ route('pemilik.payout.index') }}">
                    <span class="material-symbols-outlined text-xl {{ $active === 'payout' ? 'text-primary' : 'text-outline' }}">account_balance_wallet</span>
                    <span class="text-label-md font-semibold">Payout &amp; Finansial</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all justify-between {{ $active === 'verifikasi' ? 'bg-primary/10 text-primary font-bold shadow-sm' : 'text-on-surface-variant hover:text-primary hover:bg-white' }}"
                   href="{{ route('pemilik.verifikasi.create') }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-xl {{ $active === 'verifikasi' ? 'text-primary' : 'text-outline' }}">verified_user</span>
                        <span class="text-label-md font-semibold">Verifikasi Arena</span>
                    </div>
                    @if (auth()->user()->status_verifikasi === 'terverifikasi')
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    @endif
                </a>
            </nav>
        </div>

        <div class="space-y-1.5 border-t border-surface-container-highest pt-3">
            <div class="px-3 py-2.5 bg-white rounded-xl border border-surface-container-high flex items-center justify-between text-xs mt-1">
                <div class="flex flex-col">
                    <span class="text-outline text-[11px]">Status Verifikasi</span>
                    <span class="font-bold text-on-surface capitalize">{{ str_replace('_', ' ', auth()->user()->status_verifikasi ?? 'belum diajukan') }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-on-surface-variant hover:text-secondary hover:bg-white transition-all text-xs font-medium">
                    <span class="material-symbols-outlined text-base text-outline">logout</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>