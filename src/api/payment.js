import { apiFetch, getToken } from './client'

const BASE_URL = import.meta.env.VITE_API_URL || '/api'

// POST /api/booking/{id}/bayar — dapetin snap_token Midtrans
export function mintaSnapToken(bookingId) {
  return apiFetch(`/booking/${bookingId}/bayar`, { method: 'POST', auth: true })
}

// POST /api/booking/{id}/cek-status — dipanggil setelah popup Midtrans
// selesai (onSuccess/onPending), buat mastiin status ke-update meski webhook
// server-to-server-nya belum sempat masuk.
export function cekStatusPembayaran(bookingId) {
  return apiFetch(`/booking/${bookingId}/cek-status`, { method: 'POST', auth: true })
}

// GET /api/booking/{id}/invoice — respons-nya PDF mentah (bukan JSON), jadi
// tidak lewat apiFetch biasa. Diunduh sebagai blob lalu di-trigger via <a> aja
// karena butuh header Authorization yang tidak bisa dikirim lewat <a href>.
export async function unduhInvoice(bookingId) {
  const token = getToken()
  const res = await fetch(`${BASE_URL}/booking/${bookingId}/invoice`, {
    headers: token ? { Authorization: `Bearer ${token}` } : {},
  })
  if (!res.ok) {
    const data = await res.json().catch(() => null)
    throw new Error(data?.message || 'Gagal mengunduh invoice.')
  }
  const blob = await res.blob()
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `invoice-booking-${bookingId}.pdf`
  document.body.appendChild(a)
  a.click()
  a.remove()
  URL.revokeObjectURL(url)
}
