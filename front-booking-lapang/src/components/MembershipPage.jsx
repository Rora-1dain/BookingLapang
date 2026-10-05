import { useCallback, useEffect, useState } from 'react'
import { useAuth } from '../context/AuthContext'
import { fetchMembershipSaya, fetchPaket } from '../api/membership'
import { mulaiChatAdmin } from '../api/chat'
import { bayarMembership } from '../lib/payMembership'
import { dengarUser } from '../lib/realtime'
import { formatPersen, formatRupiah, formatTanggal } from '../lib/format'
import useLockBodyScroll from '../lib/useLockBodyScroll'
import AuthModal from './AuthModal'
import { Panel, PageHeader, PageShell, Pill, Skeleton } from './ui'

const FAQ = [
  ['Apakah diperpanjang otomatis?', 'Tidak. Membership berlaku 30 hari. Setelah itu kamu yang memutuskan mau berlangganan lagi atau tidak.'],
  ['Bisa ganti paket di tengah jalan?', 'Bisa. Paket baru langsung berlaku dan paket lama berhenti. Sisa hari paket lama tidak dikembalikan.'],
  ['Apakah bisa digabung dengan voucher?', 'Bisa. Diskon membership dihitung dari harga setelah voucher dan terpotong otomatis saat kamu membuat booking.'],
]

export default function MembershipPage() {
  const { user } = useAuth()
  const [pakets, setPakets] = useState(null)
  const [saya, setSaya] = useState(null) // langganan aktif atau null
  const [error, setError] = useState(null)
  const [pilihan, setPilihan] = useState(null) // paket yang sedang dikonfirmasi
  const [showAuth, setShowAuth] = useState(false)
  const [sukses, setSukses] = useState(null)
  const [belanja, setBelanja] = useState(500000)
  const [chatBusy, setChatBusy] = useState(false)
  const [chatError, setChatError] = useState(null)

  const muat = useCallback(async () => {
    try {
      const res = await fetchPaket()
      setPakets(res.data ?? [])
    } catch (err) {
      setError(err.message)
    }
    if (user) {
      try {
        const res = await fetchMembershipSaya()
        setSaya(res.data ?? null)
      } catch {
        // status membership bukan hal kritikal untuk menampilkan daftar paket
      }
    } else {
      setSaya(null)
    }
  }, [user])

  useEffect(() => {
    muat()
  }, [muat])

  // Realtime: begitu webhook Midtrans mengaktifkan membership, segarkan status
  // tanpa perlu user reload (channel pribadi user.{id}).
  useEffect(() => {
    if (!user?.id) return undefined
    return dengarUser(user.id, {
      onMembershipAktif: (data) => {
        muat()
        if (data?.paket?.nama) {
          setSukses(`Pembayaran berhasil! Membership ${data.paket.nama} sudah aktif.`)
        }
      },
    })
  }, [user?.id, muat])

  function pilih(paket) {
    setSukses(null)
    if (!user) {
      setShowAuth(true)
      return
    }
    setPilihan(paket)
  }

  async function hubungiAdmin() {
    if (!user) {
      setShowAuth(true)
      return
    }
    setChatBusy(true)
    setChatError(null)
    try {
      const percakapan = await mulaiChatAdmin()
      window.location.hash = `#/chat/${percakapan.id}`
    } catch (err) {
      setChatError(err.message)
      setChatBusy(false)
    }
  }

  function labelTombol(paket) {
    if (!saya) return 'Berlangganan'
    return saya.paket.id === paket.id ? 'Perpanjang 30 hari' : `Ganti ke ${paket.nama}`
  }

  const hemat = (paket) => Math.round((belanja * paket.diskon) / 100 - paket.harga_bulanan)
  const terbaik = pakets?.length
    ? pakets.reduce((a, b) => (hemat(b) > hemat(a) ? b : a))
    : null
  const unggulanId = pakets && pakets.length >= 3 ? pakets[1].id : null

  return (
    <PageShell>
      <PageHeader
        eyebrow="MEMBERSHIP"
        title="Bayar sekali, hemat tiap booking."
        subtitle="Pilih paket 30 hari. Diskon otomatis terpotong dari total booking di semua lapangan."
      />

      {saya && (
        <div className="mb-8 bg-white border-2 border-court-green rounded-lg p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          <div>
            <div className="flex items-center gap-2 mb-1">
              <Pill tone="green">Membership aktif</Pill>
              <span className="font-display text-2xl uppercase text-ink">{saya.paket.nama}</span>
            </div>
            <p className="text-sm text-muted">
              Diskon {formatPersen(saya.paket.diskon)}% untuk setiap booking. Berakhir {formatTanggal(saya.tanggal_berakhir)}
              {' '}({saya.sisa_hari} hari lagi).
            </p>
          </div>
        </div>
      )}

      {sukses && (
        <p className="mb-6 px-4 py-3 rounded-lg bg-court-green/15 border border-court-green text-sm font-bold text-ink">
          {sukses}
        </p>
      )}

      {error && <p className="mb-6 text-whistle-red font-bold text-sm">{error}</p>}

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {pakets === null && !error && [0, 1, 2].map((i) => <Skeleton key={i} className="h-[380px]" />)}

        {pakets?.length === 0 && (
          <p className="lg:col-span-3 text-muted text-sm">Belum ada paket membership yang tersedia.</p>
        )}

        {pakets?.map((paket) => {
          const aktif = saya?.paket.id === paket.id
          const unggulan = paket.id === unggulanId
          return (
            <article
              key={paket.id}
              className={`relative bg-white rounded-lg p-6 flex flex-col ${
                unggulan ? 'border-2 border-ink shadow-tactile' : 'border-2 border-match-blue/20'
              }`}
            >
              {unggulan && (
                <span className="absolute -top-3 left-6 bg-ink text-cream text-[11px] font-bold px-2.5 py-0.5 rounded tracking-wide">
                  PALING DIPILIH
                </span>
              )}
              <div className="flex items-center justify-between">
                <h2 className="font-display text-3xl uppercase text-match-blue tracking-wide">{paket.nama}</h2>
                {aktif && <Pill tone="green">Aktif</Pill>}
              </div>

              <div className="mt-4">
                <span className="font-display text-5xl text-ink tabular-nums">{formatRupiah(paket.harga_bulanan)}</span>
                <span className="text-sm text-muted font-bold"> / 30 hari</span>
              </div>

              <ul className="mt-5 space-y-2.5 text-sm text-body flex-1">
                {[
                  `Diskon ${formatPersen(paket.diskon)}% untuk setiap booking`,
                  'Berlaku di semua lapangan',
                  'Aktif langsung setelah berlangganan',
                  'Tanpa perpanjangan otomatis',
                ].map((item) => (
                  <li key={item} className="flex items-start gap-2">
                    <span className="text-court-green font-bold">✓</span>
                    {item}
                  </li>
                ))}
              </ul>

              <button
                onClick={() => pilih(paket)}
                className={`mt-6 w-full font-bold text-sm uppercase py-3 rounded-lg transition-colors ${
                  unggulan
                    ? 'bg-match-blue hover:bg-match-blue-dark text-cream shadow-tactile-sm'
                    : 'border-2 border-ink text-ink hover:bg-cream-dim'
                }`}
              >
                {labelTombol(paket)}
              </button>
            </article>
          )
        })}
      </div>

      {pakets?.length > 0 && (
        <div className="grid grid-cols-1 lg:grid-cols-5 gap-6 mt-10">
          <Panel title="Hitung penghematan" className="lg:col-span-3">
            <label className="block">
              <span className="block text-[11px] font-bold tracking-wider mb-1 text-ink">
                PERKIRAAN TOTAL BOOKING PER BULAN (RP)
              </span>
              <input
                type="number"
                min="0"
                step="50000"
                value={belanja}
                onChange={(e) => setBelanja(Math.max(0, Number(e.target.value) || 0))}
                className="w-full sm:w-64 bg-white border border-match-blue/20 rounded-lg px-2.5 py-2 text-sm font-semibold focus:border-match-blue focus:outline-none"
              />
            </label>
            <div className="mt-4 divide-y divide-black/5">
              {pakets.map((paket) => {
                const h = hemat(paket)
                return (
                  <div key={paket.id} className="flex items-center justify-between py-2.5 text-sm">
                    <div className="flex items-center gap-2">
                      <span className="font-bold text-ink">{paket.nama}</span>
                      {terbaik?.id === paket.id && h > 0 && <Pill tone="green">Paling hemat</Pill>}
                    </div>
                    <span className={`font-bold tabular-nums ${h > 0 ? 'text-court-green-dark' : 'text-muted'}`}>
                      {h > 0 ? `Hemat ${formatRupiah(h)}` : 'Belum balik modal'}
                    </span>
                  </div>
                )
              })}
            </div>
            <p className="text-[12px] text-muted mt-3">
              Hemat = diskon dari total booking dikurangi harga paket. Angka ini hanya perkiraan.
            </p>
          </Panel>

          <Panel title="Pertanyaan umum" className="lg:col-span-2">
            <dl className="space-y-4">
              {FAQ.map(([q, a]) => (
                <div key={q}>
                  <dt className="font-bold text-sm text-ink">{q}</dt>
                  <dd className="text-[13px] text-muted mt-0.5">{a}</dd>
                </div>
              ))}
            </dl>

            <div className="mt-5 pt-4 border-t border-black/5">
              <h3 className="font-display text-lg uppercase text-ink">Ada pertanyaan lain?</h3>
              <p className="text-[13px] text-muted mt-1">
                Tim admin siap membantu soal membership, pembayaran, atau diskon booking. Mulai chat langsung dari sini.
              </p>
              {chatError && <p className="text-[12px] font-bold text-whistle-red mt-2">{chatError}</p>}
              <button
                onClick={hubungiAdmin}
                disabled={chatBusy}
                className="mt-3 w-full bg-court-green hover:bg-court-green-dark disabled:opacity-60 text-cream font-bold text-sm uppercase py-2.5 rounded-lg shadow-tactile-sm transition-colors"
              >
                {chatBusy ? 'Membuka chat...' : 'Hubungi Admin'}
              </button>
            </div>
          </Panel>
        </div>
      )}

      {pilihan && (
        <KonfirmasiModal
          paket={pilihan}
          saya={saya}
          onClose={() => setPilihan(null)}
          onSelesai={(langganan) => {
            setPilihan(null)
            setSaya(langganan)
            setSukses(
              `Membership ${langganan.paket.nama} aktif sampai ${formatTanggal(langganan.tanggal_berakhir)}.`
            )
          }}
        />
      )}
      {showAuth && <AuthModal onClose={() => setShowAuth(false)} />}
    </PageShell>
  )
}

function KonfirmasiModal({ paket, saya, onClose, onSelesai }) {
  useLockBodyScroll()
  const [mengirim, setMengirim] = useState(false)
  const [status, setStatus] = useState(null)
  const [error, setError] = useState(null)
  const perpanjang = saya?.paket.id === paket.id
  const ganti = saya && !perpanjang

  async function konfirmasi() {
    setMengirim(true)
    setError(null)
    setStatus('Menyiapkan pembayaran...')
    try {
      const hasil = await bayarMembership(paket.id, { onStatus: setStatus })
      if (hasil?.aktif && hasil?.data) {
        onSelesai(hasil.data)
      } else {
        setStatus(null)
        setError(
          `Status pembayaran: ${hasil?.transaction_status ?? 'belum selesai'}. Kalau sudah dibayar, tunggu beberapa saat lalu cek lagi di halaman ini.`
        )
        setMengirim(false)
      }
    } catch (err) {
      setError(err.message)
      setMengirim(false)
      setStatus(null)
    }
  }

  return (
    <div className="fixed inset-0 z-[100] bg-ink/60 flex justify-center px-4 py-8 overflow-y-auto overscroll-contain">
      <div className="my-auto bg-cream w-full max-w-sm rounded-lg border-2 border-ink shadow-tactile p-6">
        <div className="flex items-center justify-between mb-4">
          <h3 className="font-display text-2xl uppercase text-ink">
            {perpanjang ? 'Perpanjang' : ganti ? 'Ganti paket' : 'Berlangganan'}
          </h3>
          <button onClick={onClose} className="text-muted hover:text-ink font-bold" aria-label="Tutup">
            ✕
          </button>
        </div>

        <div className="bg-white border border-match-blue/15 rounded-lg p-4 space-y-1.5 text-sm">
          <Row k="Paket" v={paket.nama} />
          <Row k="Diskon booking" v={`${formatPersen(paket.diskon)}%`} />
          <Row k="Masa aktif" v="30 hari" />
          <div className="border-t border-black/10 pt-2 mt-2">
            <Row k="Total" v={formatRupiah(paket.harga_bulanan)} tebal />
          </div>
        </div>

        {perpanjang && (
          <p className="text-[12px] text-muted mt-3">
            Masa aktif ditambah 30 hari dari tanggal berakhir saat ini ({formatTanggal(saya.tanggal_berakhir)}).
          </p>
        )}
        {ganti && (
          <p className="text-[12px] text-muted mt-3">
            Paket {saya.paket.nama} akan berhenti sekarang dan sisa harinya tidak dikembalikan.
          </p>
        )}

        <p className="text-[12px] text-muted mt-3">
          Pembayaran diproses lewat Midtrans. Membership aktif otomatis setelah pembayaran berhasil.
        </p>

        {status && !error && <p className="text-[12px] font-bold text-match-blue mt-3">{status}</p>}
        {error && <p className="text-[12px] font-bold text-whistle-red mt-3">{error}</p>}

        <button
          onClick={konfirmasi}
          disabled={mengirim}
          className="mt-5 w-full bg-match-blue hover:bg-match-blue-dark disabled:opacity-60 text-cream font-bold text-sm uppercase py-2.5 rounded-lg shadow-tactile-sm transition-colors"
        >
          {mengirim ? 'Memproses...' : `Bayar ${formatRupiah(paket.harga_bulanan)}`}
        </button>
      </div>
    </div>
  )
}

function Row({ k, v, tebal }) {
  return (
    <div className="flex items-center justify-between">
      <span className="text-muted">{k}</span>
      <span className={tebal ? 'font-display text-2xl text-ink' : 'font-bold text-ink'}>{v}</span>
    </div>
  )
}