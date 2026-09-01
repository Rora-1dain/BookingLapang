@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-md mx-auto bg-white rounded-2xl shadow-sm p-6 text-center">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Pembayaran</h2>
        <p class="text-gray-600 mb-1">{{ $booking->lapangan->nama_lapangan }}</p>
        <p class="text-sm text-gray-500 mb-4">
            {{ $booking->tanggal_booking->format('d M Y') }} · {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}
        </p>
        <p class="text-2xl font-bold text-teal-700 mb-6">Rp{{ number_format($booking->total_harga) }}</p>

        <button id="pay-button"
            class="w-full bg-teal-600 hover:bg-teal-700 text-white rounded-lg px-4 py-3 font-medium">
            Bayar Sekarang
        </button>

        <a href="{{ route('booking.index') }}" class="block mt-4 text-sm text-gray-500 hover:underline">
            Kembali ke daftar booking
        </a>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function () {
                window.location.href = "{{ route('booking.index') }}";
            },
            onPending: function () {
                window.location.href = "{{ route('booking.index') }}";
            },
            onError: function () {
                alert('Pembayaran gagal, coba lagi.');
            },
            onClose: function () {
                // user closes popup without finishing
            }
        });
    });
</script>
@endsection