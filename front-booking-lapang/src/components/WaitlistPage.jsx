import { useEffect, useState } from 'react'
import { useAuth } from '../context/AuthContext'
import { fetchWaitlist } from '../api/waitlist'
import { formatRupiah, formatTanggal, namaJenis } from '../lib/format'
import { Gate, PageHeader, PageShell, Panel, Pill, Skeleton } from './ui'

const STATUS_WAITLIST = {
  menunggu: { tone: 'neutral', label: 'Menunggu Slot Kosong' },
  notifikasi_terkirim: { tone: 'green', label: 'Slot Tersedia!' },
  kedaluwarsa: { tone: 'red', label: 'Kedaluwarsa' },
  dibatalkan: { tone: 'red', label: 'Dibatalkan' },
}

export default function WaitlistPage() {
  const { user, checking } = useAuth()
  const [list, setList] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  function load() {
    if (!user) return
    setLoading(true)
    setError(null)
    fetchWaitlist()
      .then((res) => setList(res))
      .catch((err) => setError(err.message))
      .finally(() => setLoading(false))
  }

  useEffect(() => {
    load()
  }, [user]) // eslint-disable-line react-hooks/exhaustive-deps

  if (checking) return <PageShell><Skeleton className="h-40" /></PageShell>
  if (!user) return <Gate title="Daftar Tunggu (Waitlist)" showLogin>Masuk ke akunmu untuk melihat status antrean jadwal lapangan yang kamu ikuti.</Gate>

  return (
    <PageShell>
      <PageHeader
        eyebrow="WAITLIST"
        eyebrowTone="blue"
        title="Daftar Tunggu Slot Lapangan"
        subtitle={`Halo, ${user.name}. Di sini kamu bisa memantau jadwal lapangan penuh yang sedang kamu antrekan.`}
        actions={
          <a
            href="#"
            className="border-2 border-ink text-ink hover:bg-cream-dim font-bold text-[13px] px-4 py-2 rounded-lg transition-colors"
          >
            CARI LAPANGAN LAIN
          </a>
        }
      />

      {error && <p className="mb-6 text-whistle-red font-bold text-sm">{error}</p>}

      <Panel title="Antrean Jadwal Saya">
        {loading && list.length === 0 ? (
          <div className="space-y-3 py-4">
            <Skeleton className="h-16" />
            <Skeleton className="h-16" />
          </div>
        ) : list.length === 0 ? (
          <div className="text-center py-10">
            <p className="text-base font-bold text-ink mb-1">Tidak ada antrean waitlist aktif</p>
            <p className="text-sm text-muted mb-4 max-w-md mx-auto">
              Saat ingin memesan jadwal yang sudah terisi penuh oleh pengguna lain, kamu bisa masuk ke daftar tunggu. Sistem akan otomatis memberi tahumu jika slot dibatalkan.
            </p>
            <a
              href="#"
              className="inline-block bg-match-blue hover:bg-match-blue-dark text-cream font-bold text-xs uppercase px-5 py-2.5 rounded-lg shadow-tactile-sm transition-colors"
            >
              Jelajahi Lapangan
            </a>
          </div>
        ) : (
          <div className="space-y-3">
            {list.map((item) => {
              const statusCfg = STATUS_WAITLIST[item.status] || STATUS_WAITLIST.menunggu
              const lap = item.lapangan
              return (
                <div
                  key={item.id}
                  className="bg-white border-2 border-ink rounded-lg p-4 flex flex-col md:flex-row md:items-center justify-between gap-3 shadow-tactile-sm"
                >
                  <div className="space-y-1">
                    <div className="flex items-center gap-2">
                      <span className="font-display text-lg uppercase text-ink">
                        {lap?.nama_lapangan || 'Lapangan'}
                      </span>
                      {lap?.jenis && (
                        <span className="bg-cream-dim text-ink text-[10px] font-bold px-2 py-0.5 rounded uppercase">
                          {namaJenis(lap.jenis)}
                        </span>
                      )}
                      <Pill tone={statusCfg.tone}>{statusCfg.label}</Pill>
                    </div>

                    <p className="text-sm font-semibold text-ink">
                      📅 {formatTanggal(item.tanggal_booking)} · ⏰ {item.jam_mulai?.slice(0, 5)} - {item.jam_selesai?.slice(0, 5)}
                    </p>

                    {(lap?.alamat || lap?.kota) && (
                      <p className="text-xs text-muted">
                        📍 {lap.alamat || lap.kota}
                      </p>
                    )}

                    {lap?.harga_per_jam && (
                      <p className="text-xs text-muted">
                        Tarif: {formatRupiah(lap.harga_per_jam)} / jam
                      </p>
                    )}
                  </div>

                  {item.status === 'notifikasi_terkirim' && lap && (
                    <div className="shrink-0">
                      <a
                        href={`#/lapangan/${lap.id}`}
                        className="inline-block bg-court-green hover:bg-court-green-dark text-cream font-bold text-xs uppercase px-4 py-2 rounded-lg shadow-tactile-sm transition-colors"
                      >
                        Pesan Sekarang
                      </a>
                    </div>
                  )}
                </div>
              )
            })}
          </div>
        )}
      </Panel>
    </PageShell>
  )
}
