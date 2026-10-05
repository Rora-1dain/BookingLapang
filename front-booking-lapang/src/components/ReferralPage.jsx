import { useEffect, useState } from 'react'
import { useAuth } from '../context/AuthContext'
import { fetchReferralSaya, fetchLeaderboardReferral } from '../api/referral'
import { Gate, PageHeader, PageShell, Panel, Pill, Skeleton, Stat } from './ui'

export default function ReferralPage() {
  const { user, checking } = useAuth()
  const [dataSaya, setDataSaya] = useState(null)
  const [leaderboard, setLeaderboard] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)
  const [copied, setCopied] = useState(false)

  useEffect(() => {
    let batal = false
    setLoading(true)
    setError(null)

    const promises = [fetchLeaderboardReferral()]
    if (user) {
      promises.push(fetchReferralSaya())
    }

    Promise.all(promises)
      .then(([resLeader, resSaya]) => {
        if (batal) return
        setLeaderboard(resLeader.data ?? [])
        if (resSaya) setDataSaya(resSaya)
      })
      .catch((err) => !batal && setError(err.message))
      .finally(() => !batal && setLoading(false))

    return () => {
      batal = true
    }
  }, [user])

  if (checking) return <PageShell><Skeleton className="h-40" /></PageShell>
  if (!user) return <Gate title="Program Referral" showLogin>Masuk ke akunmu untuk mendapatkan kode dan link referral pribadimu.</Gate>

  const kode = dataSaya?.kode_referral ?? '-'
  const link = dataSaya?.link_referral || `${window.location.origin}/#/register?ref=${kode}`

  function salinLink() {
    navigator.clipboard?.writeText(link)
    setCopied(true)
    setTimeout(() => setCopied(false), 2000)
  }

  return (
    <PageShell>
      <PageHeader
        eyebrow="PROGRAM REFERRAL"
        eyebrowTone="blue"
        title="Ajak Teman & Dapatkan Hadiah"
        subtitle={`Halo, ${user.name}. Bagikan link referralmu. Dapatkan reward setiap teman yang mendaftar dan menyelesaikan booking pertamanya.`}
      />

      {error && <p className="mb-6 text-whistle-red font-bold text-sm">{error}</p>}

      <div className="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        {loading && !dataSaya ? (
          [0, 1, 2].map((i) => <Skeleton key={i} className="h-[116px]" />)
        ) : (
          <>
            <Stat
              label="Kode Referral Saya"
              value={kode}
              hint="Bagikan kode ini ke temanmu"
            />
            <Stat
              label="Teman Bergabung"
              value={dataSaya?.total_teman_daftar ?? 0}
              hint="Teman yang mendaftar memakai kodemu"
            />
            <Stat
              label="Referral Sukses"
              value={dataSaya?.total_referral_sukses ?? 0}
              hint="Teman yang sudah melakukan booking"
            />
          </>
        )}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <Panel title="Bagikan Link Referral" className="lg:col-span-1">
          <p className="text-xs text-muted mb-4">
            Salin tautan di bawah dan bagikan ke teman lewat WhatsApp, media sosial, atau pesan singkat.
          </p>

          <div className="space-y-3">
            <div>
              <span className="block text-[11px] font-bold tracking-wider mb-1 text-ink uppercase">
                Link Referral Pribadi
              </span>
              <div className="flex gap-1.5">
                <input
                  type="text"
                  readOnly
                  value={link}
                  className="w-full bg-cream-dim border border-match-blue/20 rounded-lg px-2.5 py-2 text-xs font-mono select-all focus:outline-none"
                />
                <button
                  onClick={salinLink}
                  className="bg-match-blue hover:bg-match-blue-dark text-cream font-bold text-xs uppercase px-3 py-2 rounded-lg whitespace-nowrap shadow-tactile-sm transition-colors"
                >
                  {copied ? 'Tersalin!' : 'Salin'}
                </button>
              </div>
            </div>

            <div className="pt-3 border-t border-black/5 text-xs text-muted space-y-1">
              <p>✓ Teman mendapat voucher sambutan saat mendaftar.</p>
              <p>✓ Kamu mendapat bonus poin setelah teman menyelesaikan booking pertamanya.</p>
            </div>
          </div>
        </Panel>

        <Panel title="Leaderboard Bulan Ini (Top 10)" className="lg:col-span-2">
          {leaderboard.length === 0 ? (
            <p className="text-sm text-muted text-center py-8">
              Belum ada referral bulan ini. Jadilah yang pertama di papan peringkat!
            </p>
          ) : (
            <ol className="space-y-2">
              {leaderboard.map((item, idx) => (
                <li
                  key={item.name + idx}
                  className="flex items-center justify-between p-3 rounded-lg border border-black/10 bg-white"
                >
                  <div className="flex items-center gap-3">
                    <span
                      className={`font-display text-2xl w-7 text-center ${
                        idx === 0 ? 'text-amber-500' : idx === 1 ? 'text-slate-400' : idx === 2 ? 'text-amber-700' : 'text-muted'
                      }`}
                    >
                      {idx + 1}
                    </span>
                    <span className="font-bold text-ink text-sm">{item.name}</span>
                  </div>
                  <Pill tone={idx < 3 ? 'green' : 'neutral'}>
                    {item.jumlah_referral} Teman
                  </Pill>
                </li>
              ))}
            </ol>
          )}
        </Panel>
      </div>
    </PageShell>
  )
}
