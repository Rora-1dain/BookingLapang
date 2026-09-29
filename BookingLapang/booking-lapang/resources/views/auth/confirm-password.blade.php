<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-3">
            <span class="material-symbols-outlined text-[24px]">shield_lock</span>
        </div>
        <h1 class="font-headline-sm text-headline-sm text-on-background">Konfirmasi Kata Sandi</h1>
        <p class="mt-2 font-body-md text-body-md text-on-surface-variant">
            Ini area yang aman. Masukkan kata sandi kamu lagi sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Kata Sandi')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" autofocus />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button>
            <span>Konfirmasi</span>
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </x-primary-button>
    </form>
</x-guest-layout>