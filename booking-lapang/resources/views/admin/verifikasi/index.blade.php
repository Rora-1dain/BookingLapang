<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Peninjauan Verifikasi Pemilik
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($menunggu->isEmpty())
                    <p class="text-gray-500">Tidak ada pengajuan verifikasi yang menunggu.</p>
                @else
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2 px-3">Nama</th>
                                <th class="py-2 px-3">Email</th>
                                <th class="py-2 px-3">Dokumen</th>
                                <th class="py-2 px-3">Diajukan</th>
                                <th class="py-2 px-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menunggu as $pemilik)
                                <tr class="border-b">
                                    <td class="py-2 px-3">{{ $pemilik->name }}</td>
                                    <td class="py-2 px-3">{{ $pemilik->email }}</td>
                                    <td class="py-2 px-3">
                                        <a href="{{ route('admin.verifikasi.dokumen', $pemilik) }}"
                                           target="_blank"
                                           class="text-blue-600 hover:underline">
                                            Lihat Dokumen
                                        </a>
                                    </td>
                                    <td class="py-2 px-3">{{ $pemilik->updated_at?->format('d M Y') ?? '-' }}</td>
                                    <td class="py-2 px-3 space-x-2">
                                        <form action="{{ route('admin.verifikasi.tinjau', $pemilik) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="keputusan" value="setuju">
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                                Setujui
                                            </button>
                                        </form>

                                        <button type="button"
                                                onclick="document.getElementById('tolak-modal-{{ $pemilik->id }}').classList.remove('hidden')"
                                                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                            Tolak
                                        </button>

                                        <div id="tolak-modal-{{ $pemilik->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                            <div class="bg-white p-6 rounded shadow-lg w-96">
                                                <h3 class="font-semibold mb-3">Tolak Verifikasi {{ $pemilik->name }}</h3>
                                                <form action="{{ route('admin.verifikasi.tinjau', $pemilik) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="keputusan" value="tolak">
                                                    <textarea name="catatan" rows="3"
                                                              class="w-full border rounded p-2 mb-3"
                                                              placeholder="Catatan penolakan (opsional)"></textarea>
                                                    <div class="flex justify-end space-x-2">
                                                        <button type="button"
                                                                onclick="document.getElementById('tolak-modal-{{ $pemilik->id }}').classList.add('hidden')"
                                                                class="px-3 py-1 bg-gray-300 rounded">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">
                                                            Kirim
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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