<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajak Teman
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Kode Referral Kamu</h3>
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-bold tracking-wider text-indigo-600 bg-indigo-50 px-4 py-2 rounded">
                        {{ $kodeReferral }}
                    </span>
                    <button
                        type="button"
                        onclick="navigator.clipboard.writeText('{{ $kodeReferral }}'); this.innerText='Tersalin!'"
                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-sm text-gray-700"
                    >
                        Salin
                    </button>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Link Ajak Teman</h3>
                <div class="flex items-center gap-3">
                    <input
                        type="text"
                        readonly
                        value="{{ $linkReferral }}"
                        class="flex-1 border-gray-300 rounded text-sm bg-gray-50"
                        onclick="this.select()"
                    >
                    <button
                        type="button"
                        onclick="navigator.clipboard.writeText('{{ $linkReferral }}'); this.innerText='Tersalin!'"
                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-sm text-gray-700"
                    >
                        Salin
                    </button>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    Bagikan link ini. Kamu dapat 50 poin tiap teman baru booking pertama & bayar.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-gray-900">{{ $jumlahTemanDaftar }}</p>
                    <p class="text-sm text-gray-500 mt-1">Teman Terdaftar</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-gray-900">{{ $jumlahRewardDiterima }}</p>
                    <p class="text-sm text-gray-500 mt-1">Reward Diterima</p>
                </div>
            </div>

            <a href="{{ route('referral.leaderboard') }}" class="text-indigo-600 hover:underline text-sm">
                Lihat Leaderboard Referral &rarr;
            </a>

        </div>
    </div>
</x-app-layout>