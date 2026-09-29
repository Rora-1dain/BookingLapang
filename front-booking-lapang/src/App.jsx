import Navbar from './components/Navbar'
import Hero from './components/Hero'
import VenueGrid from './components/VenueGrid'
import CommunityRadar from './components/CommunityRadar'
import HostCta from './components/HostCta'
import Footer from './components/Footer'
import { AuthProvider } from './context/AuthContext'
import { VenueProvider } from './context/VenueContext'

export default function App() {
  return (
    <AuthProvider>
      <VenueProvider>
        <div className="min-h-screen bg-cream text-body">
          <Navbar />
          <Hero />
          <VenueGrid />
          <CommunityRadar />
          <HostCta />
          <Footer />
        </div>
      </VenueProvider>
    </AuthProvider>
  )
}
