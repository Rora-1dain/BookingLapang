# Changelog — Booking Lapang

Seluruh perubahan pada project ini dicatat di file ini, disusun berdasarkan tanggal pengerjaan.

## Minggu 1 (10–12 Agustus 2026) — Fondasi

### 10 Agustus — Model & Database
- Migration tabel `lapangans` dan `bookings`
- Model `Lapangan` dan `Booking` dengan relasi Eloquent (`hasMany`, `belongsTo`)
- `BookingService` dasar: `cekKetersediaan()`, `hitungTotalHarga()`, `buatBooking()`, `batalkanBooking()`
- `BookingController` dengan dependency injection dan routing dasar

### 11 Agustus — View & Integrasi
- Form booking (`booking/create.blade.php`) dan daftar booking (`booking/index.blade.php`)
- Layout master (`layouts/app.blade.php`)
- Seeder data dummy lapangan
- Integrasi end-to-end pertama: form → Service → Model → tampil di daftar

### 12 Agustus — Validasi & Otorisasi
- Validasi lapangan berstatus nonaktif tidak bisa dibooking
- Kolom `role` pada tabel `users`, fitur konfirmasi booking oleh admin
- Middleware `AdminOnly`, proteksi route `/admin/*`

## Minggu 2 (18–20 Agustus 2026) — Notifikasi, API & Pembayaran

### 18 Agustus — Notifikasi, Laporan & Queue
- Notification `BookingDikonfirmasi` (email dikirim saat status booking jadi `confirmed`)
- Export laporan booking ke Excel untuk admin
- Notifikasi dijalankan lewat queue (`ShouldQueue`) agar tidak memperlambat response

### 19 Agustus — REST API dengan Sanctum
- API Resource: `LapanganResource`, `BookingResource`, `UserResource`, `LapanganCollection`
- `BookingApiController` (index, show, store, cancel) dengan pagination & filter status
- Autentikasi token berbasis Laravel Sanctum, rate limiting pada endpoint login

### 20 Agustus — Integrasi Payment Gateway (Midtrans Snap)
- Kolom baru pada tabel `bookings`: `metode_pembayaran`, `status_pembayaran`, `payment_reference` (unique)
- `PaymentService` untuk generate Snap Token
- Webhook callback dengan verifikasi signature dari Midtrans

## Minggu 3 (24–27 Agustus 2026) — Dashboard & Infrastruktur

### 24 Agustus — Dashboard, Caching & Persiapan Deployment
- `DashboardService`: total pendapatan, booking per status, lapangan terfavorit, pendapatan per bulan, tingkat pembatalan, pendapatan per jenis lapangan, user paling aktif
- Dashboard admin dengan grafik (Chart.js)
- Caching pada query berat (`Cache::remember`), index database pada kolom yang sering difilter
- Endpoint `/health` untuk monitoring

### 27 Agustus — Migrasi Supabase, Penyempurnaan Midtrans & Deploy Vercel
- Penyesuaian query mentah untuk kompatibilitas PostgreSQL (`DATE_FORMAT()` → `TO_CHAR()`, `IFNULL()` → `COALESCE()`)
- Verifikasi seluruh migration & 25 test tetap PASS di atas Supabase
- `cekStatusTransaksi()` untuk pengecekan status pembayaran secara aktif (tidak hanya bergantung webhook)
- Tabel `payment_logs` untuk audit trail notifikasi, idempotency check pada webhook
- Scheduled command `booking:reconcile-payment`
- Deploy pertama ke Vercel menggunakan community runtime `vercel-php`

## Minggu 4 (31 Agustus 2026) — Dokumentasi & Persiapan Demo

### 31 Agustus — Dokumentasi, Code Review & QA
- Dokumentasi teknis lengkap: README, ERD, diagram arsitektur, koleksi Postman, PHPDoc
- Code review dan refactoring: Laravel Pint, Form Request class, penghapusan kode duplikat
- QA menyeluruh end-to-end, uji keamanan dasar (XSS/SQL Injection), persiapan skenario demo
