import { useState } from 'react'
import { useVenues } from '../context/VenueContext'
import { formatRupiah, namaJenis } from '../lib/format'
import VenueDetailModal from './VenueDetailModal'

export default function VenueGrid() {
  const { venues, loading, error, categories, filters, setFilters } = useVenues()
  const [detailId, setDetailId] = useState(null)
  const activeSport = filters.jenis || 'all'

  return (
    <main id="lapangan" className="w-full max-w-content mx-auto px-6 py-16">
      <div className="flex flex-col md:flex-row md:items-end justify-between border-b-2 border-ink pb-3 mb-8 gap-4">
        <div>
          <div className="flex items-center gap-2 mb-1">
            <span className="w-3 h-3 bg-match-blue" />
            <span className="text-xs font-bold tracking-widest text-match-blue">
              ARENA STANDAR TURNAMEN
            </span>
          </div>
          <h2 className="font-display text-4xl text-ink uppercase">Lapangan Unggulan</h2>
        </div>

        <div className="flex items-center gap-1.5 overflow-x-auto pb-1">
          <button
            onClick={() => setFilters((f) => ({ ...f, jenis: '' }))}
            className={`px-3.5 py-1.5 rounded-lg text-[13px] font-bold uppercase whitespace-nowrap ${
              activeSport === 'all' ? 'bg-match-blue text-cream' : 'bg-white text-muted border border-black/10'
            }`}
          >
            Semua ({venues.length})
          </button>
          {categories.map((cat) => (
            <button
              key={cat.jenis}
              onClick={() => setFilters((f) => ({ ...f, jenis: cat.jenis }))}
              className={`px-3.5 py-1.5 rounded-lg text-[13px] font-bold uppercase whitespace-nowrap ${
                activeSport === cat.jenis
                  ? 'bg-match-blue text-cream'
                  : 'bg-white text-muted border border-black/10'
              }`}
            >
              {namaJenis(cat.jenis)} ({cat.count})
            </button>
          ))}
        </div>
      </div>

      {loading && <p className="text-center text-muted py-12">Memuat daftar lapangan...</p>}

      {error && !loading && <p className="text-center text-whistle-red font-bold py-12">{error}</p>}

      {!loading && !error && (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {venues.map((venue) => (
            <VenueCard key={venue.id} venue={venue} onLihatSlot={() => setDetailId(venue.id)} />
          ))}
        </div>
      )}

      {!loading && !error && venues.length === 0 && (
        <p className="text-center text-muted py-12">Belum ada lapangan untuk kategori ini.</p>
      )}

      {detailId && <VenueDetailModal id={detailId} onClose={() => setDetailId(null)} />}
    </main>
  )
}

function VenueCard({ venue, onLihatSlot }) {
  return (
    <div className="bg-white border-2 border-match-blue/15 rounded-lg overflow-hidden shadow-tactile hover:shadow-tactile-hover hover:-translate-y-0.5 transition-all flex flex-col">
      <div className="relative h-52 w-full overflow-hidden bg-cream-dim">
        {venue.foto_utama ? (
          <img src={venue.foto_utama} alt={venue.nama_lapangan} className="w-full h-full object-cover" />
        ) : (
          <div className="w-full h-full flex items-center justify-center text-muted text-sm font-bold">
            BELUM ADA FOTO
          </div>
        )}
        <div className="absolute top-2.5 left-2.5 flex gap-1.5">
          <span className="bg-court-green text-cream text-[11px] font-bold px-2 py-0.5 rounded">
            {namaJenis(venue.jenis).toUpperCase()}
          </span>
        </div>
        <div className="absolute bottom-2.5 right-2.5 bg-white text-ink font-display text-lg px-2.5 py-0.5 rounded border-2 border-ink">
          {formatRupiah(venue.harga_per_jam)} <span className="text-[11px] text-muted">/JAM</span>
        </div>
      </div>

      <div className="p-4 flex flex-col flex-1">
        <div className="flex items-start justify-between mb-1.5">
          <div>
            {venue.kota && <span className="text-[11px] font-bold text-match-blue">{venue.kota.toUpperCase()}</span>}
            <h3 className="font-display text-lg text-ink uppercase leading-tight mt-0.5">
              {venue.nama_lapangan}
            </h3>
          </div>
          {venue.rating != null && (
            <div className="flex items-center gap-1 bg-cream px-2 py-0.5 rounded shrink-0">
              <span>★</span>
              <span className="text-sm font-bold">{Number(venue.rating).toFixed(2)}</span>
            </div>
          )}
        </div>

        <div className="mt-auto pt-2.5 border-t border-black/10 flex items-center justify-end gap-2">
          <button
            onClick={onLihatSlot}
            className="bg-ink hover:bg-match-blue text-cream text-xs font-bold px-3 py-1.5 rounded uppercase shrink-0 transition-colors"
          >
            Lihat Slot
          </button>
        </div>
      </div>
    </div>
  )
}
