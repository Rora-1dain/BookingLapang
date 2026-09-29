<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-3">
            <span class="material-symbols-outlined text-[24px]">lock_reset</span>
        </div>
        <h1 class="font-headline-sm text-headline-sm text-on-background">Lupa Kata Sandi?</h1>
        <p class="mt-2 font-body-md text-body-md text-on-surface-variant">
            Gak masalah. Masukkan email akun kamu, kami kirimkan link untuk membuat kata sandi baru.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button>
            <span>Kirim Link Reset Sandi</span>
            <span class="material-symbols-outlined text-[18px]">send</span>
        </x-primary-button>
    </form>

    <p class="text-center mt-6 font-body-md text-body-md text-on-surface-variant">
        Sudah ingat kata sandi?
        <a href="{{ route('login') }}" class="font-title-md text-primary hover:underline underline-offset-4">Masuk di sini</a>
    </p>
</x-guest-layout>