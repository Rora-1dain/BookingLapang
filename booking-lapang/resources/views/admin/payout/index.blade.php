<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Payout
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <div class="mb-4">
                <a href="{{ route('admin.payout.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Buat Payout
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6 overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2">Pemilik</th>
                            <th class="p-2">Periode</th>
                            <th class="p-2">Total</th>
                            <th class="p-2">Status</th>
                            <th class="p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payouts as $payout)
                            <tr class="border-b">
                                <td class="p-2">{{ $payout->pemilik->name }}</td>
                                <td class="p-2">{{ $payout->periode_mulai }} - {{ $payout->periode_selesai }}</td>
                                <td class="p-2">Rp{{ number_format($payout->total_nominal, 0, ',', '.') }}</td>
                                <td class="p-2">{{ $payout->status }}</td>
                                <td class="p-2">
                                    @if ($payout->status === 'menunggu')
                                        <form action="{{ route('admin.payout.selesai', $payout->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                                Tandai Selesai
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">Belum ada payout.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>