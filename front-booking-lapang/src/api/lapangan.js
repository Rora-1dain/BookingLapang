import { apiFetch } from './client'

// GET /api/lapangan — publik, dipaginasi oleh LapanganSearchService (12/halaman).
// kriteria yang didukung backend: jenis, kota, harga_min, harga_max, rating_min, kata_kunci
export async function fetchLapangan(kriteria = {}) {
  const json = await apiFetch('/lapangan', { params: kriteria })
  // LapanganResource::collection() atas paginator -> { data, links, meta }
  return {
    data: json.data ?? [],
    meta: json.meta ?? null,
  }
}

// GET /api/lapangan/{id} — publik, hanya lapangan yang disetujui & aktif
export async function fetchLapanganDetail(id) {
  const json = await apiFetch(`/lapangan/${id}`)
  return json.data
}
