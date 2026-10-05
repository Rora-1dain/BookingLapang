import { useEffect, useState } from 'react'
import { useAuth } from '../context/AuthContext'
import { fetchPreferensiNotifikasi, simpanPreferensiNotifikasi } from '../api/notifikasi'
import { Gate, PageHeader, PageShell, Panel, Skeleton } from './ui'

export default function PengaturanNotifikasiPage() {
  const { user, checking } = useAuth()
  const [preferensi, setPreferensi] = useState(null)
  const [loading, setLoading] = useState(true)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState(null)
  const [msg, setMsg] = useState(null)

  function load() {
    if (!user) return
    setLoading(true)
    setError(null)
    fetchPreferensiNotifikasi()
      .then((res) => setPreferensi(res.data ?? {}))
      .catch((err) => setError(err.message))
      .finally(() => setLoading(false))
  }

  useEffect(() => {
    load()
  }, [user]) // eslint-disable-line react-hooks/exhaustive-deps

  if (checking) return <PageShell><Skeleton className="h-40" /></PageShell>
  if (!user) return <Gate title="Preferensi Notifikasi" showLogin>Masuk ke akunmu untuk mengatur preferensi pengiriman notifikasi.</Gate>

  function toggle(tipe, channel) {
    setPreferensi((prev) => {
      const current = prev[tipe]
      return {
        ...prev,
        [tipe]: {
          ...current,
          [channel]: !current[channel],
        },
      }
    })
  }

  async function handleSimpan(e) {
    e.preventDefault()
    setSaving(true)
    setError(null)
    setMsg(null)
    try {
      const payload = {}
      for (const [tipe, val] of Object.entries(preferensi)) {
        payload[tipe] = {
          email: Boolean(val.email),
          database: Boolean(val.database),
        }
      }
      const res = await simpanPreferensiNotifikasi(payload)
      setMsg(res.message || 'Preferensi berhasil disimpan.')
    } catch (err) {
      setError(err.message)
    } finally {
      setSaving(false)
    }
  }

  return (
    <PageShell>
      <PageHeader
        eyebrow="PENGATURAN"
        eyebrowTone="blue"
        title="Preferensi Notifikasi"
        subtitle={`Halo, ${user.name}. Atur jenis pesan apa saja yang ingin kamu terima melalui Email dan Notifikasi Dalam Aplikasi.`}
      />

      {error && <p className="mb-6 text-whistle-red font-bold text-sm">{error}</p>}
      {msg && <p className="mb-6 text-court-green font-bold text-sm">{msg}</p>}

      <Panel title="Saluran Pengiriman Notifikasi">
        {loading && !preferensi ? (
          <div className="space-y-4 py-4">
            <Skeleton className="h-10" />
            <Skeleton className="h-10" />
            <Skeleton className="h-10" />
          </div>
        ) : (
          <form onSubmit={handleSimpan} className="space-y-6">
            <p className="text-xs text-muted">
              Pilih apakah kamu ingin menerima email dan/atau notifikasi aplikasi untuk tiap kategori berikut:
            </p>

            <div className="divide-y divide-black/10">
              <div className="grid grid-cols-12 pb-2 text-[11px] font-bold uppercase text-muted">
                <span className="col-span-8">Jenis Notifikasi</span>
                <span className="col-span-2 text-center">Email</span>
                <span className="col-span-2 text-center">In-App</span>
              </div>

              {Object.entries(preferensi || {}).map(([tipe, val]) => (
                <div key={tipe} className="grid grid-cols-12 items-center py-3">
                  <div className="col-span-8 pr-2">
                    <p className="font-bold text-sm text-ink">{val.label || tipe}</p>
                    <p className="text-[11px] text-muted">Pemberitahuan terkait {val.label?.toLowerCase() || tipe}</p>
                  </div>
                  <div className="col-span-2 flex justify-center">
                    <input
                      type="checkbox"
                      checked={Boolean(val.email)}
                      onChange={() => toggle(tipe, 'email')}
                      className="w-4 h-4 text-match-blue rounded border-black/20 focus:ring-match-blue cursor-pointer"
                    />
                  </div>
                  <div className="col-span-2 flex justify-center">
                    <input
                      type="checkbox"
                      checked={Boolean(val.database)}
                      onChange={() => toggle(tipe, 'database')}
                      className="w-4 h-4 text-match-blue rounded border-black/20 focus:ring-match-blue cursor-pointer"
                    />
                  </div>
                </div>
              ))}
            </div>

            <div className="flex justify-end pt-4 border-t border-black/10">
              <button
                type="submit"
                disabled={saving}
                className="bg-court-green hover:bg-court-green-dark disabled:opacity-60 text-cream font-bold text-xs uppercase px-6 py-2.5 rounded-lg shadow-tactile-sm transition-colors"
              >
                {saving ? 'Menyimpan...' : 'Simpan Perubahan'}
              </button>
            </div>
          </form>
        )}
      </Panel>
    </PageShell>
  )
}
