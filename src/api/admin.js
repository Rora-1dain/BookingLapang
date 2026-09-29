import { apiFetch } from './client'

// -- Approval Lapangan --------------------------------------------------
export function fetchApprovalLapangan() {
  return apiFetch('/admin/lapangan/approval', { auth: true })
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

// -- Booking & Refund -----------------------------------------------------
export function fetchAdminBookings(params = {}) {
  return apiFetch('/admin/booking', { auth: true, params })
}
export function refundBooking(id, alasan) {
  return apiFetch(`/admin/booking/${id}/refund`, { method: 'POST', auth: true, body: { alasan } })
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

// -- Ulasan Dilaporkan (read-only, backend belum punya aksi moderasi) ---
export function fetchUlasanDilaporkan() {
  return apiFetch('/admin/ulasan/dilaporkan', { auth: true })
}
