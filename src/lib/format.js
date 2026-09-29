export function formatRupiah(amount) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
  }).format(amount ?? 0)
}

// Cocok dengan Carbon::dayOfWeek yang dipakai backend (0 = Minggu ... 6 = Sabtu)
export const NAMA_HARI = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu']

export function namaJenis(jenis) {
  if (!jenis) return '-'
  const map = { basket: 'Basketball' }
  const label = map[jenis.toLowerCase()] || jenis
  return label.charAt(0).toUpperCase() + label.slice(1)
}
