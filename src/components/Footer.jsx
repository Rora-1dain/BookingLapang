const LINKS = [
  { label: 'Syarat & Ketentuan', href: '#terms' },
  { label: 'Kebijakan Privasi', href: '#privacy' },
  { label: 'Protokol Lapangan', href: '#protocols' },
  { label: 'QRIS / BCA / MANDIRI / GOPAY', href: '#payment', highlight: true },
  { label: 'Bantuan', href: '#support' },
]

export default function Footer() {
  return (
    <footer className="bg-cream-dim border-t border-match-blue/10">
      <div className="w-full max-w-content mx-auto px-6 py-12 flex flex-col md:flex-row items-center justify-between gap-6">
        <div className="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
          <div className="flex items-center gap-2">
            <div className="h-7 w-7 rounded bg-match-blue flex items-center justify-center text-cream font-display text-xs">
              BL
            </div>
            <span className="font-display text-xl text-match-blue uppercase tracking-wider">
              Booking Lapang
            </span>
          </div>
          <p className="text-sm text-muted">
            © 2026 Booking Lapang. Seluruh hak cipta dilindungi.
          </p>
        </div>

        <div className="flex flex-wrap items-center justify-center gap-4">
          {LINKS.map((link) => (
            <a
              key={link.label}
              href={link.href}
              className={`text-xs font-bold tracking-wide transition-colors ${
                link.highlight ? 'text-match-blue underline' : 'text-muted hover:text-ink'
              }`}
            >
              {link.label.toUpperCase()}
            </a>
          ))}
        </div>
      </div>
    </footer>
  )
}
