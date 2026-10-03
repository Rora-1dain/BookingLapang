import { useEffect } from 'react'
import Navbar from './components/Navbar'
import Hero from './components/Hero'
import VenueGrid from './components/VenueGrid'
import MembershipSection from './components/MembershipSection'
import HostCta from './components/HostCta'
import Footer from './components/Footer'
import ChatPage from './components/ChatPage'
import MembershipPage from './components/MembershipPage'
import AdminDashboardPage from './components/AdminDashboardPage'
import PemilikDashboardPage from './components/PemilikDashboardPage'
import { AuthProvider } from './context/AuthContext'
import { VenueProvider } from './context/VenueContext'
import useHash from './lib/useHash'

// Routing ringan berbasis hash (tanpa react-router, jadi tidak butuh rewrite di
// Vercel): '#/chat' dan '#/chat/12' (chat), '#/membership', '#/admin', '#/pemilik'.
function Halaman({ hash }) {
  const chat = hash.match(/^#\/chat(?:\/(\d+))?$/)
  if (chat) return <ChatPage activeId={chat[1] ? Number(chat[1]) : null} />
  if (hash === '#/membership') return <MembershipPage />
  if (hash === '#/admin') return <AdminDashboardPage />
  if (hash === '#/pemilik') return <PemilikDashboardPage />

  return (
    <>
      <Hero />
      <VenueGrid />
      <MembershipSection />
      <HostCta />
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