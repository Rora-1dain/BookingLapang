import { useEffect, useState } from 'react'
import { useAuth } from '../context/AuthContext'
import { fetchMyBookings, cancelBooking } from '../api/booking'
import { unduhInvoice } from '../api/payment'
import { bayarBooking } from '../lib/payBooking'
import { formatRupiah } from '../lib/format'

const STATUS_BADGE = {
  pending: 'bg-cream text-ink border border-black/20',
  confirmed: 'bg-court-green text-cream',
  cancelled: 'bg-whistle-red text-cream',
  completed: 'bg-match-blue text-cream',
}

export default function MyBookingsModal({ onClose }) {
  const { user, logout } = useAuth()
  const [bookings, setBookings] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)
  const [busyId, setBusyId] = useState(null)
  const [statusMsg, setStatusMsg] = useState(null)

  useEffect(() => {
    load()
  }, [])

  function load() {
    setLoading(true)
    fetchMyBookings()
      .then((res) => setBookings(res.data))
      .catch((err) => setError(err.message))
      .finally(() => setLoading(false))
  }

  async function handleBayar(booking) {
    setBusyId(booking.id)
    setStatusMsg(null)
    try {
      const hasil = await bayarBooking(booking.id, {
        onStatus: (msg) => setStatusMsg(msg),
      })
      setStatusMsg(
        hasil.status_pembayaran === 'paid' ? 'Pembayaran berhasil!' : `Status: ${hasil.transaction_status}`
      )
      load()
    } catch (err) {
      setStatusMsg(err.message)
    } finally {
      setBusyId(null)
    }
  }

  async function handleInvoice(booking) {
    setBusyId(booking.id)
    setStatusMsg(null)
    try {
      await unduhInvoice(booking.id)
    } catch (err) {
      setStatusMsg(err.message)
    } finally {
      setBusyId(null)
    }
  }

  async function handleCancel(booking) {
    setBusyId(booking.id)
    setStatusMsg(null)
    try {
      await cancelBooking(booking.id)
      load()
    } catch (err) {
      setStatusMsg(err.message)
    } finally {
      setBusyId(null)
    }
  }

  return (
    <div className="fixed inset-0 z-[100] bg-ink/60 flex items-center justify-center px-4 py-8 overflow-y-auto">
      <div className="bg-cream w-full max-w-lg rounded-lg border-2 border-ink shadow-tactile p-6 relative">
        <button
          onClick={onClose}
          className="absolute top-3 right-3 text-muted hover:text-ink font-bold"
          aria-label="Tutup"
        >
          ✕
        </button>

        <div className="flex items-center justify-between mb-4 pr-6">
          <h3 className="font-display text-2xl uppercase text-ink">Booking Saya</h3>
          <button
            onClick={() => {
              logout()
              onClose()
            }}
            className="text-[12px] font-bold text-whistle-red underline shrink-0"
          >
            Logout
          </button>
        </div>
        <p className="text-[13px] text-muted mb-3">Masuk sebagai {user?.name}</p>

        {loading && <p className="text-muted text-sm py-6 text-center">Memuat booking...</p>}
        {error && <p className="text-whistle-red font-bold text-sm py-6 text-center">{error}</p>}
        {!loading && !error && bookings.length === 0 && (
          <p className="text-muted text-sm py-6 text-center">Belum ada booking.</p>
        )}

        {statusMsg && <p className="text-[12px] font-bold text-match-blue mb-3">{statusMsg}</p>}

        <ul className="space-y-2.5">
          {bookings.map((b) => (
            <li key={b.id} className="bg-white border border-black/10 rounded-lg p-3">
              <div className="flex items-start justify-between gap-2">
                <div>
                  <p className="font-display text-base uppercase text-ink leading-tight">
                    {b.lapangan?.nama_lapangan}
                  </p>
                  <p className="text-[12px] text-muted">
                    {b.tanggal_booking} · {b.jam_mulai}–{b.jam_selesai}
                  </p>
                  <p className="text-[12px] font-bold text-ink mt-0.5">{formatRupiah(b.total_harga)}</p>
                </div>
                <span
                  className={`text-[10px] font-bold px-2 py-0.5 rounded uppercase shrink-0 ${
                    STATUS_BADGE[b.status] || 'bg-cream text-ink border border-black/20'
                  }`}
                >
                  {b.status}
                </span>
              </div>

              <div className="flex items-center gap-2 mt-2.5 pt-2.5 border-t border-black/5">
                {b.bisa_dibayar && b.status !== 'cancelled' && (
                  <button
                    onClick={() => handleBayar(b)}
                    disabled={busyId === b.id}
                    className="bg-court-green hover:bg-court-green-dark disabled:opacity-60 text-cream text-xs font-bold px-3 py-1.5 rounded uppercase transition-colors"
                  >
                    {busyId === b.id ? 'Memproses...' : 'Bayar'}
                  </button>
                )}
                {!b.bisa_dibayar && (
                  <button
                    onClick={() => handleInvoice(b)}
                    disabled={busyId === b.id}
                    className="bg-ink hover:bg-match-blue disabled:opacity-60 text-cream text-xs font-bold px-3 py-1.5 rounded uppercase transition-colors"
                  >
                    Unduh Invoice
                  </button>
                )}
                {b.bisa_dibatalkan && (
                  <button
                    onClick={() => handleCancel(b)}
                    disabled={busyId === b.id}
                    className="border border-whistle-red text-whistle-red text-xs font-bold px-3 py-1.5 rounded uppercase disabled:opacity-60"
                  >
                    Batalkan
                  </button>
                )}
              </div>
            </li>
          ))}
        </ul>
      </div>
    </div>
  )
}
