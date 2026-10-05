import { apiFetch } from './client'

// GET /api/poin — saldo poin, tier, dan riwayat poin (paginated)
export function fetchPoin() {
  return apiFetch('/poin', { auth: true })
}

// POST /api/poin/redeem — tukar poin (kelipatan 100) jadi voucher
export function redeemPoin(jumlahPoin) {
  return apiFetch('/poin/redeem', {
    method: 'POST',
    auth: true,
    body: { jumlah_poin: jumlahPoin },
  })
}
