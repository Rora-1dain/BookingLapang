import { mintaSnapToken, cekStatusPembayaran } from '../api/payment'
import { loadMidtransSnap } from './midtrans'

// Membungkus alur mintaSnapToken -> snap.pay -> cekStatus jadi satu Promise,
// supaya BookingPanel & MyBookingsModal tidak duplikasi logika Midtrans.
// Resolve dengan hasil cekStatusPembayaran (walaupun 'pending', bukan cuma
// 'paid' — biar pemanggil yang putuskan pesan apa yang mau ditampilkan).
// Reject kalau user menutup popup atau Midtrans balikin error eksplisit —
// booking-nya sendiri TETAP ada di database, cuma belum lunas.
export async function bayarBooking(bookingId, { onStatus } = {}) {
  onStatus?.('Menyiapkan pembayaran...')
  const { snap_token, client_key, is_production } = await mintaSnapToken(bookingId)
  const snap = await loadMidtransSnap(client_key, is_production)

  return new Promise((resolve, reject) => {
    snap.pay(snap_token, {
      onSuccess: () => {
        onStatus?.('Mengonfirmasi status pembayaran...')
        cekStatusPembayaran(bookingId).then(resolve).catch(reject)
      },
      onPending: () => {
        onStatus?.('Mengonfirmasi status pembayaran...')
        cekStatusPembayaran(bookingId).then(resolve).catch(reject)
      },
      onError: () => reject(new Error('Pembayaran gagal, coba lagi.')),
      onClose: () => reject(new Error('Popup ditutup sebelum pembayaran selesai.')),
    })
  })
}
