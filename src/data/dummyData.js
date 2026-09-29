// ---------------------------------------------------------------------------
// DUMMY DATA — frontend-only placeholder.
//
// Every shape here is written the way a real API response would look, so
// swapping this file for a fetch() call to your own backend later should be
// a drop-in change. See README.md for the suggested endpoints.
// ---------------------------------------------------------------------------

export const platformStats = [
  { id: 'venues', value: '340+', label: 'Lapangan Terverifikasi' },
  { id: 'settlement', value: '< 45 Detik', label: 'Settlement QRIS Instan' },
  { id: 'availability', value: '100%', label: 'Slot Real-Time Terjamin' },
]

export const sportCategories = [
  { id: 'futsal', name: 'Futsal', count: 38, icon: 'sports_soccer' },
  { id: 'badminton', name: 'Badminton', count: 26, icon: 'sports_tennis' },
  { id: 'basketball', name: 'Basketball', count: 20, icon: 'sports_basketball' },
  { id: 'minisoccer', name: 'Mini Soccer', count: 0, icon: 'sports' },
]

export const featuredVenue = {
  id: 'v-001',
  name: 'Senayan National Futsal Stadium',
  cluster: 'Gelora Bung Karno Cluster',
  sport: 'Futsal',
  pricePerHour: 350000,
  rating: 4.98,
  slotsLeftToday: 4,
  image:
    'https://images.unsplash.com/photo-1552667466-07770ae110d0?q=80&w=1200&auto=format&fit=crop',
  tags: ['Vinyl Interlock 7mm', 'Stadium Air-Con', 'Wasit Resmi Tersedia'],
  slots: [
    { time: '19:00 - 20:00', status: 'available' },
    { time: '20:00 - 21:00', status: 'selected' },
    { time: '21:00 - 22:00', status: 'booked' },
  ],
}

export const venues = [
  {
    id: 'v-002',
    name: 'Sinar Harapan Arena & Badminton Hall',
    cluster: 'South Jakarta • Cilandak Timur',
    sport: 'Badminton',
    pricePerHour: 175000,
    rating: 4.9,
    reviewCount: 128,
    courts: 6,
    description:
      'Matras vinyl hijau standar Olimpiade dengan lampu LED anti-silau 650 Lux. Tersedia loker dan toko senar.',
    image:
      'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?q=80&w=1200&auto=format&fit=crop',
    tags: ['BWF Certified', 'Yonex Ennova Matting'],
    liveSlot: 'Court 2 • 17:00, 18:00, 21:00 Terbuka',
    demand: 'normal',
  },
  {
    id: 'v-003',
    name: 'DunkMaster Elite Maple Dome',
    cluster: 'West Surabaya • Mayjend Sungkono',
    sport: 'Basketball',
    pricePerHour: 420000,
    rating: 4.95,
    reviewCount: 96,
    courts: 1,
    description:
      'Lantai kayu maple Kanada dengan ring hidrolik breakaway dan shot clock 24 detik.',
    image:
      'https://images.unsplash.com/photo-1546519638-68e109498ffc?q=80&w=1200&auto=format&fit=crop',
    tags: ['FIBA Wood', 'Peak Demand'],
    liveSlot: '2 slot terbuka (21:00 - 23:00)',
    demand: 'high',
  },
  {
    id: 'v-004',
    name: 'Cilandak Town Square Arena',
    cluster: 'South Jakarta • Cilandak',
    sport: 'Futsal',
    pricePerHour: 280000,
    rating: 4.8,
    reviewCount: 74,
    courts: 3,
    description:
      'Lapangan interlock outdoor beratap dengan pencahayaan 500 Lux dan tribun penonton kecil.',
    image:
      'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?q=80&w=1200&auto=format&fit=crop',
    tags: ['Interlock Flooring', 'Covered Roof'],
    liveSlot: 'Court 1 • 18:00, 20:00 Terbuka',
    demand: 'normal',
  },
]

export const communityMatches = [
  {
    id: 'm-001',
    sport: 'FUTSAL 5V5',
    title: 'Jakarta Spartans FC vs Open Challenger',
    venue: 'GBK Futsal Hall • Pitch 1 (Vinyl)',
    timing: 'Closing in 15 min',
    detail: '2 pemain dibutuhkan',
  },
  {
    id: 'm-002',
    sport: 'BADMINTON DOUBLES',
    title: 'Advanced Mixed Doubles Round-Robin',
    venue: 'Sinar Harapan Hall • Court 3',
    timing: 'Starts 20:30 WIB',
    detail: 'Level: Competitive (PBSI C)',
  },
  {
    id: 'm-003',
    sport: 'BASKETBALL FULL COURT',
    title: 'Sudirman Corporate 5-on-5 Run',
    venue: 'Senayan Basketball Hall • Court A',
    timing: 'Today 21:00 WIB',
    detail: 'Fee: Rp 45.000 / pemain',
  },
]

export const communityTelemetry = {
  matchesThisMonth: '14.820+',
  activeLobbies: 342,
}

export const locations = [
  'Senayan & GBK, Jakarta',
  'Jakarta Selatan (Kemang / Cilandak)',
  'BSD City / Serpong',
  'Surabaya Barat (Graha Famili)',
  'Bandung Dago & Riau',
]

export function formatRupiah(amount) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
  }).format(amount)
}
