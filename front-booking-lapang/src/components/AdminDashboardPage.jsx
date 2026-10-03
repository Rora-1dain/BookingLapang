import { useEffect, useState } from 'react'
import { useAuth } from '../context/AuthContext'
import { fetchDashboardAdmin } from '../api/dashboard'
import { formatRupiah, formatTanggal, tglLokal } from '../lib/format'
import AdminPanel from './AdminPanel'
import { BarChart, BookingStatusPill, Gate, PageHeader, PageShell, Panel, Pill, Skeleton, Stat } from './ui'

const PRESET = [
  { key: 'bulan', label: 'Bulan ini' },
  { key: '30', label: '30 hari' },
  { key: '90', label: '90 hari' },
  { key: 'semua', label: 'Semua' },
]

function rentang(key) {
  const sekarang = new Date()
  if (key === 'bulan') {
    return {
      dari: tglLokal(new Date(sekarang.getFullYear(), sekarang.getMonth(), 1)),
      sampai: tglLokal(new Date(sekarang.getFullYear(), sekarang.getMonth() + 1, 0)),
    }
  }
  if (key === '30' || key === '90') {
    const dari = new Date()
    dari.setDate(dari.getDate() - Number(key))
    return { dari: tglLokal(dari), sampai: tglLokal(sekarang) }
  }
  return {} // semua
}

const ANTRIAN = [
  { key: 'lapangan_menunggu', label: 'Lapangan menunggu persetujuan', tab: 'approval' },
  { key: 'verifikasi_menunggu', label: 'Verifikasi identitas pemilik', tab: 'verifikasi' },
  { key: 'refund_diproses', label: 'Refund sedang diproses', tab: 'refund' },
  { key: 'ulasan_dilaporkan', label: 'Ulasan dilaporkan', tab: 'ulasan' },
]

export default function AdminDashboardPage() {
  const { user, checking } = useAuth()
  const [preset, setPreset] = useState('bulan')
  const [data, setData] = useState(null)
  const [error, setError] = useState(null)
  const [loading, setLoading] = useState(true)
  const [panelTab, setPanelTab] = useState(null) // null = panel tertutup

  const adalahAdmin = user?.role === 'admin'

  useEffect(() => {
    if (!adalahAdmin) return undefined
    let batal = false
    setLoading(true)
    setError(null)
    fetchDashboardAdmin(rentang(preset))
      .then((res) => !batal && setData(res))
      .catch((err) => !batal && setError(err.message))
      .finally(() => !batal && setLoading(false))
    return () => {
      batal = true
    }
  }, [preset, adalahAdmin, panelTab === null])

  if (checking) return <PageShell><Skeleton className="h-40" /></PageShell>
  if (!user) return <Gate title="Dashboard Admin" showLogin>Masuk dengan akun admin untuk melihat halaman ini.</Gate>
  if (!adalahAdmin) return <Gate title="Khusus Admin">Akunmu tidak punya akses ke dashboard admin.</Gate>

  const r = data?.ringkasan
  const persenKomisi = r && r.total_pendapatan > 0 ? ((r.total_komisi / r.total_pendapatan) * 100).toFixed(1).replace('.', ',') : null

  return (
    <PageShell>
      <PageHeader
        eyebrow="ADMIN"
        eyebrowTone="red"
        title="Dashboard Admin"
        subtitle={`Halo, ${user.name}. Ringkasan platform untuk periode yang dipilih.`}
        actions={
          <>
            <div className="flex items-center gap-1 p-1 bg-cream-dim rounded-lg">
              {PRESET.map((p) => (
                <button
                  key={p.key}
                  onClick={() => setPreset(p.key)}
                  className={`px-3 py-1.5 rounded text-[12px] font-bold uppercase ${
                    preset === p.key ? 'bg-match-blue text-cream' : 'text-muted hover:text-ink'
                  }`}
                >
                  {p.label}
                </button>
              ))}
            </div>
            <button
              onClick={() => setPanelTab('approval')}
              className="bg-ink hover:bg-match-blue text-cream font-bold text-[13px] px-4 py-2.5 rounded-lg transition-colors"
            >
              PANEL ADMIN
            </button>
            <a
              href="#"
              className="border-2 border-ink text-ink hover:bg-cream-dim font-bold text-[13px] px-4 py-2 rounded-lg transition-colors"
            >
              LIHAT SEBAGAI USER
            </a>
          </>
        }
      />

      {error && <p className="mb-6 text-whistle-red font-bold text-sm">{error}</p>}

      <div className="grid grid-cols-2 lg:grid-cols-4 gap-5">
        {loading && !data
          ? [0, 1, 2, 3].map((i) => <Skeleton key={i} className="h-[116px]" />)
          : r && (
              <>
                <Stat
                  label="Pendapatan (GMV)"
                  value={formatRupiah(r.total_pendapatan)}
                  hint={`${r.booking_dibayar} booking dibayar`}
                />
                <Stat
                  label="Komisi platform"
                  value={formatRupiah(r.total_komisi)}
                  hint={persenKomisi ? `${persenKomisi}% dari GMV` : 'Belum ada komisi'}
                />
                <Stat
                  label="Total booking"
                  value={r.total_booking}
                  hint={`${r.per_status.pending || 0} menunggu pembayaran`}
                />
                <Stat
                  label="Tingkat pembatalan"
                  value={`${String(r.tingkat_pembatalan).replace('.', ',')}%`}
                  hint={`${r.per_status.cancelled || 0} booking dibatalkan`}
                />
              </>
            )}
      </div>

      {data && (
        <>
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <Panel title="Pendapatan 6 bulan terakhir" className="lg:col-span-2">
              <BarChart data={data.pendapatan_bulanan.map((b) => ({ label: b.label, value: b.total }))} />
              <p className="text-[12px] text-muted mt-3">GMV booking yang sudah dibayar, tidak terpengaruh filter periode.</p>
            </Panel>

            <Panel title="Perlu tindakan">
              <ul className="divide-y divide-black/5">
                {ANTRIAN.map((a) => {
                  const jumlah = data.antrian[a.key] || 0
                  return (
                    <li key={a.key} className="flex items-center justify-between gap-3 py-2.5">
                      <div className="min-w-0">
                        <div className="text-sm font-bold text-ink truncate">{a.label}</div>
                      </div>
                      <div className="flex items-center gap-2 shrink-0">
                        <Pill tone={jumlah > 0 ? 'red' : 'neutral'}>{jumlah}</Pill>
                        <button
                          onClick={() => setPanelTab(a.tab)}
                          className="text-[11px] font-bold uppercase text-match-blue hover:underline"
                        >
                          Buka
                        </button>
                      </div>
                    </li>
                  )
                })}
              </ul>
              <p className="text-[12px] text-muted mt-3 pt-3 border-t border-black/5">
                Pengguna terdaftar: {data.pengguna.pemesan} pemesan, {data.pengguna.pemilik} pemilik lapangan.
              </p>
            </Panel>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <Panel title="Lapangan terlaris">
              {data.lapangan_favorit.length === 0 ? (
                <p className="text-sm text-muted">Belum ada booking dibayar pada periode ini.</p>
              ) : (
                <ol className="space-y-3">
                  {data.lapangan_favorit.map((l, i) => (
                    <li key={l.lapangan_id} className="flex items-center gap-3">
                      <span className="font-display text-2xl text-match-blue w-6">{i + 1}</span>
                      <div className="min-w-0 flex-1">
                        <div className="text-sm font-bold text-ink truncate">{l.nama}</div>
                        <div className="text-[12px] text-muted">
                          {l.total_booking} booking · {formatRupiah(l.pendapatan)}
                        </div>
                      </div>
                    </li>
                  ))}
                </ol>
              )}
            </Panel>

            <Panel title="Booking terbaru" className="lg:col-span-2">
              {data.booking_terbaru.length === 0 ? (
                <p className="text-sm text-muted">Belum ada booking pada periode ini.</p>
              ) : (
                <div className="overflow-x-auto">
                  <table className="w-full text-sm">
                    <thead>
                      <tr className="text-left text-[11px] font-bold tracking-wider text-muted uppercase">
                        <th className="pb-2 pr-3">Jadwal</th>
                        <th className="pb-2 pr-3">Lapangan</th>
                        <th className="pb-2 pr-3">Pemesan</th>
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
          </div>
        </>
      )}

      {panelTab && <AdminPanel initialTab={panelTab} onClose={() => setPanelTab(null)} />}
    </PageShell>
  )
}