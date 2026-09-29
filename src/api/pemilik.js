import { apiFetch } from './client'

// GET /api/pemilik/lapangan — lapangan milik user yang sedang login
// (mengembalikan model mentah, bukan LapanganResource, jadi field-nya:
// nama_lapangan, jenis, harga_per_jam, status, status_approval, kota)
export function fetchMyLapangan() {
  return apiFetch('/pemilik/lapangan', { auth: true })
}

// POST /api/pemilik/lapangan — ditolak dengan 403 kalau user belum
// terverifikasi (status_verifikasi !== 'terverifikasi'); pesan errornya
// sudah jelas dari backend, jadi dilempar apa adanya lewat ApiError.
export function submitLapangan({ nama_lapangan, jenis, harga_per_jam, kota }) {
  return apiFetch('/pemilik/lapangan', {
    method: 'POST',
    auth: true,
    body: { nama_lapangan, jenis, harga_per_jam, kota: kota || null },
  })
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
