import { apiFetch, getToken } from './client'

const BASE_URL = import.meta.env.VITE_API_URL || '/api'

// -- Approval Lapangan --------------------------------------------------
export function fetchApprovalLapangan() {
  return apiFetch('/admin/lapangan/approval', { auth: true })
}

// PUT /api/admin/lapangan/{id}/komisi — ubah persentase komisi platform
export function ubahKomisi(id, persentaseKomisi) {
  return apiFetch(`/admin/lapangan/${id}/komisi`, {
    method: 'PUT',
    auth: true,
    body: { persentase_komisi: persentaseKomisi },
  })
}
export function setujuiLapangan(id) {
  return apiFetch(`/admin/lapangan/${id}/setujui`, { method: 'POST', auth: true })
}
export function tolakLapangan(id, alasan) {
  return apiFetch(`/admin/lapangan/${id}/tolak`, { method: 'POST', auth: true, body: { alasan } })
}

// -- Verifikasi KYC Pemilik ----------------------------------------------
export function fetchVerifikasiPending() {
  return apiFetch('/admin/verifikasi', { auth: true })
}
export function tinjauVerifikasi(pemilikId, keputusan, catatan) {
  return apiFetch(`/admin/verifikasi/${pemilikId}/tinjau`, {
    method: 'POST',
    auth: true,
    body: { keputusan, catatan: catatan || null },
  })
}

// -- Refund ---------------------------------------------------------------
// Daftar seluruh booking (opsional filter status_pembayaran). Masih dipakai
// endpoint /admin/booking; daftar refund kini lewat fetchRefundRequests().
export function fetchAdminBookings(params = {}) {
  return apiFetch('/admin/booking', { auth: true, params })
}

// Daftar pengajuan refund (semua status kecuali belum_refund). Respons:
// { data: { data: [...], meta }, counts } — diratakan jadi { data, counts }.
export async function fetchRefundRequests(params = {}) {
  const json = await apiFetch('/admin/refund', { auth: true, params })
  return { data: json.data?.data ?? json.data ?? [], counts: json.counts ?? {} }
}
export function refundBooking(id, alasan) {
  return apiFetch(`/admin/booking/${id}/refund`, { method: 'POST', auth: true, body: { alasan } })
}
export function tolakRefund(id, catatan) {
  return apiFetch(`/admin/booking/${id}/refund/tolak`, {
    method: 'POST',
    auth: true,
    body: { catatan: catatan || null },
  })
}

// -- Payout ke Pemilik ------------------------------------------------------
export function fetchPayouts() {
  return apiFetch('/admin/payout', { auth: true })
}
export function createPayout({ pemilik_id, periode_mulai, periode_selesai }) {
  return apiFetch('/admin/payout', {
    method: 'POST',
    auth: true,
    body: { pemilik_id, periode_mulai, periode_selesai },
  })
}
export function selesaikanPayout(id) {
  return apiFetch(`/admin/payout/${id}/selesai`, { method: 'POST', auth: true })
}

// -- Laporan Platform ---------------------------------------------------
export function fetchLaporanPlatform(params = {}) {
  return apiFetch('/admin/laporan-platform', { auth: true, params })
}

// GET /api/admin/laporan/ledger/export — respons .xlsx mentah (bukan JSON),
// jadi tidak lewat apiFetch. Diunduh sebagai blob lalu di-trigger via <a>
// karena butuh header Authorization yang tidak bisa dikirim lewat <a href>.
export async function exportLedger(mulai, selesai) {
  const token = getToken()
  const params = new URLSearchParams({ mulai, selesai }).toString()
  const res = await fetch(`${BASE_URL}/admin/laporan/ledger/export?${params}`, {
    headers: token ? { Authorization: `Bearer ${token}` } : {},
  })
  if (!res.ok) {
    const data = await res.json().catch(() => null)
    throw new Error(data?.message || 'Gagal mengekspor ledger.')
  }
  const blob = await res.blob()
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `ledger_${mulai}_${selesai}.xlsx`
  document.body.appendChild(a)
  a.click()
  a.remove()
  URL.revokeObjectURL(url)
}

// -- Ulasan Dilaporkan (read-only, backend belum punya aksi moderasi) ---
export function fetchUlasanDilaporkan() {
  return apiFetch('/admin/ulasan/dilaporkan', { auth: true })
}
