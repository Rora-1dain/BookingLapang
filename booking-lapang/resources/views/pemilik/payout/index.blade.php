<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Payout
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if ($payouts->isEmpty())
                    <p class="text-gray-500">Belum ada riwayat payout.</p>
                @else
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2 px-3">Periode</th>
                                <th class="py-2 px-3">Total Nominal</th>
                                <th class="py-2 px-3">Status</th>
                                <th class="py-2 px-3">Laporan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payouts as $payout)
                                <tr class="border-b">
                                    <td class="py-2 px-3">
                                        {{ \Carbon\Carbon::parse($payout->periode_mulai)->format('d M Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($payout->periode_selesai)->format('d M Y') }}
                                    </td>
                                    <td class="py-2 px-3">
                                        Rp{{ number_format($payout->total_nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2 px-3">
                                        <span class="px-2 py-1 rounded text-xs
                                            @if($payout->status === 'selesai') bg-green-100 text-green-800
                                            @elseif($payout->status === 'diproses') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($payout->status) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3">
                                        @if($payout->status === 'selesai')
                                            <a href="#" class="text-blue-600 hover:underline">Unduh PDF</a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>