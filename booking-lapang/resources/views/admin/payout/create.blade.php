<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Payout
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
                <form action="{{ route('admin.payout.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Pemilik Lapangan</label>
                        <select name="pemilik_id" required class="w-full border rounded p-2">
                            <option value="">-- Pilih Pemilik --</option>
                            @foreach ($pemilikList as $pemilik)
                                <option value="{{ $pemilik->id }}">{{ $pemilik->name }}</option>
                            @endforeach
                        </select>
                        @error('pemilik_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Periode Mulai</label>
                        <input type="date" name="periode_mulai" required class="w-full border rounded p-2">
                        @error('periode_mulai') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Periode Selesai</label>
                        <input type="date" name="periode_selesai" required class="w-full border rounded p-2">
                        @error('periode_selesai') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Buat Payout
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>