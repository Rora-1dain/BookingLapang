import { useState } from 'react'
import AuthModal from './AuthModal'
import { formatRingkas } from '../lib/format'

// Komponen kecil yang dipakai bersama oleh halaman Membership, Dashboard Admin,
// dan Dashboard Pemilik, supaya tampilannya konsisten dengan sisa aplikasi
// (cream, kartu putih bergaris, bayangan tactile, judul Bebas Neue).

export function PageShell({ children }) {
  return (
    <main className="w-full max-w-content mx-auto px-6 py-8 min-h-[calc(100vh-4rem)]">{children}</main>
  )
}

export function PageHeader({ eyebrow, eyebrowTone = 'ink', title, subtitle, actions }) {
  const tone = { ink: 'bg-ink text-cream', red: 'bg-whistle-red text-cream', blue: 'bg-match-blue text-cream' }
  return (
    <div className="flex flex-col lg:flex-row lg:items-end justify-between gap-5 pb-6 mb-8 border-b border-match-blue/15">
      <div className="max-w-2xl">
        {eyebrow && (
          <span className={`inline-block ${tone[eyebrowTone]} text-xs font-bold px-2 py-0.5 rounded mb-2 tracking-wide`}>
            {eyebrow}
          </span>
        )}
        <h1 className="font-display text-4xl lg:text-5xl uppercase text-ink leading-tight tracking-wide">{title}</h1>
        {subtitle && <p className="text-muted mt-1.5">{subtitle}</p>}
      </div>
      {actions && <div className="flex flex-wrap items-center gap-3">{actions}</div>}
    </div>
  )
}

export function Panel({ title, action, children, className = '' }) {
  return (
    <section className={`bg-white border border-match-blue/15 rounded-lg ${className}`}>
      {(title || action) && (
        <div className="flex items-center justify-between px-5 pt-4 pb-3 border-b border-black/5">
          <h2 className="font-display text-xl uppercase tracking-wide text-ink">{title}</h2>
          {action}
        </div>
      )}
      <div className="p-5">{children}</div>
    </section>
  )
}

export function Stat({ label, value, hint }) {
  return (
    <div className="bg-white border-2 border-ink rounded-lg shadow-tactile-sm p-4">
      <div className="text-[11px] font-bold tracking-widest text-muted uppercase">{label}</div>
      <div className="font-display text-4xl leading-none text-ink mt-2 tabular-nums">{value}</div>
      {hint && <div className="text-[12px] text-muted mt-2">{hint}</div>}
    </div>
  )
}

const PILL = {
  neutral: 'bg-cream text-ink border border-black/20',
  green: 'bg-court-green text-cream',
  red: 'bg-whistle-red text-cream',
  blue: 'bg-match-blue text-cream',
  ink: 'bg-ink text-cream',
}

export function Pill({ tone = 'neutral', children }) {
  return (
    <span className={`inline-block text-[11px] font-bold px-2 py-0.5 rounded uppercase whitespace-nowrap ${PILL[tone]}`}>
      {children}
    </span>
  )
}

// Grafik batang sederhana. data: [{ label, value }]. Batang terakhir (bulan ini) berwarna hijau.
export function BarChart({ data, format = formatRingkas, height = 180 }) {
  const max = Math.max(...data.map((d) => d.value), 1)
  const area = height - 26 // sisakan ruang untuk angka di atas batang
  return (
    <div>
      <div className="flex items-end gap-3" style={{ height }}>
        {data.map((d, i) => (
          <div key={d.label + i} className="flex-1 flex flex-col justify-end items-center gap-1.5 h-full">
            <span className="text-[11px] font-bold text-ink tabular-nums">{format(d.value)}</span>
            <div
              className={`w-full rounded-t ${i === data.length - 1 ? 'bg-court-green' : 'bg-match-blue'}`}
              style={{ height: Math.max((d.value / max) * area, 3) }}
            />
          </div>
        ))}
      </div>
      <div className="flex gap-3 mt-2 border-t border-black/10 pt-1.5">
        {data.map((d, i) => (
          <div key={d.label + i} className="flex-1 text-center text-[11px] font-bold text-muted uppercase">
            {d.label}
          </div>
        ))}
      </div>
    </div>
  )
}

export function Skeleton({ className = '' }) {
  return <div className={`animate-pulse bg-black/10 rounded ${className}`} />
}

// Halaman yang butuh login / peran tertentu.
export function Gate({ title, children, showLogin = false }) {
  const [showAuth, setShowAuth] = useState(false)
  return (
    <PageShell>
      <div className="max-w-md mx-auto mt-12 bg-white border-2 border-ink rounded-lg shadow-tactile p-8 text-center">
        <h1 className="font-display text-3xl uppercase text-ink">{title}</h1>
        <p className="text-sm text-muted mt-2">{children}</p>
        <div className="flex items-center justify-center gap-3 mt-5">
          {showLogin && (
            <button
              onClick={() => setShowAuth(true)}
              className="bg-match-blue hover:bg-match-blue-dark text-cream font-bold text-sm uppercase px-5 py-2.5 rounded-lg shadow-tactile-sm transition-colors"
            >
              Masuk
            </button>
          )}
          <a
            href="#"
            className="border-2 border-ink text-ink hover:bg-cream-dim font-bold text-sm uppercase px-5 py-2 rounded-lg transition-colors"
          >
            Ke Beranda
          </a>
        </div>
      </div>
      {showAuth && <AuthModal onClose={() => setShowAuth(false)} />}
    </PageShell>
  )
}

export const STATUS_BOOKING = {
  paid: { tone: 'green', label: 'Dibayar' },
  confirmed: { tone: 'green', label: 'Dikonfirmasi' },
  completed: { tone: 'blue', label: 'Selesai' },
  pending: { tone: 'neutral', label: 'Pending' },
  cancelled: { tone: 'red', label: 'Dibatalkan' },
}

export function BookingStatusPill({ status }) {
  const s = STATUS_BOOKING[status] || { tone: 'neutral', label: status || '-' }
  return <Pill tone={s.tone}>{s.label}</Pill>
}