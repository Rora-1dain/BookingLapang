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

// 'YYYY-MM-DD' -> '2 Nov 2026'. Dipecah manual (bukan new Date(string)) supaya
// tidak bergeser sehari karena zona waktu.
export function formatTanggal(iso) {
  if (!iso) return '-'
  const [y, m, d] = String(iso).slice(0, 10).split('-').map(Number)
  return new Date(y, m - 1, d).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  })
}

// Date -> 'YYYY-MM-DD' memakai zona waktu lokal (toISOString memakai UTC).
export function tglLokal(date) {
  const p = (n) => String(n).padStart(2, '0')
  return `${date.getFullYear()}-${p(date.getMonth() + 1)}-${p(date.getDate())}`
}

// 1.250.000 -> '1,3 jt', 85.000 -> '85 rb'. Untuk label grafik yang sempit.
export function formatRingkas(n) {
  const v = Number(n) || 0
  if (v >= 1_000_000) return `${(v / 1_000_000).toFixed(1).replace('.', ',')} jt`
  if (v >= 1_000) return `${Math.round(v / 1_000)} rb`
  return String(Math.round(v))
}

export function formatPersen(n) {
  const v = Number(n) || 0
  return Number.isInteger(v) ? String(v) : v.toFixed(1).replace('.', ',')
}

// Nomor WA Indonesia -> link wa.me. Menerima '08xx', '+628xx', '628xx',
// spasi/tanda hubung. Mengembalikan null kalau nomor kosong/tidak valid
// supaya pemanggil bisa menyembunyikan tombolnya.
export function waLink(no, pesan) {
  if (!no) return null
  let digit = String(no).replace(/[^\d]/g, '')
  if (!digit) return null
  if (digit.startsWith('0')) digit = `62${digit.slice(1)}`
  else if (!digit.startsWith('62')) digit = `62${digit}`
  const teks = pesan ? `?text=${encodeURIComponent(pesan)}` : ''
  return `https://wa.me/${digit}${teks}`
}