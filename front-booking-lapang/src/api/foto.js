import { apiFetch } from './client'

// GET /api/lapangan/{id}/foto — daftar foto lapangan (publik, tapi butuh login
// karena route-nya ada di grup auth:sanctum).
export function fetchFotoLapangan(lapanganId) {
  return apiFetch(`/lapangan/${lapanganId}/foto`, { auth: true })
}

// POST /api/lapangan/{id}/foto — unggah satu/banyak foto (multipart).
// Backend: field 'foto' (array atau single). Maks 8 foto & 2MB per file.
export function unggahFotoLapangan(lapanganId, files) {
  const list = Array.isArray(files) ? files : [files]
  const formData = new FormData()
  list.forEach((f) => formData.append('foto[]', f))
  return apiFetch(`/lapangan/${lapanganId}/foto`, {
    method: 'POST',
    auth: true,
    body: formData,
  })
}

// DELETE /api/foto/{id}
export function hapusFoto(fotoId) {
  return apiFetch(`/foto/${fotoId}`, { method: 'DELETE', auth: true })
}

// POST /api/foto/{id}/jadikan-utama
export function jadikanFotoUtama(fotoId) {
  return apiFetch(`/foto/${fotoId}/jadikan-utama`, { method: 'POST', auth: true })
}
