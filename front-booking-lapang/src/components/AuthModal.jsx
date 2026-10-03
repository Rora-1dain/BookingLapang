import { useState } from 'react'
import { useAuth } from '../context/AuthContext'
import useLockBodyScroll from '../lib/useLockBodyScroll'

const PERAN = [
  {
    value: 'user',
    judul: 'Pemesan Lapangan',
    deskripsi: 'Cari lapangan, booking, kumpulkan poin, dan pakai membership.',
  },
  {
    value: 'pemilik_lapangan',
    judul: 'Pemilik Lapangan',
    deskripsi: 'Daftarkan arena, terima booking, dan pantau pendapatan dari dashboard.',
  },
]

// Setelah masuk/daftar: admin ke dashboard admin, pemilik ke dashboard pemilik,
// pemesan tetap di beranda.
function arahkanBerdasarkanPeran(user) {
  if (user?.role === 'admin') window.location.hash = '#/admin'
  else if (user?.role === 'pemilik_lapangan') window.location.hash = '#/pemilik'
}

export default function AuthModal({ onClose }) {
  useLockBodyScroll()
  const { login, register } = useAuth()
  const [mode, setMode] = useState('login') // 'login' | 'register'
  const [langkah, setLangkah] = useState('peran') // khusus register: 'peran' -> 'form'
  const [peran, setPeran] = useState('user')
  const [form, setForm] = useState({ name: '', email: '', password: '', password_confirmation: '' })
  const [error, setError] = useState(null)
  const [submitting, setSubmitting] = useState(false)

  function update(field) {
    return (e) => setForm((f) => ({ ...f, [field]: e.target.value }))
  }

  function gantiMode() {
    setError(null)
    setLangkah('peran')
    setMode(mode === 'login' ? 'register' : 'login')
  }

  async function handleSubmit(e) {
    e.preventDefault()
    setSubmitting(true)
    setError(null)
    try {
      const user =
        mode === 'login' ? await login(form.email, form.password) : await register({ ...form, role: peran })
      onClose()
      arahkanBerdasarkanPeran(user)
    } catch (err) {
      setError(err.message)
    } finally {
      setSubmitting(false)
    }
  }

  const pilihPeran = mode === 'register' && langkah === 'peran'
  const peranTerpilih = PERAN.find((p) => p.value === peran)

  return (
    <div className="fixed inset-0 z-[100] bg-ink/60 flex justify-center px-4 py-8 overflow-y-auto overscroll-contain">
      <div className="my-auto bg-cream w-full max-w-sm rounded-lg border-2 border-ink shadow-tactile p-6">
        <div className="flex items-center justify-between mb-4">
          <h3 className="font-display text-2xl uppercase text-ink">
            {mode === 'login' ? 'Masuk' : pilihPeran ? 'Daftar Sebagai' : 'Daftar Akun'}
          </h3>
          <button onClick={onClose} className="text-muted hover:text-ink font-bold" aria-label="Tutup">
            ✕
          </button>
        </div>

        {pilihPeran ? (
          <div>
            <div className="space-y-3" role="radiogroup" aria-label="Daftar sebagai">
              {PERAN.map((p) => {
                const aktif = peran === p.value
                return (
                  <button
                    key={p.value}
                    type="button"
                    role="radio"
                    aria-checked={aktif}
                    onClick={() => setPeran(p.value)}
                    className={`w-full text-left rounded-lg p-4 border-2 transition-colors ${
                      aktif ? 'border-match-blue bg-white shadow-tactile-sm' : 'border-match-blue/20 bg-white/60 hover:border-match-blue/50'
                    }`}
                  >
                    <div className="flex items-center justify-between">
                      <span className="font-display text-xl uppercase text-ink">{p.judul}</span>
                      <span
                        className={`w-4 h-4 rounded-full border-2 ${
                          aktif ? 'border-match-blue bg-match-blue' : 'border-match-blue/40'
                        }`}
                      />
                    </div>
                    <p className="text-[13px] text-muted mt-1">{p.deskripsi}</p>
                  </button>
                )
              })}
            </div>
            <button
              type="button"
              onClick={() => setLangkah('form')}
              className="mt-5 w-full bg-match-blue hover:bg-match-blue-dark text-cream font-bold text-sm uppercase py-2.5 rounded-lg shadow-tactile-sm transition-colors"
            >
              Lanjut
            </button>
          </div>
        ) : (
          <form onSubmit={handleSubmit} className="space-y-3">
            {mode === 'register' && (
              <div className="flex items-center justify-between bg-white border border-match-blue/20 rounded-lg px-3 py-2">
                <div>
                  <div className="text-[11px] font-bold tracking-wider text-muted">DAFTAR SEBAGAI</div>
                  <div className="text-sm font-bold text-ink">{peranTerpilih.judul}</div>
                </div>
                <button
                  type="button"
                  onClick={() => setLangkah('peran')}
                  className="text-[12px] font-bold text-match-blue underline"
                >
                  Ganti
                </button>
              </div>
            )}

            {mode === 'register' && (
              <Field label="NAMA" type="text" value={form.name} onChange={update('name')} required />
            )}
            <Field label="EMAIL" type="email" value={form.email} onChange={update('email')} required />
            <Field
              label="PASSWORD"
              type="password"
              value={form.password}
              onChange={update('password')}
              required
            />
            {mode === 'register' && (
              <Field
                label="KONFIRMASI PASSWORD"
                type="password"
                value={form.password_confirmation}
                onChange={update('password_confirmation')}
                required
              />
            )}

            {error && <p className="text-[12px] font-bold text-whistle-red">{error}</p>}

            <button
              type="submit"
              disabled={submitting}
              className="w-full bg-match-blue hover:bg-match-blue-dark disabled:opacity-60 text-cream font-bold text-sm uppercase py-2.5 rounded-lg shadow-tactile-sm transition-colors"
            >
              {submitting ? 'Memproses...' : mode === 'login' ? 'Masuk' : 'Daftar'}
            </button>
          </form>
        )}

        <button onClick={gantiMode} className="mt-4 text-[13px] font-bold text-match-blue underline block mx-auto">
          {mode === 'login' ? 'Belum punya akun? Daftar' : 'Sudah punya akun? Masuk'}
        </button>
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