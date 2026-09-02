<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- TODO(merge Ardan): sambungkan $poin, $tier, $riwayatPoin dari LoyaltyService --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Poin & Membership</h3>
                    <x-badge-tier :tier="auth()->user()->tier ?? 'Silver'" />
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Sisa Poin</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ auth()->user()->poin ?? 0 }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tier Saat Ini</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ auth()->user()->tier ?? 'Silver' }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Riwayat Poin</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead class="text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                                <tr>
                                    <th class="py-2 pr-4">Tanggal</th>
                                    <th class="py-2 pr-4">Jumlah</th>
                                    <th class="py-2 pr-4">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-gray-700">
                                @forelse (($riwayatPoin ?? []) as $riwayat)
                                    <tr>
                                        <td class="py-2 pr-4 text-gray-700 dark:text-gray-300">{{ $riwayat->created_at->format('d M Y') }}</td>
                                        <td class="py-2 pr-4 font-medium {{ $riwayat->jumlah > 0 ? 'text-teal-600' : 'text-red-600' }}">
                                            {{ $riwayat->jumlah > 0 ? '+' : '' }}{{ $riwayat->jumlah }}
                                        </td>
                                        <td class="py-2 pr-4 text-gray-700 dark:text-gray-300">{{ $riwayat->keterangan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-3 text-gray-400">Belum ada riwayat poin.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <form action="{{ route('poin.redeem') }}" method="POST" class="flex items-end gap-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Poin</label>
                        <input type="number" name="jumlah_poin" min="100" step="100" required
                            class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg p-2 text-sm w-40"
                            placeholder="100">
                    </div>
                    <button type="submit"
                        class="bg-teal-600 hover:bg-teal-700 text-white rounded-lg px-4 py-2 text-sm font-medium">
                        Tukar Poin jadi Voucher
                    </button>
                </form>
                <p class="text-xs text-gray-400 mt-2">Kelipatan 100 poin = Rp10.000 diskon.</p>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>