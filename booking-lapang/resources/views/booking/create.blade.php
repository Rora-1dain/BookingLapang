<x-app-layout>
    <div class="max-w-xl mx-auto py-8">
        <h1 class="text-xl font-bold mb-4 text-white">Booking Lapangan</h1>

       @if (session('error'))
             <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('booking.store') }}">
            @csrf

            <label class="text-white">Lapangan</label>
            <select name="lapangan_id" class="w-full border rounded p-2 mb-3 text-gray-900 bg-white">
                <option value="">-- Pilih Lapangan --</option>
                @foreach ($lapangans as $lapangan)
                    <option value="{{ $lapangan->id }}">
                        {{ $lapangan->nama_lapangan }} - Rp{{ number_format($lapangan->harga_per_jam) }}/jam
                    </option>
                @endforeach
            </select>

            <label class="text-white">Tanggal</label>
            <input type="date" name="tanggal_booking" class="w-full border rounded p-2 mb-3 text-gray-900 bg-white" value="{{ old('tanggal_booking') }}">

            <label class="text-white">Jam Mulai</label>
            <input type="time" name="jam_mulai" class="w-full border rounded p-2 mb-3 text-gray-900 bg-white" value="{{ old('jam_mulai') }}">

            <label class="text-white">Jam Selesai</label>
            <input type="time" name="jam_selesai" class="w-full border rounded p-2 mb-3 text-gray-900 bg-white" value="{{ old('jam_selesai') }}">

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Booking Sekarang</button>
        </form>
    </div>
</x-app-layout>