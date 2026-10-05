import { useEffect, useState } from 'react'
import useLockBodyScroll from '../lib/useLockBodyScroll'
import { useAuth } from '../context/AuthContext'
import { fetchMyBookings, cancelBooking } from '../api/booking'
import { unduhInvoice } from '../api/payment'
import { mulaiChat } from '../api/chat'
import IconChat from './IconChat'
import { bayarBooking } from '../lib/payBooking'
import { formatRupiah, waLink } from '../lib/format'

const STATUS_BADGE = {
  pending: 'bg-cream text-ink border border-black/20',
  confirmed: 'bg-court-green text-cream',
  cancelled: 'bg-whistle-red text-cream',
  completed: 'bg-match-blue text-cream',
}

export default function MyBookingsModal({ onClose }) {
  useLockBodyScroll()
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

  // Mulai (atau lanjutkan) chat dengan pemilik lapangan booking ini, lalu
  // pindah ke halaman chat. Backend: POST /api/percakapan { lapangan_id }.
  async function handleChat(booking) {
    if (!booking.lapangan?.id) return
    setBusyId(booking.id)
    setStatusMsg(null)
    try {
      const percakapan = await mulaiChat(booking.lapangan.id)
      onClose()
      window.location.hash = `#/chat/${percakapan.id}`
    } catch (err) {
      setStatusMsg(err.message)
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
    <div className="fixed inset-0 z-[100] bg-ink/60 flex justify-center px-4 py-8 overflow-y-auto overscroll-contain">
      <div className="my-auto bg-cream w-full max-w-lg rounded-lg border-2 border-ink shadow-tactile p-6 relative">
        <button
          onClick={onClose}
          className="absolute top-3 right-3 text-muted hover:text-ink font-bold"
          aria-label="Tutup"
        >
          ✕
        </button>

        <div className="flex items-center justify-between mb-2 pr-6">
          <h3 className="font-display text-2xl uppercase text-ink">Akun Saya</h3>
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

        {/* Menu Navigasi Fitur User */}
        <div className="grid grid-cols-2 sm:grid-cols-4 gap-1.5 p-1 bg-cream-dim rounded-lg mb-4 border border-black/10">
          <a
            href="#/poin"
            onClick={onClose}
            className="text-center py-2 px-1 text-xs font-bold uppercase text-ink hover:bg-white rounded transition-colors"
          >
            🎁 Poin
          </a>
          <a
            href="#/referral"
            onClick={onClose}
            className="text-center py-2 px-1 text-xs font-bold uppercase text-ink hover:bg-white rounded transition-colors"
          >
            👥 Referral
          </a>
          <a
            href="#/waitlist"
            onClick={onClose}
            className="text-center py-2 px-1 text-xs font-bold uppercase text-ink hover:bg-white rounded transition-colors"
          >
            ⏳ Waitlist
          </a>
          <a
            href="#/pengaturan/notifikasi"
            onClick={onClose}
            className="text-center py-2 px-1 text-xs font-bold uppercase text-ink hover:bg-white rounded transition-colors"
          >
            ⚙ Notif
          </a>
        </div>

        <div className="pt-2 border-t border-black/10 mb-3">
          <h4 className="font-display text-lg uppercase text-ink">Daftar Booking</h4>
        </div>

        {loading && <p className="text-muted text-sm py-6 text-center">Memuat booking...</p>}
        {error && <p className="text-whistle-red font-bold text-sm py-6 text-center">{error}</p>}
        {!loading && !error && bookings.length === 0 && (
          <p className="text-muted text-sm py-6 text-center">Belum ada booking.</p>
        )}

        {statusMsg && <p className="text-[12px] font-bold text-match-blue mb-3">{statusMsg}</p>}

        <ul className="space-y-2.5 max-h-[50vh] overflow-y-auto pr-1">
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
                  {(b.lapangan?.alamat || b.lapangan?.kota) && (
                    <p className="text-[12px] text-muted mt-0.5 flex items-start gap-1">
                      <span aria-hidden>📍</span>
                      <span>{b.lapangan?.alamat || b.lapangan?.kota}</span>
                    </p>
                  )}
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
                {b.lapangan?.id && (
                  <button
                    onClick={() => handleChat(b)}
                    disabled={busyId === b.id}
                    title="Chat dengan pemilik lapangan"
                    aria-label="Chat dengan pemilik lapangan"
                    className="w-8 h-8 inline-flex items-center justify-center rounded border border-match-blue/30 text-match-blue hover:bg-match-blue hover:text-cream disabled:opacity-60 transition-colors"
                  >
                    <IconChat />
                  </button>
                )}
                {waLink(b.lapangan?.no_wa, `Halo, saya ingin bertanya tentang booking ${b.lapangan?.nama_lapangan ?? ''}.`) && (
                  <a
                    href={waLink(b.lapangan?.no_wa, `Halo, saya ingin bertanya tentang booking ${b.lapangan?.nama_lapangan ?? ''}.`)}
                    target="_blank"
                    rel="noreferrer"
                    title="Chat via WhatsApp"
                    aria-label="Chat via WhatsApp"
                    className="w-8 h-8 inline-flex items-center justify-center rounded border border-[#25D366] text-[#128C7E] hover:bg-[#25D366] hover:text-white transition-colors"
                  >
                    <svg viewBox="0 0 24 24" fill="currentColor" className="w-[18px] h-[18px]">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                    </svg>
                  </a>
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
