import { useEffect, useState } from 'react'
import Navbar from './components/Navbar'
import Hero from './components/Hero'
import VenueGrid from './components/VenueGrid'
import CommunityRadar from './components/CommunityRadar'
import HostCta from './components/HostCta'
import Footer from './components/Footer'
import ChatPage from './components/ChatPage'
import { AuthProvider } from './context/AuthContext'
import { VenueProvider } from './context/VenueContext'

// Routing ringan berbasis hash (tanpa react-router, jadi tidak butuh rewrite di
// Vercel): '#/chat' = daftar pesan, '#/chat/12' = buka percakapan nomor 12.
function useHash() {
  const [hash, setHash] = useState(window.location.hash)
  useEffect(() => {
    const onChange = () => setHash(window.location.hash)
    window.addEventListener('hashchange', onChange)
    return () => window.removeEventListener('hashchange', onChange)
  }, [])
  return hash
}

export default function App() {
  const hash = useHash()
  const match = hash.match(/^#\/chat(?:\/(\d+))?$/)
  const halamanChat = !!match
  const chatId = match?.[1] ? Number(match[1]) : null

  useEffect(() => {
    if (halamanChat) window.scrollTo(0, 0)
  }, [halamanChat])

  return (
    <AuthProvider>
      <VenueProvider>
        <div className="min-h-screen bg-cream text-body">
          <Navbar />
          {halamanChat ? (
            <ChatPage activeId={chatId} />
          ) : (
            <>
              <Hero />
              <VenueGrid />
              <CommunityRadar />
              <HostCta />
            </>
          )}
          <Footer />
        </div>
      </VenueProvider>
    </AuthProvider>
  )
}