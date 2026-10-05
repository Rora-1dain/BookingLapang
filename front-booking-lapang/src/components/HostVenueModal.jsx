import { useEffect, useState } from 'react'
import useLockBodyScroll from '../lib/useLockBodyScroll'
import { useAuth } from '../context/AuthContext'
import { useVenues } from '../context/VenueContext'
import { fetchMyLapangan, submitLapangan, ajukanVerifikasi } from '../api/pemilik'
import { formatRupiah, namaJenis } from '../lib/format'
import AuthModal from './AuthModal'

const BADGE = {
  pending: 'bg-cream text-ink border border-black/20',
  disetujui: 'bg-court-green text-cream',
  ditolak: 'bg-whistle-red text-cream',
}

export default function HostVenueModal({ onClose }) {
  useLockBodyScroll()
  const { user } = useAuth()
  const { categories } = useVenues()
  const [showAuth, setShowAuth] = useState(false)

  // 'form' -> isi data lapangan | 'verifikasi' -> upload dokumen KYC | 'done' -> sukses
  const [step, setStep] = useState('form')
  const [myLapangan, setMyLapangan] = useState([])
  const [form, setForm] = useState({
    nama_lapangan: '',
    jenis: '',
    harga_per_jam: '',
    alamat: '',
    no_wa: '',
    kota: '',
  })
  const [dokumen, setDokumen] = useState(null)
  const [error, setError] = useState(null)
  const [info, setInfo] = useState(null)
  const [submitting, setSubmitting] = useState(false)

  useEffect(() => {
    if (!user) return
    fetchMyLapangan()
      .then((res) => setMyLapangan(res.data ?? []))
      .catch(() => {})
  }, [user, step])

  function update(field) {
    return (e) => setForm((f) => ({ ...f, [field]: e.target.value }))
  }

  async function handleSubmitLapangan(e) {
    e.preventDefault()
    setSubmitting(true)
    setError(null)
    try {
      await submitLapangan({
        ...form,
        harga_per_jam: Number(form.harga_per_jam),
      })
      setStep('done')
      setInfo('Lapangan diajukan, menunggu persetujuan admin.')
    } catch (err) {
      if (err.status === 403) {
        // belum terverifikasi -> arahkan ke upload dokumen KYC dulu
        setStep('verifikasi')
        setError(err.message)
      } else {
        setError(err.message)
      }
    } finally {
      setSubmitting(false)
    }
  }

  async function handleUploadDokumen(e) {
    e.preventDefault()
    if (!dokumen) return
    if (dokumen.size > 2 * 1024 * 1024) {
      setError('Ukuran file maksimal 2MB.')
      return
    }
    setSubmitting(true)
    setError(null)
    try {
      const res = await ajukanVerifikasi(dokumen)
      setInfo(res.message)
      setStep('menunggu-verifikasi')
    } catch (err) {
      setError(err.message)
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div className="fixed inset-0 z-[100] bg-ink/60 flex justify-center px-4 py-8 overflow-y-auto overscroll-contain">
      <div className="my-auto bg-cream w-full max-w-md rounded-lg border-2 border-ink shadow-tactile p-6 relative">
        <button
          onClick={onClose}
          className="absolute top-3 right-3 text-muted hover:text-ink font-bold"
          aria-label="Tutup"
        >
          ✕
        </button>

        <h3 className="font-display text-2xl uppercase text-ink mb-1">Daftarkan Venue</h3>

        {!user && (
          <>
            <p className="text-sm text-muted mb-4">
              Login atau daftar dulu sebagai pemilik lapangan sebelum mengajukan venue.
            </p>
            <button
              onClick={() => setShowAuth(true)}
              className="bg-match-blue hover:bg-match-blue-dark text-cream font-bold text-sm uppercase py-2.5 rounded-lg w-full shadow-tactile-sm transition-colors"
            >
              Masuk / Daftar
            </button>
            {showAuth && <AuthModal onClose={() => setShowAuth(false)} />}
          </>
        )}

        {user && step === 'form' && (
          <form onSubmit={handleSubmitLapangan} className="space-y-3">
            <Field label="NAMA LAPANGAN" value={form.nama_lapangan} onChange={update('nama_lapangan')} required />
            <label className="block">
              <span className="block text-[11px] font-bold tracking-wider mb-1 text-ink">JENIS OLAHRAGA</span>
              <input
                list="jenis-options"
                value={form.jenis}
                onChange={update('jenis')}
                placeholder="mis. futsal"
                required
                className="w-full bg-white border border-match-blue/20 rounded-lg px-2.5 py-2 text-sm font-semibold focus:border-match-blue focus:outline-none"
              />
              <datalist id="jenis-options">
                {['futsal', 'badminton', 'basket', ...categories.map((c) => c.jenis)]
                  .filter((v, i, arr) => arr.indexOf(v) === i)
                  .map((j) => (
                    <option key={j} value={j}>
                      {namaJenis(j)}
                    </option>
                  ))}
              </datalist>
            </label>
            <Field
              label="HARGA / JAM (RP)"
              type="number"
              min="0"
              value={form.harga_per_jam}
              onChange={update('harga_per_jam')}
              required
            />
            <Field
              label="ALAMAT LAPANGAN (WAJIB)"
              value={form.alamat}
              onChange={update('alamat')}
              placeholder="mis. Jl. Merdeka No. 10, Bandung"
              required
            />
            <Field
              label="NOMOR WHATSAPP (WAJIB)"
              type="tel"
              value={form.no_wa}
              onChange={update('no_wa')}
              placeholder="mis. 081234567890"
              required
            />
            <Field
              label="KOTA (OPSIONAL — untuk filter)"
              value={form.kota}
              onChange={update('kota')}
              placeholder="mis. Bandung"
            />

            {error && <p className="text-[12px] font-bold text-whistle-red">{error}</p>}

            <button
              type="submit"
              disabled={submitting}
              className="w-full bg-court-green hover:bg-court-green-dark disabled:opacity-60 text-cream font-bold text-sm uppercase py-2.5 rounded-lg shadow-tactile-sm transition-colors"
            >
              {submitting ? 'Mengirim...' : 'Ajukan Lapangan'}
            </button>
          </form>
        )}

        {user && step === 'verifikasi' && (
          <form onSubmit={handleUploadDokumen} className="space-y-3">
            <p className="text-[13px] font-bold text-whistle-red">{error}</p>
            <p className="text-sm text-muted">
              Unggah dokumen identitas (KTP/NPWP) untuk verifikasi sebelum bisa mengajukan lapangan. Format
              JPG/PNG/PDF, maks 2MB.
            </p>
            <input
              type="file"
              accept=".jpg,.jpeg,.png,.pdf"
              onChange={(e) => setDokumen(e.target.files?.[0] ?? null)}
              className="w-full bg-white border border-match-blue/20 rounded-lg px-2.5 py-2 text-sm"
              required
            />
            <button
              type="submit"
              disabled={submitting}
              className="w-full bg-match-blue hover:bg-match-blue-dark disabled:opacity-60 text-cream font-bold text-sm uppercase py-2.5 rounded-lg shadow-tactile-sm transition-colors"
            >
              {submitting ? 'Mengunggah...' : 'Unggah Dokumen'}
            </button>
          </form>
        )}

        {user && step === 'menunggu-verifikasi' && (
          <p className="text-sm font-bold text-ink">{info} Setelah disetujui admin, kamu bisa balik ke sini untuk mengajukan lapangan.</p>
        )}

        {user && step === 'done' && (
          <div>
            <p className="text-sm font-bold text-court-green mb-3">{info}</p>
            <button
              onClick={() => setStep('form')}
              className="text-[13px] font-bold text-match-blue underline"
            >
              Ajukan lapangan lain
            </button>
          </div>
        )}

        {user && myLapangan.length > 0 && (
          <div className="mt-5 pt-4 border-t border-black/10">
            <span className="block text-[11px] font-bold tracking-wider text-muted mb-2">
              LAPANGAN SAYA
            </span>
            <ul className="space-y-1.5">
              {myLapangan.map((l) => (
                <li key={l.id} className="flex items-center justify-between text-sm">
                  <span className="font-semibold text-ink">
                    {l.nama_lapangan}{' '}
                    <span className="text-muted font-normal">· {formatRupiah(l.harga_per_jam)}/jam</span>
                  </span>
                  <span
                    className={`text-[10px] font-bold px-2 py-0.5 rounded uppercase ${
                      BADGE[l.status_approval] || 'bg-cream text-ink border border-black/20'
                    }`}
                  >
                    {l.status_approval}
                  </span>
                </li>
              ))}
            </ul>
          </div>
        )}
      </div>
    </div>
  )
}

function Field({ label, ...props }) {
  return (
    <label className="block">
      <span className="block text-[11px] font-bold tracking-wider mb-1 text-ink">{label}</span>
      <input
        {...props}
        className="w-full bg-white border border-match-blue/20 rounded-lg px-2.5 py-2 text-sm font-semibold focus:border-match-blue focus:outline-none"
      />
    </label>
  )
}