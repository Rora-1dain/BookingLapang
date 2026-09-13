@props(['active' => 'dashboard'])

<aside class="h-screen w-64 flex flex-col fixed left-0 top-0 z-30 bg-surface-container-lowest border-r border-outline-variant shadow-sm">
    <div class="flex flex-col justify-between h-full p-4">
        <div class="flex flex-col gap-6">
            <div class="flex items-center gap-3 px-2 py-1">
                <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-on-primary shadow-sm">
                    <span class="material-symbols-outlined text-title-lg">stadium</span>
                </div>
                <div>
                    <div class="text-title-lg font-bold text-primary tracking-tight">Booking Lapang</div>
                    <div class="text-label-sm text-on-surface-variant">Admin Console</div>
                </div>
            </div>

            <nav aria-label="Navigasi Admin" class="flex flex-col gap-1.5">
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors font-label-lg {{ $active === 'dashboard' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}"
                   href="{{ route('admin.dashboard') }}">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ $active === 'dashboard' ? 1 : 0 }};">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a class="flex items-center justify-between px-3 py-2.5 rounded-xl transition-colors font-label-lg {{ $active === 'verifikasi' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}"
                   href="{{ route('admin.verifikasi.index') }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">verified_user</span>
                        <span>Verifikasi Mitra</span>
                    </div>
                </a>
                <a class="flex items-center justify-between px-3 py-2.5 rounded-xl transition-colors font-label-lg {{ $active === 'approval-lapangan' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}"
                   href="{{ route('admin.lapangan.approval') }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">sports_tennis</span>
                        <span>Persetujuan Lapangan</span>
                    </div>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors font-label-lg {{ $active === 'booking' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}"
                   href="{{ route('admin.booking.index') }}">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <span>Semua Booking</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors font-label-lg {{ $active === 'payout' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}"
                   href="{{ route('admin.payout.index') }}">
                    <span class="material-symbols-outlined">payments</span>
                    <span>Payout Mitra</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors font-label-lg {{ $active === 'refund' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}"
                   href="{{ route('admin.refund.index') }}">
                    <span class="material-symbols-outlined">assignment_return</span>
                    <span>Dispute &amp; Refund</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors font-label-lg {{ $active === 'ulasan' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}"
                   href="{{ route('admin.ulasan.index') }}">
                    <span class="material-symbols-outlined">rate_review</span>
                    <span>Moderasi Ulasan</span>
                </a>
            </nav>
        </div>

        <div class="flex flex-col gap-2 pt-4 border-t border-outline-variant">
            <div class="flex items-center gap-3 px-2 py-2 rounded-xl bg-surface-container-low border border-outline-variant">
                <div class="w-9 h-9 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-title-md">shield_person</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-label-md text-on-surface truncate">{{ auth()->user()->name }}</div>
                    <div class="text-body-sm text-on-surface-variant">Admin</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-on-surface-variant hover:text-secondary hover:bg-surface-container transition-colors font-label-md">
                    <span class="material-symbols-outlined text-lg">logout</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>