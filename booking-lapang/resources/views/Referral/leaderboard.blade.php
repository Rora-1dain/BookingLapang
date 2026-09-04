<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Leaderboard Referral Bulan Ini
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if($topReferrer->isEmpty())
                    <p class="text-gray-500 text-sm">Belum ada data referral bulan ini.</p>
                @else
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-2">#</th>
                                <th class="py-2">Nama</th>
                                <th class="py-2 text-right">Jumlah Referral</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topReferrer as $index => $user)
                                <tr class="border-b last:border-0">
                                    <td class="py-2">{{ $index + 1 }}</td>
                                    <td class="py-2">{{ $user->name }}</td>
                                    <td class="py-2 text-right font-semibold">{{ $user->referrals_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>