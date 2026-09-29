import { useState } from 'react'
import { useVenues } from '../context/VenueContext'
import { formatRupiah, namaJenis } from '../lib/format'
import BookingPanel from './BookingPanel'

export default function Hero() {
  const { featured, categories, setFilters } = useVenues()
  const [sport, setSport] = useState('')
  const [kota, setKota] = useState('')

  function handleSearch(e) {
    e.preventDefault()
    setFilters((f) => ({ ...f, jenis: sport, kota }))
    document.getElementById('lapangan')?.scrollIntoView({ behavior: 'smooth' })
  }

  return (
    <section className="relative w-full overflow-hidden bg-cream border-b border-match-blue/10">
      <div className="max-w-[1440px] mx-auto min-h-[600px] flex flex-col lg:flex-row items-stretch">
        {/* Left: search hub */}
        <div className="lg:w-[58%] bg-match-blue clip-hero text-cream px-6 lg:px-16 py-14 flex flex-col justify-center">
          <div className="inline-flex items-center gap-2 bg-ink px-3 py-1 rounded-lg w-max mb-4 border border-white/10">
            <span className="w-2 h-2 rounded-full bg-court-green animate-pulse" />
            <span className="text-xs tracking-widest text-cream">
              JARINGAN STADIUM RESMI • WILAYAH JAKARTA
            </span>
          </div>

          <h1 className="font-display text-5xl lg:text-7xl uppercase leading-[0.95] tracking-tight mb-4">
            Sewa Lapanganmu.
            <br />
            <span className="text-[#7cd9a4]">Kuasai Pertandingan.</span>
          </h1>

          <p className="text-lg text-cream/90 max-w-xl mb-8 leading-relaxed">
            Booking lapangan terverifikasi secara instan. Ketersediaan dicek langsung ke
            backend saat kamu mengunci jadwal.
          </p>

          <form
            onSubmit={handleSearch}
            className="bg-cream text-ink rounded-lg p-4 border-2 border-ink shadow-tactile max-w-xl"
          >
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
              <label className="block">
                <span className="block text-[11px] font-bold tracking-wider mb-1">PILIH OLAHRAGA</span>
                <select
                  value={sport}
                  onChange={(e) => setSport(e.target.value)}
                  className="w-full bg-white border border-match-blue/20 rounded-lg px-2.5 py-2 text-sm font-semibold focus:border-match-blue focus:outline-none"
                >
                  <option value="">Semua Olahraga</option>
                  {categories.map((cat) => (
                    <option key={cat.jenis} value={cat.jenis}>
                      {namaJenis(cat.jenis)}
                    </option>
                  ))}
                </select>
              </label>
              <label className="block">
                <span className="block text-[11px] font-bold tracking-wider mb-1">KOTA</span>
                <input
                  type="text"
                  value={kota}
                  onChange={(e) => setKota(e.target.value)}
                  placeholder="mis. Jakarta Selatan"
                  className="w-full bg-white border border-match-blue/20 rounded-lg px-2.5 py-2 text-sm font-semibold focus:border-match-blue focus:outline-none"
                />
              </label>
            </div>
            <button
              type="submit"
              className="w-full bg-court-green hover:bg-court-green-dark text-cream font-bold text-sm uppercase py-2.5 rounded-lg shadow-tactile-sm transition-colors"
            >
              Cari Lapangan
            </button>
          </form>
        </div>

        {/* Right: featured venue card — venue rating tertinggi dari data asli */}
        <div className="lg:w-[42%] p-6 lg:p-10 flex items-center justify-center diagonal-stripes">
          {!featured ? (
            <div className="bg-white/70 border-2 border-match-blue/20 rounded-lg p-6 max-w-md w-full text-center text-muted font-bold">
              Memuat lapangan unggulan...
            </div>
          ) : (
            <div className="bg-white border-2 border-match-blue/20 rounded-lg p-3 shadow-tactile max-w-md w-full">
              <div className="relative h-64 sm:h-72 w-full overflow-hidden rounded bg-cream-dim">
                {featured.foto_utama ? (
                  <img
                    src={featured.foto_utama}
                    alt={featured.nama_lapangan}
                    className="w-full h-full object-cover"
                  />
                ) : (
                  <div className="w-full h-full flex items-center justify-center text-muted font-bold">
                    BELUM ADA FOTO
                  </div>
                )}
                <div className="absolute top-2 left-2 flex gap-1.5">
                  <span className="bg-ink text-cream text-[11px] font-bold px-2 py-0.5 rounded tracking-wide">
                    ARENA MINGGU INI
                  </span>
                </div>
                <div className="absolute bottom-2 right-2 bg-match-blue text-cream font-display text-xl px-3 py-1 rounded">
                  {formatRupiah(featured.harga_per_jam)} <span className="text-xs">/JAM</span>
                </div>
              </div>

              <div className="mt-3">
                <div className="flex items-start justify-between">
                  <div>
                    {featured.kota && (
                      <span className="text-xs font-bold text-match-blue tracking-wide">
                        {featured.kota.toUpperCase()}
                      </span>
                    )}
                    <h3 className="font-display text-xl uppercase mt-0.5">{featured.nama_lapangan}</h3>
                  </div>
                  {featured.rating != null && (
                    <div className="flex items-center gap-1 bg-cream px-2 py-1 rounded border border-match-blue/10 shrink-0">
                      <span className="text-ink">★</span>
                      <span className="font-bold text-sm">{Number(featured.rating).toFixed(2)}</span>
                    </div>
                  )}
                </div>

                <BookingPanel lapangan={featured} />
              </div>
            </div>
          )}
        </div>
      </div>
    </section>
  )
}
