import { communityMatches, communityTelemetry } from '../data/dummyData'

// Catatan: backend belum punya fitur/endpoint untuk "pertandingan komunitas"
// (bukan bagian dari routes/api.php), jadi section ini masih memakai data
// contoh (dummyData.js) sampai fitur itu benar-benar dibangun di backend.
export default function CommunityRadar() {
  return (
    <section id="community" className="w-full bg-ink text-cream py-16 border-y-4 border-court-green">
      <div className="max-w-content mx-auto px-6">
        <div className="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-white/15 gap-4">
          <div>
            <div className="flex items-center gap-2 mb-1">
              <span className="w-2.5 h-2.5 rounded-full bg-whistle-red animate-pulse" />
              <span className="text-xs font-bold tracking-widest text-court-green">
                TELEMETRI KOMUNITAS LIVE
              </span>
            </div>
            <h2 className="font-display text-4xl uppercase tracking-wide">Radar Komunitas Matchday</h2>
          </div>
          <div className="flex items-center gap-6">
            <div className="text-right">
              <div className="font-display text-3xl leading-tight text-[#7cd9a4]">
                {communityTelemetry.matchesThisMonth}
              </div>
              <div className="text-[11px] text-cream/60">PERTANDINGAN BULAN INI</div>
            </div>
            <div className="h-10 w-px bg-white/20 hidden sm:block" />
            <div className="text-right hidden sm:block">
              <div className="font-display text-3xl leading-tight">{communityTelemetry.activeLobbies}</div>
              <div className="text-[11px] text-cream/60">LOBI SPARRING AKTIF</div>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-5 mt-8">
          {communityMatches.map((match) => (
            <div
              key={match.id}
              className="bg-white/5 border border-white/10 rounded-lg p-4 hover:border-court-green transition-colors"
            >
              <div className="flex items-center justify-between mb-2">
                <span className="bg-match-blue text-cream text-[11px] font-bold px-2 py-0.5 rounded">
                  {match.sport}
                </span>
                <span className="text-[11px] font-bold text-court-green">{match.timing}</span>
              </div>
              <h4 className="font-display text-lg uppercase mb-1">{match.title}</h4>
              <p className="text-[13px] text-cream/60 mb-3">{match.venue}</p>
              <div className="flex items-center justify-between pt-2 border-t border-white/10">
                <span className="text-[12px] font-bold text-cream/70">{match.detail}</span>
                <button className="bg-court-green hover:bg-court-green-dark text-cream text-[11px] font-bold px-3 py-1 rounded uppercase transition-colors">
                  Gabung
                </button>
              </div>
            </div>
          ))}
        </div>

        <div className="mt-8 bg-white/5 border border-dashed border-white/20 p-4 rounded-lg flex flex-col sm:flex-row items-center justify-between gap-4">
          <div>
            <div className="font-bold text-sm uppercase">Punya slot booking tapi kurang lawan main?</div>
            <div className="text-[13px] text-cream/60">
              Buka pertandinganmu untuk komunitas. Pemain terverifikasi bisa langsung gabung.
            </div>
          </div>
          <button className="bg-cream text-ink font-bold text-[13px] px-5 py-2.5 rounded uppercase whitespace-nowrap hover:bg-white transition-colors">
            Buat Spar Radar
          </button>
        </div>
      </div>
    </section>
  )
}
