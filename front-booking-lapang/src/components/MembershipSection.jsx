import { useEffect, useState } from 'react'
import { fetchPaket } from '../api/membership'
import { formatPersen, formatRupiah } from '../lib/format'

// Pengganti section "Radar Komunitas Matchday". Ukuran dan struktur sengaja sama:
// header + statistik di kanan, 3 kartu, dan strip ajakan di bawah.
export default function MembershipSection() {
  const [pakets, setPakets] = useState(null)
  const [error, setError] = useState(null)

  useEffect(() => {
    fetchPaket()
      .then((res) => setPakets(res.data ?? []))
      .catch((err) => setError(err.message))
  }, [])

  const tertinggi = pakets?.length ? Math.max(...pakets.map((p) => p.diskon)) : null

  return (
    <section id="membership" className="w-full bg-ink text-cream py-16 border-y-4 border-court-green">
      <div className="max-w-content mx-auto px-6">
        <div className="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-white/15 gap-4">
          <div>
            <div className="flex items-center gap-2 mb-1">
              <span className="w-2.5 h-2.5 rounded-full bg-whistle-red animate-pulse" />
              <span className="text-xs font-bold tracking-widest text-court-green">KEANGGOTAAN PEMAIN</span>
            </div>
            <h2 className="font-display text-4xl uppercase tracking-wide">Membership Booking Lapang</h2>
          </div>
          <div className="flex items-center gap-6">
            <div className="text-right">
              <div className="font-display text-3xl leading-tight text-[#7cd9a4]">
                {tertinggi != null ? `${formatPersen(tertinggi)}%` : '-'}
              </div>
              <div className="text-[11px] text-cream/60">DISKON TERTINGGI</div>
            </div>
            <div className="h-10 w-px bg-white/20 hidden sm:block" />
            <div className="text-right hidden sm:block">
              <div className="font-display text-3xl leading-tight">{pakets ? pakets.length : '-'}</div>
              <div className="text-[11px] text-cream/60">PAKET TERSEDIA</div>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-5 mt-8">
          {pakets === null && !error &&
            [0, 1, 2].map((i) => (
              <div key={i} className="bg-white/5 border border-white/10 rounded-lg p-4 h-[148px] animate-pulse" />
            ))}

          {error && (
            <p className="md:col-span-3 text-[13px] text-cream/60 bg-white/5 border border-white/10 rounded-lg p-4">
              Paket membership belum bisa dimuat. {error}
            </p>
          )}

          {pakets?.length === 0 && (
            <p className="md:col-span-3 text-[13px] text-cream/60 bg-white/5 border border-white/10 rounded-lg p-4">
              Belum ada paket membership yang tersedia.
            </p>
          )}

          {pakets?.map((paket) => (
            <div
              key={paket.id}
              className="bg-white/5 border border-white/10 rounded-lg p-4 hover:border-court-green transition-colors"
            >
              <div className="flex items-center justify-between mb-2">
                <span className="bg-match-blue text-cream text-[11px] font-bold px-2 py-0.5 rounded uppercase">
                  {paket.nama}
                </span>
                <span className="text-[11px] font-bold text-court-green">30 HARI</span>
              </div>
              <h4 className="font-display text-lg uppercase mb-1">Diskon {formatPersen(paket.diskon)}% tiap booking</h4>
              <p className="text-[13px] text-cream/60 mb-3">Berlaku di semua lapangan, terpotong otomatis</p>
              <div className="flex items-center justify-between pt-2 border-t border-white/10">
                <span className="text-[12px] font-bold text-cream/70">{formatRupiah(paket.harga_bulanan)} / bulan</span>
                <a
                  href="#/membership"
                  className="bg-court-green hover:bg-court-green-dark text-cream text-[11px] font-bold px-3 py-1 rounded uppercase transition-colors"
                >
                  Pilih
                </a>
              </div>
            </div>
          ))}
        </div>

        <div className="mt-8 bg-white/5 border border-dashed border-white/20 p-4 rounded-lg flex flex-col sm:flex-row items-center justify-between gap-4">
          <div>
            <div className="font-bold text-sm uppercase">Sering booking tiap minggu?</div>
            <div className="text-[13px] text-cream/60">
              Bandingkan paketnya dan hitung berapa yang bisa kamu hemat dalam sebulan.
            </div>
          </div>
          <a
            href="#/membership"
            className="bg-cream text-ink font-bold text-[13px] px-5 py-2.5 rounded uppercase whitespace-nowrap hover:bg-white transition-colors"
          >
            Lihat Semua Paket
          </a>
        </div>
      </div>
    </section>
  )
}