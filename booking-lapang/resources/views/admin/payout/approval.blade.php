<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Persetujuan Lapangan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                @endif

                @if ($menunggu->isEmpty())
                    <p class="text-gray-500">Tidak ada lapangan menunggu persetujuan.</p>
                @else
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2 px-3">Nama Lapangan</th>
                                <th class="py-2 px-3">Pemilik</th>
                                <th class="py-2 px-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menunggu as $lapangan)
                                <tr class="border-b">
                                    <td class="py-2 px-3">{{ $lapangan->nama_lapangan }}</td>
                                    <td class="py-2 px-3">{{ $lapangan->pemilik->name ?? '-' }}</td>
                                    <td class="py-2 px-3 space-x-2">
                                        <form action="{{ route('admin.lapangan.setujui', $lapangan->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded">Setujui</button>
                                        </form>

                                        <form action="{{ route('admin.lapangan.tolak', $lapangan->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="text" name="alasan" placeholder="Alasan" required class="border rounded p-1 text-sm">
                                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Tolak</button>
                                        </form>
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