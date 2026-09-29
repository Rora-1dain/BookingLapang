<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-3">
            <span class="material-symbols-outlined text-[24px]">mark_email_read</span>
        </div>
        <h1 class="font-headline-sm text-headline-sm text-on-background">Verifikasi Email Kamu</h1>
        <p class="mt-2 font-body-md text-body-md text-on-surface-variant">
            Terima kasih sudah mendaftar! Sebelum mulai, klik link verifikasi yang sudah kami kirim ke email kamu. Belum dapat emailnya? Kami kirim ulang.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <x-auth-session-status class="mb-4" status="Link verifikasi baru sudah dikirim ke email yang kamu daftarkan." />
    @endif

    <div class="mt-4 flex flex-col sm:flex-row items-center gap-3">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full">
            @csrf
            <x-primary-button>
                <span>Kirim Ulang Email Verifikasi</span>
                <span class="material-symbols-outlined text-[18px]">refresh</span>
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full h-12 px-5 rounded-xl border border-outline-variant text-on-surface-variant font-label-lg text-label-lg hover:bg-surface-container-low transition-colors">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>