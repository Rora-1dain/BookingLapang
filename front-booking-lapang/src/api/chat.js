import { apiFetch } from './client'

// GET /api/percakapan — semua percakapan milik user (sebagai pemesan ATAU pemilik)
export async function fetchPercakapan() {
  const json = await apiFetch('/percakapan', { auth: true })
  return json.data ?? []
}

// POST /api/percakapan — mulai atau lanjutkan chat dengan pemilik sebuah lapangan.
// Backend pakai firstOrCreate (unik per lapangan + user), jadi aman dipanggil berulang.
export async function mulaiChat(lapanganId) {
  const json = await apiFetch('/percakapan', {
    method: 'POST',
    auth: true,
    body: { lapangan_id: lapanganId },
  })
  return json.data
}

// GET /api/percakapan/{id} — detail + seluruh pesan
export async function fetchDetailPercakapan(id) {
  const json = await apiFetch(`/percakapan/${id}`, { auth: true })
  return json.data
}

// POST /api/percakapan/{id}/pesan  (isi maksimal 1000 karakter)
export async function kirimPesan(id, isi) {
  const json = await apiFetch(`/percakapan/${id}/pesan`, {
    method: 'POST',
    auth: true,
    body: { isi },
  })
  return json.data
}

// POST /api/percakapan/{id}/tandai-dibaca
export function tandaiDibaca(id) {
  return apiFetch(`/percakapan/${id}/tandai-dibaca`, { method: 'POST', auth: true })
}