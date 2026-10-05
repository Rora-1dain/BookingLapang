import { apiFetch } from './client'

// GET /api/notifikasi/preferensi — preferensi notifikasi user saat ini
export function fetchPreferensiNotifikasi() {
  return apiFetch('/notifikasi/preferensi', { auth: true })
}

// PUT /api/notifikasi/preferensi — simpan preferensi.
// payload: { preferensi: { booking: { email, database }, ... } }
export function simpanPreferensiNotifikasi(preferensi) {
  return apiFetch('/notifikasi/preferensi', {
    method: 'PUT',
    auth: true,
    body: { preferensi },
  })
}
