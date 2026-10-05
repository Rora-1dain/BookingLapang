import { useEffect, useState } from 'react'
import { useAuth } from '../context/AuthContext'
import { fetchPoin, redeemPoin } from '../api/poin'
import { formatRupiah, formatTanggal } from '../lib/format'
import { Gate, PageHeader, PageShell, Panel, Pill, Skeleton, Stat } from './ui'

export default function PoinPage() {
  const { user, checking } = useAuth()
  const [data, setData] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)
  const [redeemJumlah, setRedeemJumlah] = useState(100)
  const [redeeming, setRedeeming] = useState(false)
  const [voucherBaru, setVoucherBaru] = useState(null)
  const [msg, setMsg] = useState(null)

  function load() {
    if (!user) return
    setLoading(true)
    setError(null)
    fetchPoin()
      .then((res) => setData(res))
      .catch((err) => setError(err.message))
      .finally(() => setLoading(false))
  }

  useEffect(() => {
    load()
  }, [user]) // eslint-disable-line react-hooks/exhaustive-deps

  if (checking) return <PageShell><Skeleton className="h-40" /></PageShell>
  if (!user) return <Gate title="Program Poin & Loyalty" showLogin>Masuk ke akunmu untuk melihat saldo dan riwayat poin loyalty.</Gate>

  async function handleRedeem(e) {
    e.preventDefault()
    if (!redeemJumlah || redeemJumlah < 100 || redeemJumlah % 100 !== 0) {
      setError('Jumlah poin yang ditukar harus kelipatan 100.')
      return
    }
    setRedeeming(true)
    setError(null)
    setMsg(null)
    try {
      const res = await redeemPoin(Number(redeemJumlah))
      setVoucherBaru(res.voucher)
      setMsg(res.message || 'Poin berhasil ditukar jadi voucher!')
      load()
    } catch (err) {
      setError(err.message)
    } finally {
      setRedeeming(false)
    }
  }

  const saldo = data?.poin_saat_ini ?? 0
  const tier = data?.tier ?? 'Classic'
  const riwayat = data?.riwayat?.data ?? []

  return (
    <PageShell>
      <PageHeader
        eyebrow="LOYALTY"
        eyebrowTone="blue"
        title="Poin & Hadiah"
        subtitle={`Halo, ${user.name}. Kumpulkan poin dari setiap booking lapangan dan tukarkan jadi voucher diskon.`}
      />

      {error && <p className="mb-6 text-whistle-red font-bold text-sm">{error}</p>}
      {msg && <p className="mb-6 text-court-green font-bold text-sm">{msg}</p>}

      <div className="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        {loading && !data ? (
          [0, 1, 2].map((i) => <Skeleton key={i} className="h-[116px]" />)
        ) : (
          <>
            <Stat label="Saldo Poin" value={saldo} hint="100 poin = voucher Rp 10.000" />
            <Stat label="Tier Member" value={tier} hint="Tingkatkan frekuensi booking untuk naik tier" />
            <Stat
              label="Nilai Tukar"
              value={formatRupiah((saldo / 100) * 10000)}
              hint="Potensi penghematan dari saldo poinmu"
            />
          </>
        )}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <Panel title="Tukar Poin Jadi Voucher" className="lg:col-span-1">
          <p className="text-xs text-muted mb-4">
            Tukar setiap <strong>100 poin</strong> menjadi voucher diskon seharga <strong>Rp 10.000</strong> yang bisa dipakai saat checkout booking berikutnya.
          </p>

          <form onSubmit={handleRedeem} className="space-y-3">
            <div>
              <label className="block text-[11px] font-bold tracking-wider mb-1 text-ink uppercase">
                Jumlah Poin
              </label>
              <select
                value={redeemJumlah}
                onChange={(e) => setRedeemJumlah(Number(e.target.value))}
                className="w-full bg-white border border-match-blue/20 rounded-lg px-2.5 py-2 text-sm font-semibold focus:border-match-blue focus:outline-none"
              >
                {[100, 200, 300, 500, 1000].map((pts) => (
                  <option key={pts} value={pts} disabled={saldo < pts}>
                    {pts} poin → Voucher {formatRupiah((pts / 100) * 10000)} {saldo < pts ? '(Kurang)' : ''}
                  </option>
                ))}
              </select>
            </div>

            <button
              type="submit"
              disabled={redeeming || saldo < 100}
              className="w-full bg-court-green hover:bg-court-green-dark disabled:opacity-50 text-cream font-bold text-xs uppercase py-2.5 rounded-lg shadow-tactile-sm transition-colors"
            >
              {redeeming ? 'Memproses...' : 'Tukar Sekarang'}
            </button>
          </form>

          {voucherBaru && (
            <div className="mt-4 p-3 bg-cream-dim border-2 border-match-blue/30 rounded-lg">
              <span className="block text-[10px] font-bold uppercase text-muted">VOUCHER KAMU:</span>
              <p className="font-mono font-bold text-lg text-match-blue tracking-wider">{voucherBaru.kode}</p>
              <p className="text-xs text-ink font-semibold">
                Nilai: {formatRupiah(voucherBaru.nilai)} · Berlaku sampai: {voucherBaru.berlaku_sampai ? formatTanggal(voucherBaru.berlaku_sampai.slice(0, 10)) : '-'}
              </p>
            </div>
          )}
        </Panel>

        <Panel title="Riwayat Poin" className="lg:col-span-2">
          {riwayat.length === 0 ? (
            <p className="text-sm text-muted text-center py-8">
              Belum ada riwayat perolehan atau penggunaan poin.
            </p>
          ) : (
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="text-left text-[11px] font-bold tracking-wider text-muted uppercase border-b border-black/10">
                    <th className="pb-2 pr-3">Tanggal</th>
                    <th className="pb-2 pr-3">Aktivitas</th>
                    <th className="pb-2 text-right">Poin</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-black/5">
                  {riwayat.map((h) => {
                    const bertambah = (h.jumlah_poin ?? h.poin ?? 0) >= 0
                    const jumlah = Math.abs(h.jumlah_poin ?? h.poin ?? 0)
                    return (
                      <tr key={h.id}>
                        <td className="py-2.5 pr-3 text-xs text-muted whitespace-nowrap">
                          {h.created_at ? formatTanggal(h.created_at.slice(0, 10)) : '-'}
                        </td>
                        <td className="py-2.5 pr-3">
                          <p className="font-semibold text-ink">{h.keterangan || h.aktivitas || 'Transaksi loyalty'}</p>
                        </td>
                        <td className="py-2.5 text-right font-bold tabular-nums whitespace-nowrap">
                          <Pill tone={bertambah ? 'green' : 'red'}>
                            {bertambah ? `+${jumlah}` : `-${jumlah}`}
                          </Pill>
                        </td>
                      </tr>
                    )
                  })}
                </tbody>
              </table>
            </div>
          )}
        </Panel>
      </div>
    </PageShell>
  )
}
