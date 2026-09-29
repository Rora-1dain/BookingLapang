<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-3">
            <span class="material-symbols-outlined text-[24px]">key</span>
        </div>
        <h1 class="font-headline-sm text-headline-sm text-on-background">Buat Kata Sandi Baru</h1>
        <p class="mt-2 font-body-md text-body-md text-on-surface-variant">
            Masukkan kata sandi baru untuk akun Booking Lapang kamu.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Kata Sandi Baru')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button>
            <span>Simpan Kata Sandi Baru</span>
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
        </x-primary-button>
    </form>
</x-guest-layout>