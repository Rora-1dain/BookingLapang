import { berlangganan, cekStatusMembership } from '../api/membership'
import { loadMidtransSnap } from './midtrans'

// Alur pembayaran membership, meniru payBooking.js:
// berlangganan() -> snap.pay() -> cekStatusMembership().
// Resolve dengan hasil cekStatus (status transaksi + data langganan bila aktif).
// Reject kalau user menutup popup atau Midtrans balikin error eksplisit —
// transaksinya sendiri tetap ada di database, bisa dicoba lagi.
export async function bayarMembership(membershipPaketId, { onStatus } = {}) {
  onStatus?.('Menyiapkan pembayaran...')
  const res = await berlangganan(membershipPaketId)
  const snap = await loadMidtransSnap(res.client_key, res.is_production)

  return new Promise((resolve, reject) => {
    snap.pay(res.snap_token, {
      onSuccess: () => {
        onStatus?.('Mengonfirmasi status pembayaran...')
        cekStatusMembership(res.transaction_id).then(resolve).catch(reject)
      },
      onPending: () => {
        onStatus?.('Mengonfirmasi status pembayaran...')
        cekStatusMembership(res.transaction_id).then(resolve).catch(reject)
      },
      onError: () => reject(new Error('Pembayaran gagal, coba lagi.')),
      onClose: () => reject(new Error('Popup ditutup sebelum pembayaran selesai.')),
    })
  })
}
