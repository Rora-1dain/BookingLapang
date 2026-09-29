import { apiFetch } from './client'

// GET /api/booking — daftar booking milik user yang sedang login (paginated)
export async function fetchMyBookings(params = {}) {
  const json = await apiFetch('/booking', { auth: true, params })
  return { data: json.data ?? [], meta: json.meta ?? null }
}

// POST /api/booking/{id}/cancel
export function cancelBooking(id) {
  return apiFetch(`/booking/${id}/cancel`, { method: 'POST', auth: true })
}

// POST /api/booking — butuh auth:sanctum. Backend yang mengecek bentrok jadwal
// & jam operasional (BookingService::cekKetersediaan / dalamJamOperasional),
// jadi frontend tidak perlu (dan tidak bisa) menampilkan grid slot real-time —
// tidak ada endpoint publik untuk itu. Kita kirim rentang jam yang dipilih
// user dan tampilkan pesan error dari backend kalau ternyata bentrok.
export function createBooking({ lapangan_id, tanggal_booking, jam_mulai, jam_selesai }) {
  return apiFetch('/booking', {
    method: 'POST',
    auth: true,
    body: { lapangan_id, tanggal_booking, jam_mulai, jam_selesai },
  })
}