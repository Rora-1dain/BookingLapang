import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import { VitePWA } from 'vite-plugin-pwa'

// Proxy '/api' ke backend Laravel saat development, supaya tidak perlu
// mengatur CORS di backend dan tidak perlu domain absolut di frontend.
// Sesuaikan target kalau `php artisan serve` kamu jalan di port lain.
export default defineConfig({
  plugins: [
    react(),
    VitePWA({
      registerType: 'autoUpdate',
      // Ganti "BL" placeholder di public/icon-*.png kapan pun ada logo asli —
      // manifest & service worker-nya tidak perlu diubah sama sekali.
      manifest: {
        name: 'Booking Lapang',
        short_name: 'BookingLapang',
        description: 'Sewa lapangan olahraga terverifikasi — booking & bayar instan.',
        theme_color: '#16407A', // match-blue, sinkron sama tailwind.config.js
        background_color: '#F7F1E3', // cream
        display: 'standalone',
        start_url: '/',
        icons: [
          { src: '/icon-192.png', sizes: '192x192', type: 'image/png', purpose: 'any' },
          { src: '/icon-512.png', sizes: '512x512', type: 'image/png', purpose: 'any' },
          { src: '/icon-192-maskable.png', sizes: '192x192', type: 'image/png', purpose: 'maskable' },
          { src: '/icon-512-maskable.png', sizes: '512x512', type: 'image/png', purpose: 'maskable' },
        ],
      },
      workbox: {
        // Aset build (JS/CSS/font/gambar) di-precache & cache-first — ini yang
        // bikin app kebuka cepat & tetap bisa dibuka offline.
        globPatterns: ['**/*.{js,css,html,png,svg,woff2}'],
        runtimeCaching: [
          // Booking & pembayaran: JANGAN pernah dicache. Status booking,
          // snap_token Midtrans, dan cek status pembayaran wajib selalu fresh
          // dari server — kalau ke-cache bisa fatal (token expired kepakai,
          // status booking basi kelihatan "pending" padahal udah lunas, dst).
          {
            urlPattern: /\/api\/booking/,
            handler: 'NetworkOnly',
          },
          {
            urlPattern: /\/api\/pemilik/,
            handler: 'NetworkOnly',
          },
          // Data lapangan & lainnya: network-first — coba ambil yang terbaru
          // dulu, baru jatuh ke cache kalau offline/koneksi lambat, supaya app
          // tetap kepakai (walau datanya mungkin sedikit basi) tanpa internet.
          {
            urlPattern: /\/api\//,
            handler: 'NetworkFirst',
            options: {
              cacheName: 'api-cache',
              networkTimeoutSeconds: 5,
              cacheableResponse: { statuses: [0, 200] },
            },
          },
        ],
      },
      devOptions: {
        enabled: true, // biar service worker juga aktif pas `npm run dev` buat testing
      },
    }),
  ],
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
    },
  },
  // `server.proxy` di atas cuma berlaku pas `npm run dev` — `vite preview`
  // (dipakai buat testing PWA di localhost:4173/5173) butuh proxy-nya
  // didaftarin terpisah di sini, kalau nggak semua fetch ke /api/* bakal
  // 404 karena nyasar ke origin preview server itu sendiri.
  preview: {
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
    },
  },
})