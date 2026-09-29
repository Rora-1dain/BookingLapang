import { useState } from 'react'
import { useAuth } from '../context/AuthContext'
import { useVenues } from '../context/VenueContext'
import AuthModal from './AuthModal'
import HostVenueModal from './HostVenueModal'
import MyBookingsModal from './MyBookingsModal'
import AdminPanel from './AdminPanel'

const NAV_LINKS = [
  { label: 'Futsal', jenis: 'futsal' },
  { label: 'Badminton', jenis: 'badminton' },
  { label: 'Basketball', jenis: 'basket' },
  { label: 'Community Matches', href: '#community' },
]

export default function Navbar() {
  const { filters, setFilters } = useVenues()
  const { user } = useAuth()
  const [showAuth, setShowAuth] = useState(false)
  const [showHost, setShowHost] = useState(false)
  const [showBookings, setShowBookings] = useState(false)
  const [showAdmin, setShowAdmin] = useState(false)

  function initials(name) {
    if (!name) return '?'
    return name
      .split(' ')
      .map((p) => p[0])
      .slice(0, 2)
      .join('')
      .toUpperCase()
  }

  return (
    <header className="sticky top-0 z-50 bg-cream border-b border-match-blue/10">
      <div className="h-16 w-full max-w-content mx-auto px-6 flex items-center justify-between">
        <div className="flex items-center gap-8">
          <a href="#" className="flex items-center gap-2">
            <div className="h-9 w-9 rounded-lg bg-match-blue flex items-center justify-center text-cream font-display text-lg">
              BL
            </div>
            <span className="font-display text-2xl tracking-widest text-match-blue uppercase">
              Booking Lapang
            </span>
          </a>
          <nav className="hidden md:flex items-center gap-6">
            {NAV_LINKS.map((link) => {
              const active = link.jenis && filters.jenis === link.jenis
              if (link.href) {
                return (
                  <a
                    key={link.label}
                    href={link.href}
                    className="text-muted hover:text-ink transition-colors font-bold text-sm"
                  >
                    {link.label.toUpperCase()}
                  </a>
                )
              }
              return (
                <button
                  key={link.label}
                  onClick={() => {
                    setFilters((f) => ({ ...f, jenis: active ? '' : link.jenis }))
                    document.getElementById('lapangan')?.scrollIntoView({ behavior: 'smooth' })
                  }}
                  className={
                    active
                      ? 'border-b-2 border-match-blue text-match-blue pb-1 font-bold text-sm'
                      : 'text-muted hover:text-ink transition-colors font-bold text-sm'
                  }
                >
                  {link.label.toUpperCase()}
                </button>
              )
            })}
          </nav>
        </div>

        <div className="flex items-center gap-3">
          <button
            onClick={() => setShowHost(true)}
            className="hidden sm:inline-flex items-center gap-1.5 bg-ink text-cream font-bold text-[13px] px-3.5 py-2 rounded-lg hover:bg-match-blue transition-colors"
          >
            HOST VENUE
          </button>
          {user?.role === 'admin' && (
            <button
              onClick={() => setShowAdmin(true)}
              className="hidden sm:inline-flex bg-whistle-red text-cream font-bold text-[13px] px-3.5 py-2 rounded-lg hover:opacity-90 transition-opacity"
            >
              ADMIN
            </button>
          )}
          {user ? (
            <button
              onClick={() => setShowBookings(true)}
              title={`Booking Saya (${user.name})`}
              className="w-8 h-8 rounded bg-match-blue text-cream flex items-center justify-center font-bold text-sm"
            >
              {initials(user.name)}
            </button>
          ) : (
            <button
              onClick={() => setShowAuth(true)}
              className="border-2 border-ink text-ink hover:bg-ink hover:text-cream font-bold text-[13px] px-3.5 py-1.5 rounded-lg transition-colors"
            >
              MASUK
            </button>
          )}
        </div>
      </div>

      {showAuth && <AuthModal onClose={() => setShowAuth(false)} />}
      {showHost && <HostVenueModal onClose={() => setShowHost(false)} />}
      {showBookings && <MyBookingsModal onClose={() => setShowBookings(false)} />}
      {showAdmin && <AdminPanel onClose={() => setShowAdmin(false)} />}
    </header>
  )
}