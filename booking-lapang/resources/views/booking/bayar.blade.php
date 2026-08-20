@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-lg shadow">
    <h1 class="text-xl font-bold mb-4">Pembayaran Booking #{{ $booking->id }}</h1>

    <div class="mb-6 space-y-1 text-gray-700">
        <p><strong>Lapangan:</strong> {{ $booking->lapangan->nama_lapangan ?? '-' }}</p>
        <p><strong>Total Harga:</strong> Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
        <p><strong>Status Pembayaran:</strong>
            <span class="font-semibold uppercase">{{ $booking->status_pembayaran }}</span>
        </p>
    </div>

    @if ($booking->status_pembayaran === 'unpaid')
        <button id="pay-button" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
            Bayar Sekarang
        </button>
    @else
        <p class="text-green-600 font-semibold">Booking ini sudah {{ $booking->status_pembayaran }}.</p>
    @endif
</div>

{{-- Snap.js dari Midtrans (Sandbox) --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    const payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function () {
            payButton.disabled = true;
            payButton.innerText = 'Memproses...';

            snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) {
                    alert('Pembayaran berhasil!');
                    window.location.reload();
                },
                onPending: function (result) {
                    alert('Menunggu pembayaran diselesaikan.');
                    window.location.reload();
                },
                onError: function (result) {
                    alert('Pembayaran gagal.');
                    payButton.disabled = false;
                    payButton.innerText = 'Bayar Sekarang';
                },
                onClose: function () {
                    payButton.disabled = false;
                    payButton.innerText = 'Bayar Sekarang';
                }
            });
        });
    }
</script>
@endsection