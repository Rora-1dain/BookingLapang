import { useEffect, useState } from 'react'
import useLockBodyScroll from '../lib/useLockBodyScroll'
import * as adminApi from '../api/admin'
import { fetchAuditLog } from '../api/audit'
import { formatRupiah, formatTanggal } from '../lib/format'

const TABS = [
  { key: 'approval', label: 'Approval Lapangan' },
  { key: 'verifikasi', label: 'Verifikasi KYC' },
  { key: 'refund', label: 'Booking & Refund' },
  { key: 'payout', label: 'Payout' },
  { key: 'laporan', label: 'Laporan' },
  { key: 'ulasan', label: 'Ulasan Dilaporkan' },
  { key: 'audit', label: 'Audit Log' },
]

export default function AdminPanel({ onClose, initialTab = 'approval' }) {
  useLockBodyScroll()
  const [tab, setTab] = useState(initialTab)

  return (
    <div className="fixed inset-0 z-[100] bg-ink/60 flex justify-center px-4 py-8 overflow-y-auto overscroll-contain">
      <div className="my-auto bg-cream w-full max-w-3xl rounded-lg border-2 border-ink shadow-tactile p-6 relative">
        <button
          onClick={onClose}
          className="absolute top-3 right-3 text-muted hover:text-ink font-bold"
          aria-label="Tutup"
        >
          ✕
        </button>

        <h3 className="font-display text-2xl uppercase text-ink mb-4">Panel Admin</h3>

        <div className="flex items-center gap-1.5 overflow-x-auto pb-3 mb-4 border-b border-black/10">
          {TABS.map((t) => (
            <button
              key={t.key}
              onClick={() => setTab(t.key)}
              className={`px-3 py-1.5 rounded-lg text-[12px] font-bold uppercase whitespace-nowrap ${
                tab === t.key ? 'bg-match-blue text-cream' : 'bg-white text-muted border border-black/10'
              }`}
            >
              {t.label}
            </button>
          ))}
        </div>

        {tab === 'approval' && <ApprovalTab />}
        {tab === 'verifikasi' && <VerifikasiTab />}
        {tab === 'refund' && <RefundTab />}
        {tab === 'payout' && <PayoutTab />}
        {tab === 'laporan' && <LaporanTab />}
        {tab === 'ulasan' && <UlasanTab />}
        {tab === 'audit' && <AuditTab />}
      </div>
    </div>
  )
}

// Kerangka umum tiap tab: fetch on mount, tampilin loading/error/empty,
// list item + satu tombol aksi per baris. Disatuin di satu file supaya nggak
// kebanyakan file kecil buat komponen yang sifatnya mirip semua.
function useTabData(fetcher) {
  const [data, setData] = useState(null)
  const [error, setError] = useState(null)
  const [busyId, setBusyId] = useState(null)
  const [msg, setMsg] = useState(null)

  function load() {
    setError(null)
    fetcher()
      .then((res) => setData(res.data ?? res))
      .catch((err) => setError(err.message))
  }

  useEffect(load, []) // eslint-disable-line react-hooks/exhaustive-deps

  return { data, error, busyId, setBusyId, msg, setMsg, reload: load }
}

function TabShell({ error, msg, loading, empty, onRetry, children }) {
  return (
    <div>
      {msg && <p className="text-[12px] font-bold text-match-blue mb-2">{msg}</p>}
      {error && (
        <div className="mb-2 flex items-center justify-between gap-3 bg-whistle-red/10 border border-whistle-red/30 rounded-lg px-3 py-2">
          <p className="text-[12px] font-bold text-whistle-red">{error}</p>
          {onRetry && (
            <button
              onClick={onRetry}
              className="text-[11px] font-bold uppercase text-whistle-red border border-whistle-red px-2.5 py-1 rounded shrink-0 hover:bg-whistle-red hover:text-cream transition-colors"
            >
              Coba lagi
            </button>
          )}
        </div>
      )}
      {loading && <p className="text-muted text-sm py-6 text-center">Memuat...</p>}
      {empty && <p className="text-muted text-sm py-6 text-center">Tidak ada data.</p>}
      {children}
    </div>
  )
}

function ApprovalTab() {
  const { data, error, busyId, setBusyId, msg, setMsg, reload } = useTabData(adminApi.fetchApprovalLapangan)
  const [komisiValues, setKomisiValues] = useState({})
  const [savingKomisiId, setSavingKomisiId] = useState(null)

  async function handle(id, action) {
    setBusyId(id)
    setMsg(null)
    try {
      if (action === 'setujui') {
        await adminApi.setujuiLapangan(id)
      } else {
        const alasan = window.prompt('Alasan penolakan?')
        if (!alasan) return
        await adminApi.tolakLapangan(id, alasan)
      }
      reload()
    } catch (err) {
      setMsg(err.message)
    } finally {
      setBusyId(null)
    }
  }

  async function handleSimpanKomisi(id) {
    const val = komisiValues[id]
    if (val === undefined || val === '') return
    setSavingKomisiId(id)
    setMsg(null)
    try {
      await adminApi.ubahKomisi(id, Number(val))
      setMsg(`Komisi lapangan #${id} berhasil diubah jadi ${val}%.`)
      reload()
    } catch (err) {
      setMsg(err.message)
    } finally {
      setSavingKomisiId(null)
    }
  }

  return (
    <TabShell error={error} msg={msg} loading={!data && !error} empty={data?.length === 0} onRetry={reload}>
      <ul className="space-y-3">
        {data?.map((l) => (
          <li key={l.id} className="bg-white border border-black/10 rounded-lg p-3 space-y-2">
            <div className="flex items-start justify-between gap-2">
              <div>
                <p className="font-bold text-ink text-sm">{l.nama_lapangan}</p>
                <p className="text-[12px] text-muted">
                  {l.jenis} · {formatRupiah(l.harga_per_jam)}/jam · pemilik: {l.pemilik?.name}
                </p>
                {l.alamat && <p className="text-[11px] text-muted">📍 {l.alamat}</p>}
                {l.no_wa && <p className="text-[11px] text-muted">WA: {l.no_wa}</p>}
              </div>
              <div className="flex gap-1.5 shrink-0">
                <button
                  onClick={() => handle(l.id, 'setujui')}
                  disabled={busyId === l.id}
                  className="bg-court-green text-cream text-xs font-bold px-2.5 py-1.5 rounded uppercase disabled:opacity-60"
                >
                  Setujui
                </button>
                <button
                  onClick={() => handle(l.id, 'tolak')}
                  disabled={busyId === l.id}
                  className="border border-whistle-red text-whistle-red text-xs font-bold px-2.5 py-1.5 rounded uppercase disabled:opacity-60"
                >
                  Tolak
                </button>
              </div>
            </div>

            <div className="pt-2 border-t border-black/5 flex items-center justify-between gap-2">
              <span className="text-[11px] text-muted">
                Komisi: <strong className="text-ink">{l.persentase_komisi ?? 10}%</strong>
              </span>
              <div className="flex items-center gap-1.5">
                <input
                  type="number"
                  min="0"
                  max="100"
                  step="0.5"
                  placeholder={String(l.persentase_komisi ?? 10)}
                  value={komisiValues[l.id] ?? ''}
                  onChange={(e) => setKomisiValues({ ...komisiValues, [l.id]: e.target.value })}
                  className="w-16 px-2 py-1 text-xs border border-black/20 rounded bg-cream-dim text-ink"
                />
                <span className="text-xs text-muted">%</span>
                <button
                  onClick={() => handleSimpanKomisi(l.id)}
                  disabled={savingKomisiId === l.id || komisiValues[l.id] === undefined || komisiValues[l.id] === ''}
                  className="bg-ink hover:bg-match-blue text-cream text-[11px] font-bold px-2 py-1 rounded uppercase disabled:opacity-50 transition-colors"
                >
                  {savingKomisiId === l.id ? '...' : 'Ubah'}
                </button>
              </div>
            </div>
          </li>
        ))}
      </ul>
    </TabShell>
  )
}

function VerifikasiTab() {
  const { data, error, busyId, setBusyId, msg, setMsg, reload } = useTabData(adminApi.fetchVerifikasiPending)

  async function handle(userId, keputusan) {
    let catatan = null
    if (keputusan === 'tolak') {
      catatan = window.prompt('Catatan penolakan (opsional)?') || null
    }
    setBusyId(userId)
    setMsg(null)
    try {
      await adminApi.tinjauVerifikasi(userId, keputusan, catatan)
      reload()
    } catch (err) {
      setMsg(err.message)
    } finally {
      setBusyId(null)
    }
  }

  return (
    <TabShell error={error} msg={msg} loading={!data && !error} empty={data?.length === 0} onRetry={reload}>
      <ul className="space-y-2">
        {data?.map((u) => (
          <li key={u.id} className="bg-white border border-black/10 rounded-lg p-3 flex items-center justify-between gap-2">
            <div>
              <p className="font-bold text-ink text-sm">{u.name}</p>
              <p className="text-[12px] text-muted">{u.email}</p>
            </div>
            <div className="flex gap-1.5 shrink-0">
              <button
                onClick={() => handle(u.id, 'setuju')}
                disabled={busyId === u.id}
                className="bg-court-green text-cream text-xs font-bold px-2.5 py-1.5 rounded uppercase disabled:opacity-60"
              >
                Setujui
              </button>
              <button
                onClick={() => handle(u.id, 'tolak')}
                disabled={busyId === u.id}
                className="border border-whistle-red text-whistle-red text-xs font-bold px-2.5 py-1.5 rounded uppercase disabled:opacity-60"
              >
                Tolak
              </button>
            </div>
          </li>
        ))}
      </ul>
    </TabShell>
  )
}

function RefundTab() {
  const { data, error, busyId, setBusyId, msg, setMsg, reload } = useTabData(() =>
    adminApi.fetchAdminBookings({ status_pembayaran: 'paid' })
  )

  async function handleRefund(id) {
    const alasan = window.prompt('Alasan refund?')
    if (!alasan) return
    setBusyId(id)
    setMsg(null)
    try {
      await adminApi.refundBooking(id, alasan)
      reload()
    } catch (err) {
      setMsg(err.message)
    } finally {
      setBusyId(null)
    }
  }

  const list = data?.data ?? data // paginator: {data: [...]} atau langsung array

  return (
    <TabShell error={error} msg={msg} loading={!data && !error} empty={list?.length === 0} onRetry={reload}>
      <p className="text-[11px] text-muted mb-2">Menampilkan booking berstatus lunas (bisa diajukan refund).</p>
      <ul className="space-y-2">
        {list?.map((b) => (
          <li key={b.id} className="bg-white border border-black/10 rounded-lg p-3 flex items-center justify-between gap-2">
            <div>
              <p className="font-bold text-ink text-sm">{b.lapangan?.nama_lapangan}</p>
              <p className="text-[12px] text-muted">
                {b.user?.name} · {b.tanggal_booking} · {formatRupiah(b.total_harga)}
              </p>
            </div>
            <button
              onClick={() => handleRefund(b.id)}
              disabled={busyId === b.id}
              className="border border-whistle-red text-whistle-red text-xs font-bold px-2.5 py-1.5 rounded uppercase shrink-0 disabled:opacity-60"
            >
              Refund
            </button>
          </li>
        ))}
      </ul>
    </TabShell>
  )
}

function PayoutTab() {
  const { data, error, busyId, setBusyId, msg, setMsg, reload } = useTabData(adminApi.fetchPayouts)
  const list = data?.data ?? data

  const [tampilForm, setTampilForm] = useState(false)
  const [pemilikId, setPemilikId] = useState('')
  const [periodeMulai, setPeriodeMulai] = useState('')
  const [periodeSelesai, setPeriodeSelesai] = useState('')
  const [submitting, setSubmitting] = useState(false)

  async function handleBuatPayout(e) {
    e.preventDefault()
    if (!pemilikId || !periodeMulai || !periodeSelesai) {
      setMsg('Harap isi pemilik ID, tanggal mulai, dan tanggal selesai.')
      return
    }
    setSubmitting(true)
    setMsg(null)
    try {
      await adminApi.createPayout({
        pemilik_id: Number(pemilikId),
        periode_mulai: periodeMulai,
        periode_selesai: periodeSelesai,
      })
      setMsg('Payout berhasil dibuat.')
      setPemilikId('')
      setPeriodeMulai('')
      setPeriodeSelesai('')
      setTampilForm(false)
      reload()
    } catch (err) {
      setMsg(err.message)
    } finally {
      setSubmitting(false)
    }
  }

  async function handleSelesai(id) {
    setBusyId(id)
    setMsg(null)
    try {
      await adminApi.selesaikanPayout(id)
      reload()
    } catch (err) {
      setMsg(err.message)
    } finally {
      setBusyId(null)
    }
  }

  return (
    <TabShell error={error} msg={msg} loading={!data && !error} empty={list?.length === 0 && !tampilForm} onRetry={reload}>
      <div className="mb-3 flex items-center justify-between">
        <p className="text-[11px] text-muted">
          Kelola pembayaran ke pemilik lapangan.
        </p>
        <button
          onClick={() => setTampilForm(!tampilForm)}
          className="bg-match-blue text-cream text-[11px] font-bold uppercase px-3 py-1.5 rounded"
        >
          {tampilForm ? 'Batal' : '+ Buat Payout Baru'}
        </button>
      </div>

      {tampilForm && (
        <form onSubmit={handleBuatPayout} className="bg-white border-2 border-ink rounded-lg p-3 mb-3 space-y-2">
          <p className="font-bold text-xs uppercase text-ink">Form Payout Baru</p>
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-2">
            <div>
              <label className="block text-[10px] font-bold uppercase text-muted mb-0.5">ID Pemilik</label>
              <input
                type="number"
                placeholder="User ID pemilik"
                value={pemilikId}
                onChange={(e) => setPemilikId(e.target.value)}
                required
                className="w-full px-2 py-1 text-xs border border-black/20 rounded bg-cream-dim"
              />
            </div>
            <div>
              <label className="block text-[10px] font-bold uppercase text-muted mb-0.5">Periode Mulai</label>
              <input
                type="date"
                value={periodeMulai}
                onChange={(e) => setPeriodeMulai(e.target.value)}
                required
                className="w-full px-2 py-1 text-xs border border-black/20 rounded bg-cream-dim"
              />
            </div>
            <div>
              <label className="block text-[10px] font-bold uppercase text-muted mb-0.5">Periode Selesai</label>
              <input
                type="date"
                value={periodeSelesai}
                onChange={(e) => setPeriodeSelesai(e.target.value)}
                required
                className="w-full px-2 py-1 text-xs border border-black/20 rounded bg-cream-dim"
              />
            </div>
          </div>
          <div className="flex justify-end pt-1">
            <button
              type="submit"
              disabled={submitting}
              className="bg-court-green text-cream text-xs font-bold px-3 py-1.5 rounded uppercase disabled:opacity-60"
            >
              {submitting ? 'Memproses...' : 'Proses Payout'}
            </button>
          </div>
        </form>
      )}

      <ul className="space-y-2">
        {list?.map((p) => (
          <li key={p.id} className="bg-white border border-black/10 rounded-lg p-3 flex items-center justify-between gap-2">
            <div>
              <p className="font-bold text-ink text-sm">{p.pemilik?.name}</p>
              <p className="text-[12px] text-muted">
                {p.periode_mulai} – {p.periode_selesai} · {formatRupiah(p.total_nominal)} · <span className="font-bold uppercase text-ink">{p.status}</span>
              </p>
            </div>
            {p.status !== 'selesai' && (
              <button
                onClick={() => handleSelesai(p.id)}
                disabled={busyId === p.id}
                className="bg-court-green text-cream text-xs font-bold px-2.5 py-1.5 rounded uppercase shrink-0 disabled:opacity-60"
              >
                Tandai Selesai
              </button>
            )}
          </li>
        ))}
      </ul>
    </TabShell>
  )
}

function LaporanTab() {
  const { data, error, reload } = useTabData(adminApi.fetchLaporanPlatform)
  const [mulai, setMulai] = useState('')
  const [selesai, setSelesai] = useState('')
  const [downloading, setDownloading] = useState(false)
  const [exportMsg, setExportMsg] = useState(null)

  async function handleExport(e) {
    e.preventDefault()
    if (!mulai || !selesai) {
      setExportMsg('Pilih rentang tanggal mulai dan selesai.')
      return
    }
    setDownloading(true)
    setExportMsg(null)
    try {
      await adminApi.exportLedger(mulai, selesai)
      setExportMsg('Export berhasil diunduh.')
    } catch (err) {
      setExportMsg(err.message)
    } finally {
      setDownloading(false)
    }
  }

  return (
    <TabShell error={error} loading={!data && !error} empty={false} onRetry={reload}>
      {data && (
        <div>
          <div className="bg-white border border-black/10 rounded-lg p-3 mb-4">
            <p className="font-bold text-xs uppercase text-ink mb-1.5">Export Ledger (.xlsx)</p>
            <form onSubmit={handleExport} className="flex flex-wrap items-end gap-2">
              <div>
                <label className="block text-[10px] font-bold uppercase text-muted mb-0.5">Mulai</label>
                <input
                  type="date"
                  value={mulai}
                  onChange={(e) => setMulai(e.target.value)}
                  required
                  className="px-2 py-1 text-xs border border-black/20 rounded bg-cream-dim"
                />
              </div>
              <div>
                <label className="block text-[10px] font-bold uppercase text-muted mb-0.5">Selesai</label>
                <input
                  type="date"
                  value={selesai}
                  onChange={(e) => setSelesai(e.target.value)}
                  required
                  className="px-2 py-1 text-xs border border-black/20 rounded bg-cream-dim"
                />
              </div>
              <button
                type="submit"
                disabled={downloading}
                className="bg-ink hover:bg-match-blue text-cream text-xs font-bold px-3 py-1.5 rounded uppercase disabled:opacity-60 transition-colors"
              >
                {downloading ? 'Mengunduh...' : '📥 Unduh Excel'}
              </button>
            </form>
            {exportMsg && <p className="text-[11px] font-bold text-match-blue mt-1.5">{exportMsg}</p>}
          </div>

          <p className="text-[12px] text-muted mb-3">
            Periode: {data.periode?.mulai} – {data.periode?.selesai}
          </p>
          <div className="grid grid-cols-2 gap-2 mb-4">
            {Object.entries(data.ringkasan ?? {}).map(([key, val]) => (
              <div key={key} className="bg-white border border-black/10 rounded-lg p-3">
                <p className="text-[11px] text-muted uppercase">{key.replaceAll('_', ' ')}</p>
                <p className="font-bold text-ink text-sm">
                  {key === 'jumlah_booking' ? String(val) : formatRupiah(val)}
                </p>
              </div>
            ))}
          </div>
          <p className="text-[11px] font-bold text-muted mb-1.5">TOP PEMILIK</p>
          <ul className="space-y-1">
            {data.top_pemilik?.map((p, i) => (
              <li key={p.id ?? i} className="text-sm flex justify-between">
                <span>{p.name}</span>
                <span className="font-bold">{formatRupiah(p.pendapatan ?? 0)}</span>
              </li>
            ))}
          </ul>
        </div>
      )}
    </TabShell>
  )
}

function UlasanTab() {
  const { data, error, reload } = useTabData(adminApi.fetchUlasanDilaporkan)

  return (
    <TabShell error={error} loading={!data && !error} empty={data?.length === 0} onRetry={reload}>
      <ul className="space-y-2">
        {data?.map((u) => (
          <li key={u.id} className="bg-white border border-black/10 rounded-lg p-3">
            <p className="text-sm text-ink">{u.komentar}</p>
            <p className="text-[11px] text-muted mt-1">oleh {u.booking?.user?.name}</p>
          </li>
        ))}
      </ul>
    </TabShell>
  )
}

function AuditTab() {
  const [data, setData] = useState([])
  const [meta, setMeta] = useState(null)
  const [daftarAksi, setDaftarAksi] = useState([])
  const [aksi, setAksi] = useState('')
  const [pelaku, setPelaku] = useState('')
  const [error, setError] = useState(null)
  const [loading, setLoading] = useState(true)

  function load(params = {}) {
    setLoading(true)
    setError(null)
    fetchAuditLog(params)
      .then((res) => {
        setData(res.data)
        setMeta(res.meta)
        if (res.daftarAksi?.length) setDaftarAksi(res.daftarAksi)
      })
      .catch((err) => setError(err.message))
      .finally(() => setLoading(false))
  }

  useEffect(() => {
    load()
  }, [])

  function handleFilter(e) {
    e.preventDefault()
    load({ aksi: aksi || undefined, pelaku: pelaku || undefined })
  }

  return (
    <TabShell error={error} loading={loading && data.length === 0} empty={!loading && data.length === 0} onRetry={() => load({ aksi, pelaku })}>
      <form onSubmit={handleFilter} className="bg-white border border-black/10 rounded-lg p-2.5 mb-3 flex flex-wrap items-end gap-2">
        <div className="flex-1 min-w-[120px]">
          <label className="block text-[10px] font-bold uppercase text-muted mb-0.5">Aksi</label>
          <select
            value={aksi}
            onChange={(e) => setAksi(e.target.value)}
            className="w-full px-2 py-1 text-xs border border-black/20 rounded bg-cream-dim"
          >
            <option value="">Semua Aksi</option>
            {daftarAksi.map((a) => (
              <option key={a} value={a}>
                {a}
              </option>
            ))}
          </select>
        </div>
        <div className="flex-1 min-w-[120px]">
          <label className="block text-[10px] font-bold uppercase text-muted mb-0.5">Pelaku</label>
          <input
            type="text"
            placeholder="Cari nama pelaku"
            value={pelaku}
            onChange={(e) => setPelaku(e.target.value)}
            className="w-full px-2 py-1 text-xs border border-black/20 rounded bg-cream-dim"
          />
        </div>
        <button
          type="submit"
          className="bg-ink hover:bg-match-blue text-cream text-xs font-bold px-3 py-1.5 rounded uppercase transition-colors"
        >
          Filter
        </button>
      </form>

      <ul className="space-y-2">
        {data.map((l) => (
          <li key={l.id} className="bg-white border border-black/10 rounded-lg p-3 text-xs space-y-1">
            <div className="flex items-center justify-between gap-2">
              <span className="font-bold text-ink uppercase tracking-wide bg-cream-dim px-2 py-0.5 rounded">
                {l.aksi}
              </span>
              <span className="text-[11px] text-muted">
                {l.dicatat_pada ? formatTanggal(l.dicatat_pada.slice(0, 10)) : '-'}
              </span>
            </div>
            <p className="text-muted">
              Pelaku: <strong className="text-ink">{l.pelaku ?? 'Sistem'}</strong>
              {l.objek_type && ` · Objek: ${l.objek_type.split('\\').pop()} #${l.objek_id}`}
            </p>
            {(l.data_sebelum || l.data_sesudah) && (
              <div className="grid grid-cols-2 gap-2 pt-1 font-mono text-[10px] bg-cream-dim p-2 rounded">
                <div>
                  <span className="text-muted block font-bold mb-0.5">SEBELUM:</span>
                  <pre className="overflow-x-auto whitespace-pre-wrap">
                    {l.data_sebelum ? JSON.stringify(l.data_sebelum, null, 2) : '-'}
                  </pre>
                </div>
                <div>
                  <span className="text-muted block font-bold mb-0.5">SESUDAH:</span>
                  <pre className="overflow-x-auto whitespace-pre-wrap">
                    {l.data_sesudah ? JSON.stringify(l.data_sesudah, null, 2) : '-'}
                  </pre>
                </div>
              </div>
            )}
          </li>
        ))}
      </ul>

      {meta && meta.total > 0 && (
        <p className="text-[11px] text-muted text-center mt-3">
          Menampilkan {data.length} dari total {meta.total} log aktivitas.
        </p>
      )}
    </TabShell>
  )
}
