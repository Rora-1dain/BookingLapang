import { apiFetch } from './client'

// GET /api/waitlist — daftar waitlist milik user
export async function fetchWaitlist() {
  const json = await apiFetch('/waitlist', { auth: true })
  return json.data ?? []
}

// POST /api/waitlist/daftar — daftar tunggu untuk slot yang sudah penuh
export function daftarWaitlist({ lapangan_id, tanggal_booking, jam_mulai, jam_selesai }) {
  return apiFetch('/waitlist/daftar', {
    method: 'POST',
    auth: true,
    body: { lapangan_id, tanggal_booking, jam_mulai, jam_selesai },
  })
}
