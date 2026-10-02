import { useState } from 'react'
import { useAuth } from '../context/AuthContext'
import useLockBodyScroll from '../lib/useLockBodyScroll'

export default function AuthModal({ onClose }) {
  useLockBodyScroll()
  const { login, register } = useAuth()
  const [mode, setMode] = useState('login') // 'login' | 'register'
  const [form, setForm] = useState({ name: '', email: '', password: '', password_confirmation: '' })
  const [error, setError] = useState(null)
  const [submitting, setSubmitting] = useState(false)

  function update(field) {
    return (e) => setForm((f) => ({ ...f, [field]: e.target.value }))
  }

  async function handleSubmit(e) {
    e.preventDefault()
    setSubmitting(true)
    setError(null)
    try {
      if (mode === 'login') {
        await login(form.email, form.password)
      } else {
        await register(form)
      }
      onClose()
    } catch (err) {
      setError(err.message)
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div className="fixed inset-0 z-[100] bg-ink/60 flex justify-center px-4 py-8 overflow-y-auto overscroll-contain">
      <div className="my-auto bg-cream w-full max-w-sm rounded-lg border-2 border-ink shadow-tactile p-6">
        <div className="flex items-center justify-between mb-4">
          <h3 className="font-display text-2xl uppercase text-ink">
            {mode === 'login' ? 'Masuk' : 'Daftar Akun'}
          </h3>
          <button onClick={onClose} className="text-muted hover:text-ink font-bold" aria-label="Tutup">
            ✕
          </button>
        </div>

        <form onSubmit={handleSubmit} className="space-y-3">
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

        <button
          onClick={() => {
            setError(null)
            setMode(mode === 'login' ? 'register' : 'login')
          }}
          className="mt-4 text-[13px] font-bold text-match-blue underline block mx-auto"
        >
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