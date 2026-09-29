# Booking Lapang — Landing Page (Frontend Only)

Landing page untuk platform booking lapangan olahraga ("Booking Lapang"), dibangun
dengan React + Vite + Tailwind CSS, mengikuti design system dari export Stitch
yang kamu upload (warna Match Blue / Court Green / Stadium Cream, font Bebas Neue
+ Plus Jakarta Sans).

Project ini **tidak punya backend** — semua data (daftar lapangan, jadwal, statistik)
berasal dari `src/data/dummyData.js`. Tujuannya supaya kamu bisa menyambungkannya
ke backend/API kamu sendiri kapan saja.

## Menjalankan secara lokal

```bash
npm install
npm run dev
```

Buka `http://localhost:5173`.

Build untuk produksi:

```bash
npm run build
npm run preview
```

## Struktur project

```
src/
  data/dummyData.js     ← semua data dummy (venues, kategori olahraga, statistik, dll)
  components/
    Navbar.jsx
    Hero.jsx             ← search widget + kartu lapangan unggulan
    VenueGrid.jsx        ← grid lapangan + filter kategori olahraga
    CommunityRadar.jsx   ← daftar pertandingan komunitas (live match)
    HostCta.jsx          ← ajakan daftar sebagai pemilik venue
    Footer.jsx
  App.jsx                ← merangkai semua section jadi satu landing page
tailwind.config.js       ← token warna & tipografi sesuai design system
```

## Menyambungkan ke backend kamu

Setiap array/objek di `src/data/dummyData.js` sengaja dibentuk seperti respons API
sungguhan, supaya gampang diganti. Contoh paling sederhana: ganti import statis
dengan `fetch`/`axios` di dalam `useEffect`, misalnya di `VenueGrid.jsx`:

```jsx
// Sebelum (dummy):
import { venues } from '../data/dummyData'

// Sesudah (real API):
const [venues, setVenues] = useState([])
useEffect(() => {
  fetch('/api/venues')
    .then((res) => res.json())
    .then(setVenues)
}, [])
```

Endpoint yang masuk akal untuk backend kamu, berdasarkan data dummy yang ada:

- `GET /api/venues` → daftar lapangan (bentuknya sama seperti array `venues`)
- `GET /api/venues/featured` → lapangan unggulan di hero (`featuredVenue`)
- `GET /api/venues/:id/slots` → jadwal slot per lapangan
- `GET /api/categories` → kategori olahraga + jumlah lapangan (`sportCategories`)
- `GET /api/community-matches` → pertandingan komunitas yang terbuka (`communityMatches`)
- `POST /api/bookings` → dipanggil saat tombol "Kunci Lapangan Sekarang" ditekan

Form pencarian di `Hero.jsx` dan tombol booking saat ini hanya `alert()` / belum
melakukan apa-apa — itu adalah titik yang paling masuk akal untuk kamu sambungkan
ke logic booking/backend asli.

## Catatan

- Font di-load dari Google Fonts CDN di `index.html`.
- Tidak ada state management library — semua state lokal per komponen (`useState`).
  Kalau butuh state global (auth, keranjang booking, dsb) setelah backend masuk,
  React Context atau library seperti Zustand akan jadi langkah alami berikutnya.
- `react-router-dom` sudah ada di `package.json` untuk memudahkan kamu menambah
  halaman lain (detail lapangan, login, dashboard owner) nanti — belum dipakai
  di landing page ini.
