import { useEffect, useState } from 'react'
import * as adminApi from '../api/admin'
import { formatRupiah } from '../lib/format'

const TABS = [
  { key: 'approval', label: 'Approval Lapangan' },
  { key: 'verifikasi', label: 'Verifikasi KYC' },
  { key: 'refund', label: 'Booking & Refund' },
  { key: 'payout', label: 'Payout' },
  { key: 'laporan', label: 'Laporan' },
  { key: 'ulasan', label: 'Ulasan Dilaporkan' },
]

export default function AdminPanel({ onClose }) {
  const [tab, setTab] = useState('approval')

  return (
    <div className="fixed inset-0 z-[100] bg-ink/60 flex items-center justify-center px-4 py-8 overflow-y-auto">
      <div className="bg-cream w-full max-w-2xl rounded-lg border-2 border-ink shadow-tactile p-6 relative">
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

function TabShell({ error, msg, loading, empty, children }) {
  return (
    <div>
      {msg && <p className="text-[12px] font-bold text-match-blue mb-2">{msg}</p>}
      {error && <p className="text-[12px] font-bold text-whistle-red mb-2">{error}</p>}
      {loading && <p className="text-muted text-sm py-6 text-center">Memuat...</p>}
      {empty && <p className="text-muted text-sm py-6 text-center">Tidak ada data.</p>}
      {children}
    </div>
  )
}

function ApprovalTab() {
  const { data, error, busyId, setBusyId, msg, setMsg, reload } = useTabData(adminApi.fetchApprovalLapangan)

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

  return (
    <TabShell error={error} msg={msg} loading={!data && !error} empty={data?.length === 0}>
      <ul className="space-y-2">
        {data?.map((l) => (
          <li key={l.id} className="bg-white border border-black/10 rounded-lg p-3 flex items-center justify-between gap-2">
            <div>
              <p className="font-bold text-ink text-sm">{l.nama_lapangan}</p>
              <p className="text-[12px] text-muted">
                {l.jenis} · {formatRupiah(l.harga_per_jam)}/jam · pemilik: {l.pemilik?.name}
              </p>
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
    <TabShell error={error} msg={msg} loading={!data && !error} empty={data?.length === 0}>
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
    <TabShell error={error} msg={msg} loading={!data && !error} empty={list?.length === 0}>
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
    <TabShell error={error} msg={msg} loading={!data && !error} empty={list?.length === 0}>
      <p className="text-[11px] text-muted mb-2">
        Payout baru dibuat lewat proses periodik — di sini cuma bisa tandai selesai.
      </p>
      <ul className="space-y-2">
        {list?.map((p) => (
          <li key={p.id} className="bg-white border border-black/10 rounded-lg p-3 flex items-center justify-between gap-2">
            <div>
              <p className="font-bold text-ink text-sm">{p.pemilik?.name}</p>
              <p className="text-[12px] text-muted">
                {p.periode_mulai} – {p.periode_selesai} · {formatRupiah(p.total_nominal)} · {p.status}
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
  const { data, error } = useTabData(adminApi.fetchLaporanPlatform)

  return (
    <TabShell error={error} loading={!data && !error} empty={false}>
      {data && (
        <div>
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
  const { data, error } = useTabData(adminApi.fetchUlasanDilaporkan)

  return (
    <TabShell error={error} loading={!data && !error} empty={data?.length === 0}>
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
