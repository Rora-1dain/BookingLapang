<x-app-layout>
    <div class="max-w-xl mx-auto py-8">
        <h1 class="text-xl font-bold mb-4">Booking Lapangan</h1>

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

            <label>Lapangan</label>
            <select name="lapangan_id" class="w-full border rounded p-2 mb-3">
                <option value="">-- Pilih Lapangan --</option>
                {{-- nanti diisi loop dari $lapangans kalau mau, atau hardcode dulu buat test --}}
            </select>

            <label>Tanggal</label>
            <input type="date" name="tanggal_booking" class="w-full border rounded p-2 mb-3" value="{{ old('tanggal_booking') }}">

            <label>Jam Mulai</label>
            <input type="time" name="jam_mulai" class="w-full border rounded p-2 mb-3" value="{{ old('jam_mulai') }}">

            <label>Jam Selesai</label>
            <input type="time" name="jam_selesai" class="w-full border rounded p-2 mb-3" value="{{ old('jam_selesai') }}">

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Booking Sekarang</button>
        </form>
    </div>
</x-app-layout>