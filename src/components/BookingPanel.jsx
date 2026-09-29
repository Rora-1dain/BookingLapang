import { useState } from 'react'
import { useAuth } from '../context/AuthContext'
import { createBooking } from '../api/booking'
import { bayarBooking } from '../lib/payBooking'
import { NAMA_HARI } from '../lib/format'

// Catatan: backend tidak punya endpoint publik untuk "daftar slot tersedia" —
// ketersediaan cuma dicek server-side saat POST /api/booking (BookingService
// ::cekKetersediaan & dalamJamOperasional). Jadi di sini kita tampilkan jam
// operasional lapangan (kalau ada, dari jadwal_hari_ini) sebagai panduan, lalu
// biarkan backend yang memutuskan bentrok/tidak saat form dikirim.
export default function BookingPanel({ lapangan, compact = false }) {
  const { user } = useAuth()
  const today = new Date().toISOString().slice(0, 10)
  const [tanggal, setTanggal] = useState(today)
  const [jamMulai, setJamMulai] = useState('19:00')
  const [jamSelesai, setJamSelesai] = useState('20:00')
  const [status, setStatus] = useState(null) // { type: 'success' | 'error', message }
  const [submitting, setSubmitting] = useState(false)

  const jadwal = lapangan?.jadwal_hari_ini
  const hariIni = NAMA_HARI[new Date().getDay()]

  async function handleSubmit(e) {
    e.preventDefault()
    if (!user) {
      setStatus({ type: 'error', message: 'Login dulu untuk mengunci lapangan.' })
      return
    }
    setSubmitting(true)
    setStatus({ type: 'info', message: 'Mengunci slot...' })
    try {
      const booking = await createBooking({
        lapangan_id: lapangan.id,
        tanggal_booking: tanggal,
        jam_mulai: jamMulai,
        jam_selesai: jamSelesai,
      })
      await lanjutkanPembayaran(booking.data.id)
    } catch (err) {
      setStatus({ type: 'error', message: err.message })
      setSubmitting(false)
    }
  }

  // Slot sudah terkunci (status booking 'pending') begitu createBooking()
  // sukses — langkah ini cuma soal pembayarannya, jadi kalau gagal di sini
  // booking-nya TETAP ada, bisa dilanjutkan lagi lewat "Booking Saya" (Navbar).
  async function lanjutkanPembayaran(bookingId) {
    try {
      const hasil = await bayarBooking(bookingId, {
        onStatus: (msg) => setStatus({ type: 'info', message: msg }),
      })
      setStatus({
        type: hasil.status_pembayaran === 'paid' ? 'success' : 'error',
        message:
          hasil.status_pembayaran === 'paid'
            ? 'Pembayaran berhasil! Lapangan sudah dikonfirmasi.'
            : `Status: ${hasil.transaction_status}. Booking tersimpan, bisa dilanjutkan lewat Booking Saya.`,
      })
    } catch (err) {
      setStatus({
        type: 'error',
        message: `${err.message} Booking tetap tersimpan, coba bayar lagi lewat Booking Saya.`,
      })
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div className={compact ? '' : 'mt-3 pt-2.5 border-t border-black/10'}>
      {jadwal !== undefined && (
        <div className="text-[11px] font-bold text-muted mb-1.5">
          JAM OPERASIONAL {hariIni.toUpperCase()}:{' '}
          {jadwal === null || jadwal.is_tutup ? (
            <span className="text-whistle-red">TUTUP</span>
          ) : (
            <span className="text-court-green">
              {jadwal.jam_buka} – {jadwal.jam_tutup}
            </span>
          )}
        </div>
      )}

      <form onSubmit={handleSubmit} className="grid grid-cols-3 gap-1.5">
        <label className="col-span-1 block">
          <span className="block text-[10px] font-bold text-muted mb-0.5">TANGGAL</span>
          <input
            type="date"
            min={today}
            value={tanggal}
            onChange={(e) => setTanggal(e.target.value)}
            className="w-full bg-white border border-black/10 rounded px-1.5 py-1 text-[12px] font-semibold focus:border-match-blue focus:outline-none"
            required
          />
        </label>
        <label className="col-span-1 block">
          <span className="block text-[10px] font-bold text-muted mb-0.5">JAM MULAI</span>
          <input
            type="time"
            value={jamMulai}
            onChange={(e) => setJamMulai(e.target.value)}
            className="w-full bg-white border border-black/10 rounded px-1.5 py-1 text-[12px] font-semibold focus:border-match-blue focus:outline-none"
            required
          />
        </label>
        <label className="col-span-1 block">
          <span className="block text-[10px] font-bold text-muted mb-0.5">JAM SELESAI</span>
          <input
            type="time"
            value={jamSelesai}
            onChange={(e) => setJamSelesai(e.target.value)}
            className="w-full bg-white border border-black/10 rounded px-1.5 py-1 text-[12px] font-semibold focus:border-match-blue focus:outline-none"
            required
          />
        </label>

        <button
          type="submit"
          disabled={submitting}
          className="col-span-3 mt-1.5 w-full bg-court-green hover:bg-court-green-dark disabled:opacity-60 text-cream font-bold uppercase py-2.5 rounded-lg shadow-tactile-sm transition-colors"
        >
          {submitting ? 'Mengirim...' : 'Kunci Lapangan Sekarang'}
        </button>
      </form>

      {status && (
        <p
          className={`mt-2 text-[12px] font-bold ${
            status.type === 'success'
              ? 'text-court-green'
              : status.type === 'info'
                ? 'text-match-blue'
                : 'text-whistle-red'
          }`}
        >
          {status.message}
        </p>
      )}
    </div>
  )
}