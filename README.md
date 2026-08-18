# Booking Lapang

Aplikasi booking lapangan olahraga (futsal, badminton, basket, dll) berbasis Laravel. Dibuat sebagai bagian dari Tugas Praktik PKL SMK Telkom Bandung.

## Fitur

- **Booking Lapangan** — user membuat booking dengan cek ketersediaan jadwal otomatis dan perhitungan total harga berdasarkan durasi.
- **Kelola Booking (Admin)** — admin dapat melihat, mengonfirmasi, atau membatalkan booking yang masuk.
- **Notifikasi Email** — user menerima notifikasi otomatis saat booking dikonfirmasi admin.
- **Export Laporan Excel** — admin dapat mengekspor data booking ke file Excel (.xlsx).
- **Queue & Job** — pengiriman notifikasi diproses lewat queue agar tidak memperlambat response saat admin mengonfirmasi booking.

## Requirement

- PHP ^8.3 (dengan ekstensi `zip`, `xml`, `gd`, `mbstring` aktif)
- Composer
- Laravel 13.x
- Database (MySQL)

## Instalasi

```bash
git clone <repo-url>
cd booking-lapang
composer install
cp .env.example .env
php artisan key:generate
```

Set konfigurasi database dan mail di `.env`:

```env
DB_CONNECTION=mysql
MAIL_MAILER=log
QUEUE_CONNECTION=database
```

Jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

Jalankan tabel queue (jika belum ada):

```bash
php artisan queue:table
php artisan migrate
```

Jalankan aplikasi:

```bash
php artisan serve
```

## Menjalankan Queue Worker

Notifikasi booking dikirim lewat queue, sehingga worker harus dijalankan agar job diproses:

```bash
php artisan queue:work
```


## Struktur Fitur Utama

| Fitur | Lokasi File |
|---|---|
| Notifikasi konfirmasi booking | `app/Notifications/BookingDikonfirmasi.php` |
| Logika bisnis booking | `app/Services/BookingService.php` |
| Export laporan Excel | `app/Exports/BookingExport.php` |
| Controller admin | `app/Http/Controllers/AdminBookingController.php` |
| Halaman kelola booking admin | `resources/views/admin/booking/index.blade.php` |

## Catatan Kompatibilitas

Project ini menggunakan **Laravel 13**, sehingga package `maatwebsite/excel` harus versi `^4.0` (versi 3.1.x lama belum mendukung `illuminate/support ^13.0`). Package ini juga membutuhkan ekstensi PHP `zip` aktif.
