import { useCallback, useEffect, useMemo, useRef, useState } from 'react'
import { useAuth } from '../context/AuthContext'
import {
  fetchPercakapan,
  fetchDetailPercakapan,
  kirimPesan,
  tandaiDibaca,
} from '../api/chat'
import { namaJenis } from '../lib/format'
import IconChat from './IconChat'
import { dengarPercakapan, ikutiStatus } from '../lib/realtime'

const MAX_PESAN = 1000
// Saat realtime tersambung, polling cuma jadi jaring pengaman (lambat).
// Saat realtime mati / tidak dikonfigurasi, polling jadi mekanisme utama.
const POLL_LIST_MS = 10000
const POLL_CHAT_MS = 4000
const POLL_LIST_RT_MS = 30000
const POLL_CHAT_RT_MS = 20000

const QUICK_REPLIES = [
  'Apakah lapangan masih tersedia di jam tersebut?',
  'Kami mungkin telat 10 menit, boleh?',
  'Apakah tersedia sewa bola / rompi?',
  'Bisa minta info parkir dan fasilitas?',
]

// ---------- helper ----------
function inisial(nama) {
  if (!nama) return '?'
  return nama
    .split(' ')
    .map((p) => p[0])
    .slice(0, 2)
    .join('')
    .toUpperCase()
}

function jam(iso) {
  if (!iso) return ''
  return new Date(iso).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
}

function labelHari(iso) {
  const d = new Date(iso)
  const hariIni = new Date()
  const kemarin = new Date()
  kemarin.setDate(hariIni.getDate() - 1)
  if (d.toDateString() === hariIni.toDateString()) return 'Hari ini'
  if (d.toDateString() === kemarin.toDateString()) return 'Kemarin'
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
}

// Waktu singkat untuk daftar percakapan: jam kalau hari ini, selain itu tanggal.
function waktuSingkat(iso) {
  if (!iso) return ''
  const d = new Date(iso)
  if (d.toDateString() === new Date().toDateString()) return jam(iso)
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
}

// Polling yang berhenti saat tab tidak aktif.
function usePolling(fn, ms, aktif = true) {
  const ref = useRef(fn)
  ref.current = fn
  useEffect(() => {
    if (!aktif) return undefined
    const t = setInterval(() => {
      if (!document.hidden) ref.current()
    }, ms)
    return () => clearInterval(t)
  }, [ms, aktif])
}

function pindahKe(id) {
  window.location.hash = id ? `#/chat/${id}` : '#/chat'
}

// ---------- komponen ----------
export default function ChatPage({ activeId }) {
  const { user, checking } = useAuth()

  const [list, setList] = useState([])
  const [listLoading, setListLoading] = useState(true)
  const [listError, setListError] = useState(null)
  const [cari, setCari] = useState('')
  const [filter, setFilter] = useState('semua') // semua | belum

  const [detail, setDetail] = useState(null)
  const [detailLoading, setDetailLoading] = useState(false)
  const [detailError, setDetailError] = useState(null)

  const [teks, setTeks] = useState('')
  const [mengirim, setMengirim] = useState(false)
  const [kirimError, setKirimError] = useState(null)

  const [rt, setRt] = useState('off') // status koneksi realtime

  const scrollRef = useRef(null)
  const dekatBawah = useRef(true)
  const idAktifRef = useRef(activeId)
  idAktifRef.current = activeId

  // ----- daftar percakapan -----
  const muatList = useCallback(async () => {
    try {
      const data = await fetchPercakapan()
      setList(data)
      setListError(null)
      window.dispatchEvent(new Event('chat-updated'))
    } catch (err) {
      setListError(err.message)
    } finally {
      setListLoading(false)
    }
  }, [])

  // ----- detail percakapan aktif -----
  const muatDetail = useCallback(
    async (id) => {
      try {
        const data = await fetchDetailPercakapan(id)
        if (idAktifRef.current !== id) return // user sudah pindah percakapan
        // gabungkan: pesan dari realtime yang lebih baru dari hasil fetch ini jangan hilang
        setDetail((prev) => {
          if (!prev || prev.id !== data.id) return data
          const adaId = new Set(data.pesans.map((p) => p.id))
          const maxId = data.pesans.length ? data.pesans[data.pesans.length - 1].id : 0
          const ekstra = prev.pesans.filter((p) => !adaId.has(p.id) && p.id > maxId)
          return ekstra.length ? { ...data, pesans: [...data.pesans, ...ekstra] } : data
        })
        setDetailError(null)
        const adaBelumDibaca = data.pesans?.some(
          (p) => p.pengirim_id !== user?.id && !p.dibaca_pada
        )
        if (adaBelumDibaca) {
          tandaiDibaca(id)
            .then(muatList)
            .catch(() => {})
        }
      } catch (err) {
        if (idAktifRef.current === id) setDetailError(err.message)
      } finally {
        if (idAktifRef.current === id) setDetailLoading(false)
      }
    },
    [user?.id, muatList]
  )

  useEffect(() => {
    if (!user) return
    muatList()
  }, [user, muatList])

  useEffect(() => {
    setDetail(null)
    setDetailError(null)
    setKirimError(null)
    dekatBawah.current = true
    if (activeId && user) {
      setDetailLoading(true)
      muatDetail(activeId)
    } else {
      setDetailLoading(false)
    }
  }, [activeId, user, muatDetail])

  const rtOn = rt === 'connected'
  usePolling(muatList, rtOn ? POLL_LIST_RT_MS : POLL_LIST_MS, !!user)
  usePolling(
    () => activeId && muatDetail(activeId),
    rtOn ? POLL_CHAT_RT_MS : POLL_CHAT_MS,
    !!user && !!activeId
  )

  // ----- realtime (Pusher) -----
  useEffect(() => {
    if (!user) return undefined
    return ikutiStatus(setRt)
  }, [user])

  // handler selalu terbaru lewat ref, jadi langganan channel tidak diulang tiap render
  const handlerRealtime = useRef({})
  handlerRealtime.current = {
    onPesan(data) {
      if (data.percakapan_id !== idAktifRef.current) {
        muatList() // percakapan lain: cukup perbarui daftar + badge
        return
      }
      setDetail((d) => {
        if (!d || d.id !== data.percakapan_id || d.pesans.some((p) => p.id === data.id)) return d
        return { ...d, pesans: [...d.pesans, { ...data, pengirim: null }] }
      })
      if (data.pengirim_id !== user.id && !document.hidden) {
        tandaiDibaca(data.percakapan_id)
          .catch(() => {})
          .finally(muatList)
      } else {
        muatList()
      }
    },
    onDibaca(data) {
      if (data.pembaca_id === user.id) return
      setDetail((d) =>
        d && d.id === data.percakapan_id
          ? {
              ...d,
              pesans: d.pesans.map((p) =>
                p.pengirim_id === user.id && !p.dibaca_pada ? { ...p, dibaca_pada: data.dibaca_pada } : p
              ),
            }
          : d
      )
      muatList()
    },
  }

  const idsKey = list.map((p) => p.id).join(',')
  useEffect(() => {
    if (!user || !idsKey) return undefined
    const lepasSemua = idsKey.split(',').map((id) =>
      dengarPercakapan(Number(id), {
        onPesan: (d) => handlerRealtime.current.onPesan(d),
        onDibaca: (d) => handlerRealtime.current.onDibaca(d),
      })
    )
    return () => lepasSemua.forEach((lepas) => lepas())
  }, [user, idsKey])

  // tab kembali aktif: segarkan chat yang terbuka (pesan yang masuk saat tab di background)
  useEffect(() => {
    function onVisible() {
      if (!document.hidden && idAktifRef.current) muatDetail(idAktifRef.current)
    }
    document.addEventListener('visibilitychange', onVisible)
    return () => document.removeEventListener('visibilitychange', onVisible)
  }, [muatDetail])

  // ----- auto-scroll ke pesan terbaru (hanya kalau user memang sedang di bawah) -----
  const jumlahPesan = detail?.pesans?.length ?? 0
  useEffect(() => {
    const el = scrollRef.current
    if (el && dekatBawah.current) el.scrollTop = el.scrollHeight
  }, [jumlahPesan, activeId, detailLoading])

  function onScrollPesan(e) {
    const el = e.currentTarget
    dekatBawah.current = el.scrollHeight - el.scrollTop - el.clientHeight < 80
  }

  // ----- kirim pesan -----
  async function handleKirim(e) {
    e?.preventDefault()
    const isi = teks.trim()
    if (!isi || mengirim || !activeId) return
    setMengirim(true)
    setKirimError(null)
    try {
      const pesan = await kirimPesan(activeId, isi)
      setTeks('')
      dekatBawah.current = true
      setDetail((d) =>
        d ? { ...d, pesans: [...d.pesans, { ...pesan, pengirim_id: user.id }] } : d
      )
      muatList()
    } catch (err) {
      setKirimError(err.message)
    } finally {
      setMengirim(false)
    }
  }

  function onKeyDownInput(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault()
      handleKirim()
    }
  }

  // ----- turunan data -----
  const totalBelumDibaca = list.reduce((n, p) => n + (p.belum_dibaca || 0), 0)

  const listTampil = useMemo(() => {
    const q = cari.trim().toLowerCase()
    return list.filter((p) => {
      if (filter === 'belum' && !p.belum_dibaca) return false
      if (!q) return true
      return [p.lapangan, p.lawan_bicara, p.pesan_terakhir, `#${p.id}`]
        .filter(Boolean)
        .some((v) => v.toLowerCase().includes(q))
    })
  }, [list, cari, filter])

  // pesan dikelompokkan per hari untuk pembatas tanggal
  const pesanBerkelompok = useMemo(() => {
    const hasil = []
    let hariTerakhir = null
    for (const p of detail?.pesans ?? []) {
      const hari = new Date(p.created_at).toDateString()
      if (hari !== hariTerakhir) {
        hasil.push({ tipe: 'hari', key: `h-${hari}`, label: labelHari(p.created_at) })
        hariTerakhir = hari
      }
      hasil.push({ tipe: 'pesan', key: `p-${p.id}`, pesan: p })
    }
    return hasil
  }, [detail])

  const idPesanSayaTerakhir = useMemo(() => {
    const milikku = (detail?.pesans ?? []).filter((p) => p.pengirim_id === user?.id)
    return milikku.length ? milikku[milikku.length - 1].id : null
  }, [detail, user?.id])

  // ----- guard login -----
  if (checking) {
    return <PageShell><p className="text-muted text-sm py-16 text-center">Memuat...</p></PageShell>
  }
  if (!user) {
    return (
      <PageShell>
        <div className="bg-white border-2 border-ink rounded-lg shadow-tactile p-8 text-center max-w-md mx-auto">
          <h2 className="font-display text-3xl uppercase text-ink">Chat dengan Pemilik Lapangan</h2>
          <p className="text-sm text-muted mt-2">
            Masuk dulu untuk melihat dan mengirim pesan. Gunakan tombol MASUK di pojok kanan atas.
          </p>
        </div>
      </PageShell>
    )
  }

  const labelRt = rt === 'connected' ? 'Realtime aktif' : rt === 'off' ? null : 'Menyambung...'
  const ringkasAktif = list.find((p) => p.id === activeId)
  const info = detail ?? ringkasAktif

  return (
    <PageShell>
      <div className="grid grid-cols-12 gap-5 items-start">
        {/* ============ KIRI: DAFTAR PERCAKAPAN ============ */}
        <aside
          className={`col-span-12 lg:col-span-4 bg-white border border-match-blue/15 rounded-lg h-[78vh] min-h-[520px] overflow-hidden flex-col ${
            activeId ? 'hidden lg:flex' : 'flex'
          }`}
        >
          <div className="p-4 border-b border-black/10 bg-cream-dim/50">
            <div className="flex items-center justify-between mb-3">
              <h2 className="font-display text-2xl uppercase text-match-blue tracking-wide">Pesan</h2>
              {totalBelumDibaca > 0 && (
                <span className="px-2 py-0.5 rounded bg-court-green text-cream text-[11px] font-bold">
                  {totalBelumDibaca} BARU
                </span>
              )}
            </div>
            <input
              value={cari}
              onChange={(e) => setCari(e.target.value)}
              placeholder="Cari lapangan, nama, atau pesan..."
              className="w-full h-10 px-3 bg-white border border-match-blue/20 rounded-lg text-sm focus:outline-none focus:border-match-blue"
            />
            <div className="flex gap-1.5 p-1 mt-3 bg-cream-dim rounded-lg text-[12px] font-bold">
              {[
                ['semua', `SEMUA (${list.length})`],
                ['belum', `BELUM DIBACA (${list.filter((p) => p.belum_dibaca).length})`],
              ].map(([k, label]) => (
                <button
                  key={k}
                  onClick={() => setFilter(k)}
                  className={`flex-1 py-1 rounded ${
                    filter === k ? 'bg-match-blue text-cream' : 'text-muted hover:text-ink'
                  }`}
                >
                  {label}
                </button>
              ))}
            </div>
          </div>

          <div className="flex-1 overflow-y-auto divide-y divide-black/5">
            {listLoading && <p className="text-muted text-sm p-6 text-center">Memuat percakapan...</p>}
            {listError && <p className="text-whistle-red font-bold text-sm p-6 text-center">{listError}</p>}
            {!listLoading && !listError && listTampil.length === 0 && (
              <p className="text-muted text-sm p-6 text-center">
                {list.length === 0
                  ? 'Belum ada percakapan. Mulai dari ikon chat di menu Booking Saya.'
                  : 'Tidak ada percakapan yang cocok.'}
              </p>
            )}
            {listTampil.map((p) => {
              const aktif = p.id === activeId
              return (
                <button
                  key={p.id}
                  onClick={() => pindahKe(p.id)}
                  className={`w-full text-left p-3.5 flex gap-3 transition-colors ${
                    aktif
                      ? 'bg-cream-dim/60 border-l-4 border-match-blue'
                      : 'hover:bg-cream-dim/40 border-l-4 border-transparent'
                  }`}
                >
                  <div className="w-10 h-10 rounded-lg bg-match-blue text-cream font-display text-lg flex items-center justify-center shrink-0">
                    {inisial(p.lapangan)}
                  </div>
                  <div className="flex-1 min-w-0">
                    <div className="flex items-center justify-between gap-2">
                      <p className={`truncate text-sm ${p.belum_dibaca ? 'font-extrabold' : 'font-bold'} text-ink`}>
                        {p.lapangan}
                      </p>
                      <span className="text-[11px] text-muted shrink-0">{waktuSingkat(p.waktu)}</span>
                    </div>
                    <p className="text-[12px] text-muted truncate">
                      {p.lawan_bicara} · {p.peran_lawan === 'pemilik' ? 'Pemilik' : 'Pemesan'}
                    </p>
                    <div className="flex items-center justify-between gap-2 mt-1">
                      <p className="text-[12px] text-ink/70 truncate">
                        {p.pesan_terakhir || 'Belum ada pesan'}
                      </p>
                      {p.belum_dibaca > 0 && (
                        <span className="min-w-5 h-5 px-1 rounded-full bg-court-green text-cream text-[11px] font-bold flex items-center justify-center shrink-0">
                          {p.belum_dibaca}
                        </span>
                      )}
                    </div>
                  </div>
                </button>
              )
            })}
          </div>
        </aside>

        {/* ============ KANAN: JENDELA CHAT ============ */}
        <section
          className={`col-span-12 lg:col-span-8 bg-white border-2 border-ink rounded-lg shadow-tactile h-[78vh] min-h-[520px] overflow-hidden flex-col ${
            activeId ? 'flex' : 'hidden lg:flex'
          }`}
        >
          {!activeId ? (
            <div className="flex-1 flex items-center justify-center text-center p-8">
              <div>
                <IconChat className="w-12 h-12 mx-auto text-match-blue/40" />
                <p className="font-display text-2xl uppercase text-ink mt-3">Pilih percakapan</p>
                <p className="text-sm text-muted mt-1">
                  Pilih salah satu di kiri untuk mulai ngobrol dengan pemilik lapangan.
                </p>
              </div>
            </div>
          ) : (
            <>
              {/* header */}
              <div className="p-4 border-b border-black/10 flex items-center gap-3">
                <button
                  onClick={() => pindahKe(null)}
                  className="lg:hidden text-match-blue font-bold text-sm shrink-0"
                  aria-label="Kembali ke daftar pesan"
                >
                  ← Kembali
                </button>
                <div className="w-11 h-11 rounded-lg bg-match-blue text-cream font-display text-xl flex items-center justify-center shrink-0">
                  {inisial(info?.lapangan)}
                </div>
                <div className="min-w-0">
                  <div className="flex items-center gap-2 flex-wrap">
                    <h1 className="font-display text-2xl uppercase text-ink leading-none truncate">
                      {info?.lapangan ?? 'Percakapan'}
                    </h1>
                    {info?.jenis && (
                      <span className="px-1.5 py-0.5 rounded bg-ink text-cream text-[10px] font-bold uppercase">
                        {namaJenis(info.jenis)}
                      </span>
                    )}
                  </div>
                  <p className="text-[12px] text-muted truncate">
                    {info?.peran_lawan === 'pemilik' ? 'Pemilik' : 'Pemesan'}: <strong>{info?.lawan_bicara}</strong>
                    {' · '}Chat #{info?.id}
                    {labelRt && (
                      <span className={rt === 'connected' ? 'text-court-green font-bold' : 'text-muted'}>
                        {' · '}● {labelRt}
                      </span>
                    )}
                  </p>
                </div>
              </div>

              {/* isi pesan */}
              <div
                ref={scrollRef}
                onScroll={onScrollPesan}
                className="flex-1 overflow-y-auto p-4 space-y-3 bg-cream/60 diagonal-stripes"
              >
                {detailLoading && !detail && (
                  <p className="text-muted text-sm text-center py-10">Memuat pesan...</p>
                )}
                {detailError && (
                  <div className="text-center py-10">
                    <p className="text-whistle-red font-bold text-sm">{detailError}</p>
                    <button onClick={() => pindahKe(null)} className="mt-2 text-match-blue underline text-sm font-bold">
                      Kembali ke daftar
                    </button>
                  </div>
                )}
                {detail && jumlahPesan === 0 && (
                  <p className="text-muted text-sm text-center py-10">
                    Belum ada pesan. Sapa {info?.lawan_bicara} untuk memulai.
                  </p>
                )}

                {pesanBerkelompok.map((item) => {
                  if (item.tipe === 'hari') {
                    return (
                      <div key={item.key} className="flex justify-center">
                        <span className="px-3 py-0.5 rounded bg-cream-dim border border-black/10 text-[11px] font-bold uppercase text-match-blue tracking-wide">
                          {item.label}
                        </span>
                      </div>
                    )
                  }
                  const p = item.pesan
                  const milikku = p.pengirim_id === user.id
                  return (
                    <div key={item.key} className={`flex flex-col ${milikku ? 'items-end' : 'items-start'}`}>
                      <span className="text-[11px] text-muted mb-0.5 px-1">
                        {milikku ? 'Kamu' : p.pengirim?.name || info?.lawan_bicara} · {jam(p.created_at)}
                      </span>
                      <div
                        className={`px-4 py-2.5 rounded-lg max-w-[85%] sm:max-w-lg text-sm leading-relaxed whitespace-pre-wrap break-words shadow-tactile-sm ${
                          milikku
                            ? 'bg-match-blue text-cream'
                            : 'bg-white text-ink border border-black/10'
                        }`}
                      >
                        {p.isi}
                      </div>
                      {milikku && p.id === idPesanSayaTerakhir && (
                        <span className={`text-[11px] font-bold mt-1 px-1 ${p.dibaca_pada ? 'text-court-green' : 'text-muted'}`}>
                          {p.dibaca_pada ? '✓✓ Dibaca' : '✓ Terkirim'}
                        </span>
                      )}
                    </div>
                  )
                })}
              </div>

              {/* input */}
              <form onSubmit={handleKirim} className="border-t border-black/10 p-3 bg-white">
                <div className="flex items-center gap-2 mb-2 overflow-x-auto pb-1">
                  <span className="text-[11px] font-bold text-muted uppercase shrink-0">Cepat:</span>
                  {QUICK_REPLIES.map((q) => (
                    <button
                      key={q}
                      type="button"
                      onClick={() => setTeks(q)}
                      className="px-2.5 py-1 rounded border border-match-blue/20 bg-cream text-match-blue text-[12px] font-bold shrink-0 hover:bg-cream-dim"
                    >
                      {q}
                    </button>
                  ))}
                </div>
                {kirimError && <p className="text-whistle-red font-bold text-[12px] mb-2">{kirimError}</p>}
                <div className="flex items-end gap-2">
                  <div className="flex-1 relative">
                    <textarea
                      value={teks}
                      onChange={(e) => setTeks(e.target.value.slice(0, MAX_PESAN))}
                      onKeyDown={onKeyDownInput}
                      rows={2}
                      placeholder={`Tulis pesan untuk ${info?.lawan_bicara ?? 'lawan bicara'}...`}
                      className="w-full resize-none px-3 py-2 pb-5 bg-white border border-match-blue/25 rounded-lg text-sm focus:outline-none focus:border-match-blue"
                    />
                    <span className="absolute right-3 bottom-1.5 text-[11px] text-muted">
                      {teks.length}/{MAX_PESAN}
                    </span>
                  </div>
                  <button
                    type="submit"
                    disabled={mengirim || !teks.trim()}
                    className="h-12 px-5 rounded-lg bg-court-green hover:bg-court-green-dark disabled:opacity-50 text-cream font-bold text-sm uppercase shadow-tactile-sm active:translate-y-px transition-all"
                  >
                    {mengirim ? '...' : 'Kirim'}
                  </button>
                </div>
                <p className="text-[11px] text-muted mt-1.5">
                  Enter untuk kirim, Shift+Enter untuk baris baru.
                </p>
              </form>
            </>
          )}
        </section>
      </div>
    </PageShell>
  )
}

function PageShell({ children }) {
  return (
    <main className="w-full max-w-content mx-auto px-6 py-6 min-h-[calc(100vh-4rem)]">
      <div className="flex items-center justify-between mb-4">
        <h1 className="font-display text-4xl uppercase text-ink tracking-wide">Live Chat</h1>
        <a href="#" className="text-match-blue font-bold text-sm hover:underline">
          ← Kembali ke beranda
        </a>
      </div>
      {children}
    </main>
  )
}