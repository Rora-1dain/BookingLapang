<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Verifikasi Identitas
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <span class="font-medium">Status saat ini: </span>
                    @php
                        $badgeColor = match(auth()->user()->status_verifikasi) {
                            'terverifikasi' => 'bg-green-100 text-green-800',
                            'menunggu' => 'bg-yellow-100 text-yellow-800',
                            'ditolak' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="px-2 py-1 rounded text-xs {{ $badgeColor }}">
                        {{ ucfirst(str_replace('_', ' ', auth()->user()->status_verifikasi)) }}
                    </span>
                </div>

                @if (auth()->user()->status_verifikasi === 'ditolak' && auth()->user()->catatan_verifikasi)
                    <div class="mb-4 p-3 bg-red-50 text-red-700 text-sm rounded">
                        Alasan penolakan: {{ auth()->user()->catatan_verifikasi }}
                    </div>
                @endif

                @if (auth()->user()->status_verifikasi !== 'terverifikasi')
                    <form action="{{ route('pemilik.verifikasi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Dokumen Identitas (KTP/SIM)</label>
                            <input type="file" name="dokumen_identitas" required
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="w-full border rounded p-2">
                            @error('dokumen_identitas') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Ajukan Verifikasi
                        </button>
                    </form>
                @else
                    <p class="text-green-700">Akun Anda sudah terverifikasi. Anda bisa mengajukan lapangan baru.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>