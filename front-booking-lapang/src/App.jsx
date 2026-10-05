import { useEffect } from 'react'
import Navbar from './components/Navbar'
import Hero from './components/Hero'
import VenueGrid from './components/VenueGrid'
import MembershipSection from './components/MembershipSection'
import Footer from './components/Footer'
import ChatPage from './components/ChatPage'
import MembershipPage from './components/MembershipPage'
import AdminDashboardPage from './components/AdminDashboardPage'
import PemilikDashboardPage from './components/PemilikDashboardPage'
import PoinPage from './components/PoinPage'
import ReferralPage from './components/ReferralPage'
import WaitlistPage from './components/WaitlistPage'
import PengaturanNotifikasiPage from './components/PengaturanNotifikasiPage'
import { AuthProvider } from './context/AuthContext'
import { VenueProvider } from './context/VenueContext'
import useHash from './lib/useHash'

// Routing ringan berbasis hash:
// - '#/chat' & '#/chat/12' (chat pengguna & admin)
// - '#/membership'
// - '#/admin'
// - '#/pemilik'
// - '#/poin' (loyalty & hadiah)
// - '#/referral' (kode & leaderboard)
// - '#/waitlist' (antrean slot)
// - '#/pengaturan/notifikasi' (preferensi)
function Halaman({ hash }) {
  const chat = hash.match(/^#\/chat(?:\/(\d+))?$/)
  if (chat) return <ChatPage activeId={chat[1] ? Number(chat[1]) : null} />
  if (hash === '#/membership') return <MembershipPage />
  if (hash === '#/admin') return <AdminDashboardPage />
  if (hash === '#/pemilik') return <PemilikDashboardPage />
  if (hash === '#/poin') return <PoinPage />
  if (hash === '#/referral') return <ReferralPage />
  if (hash === '#/waitlist') return <WaitlistPage />
  if (hash === '#/pengaturan/notifikasi') return <PengaturanNotifikasiPage />

  return (
    <>
      <Hero />
      <VenueGrid />
      <MembershipSection />
    </>
  )
}

export default function App() {
  const hash = useHash()
  const halamanLain = hash.startsWith('#/')

  useEffect(() => {
    if (halamanLain) window.scrollTo(0, 0)
  }, [hash, halamanLain])

  return (
    <AuthProvider>
      <VenueProvider>
        <div className="min-h-screen bg-cream text-body">
          <Navbar />
          <Halaman hash={hash} />
          <Footer />
        </div>
      </VenueProvider>
    </AuthProvider>
  )
}
