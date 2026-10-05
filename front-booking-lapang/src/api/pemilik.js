import { apiFetch } from './client'

// GET /api/pemilik/lapangan — lapangan milik user yang sedang login
// (mengembalikan model mentah, bukan LapanganResource, jadi field-nya:
// nama_lapangan, jenis, harga_per_jam, status, status_approval, alamat, no_wa, kota)
export function fetchMyLapangan() {
  return apiFetch('/pemilik/lapangan', { auth: true })
}

// POST /api/pemilik/lapangan — ditolak dengan 403 kalau user belum
// terverifikasi (status_verifikasi !== 'terverifikasi'); pesan errornya
// sudah jelas dari backend, jadi dilempar apa adanya lewat ApiError.
export function submitLapangan({ nama_lapangan, jenis, harga_per_jam, alamat, no_wa, kota }) {
  return apiFetch('/pemilik/lapangan', {
    method: 'POST',
    auth: true,
    body: { nama_lapangan, jenis, harga_per_jam, alamat, no_wa, kota: kota || null },
  })
}

// PUT /api/pemilik/lapangan/{id} — ubah data lapangan milik sendiri.
export function updateLapangan(id, { nama_lapangan, jenis, harga_per_jam, alamat, no_wa, kota }) {
  return apiFetch(`/pemilik/lapangan/${id}`, {
    method: 'PUT',
    auth: true,
    body: { nama_lapangan, jenis, harga_per_jam, alamat, no_wa, kota: kota || null },
  })
}

// GET /api/pemilik/payout — riwayat payout milik pemilik (paginated)
export async function fetchPayoutPemilik() {
  const json = await apiFetch('/pemilik/payout', { auth: true })
  return { data: json.data ?? [], meta: json.meta ?? null }
}

// POST /api/pemilik/verifikasi — upload dokumen KYC (multipart), dipakai
// saat submitLapangan() gagal karena belum verifikasi.
export function ajukanVerifikasi(file) {
  const formData = new FormData()
  formData.append('dokumen', file)
  return apiFetch('/pemilik/verifikasi', {
    method: 'POST',
    auth: true,
    body: formData,
  })
}
