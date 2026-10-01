import { useEffect, useState } from 'react'
import useLockBodyScroll from '../lib/useLockBodyScroll'
import { fetchLapanganDetail } from '../api/lapangan'
import { formatRupiah, namaJenis } from '../lib/format'
import BookingPanel from './BookingPanel'

export default function VenueDetailModal({ id, onClose }) {
  useLockBodyScroll()
  const [lapangan, setLapangan] = useState(null)
  const [error, setError] = useState(null)

  useEffect(() => {
    let active = true
    fetchLapanganDetail(id)
      .then((data) => active && setLapangan(data))
      .catch((err) => active && setError(err.message))
    return () => {
      active = false
    }
  }, [id])

  return (
    <div className="fixed inset-0 z-[100] bg-ink/60 flex justify-center px-4 py-8 overflow-y-auto overscroll-contain">
      <div className="my-auto bg-white w-full max-w-lg rounded-lg border-2 border-ink shadow-tactile p-5 relative">
        <button
          onClick={onClose}
          className="absolute top-3 right-3 text-muted hover:text-ink font-bold z-10"
          aria-label="Tutup"
        >
          ✕
        </button>

        {error && <p className="text-whistle-red font-bold py-8 text-center">{error}</p>}

        {!lapangan && !error && <p className="text-muted py-8 text-center">Memuat detail lapangan...</p>}

        {lapangan && (
          <>
            {lapangan.foto_utama && (
              <img
                src={lapangan.foto_utama}
                alt={lapangan.nama_lapangan}
                className="w-full h-56 object-cover rounded mb-3"
              />
            )}

            <span className="bg-court-green text-cream text-[11px] font-bold px-2 py-0.5 rounded">
              {namaJenis(lapangan.jenis).toUpperCase()}
            </span>
            <h3 className="font-display text-2xl uppercase text-ink mt-1">{lapangan.nama_lapangan}</h3>
            <div className="flex items-center gap-3 text-sm text-muted mt-1">
              {lapangan.kota && <span>{lapangan.kota}</span>}
              {lapangan.rating != null && (
                <span className="flex items-center gap-1 font-bold text-ink">
                  ★ {Number(lapangan.rating).toFixed(2)}
                </span>
              )}
            </div>
            <div className="mt-2 font-display text-xl text-match-blue">
              {formatRupiah(lapangan.harga_per_jam)} <span className="text-xs text-muted">/JAM</span>
            </div>

            {lapangan.galeri?.length > 1 && (
              <div className="flex gap-1.5 mt-3 overflow-x-auto">
                {lapangan.galeri.map((foto) => (
                  <img
                    key={foto.id}
                    src={foto.url}
                    alt=""
                    className="h-16 w-24 object-cover rounded border border-black/10 shrink-0"
                  />
                ))}
              </div>
            )}

            <BookingPanel lapangan={lapangan} />
          </>
        )}
      </div>
    </div>
  )
}