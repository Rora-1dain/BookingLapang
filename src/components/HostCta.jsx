import { useState } from 'react'
import HostVenueModal from './HostVenueModal'

export default function HostCta() {
  const [showHost, setShowHost] = useState(false)

  return (
    <section id="host" className="w-full max-w-content mx-auto px-6 py-16">
      <div className="bg-white border-2 border-match-blue/20 rounded-lg p-8 lg:p-12 shadow-tactile flex flex-col md:flex-row items-center justify-between gap-8">
        <div className="max-w-2xl">
          <div className="flex items-center gap-2 mb-2">
            <span className="bg-ink text-cream text-xs font-bold px-2 py-0.5 rounded">
              VENUE OPERATOR SUITE
            </span>
            <span className="text-xs font-bold text-match-blue">TANPA BIAYA SETUP</span>
          </div>
          <h2 className="font-display text-3xl lg:text-4xl text-ink uppercase leading-tight">
            Punya arena olahraga? Otomatiskan booking lapanganmu.
          </h2>
          <p className="text-muted mt-2">
            Hilangkan double booking lewat WhatsApp. Sinkronkan walk-in offline dengan jadwal digital
            real-time dan settlement QRIS otomatis.
          </p>
          <div className="flex flex-wrap gap-4 mt-4 text-sm font-bold text-ink">
            {['Invoicing WhatsApp Real-Time', 'Settlement Bank Keesokan Hari', 'Integrasi IoT Gerbang & Lampu'].map(
              (item) => (
                <div key={item} className="flex items-center gap-1.5">
                  <span className="text-court-green">✓</span>
                  {item}
                </div>
              )
            )}
          </div>
        </div>

        <div className="flex flex-col gap-3 w-full md:w-auto shrink-0">
          <button
            onClick={() => setShowHost(true)}
            className="bg-match-blue hover:bg-match-blue-dark text-cream font-bold text-sm uppercase px-8 py-3.5 rounded-lg shadow-tactile-sm whitespace-nowrap transition-colors"
          >
            Daftarkan Venue
          </button>
          <button className="border-2 border-ink text-ink hover:bg-cream font-bold text-sm uppercase px-8 py-3 rounded-lg whitespace-nowrap transition-colors">
            Lihat Demo
          </button>
        </div>
      </div>

      {showHost && <HostVenueModal onClose={() => setShowHost(false)} />}
    </section>
  )
}
