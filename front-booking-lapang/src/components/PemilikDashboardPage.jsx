import { useEffect, useState } from 'react'
import { useAuth } from '../context/AuthContext'
import { fetchDashboardPemilik } from '../api/dashboard'
import { formatRupiah, formatTanggal, namaJenis } from '../lib/format'
import HostVenueModal from './HostVenueModal'
import { BarChart, BookingStatusPill, Gate, PageHeader, PageShell, Panel, Pill, Skeleton, Stat } from './ui'

const APPROVAL = {
  pending: { tone: 'neutral', label: 'Menunggu admin' },
  disetujui: { tone: 'green', label: 'Disetujui' },
  ditolak: { tone: 'red', label: 'Ditolak' },
}

export default function PemilikDashboardPage() {
  const { user, checking } = useAuth()
  const [data, setData] = useState(null)
  const [error, setError] = useState(null)
  const [forbidden, setForbidden] = useState(false)
  const [loading, setLoading] = useState(true)
  const [showHost, setShowHost] = useState(false)

  useEffect(() => {
    if (!user) return undefined
    let batal = false
    setLoading(true)
    setError(null)
    fetchDashboardPemilik()
      .then((res) => !batal && setData(res))
      .catch((err) => {
        if (batal) return
        if (err.status === 403) setForbidden(true)
        else setError(err.message)
      })
      .finally(() => !batal && setLoading(false))
    return () => {
      batal = true
    }
    // muat ulang setelah modal ajukan lapangan ditutup
  }, [user, showHost])

  if (checking) return <PageShell><Skeleton className="h-40" /></PageShell>
  if (!user) return <Gate title="Dashboard Pemilik" showLogin>Masuk dengan akun pemilik lapangan untuk melihat halaman ini.</Gate>
  if (forbidden) {
    return (
      <Gate title="Khusus Pemilik Lapangan">
        Akunmu terdaftar sebagai pemesan. Untuk mulai menyewakan lapangan, daftar ulang dengan akun pemilik lapangan.
      </Gate>
    )
  }

  const r = data?.ringkasan
  const v = data?.verifikasi
  const terverifikasi = v?.status === 'terverifikasi'

  return (
    <PageShell>
      <PageHeader
        eyebrow="PEMILIK LAPANGAN"
        eyebrowTone="blue"
        title="Dashboard Pemilik"
        subtitle={`Halo, ${user.name}. Pantau lapangan, booking, dan pendapatanmu di sini.`}
        actions={
          <>
            <button
              onClick={() => setShowHost(true)}
              className="bg-match-blue hover:bg-match-blue-dark text-cream font-bold text-[13px] px-4 py-2.5 rounded-lg shadow-tactile-sm transition-colors"
            >
              AJUKAN LAPANGAN
            </button>
            <a
              href="#/chat"
              className="border-2 border-ink text-ink hover:bg-cream-dim font-bold text-[13px] px-4 py-2 rounded-lg transition-colors"
            >
              PESAN
            </a>
          </>
        }
      />

      {error && <p className="mb-6 text-whistle-red font-bold text-sm">{error}</p>}

      {data && !terverifikasi && (
        <div className="mb-6 bg-white border-2 border-ink rounded-lg p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          <div>
            <div className="flex items-center gap-2 mb-0.5">
              <Pill tone={v.status === 'menunggu' ? 'neutral' : 'red'}>
                {v.status === 'menunggu' ? 'Verifikasi diproses' : v.status === 'ditolak' ? 'Verifikasi ditolak' : 'Belum terverifikasi'}
              </Pill>
            </div>
            <p className="text-sm text-muted">
              {v.status === 'menunggu' && 'Dokumen identitasmu sedang ditinjau admin. Setelah disetujui, kamu bisa mengajukan lapangan.'}
              {v.status === 'ditolak' && `Pengajuan ditolak${v.catatan ? `: ${v.catatan}` : ''}. Kirim ulang dokumen yang lebih jelas.`}
              {v.status !== 'menunggu' && v.status !== 'ditolak' && 'Verifikasi identitas diperlukan sebelum kamu bisa mengajukan lapangan.'}
            </p>
          </div>
          {v.status !== 'menunggu' && (
            <button
              onClick={() => setShowHost(true)}
              className="border-2 border-ink text-ink hover:bg-cream-dim font-bold text-[12px] uppercase px-4 py-2 rounded-lg whitespace-nowrap transition-colors"
            >
              Verifikasi sekarang
            </button>
          )}
        </div>
      )}

      <div className="grid grid-cols-2 lg:grid-cols-4 gap-5">
        {loading && !data
          ? [0, 1, 2, 3].map((i) => <Skeleton key={i} className="h-[116px]" />)
          : r && (
              <>
                <Stat
                  label="Lapangan aktif"
                  value={r.lapangan_aktif}
                  hint={r.lapangan_menunggu > 0 ? `${r.lapangan_menunggu} menunggu persetujuan` : `${r.total_lapangan} lapangan terdaftar`}
                />
                <Stat label="Booking bulan ini" value={r.booking_bulan_ini} hint="Tidak termasuk yang dibatalkan" />
                <Stat label="Pendapatan bulan ini" value={formatRupiah(r.pendapatan_bulan_ini)} hint="Bagianmu setelah komisi" />
                <Stat
                  label="Rating rata-rata"
                  value={r.rating_rata_rata != null ? `${String(r.rating_rata_rata).replace('.', ',')}` : '-'}
                  hint={r.rating_rata_rata != null ? 'Dari seluruh ulasan' : 'Belum ada ulasan'}
                />
              </>
            )}
      </div>

      {data && (
        <>
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <Panel title="Pendapatan 6 bulan terakhir" className="lg:col-span-2">
              <BarChart data={data.pendapatan_bulanan.map((b) => ({ label: b.label, value: b.total }))} />
              <p className="text-[12px] text-muted mt-3">Bagianmu dari booking yang sudah dibayar.</p>
            </Panel>

            <Panel title="Payout">
              <dl className="space-y-4">
                <div>
                  <dt className="text-[11px] font-bold tracking-widest text-muted uppercase">Sudah diterima</dt>
                  <dd className="font-display text-3xl text-ink tabular-nums">{formatRupiah(r.payout_diterima)}</dd>
                </div>
                <div>
                  <dt className="text-[11px] font-bold tracking-widest text-muted uppercase">Menunggu pencairan</dt>
                  <dd className="font-display text-3xl text-match-blue tabular-nums">{formatRupiah(r.payout_menunggu)}</dd>
                </div>
              </dl>
              <p className="text-[12px] text-muted mt-3 pt-3 border-t border-black/5">
                Payout dicairkan admin per periode.
              </p>
            </Panel>
          </div>

          <Panel title="Lapangan saya" className="mt-6">
            {data.lapangan.length === 0 ? (
              <div className="text-center py-6">
                <p className="text-sm text-muted">Belum ada lapangan. Ajukan lapangan pertamamu untuk mulai menerima booking.</p>
                <button
                  onClick={() => setShowHost(true)}
                  className="mt-3 bg-match-blue hover:bg-match-blue-dark text-cream font-bold text-[13px] uppercase px-5 py-2.5 rounded-lg shadow-tactile-sm transition-colors"
                >
                  Ajukan lapangan
                </button>
              </div>
            ) : (
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {data.lapangan.map((l) => {
                  const a = APPROVAL[l.status_approval] || APPROVAL.pending
                  return (
                    <div key={l.id} className="border border-match-blue/15 rounded-lg p-4">
                      <div className="flex items-center justify-between gap-2 mb-2">
                        <span className="bg-match-blue text-cream text-[11px] font-bold px-2 py-0.5 rounded uppercase">
                          {namaJenis(l.jenis)}
                        </span>
                        <Pill tone={a.tone}>{a.label}</Pill>
                      </div>
                      <h3 className="font-display text-xl uppercase text-ink leading-tight">{l.nama_lapangan}</h3>
                      <p className="text-[13px] text-muted">{l.kota || 'Kota belum diisi'}</p>
                      <div className="flex items-center justify-between mt-3 pt-3 border-t border-black/5 text-sm">
                        <span className="font-bold text-ink tabular-nums">{formatRupiah(l.harga_per_jam)} / jam</span>
                        <span className="text-muted font-bold">
                          {l.rating > 0 ? `★ ${String(l.rating).replace('.', ',')}` : 'Belum ada rating'}
                        </span>
                      </div>
                      {l.status_approval === 'disetujui' && (
                        <p className="text-[12px] mt-1.5 font-bold text-muted">
                          {l.status === 'aktif' ? 'Tampil di pencarian' : 'Nonaktif'}
                        </p>
                      )}
                    </div>
                  )
                })}
              </div>
            )}
          </Panel>

          <Panel title="Booking terbaru" className="mt-6">
            {data.booking_terbaru.length === 0 ? (
              <p className="text-sm text-muted">Belum ada booking di lapanganmu.</p>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-sm">
                  <thead>
                    <tr className="text-left text-[11px] font-bold tracking-wider text-muted uppercase">
                      <th className="pb-2 pr-3">Jadwal</th>
                      <th className="pb-2 pr-3">Lapangan</th>
                      <th className="pb-2 pr-3">Penyewa</th>
                      <th className="pb-2 pr-3 text-right">Total</th>
                      <th className="pb-2">Status</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-black/5">
                    {data.booking_terbaru.map((b) => (
                      <tr key={b.id}>
                        <td className="py-2.5 pr-3 whitespace-nowrap">
                          <div className="font-bold text-ink">{formatTanggal(b.tanggal)}</div>
                          <div className="text-[12px] text-muted">
                            {b.jam_mulai} - {b.jam_selesai}
                          </div>
                        </td>
                        <td className="py-2.5 pr-3">{b.lapangan}</td>
                        <td className="py-2.5 pr-3">{b.pemesan}</td>
                        <td className="py-2.5 pr-3 text-right font-bold tabular-nums whitespace-nowrap">
                          {formatRupiah(b.total_harga)}
                        </td>
                        <td className="py-2.5">
                          <BookingStatusPill status={b.status} />
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </Panel>
        </>
      )}

      {showHost && <HostVenueModal onClose={() => setShowHost(false)} />}
    </PageShell>
  )
}