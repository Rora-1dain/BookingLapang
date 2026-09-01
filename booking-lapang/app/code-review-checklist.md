# Checklist Code Review — Bintang

| No | Item Pemeriksaan | Sudah/Belum | Catatan |
|----|---|---|---|
| 1 | Semua kode sudah melalui Laravel Pint | Sudah | |
| 2 | Tidak ada method/kode yang tidak terpakai | Sudah | Dihapus `pendapatanPerJenisLapangan()` di `DashboardService` (tidak pernah dipanggil) |
| 3 | Penamaan method konsisten di seluruh project | Sudah | Rename `applyDateFilter()` jadi `terapkanFilterTanggal()` di `DashboardService` |
| 4 | Tidak ada logika bisnis yang terduplikasi | Sudah | Logika cek kepemilikan booking ditarik jadi method `pastikanMilikUser()` di Model `Booking`, dipakai di semua method `BookingController` |
| 5 | Tidak ada kredensial/API key yang ter-hardcode di kode | Sudah | Semua key (Midtrans, database) diambil lewat `.env` dan `config()` |
| 6 | Validasi panjang sudah dipindah ke Form Request | Sudah | Dibuat `StoreBookingRequest` untuk validasi `BookingController::store()` |
| 7 | `declare(strict_types=1)` sudah di semua Service | Sudah | Ditambahkan di semua file Service dan Controller |
| 8 | Tidak ditemukan N+1 query di halaman utama | Menyusul | Dicek pakai Laravel Debugbar |

## Sebelum & Sesudah — Contoh Refactor

### 1. Duplikasi cek kepemilikan booking

**Sebelum:**
​```php
if ($booking->user_id !== Auth::id()) {
    abort(403, 'Anda tidak berhak mengakses booking ini.');
}
​```

**Sesudah (di Model Booking):**
​```php
public function pastikanMilikUser(int $userId): void
{
    if ($this->user_id !== $userId) {
        abort(403, 'Anda tidak berhak mengakses booking ini.');
    }
}
​```

Dipakai di Controller jadi:
​```php
$booking->pastikanMilikUser(Auth::id());
​```

### 2. Validasi dipindah ke Form Request

**Sebelum (di dalam Controller):**
​```php
$validated = $request->validate([
    'lapangan_id' => 'required|exists:lapangans,id',
    'tanggal_booking' => 'required|date|after_or_equal:today',
    'jam_mulai' => 'required|date_format:H:i',
    'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
]);
​```

**Sesudah:**
​```php
public function store(StoreBookingRequest $request)
{
    $validated = $request->validated();
    // ...
}
​```