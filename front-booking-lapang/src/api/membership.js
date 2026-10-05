import { apiFetch } from './client'

// GET /api/membership/paket (publik)
export async function fetchPaket() {
  return apiFetch('/membership/paket')
}

// GET /api/membership — langganan aktif milik user, atau data: null
export async function fetchMembershipSaya() {
  return apiFetch('/membership', { auth: true })
}

// POST /api/membership/berlangganan — buat transaksi Midtrans.
// Mengembalikan { snap_token, client_key, is_production, order_id, transaction_id }.
// Membership baru aktif setelah pembayaran settlement.
export function berlangganan(membershipPaketId) {
  return apiFetch('/membership/berlangganan', {
    method: 'POST',
    auth: true,
    body: { membership_paket_id: membershipPaketId },
  })
}

// POST /api/membership/cek-status/{trx} — sinkronkan status setelah popup Midtrans.
export function cekStatusMembership(transactionId) {
  return apiFetch(`/membership/cek-status/${transactionId}`, { method: 'POST', auth: true })
}
