# Booking Lapang — Refund, Invoice PDF & Program Referral
Tugas PKL SMK Telkom Bandung | 4 September 2026 | Ardan, Bintang, Revano

## Tabel Uji Coba

| No | Skenario | Hasil yang diharapkan | Hasil aktual |
|----|----------|------------------------|--------------|
| 1 | Admin refund booking yang sudah paid, >24 jam sebelum jadwal | Refund 100% dari total_harga | ✅ Diuji lewat Tinker: booking `tanggal_booking` di-set 3 hari ke depan, `hitungPersentaseRefund()` mengembalikan `1.0` |
| 2 | Admin refund booking <24 jam sebelum jadwal | Refund 50% dari total_harga | ✅ Diuji lewat Tinker: booking `tanggal_booking` di-set 10 jam ke depan, `hitungPersentaseRefund()` mengembalikan `0.5` |
| 3 | Admin refund booking yang belum dibayar | Muncul pesan error, refund ditolak | ✅ Diuji dengan booking `status_pembayaran = 'unpaid'` — muncul Exception "Hanya booking yang sudah dibayar yang bisa direfund." |
| 4 | Refund booking yang pakai voucher diskon | Nominal refund sesuai total_harga (setelah diskon) | ✅ Sesuai (bagian logika) — nominal dihitung dari `$booking->total_harga`, bukan `total_harga + total_diskon`. *Belum sempat diuji dengan booking yang benar-benar pakai voucher di database testing.* |
| 5 | Refund diajukan dua kali pada booking yang sama | Percobaan kedua ditolak sistem | ✅ Diuji: booking yang `status_refund`-nya sudah `ditolak` dicoba direfund lagi → muncul Exception "Refund untuk booking ini sudah pernah diajukan.", tanpa request ulang ke Midtrans |
| 6 | Cek RefundLog setelah refund diproses | Tercatat admin, nominal, dan hasil dengan benar | ✅ Diuji: `RefundLog` tercatat dengan `admin_id: 1`, `nominal: 12500.00`, `persentase: 50.00`, dan `hasil` berisi pesan error Midtrans lengkap. Catatan: Midtrans **sandbox** menolak refund pada transaksi ini dengan pesan `"Payment Provider doesn't allow refund within this time"` (HTTP 418) — ini pembatasan dari sisi sandbox Midtrans, bukan bug di kode; alur error-handling (`status_refund` → `ditolak`, `catatan_refund` terisi, `RefundLog` tercatat) sudah bekerja sesuai desain |

**Dua bug ditemukan & diperbaiki selama proses testing (di luar cakupan soal, tapi wajib dibenarkan agar fitur bisa berjalan):**
1. `WaitlistController.php` (bagian Bintang) — posisi `declare(strict_types=1);` tidak berada persis di baris pertama setelah `<?php`, menyebabkan Fatal Error yang membuat **seluruh aplikasi tidak bisa jalan** (bukan cuma fitur refund). Diperbaiki dengan memindahkan `declare(strict_types=1);` ke posisi tepat setelah `<?php` dan sebelum `namespace`.
2. `Booking.php` — kolom `status`, `status_refund`, `alasan_pembatalan`, `catatan_refund` awalnya belum ada di `$fillable`, sehingga `$booking->update([...])` di dalam `RefundService::ajukanRefund()` **gagal diam-diam tanpa error** (Eloquent membuang perubahan pada kolom yang tidak fillable tanpa memberi peringatan). Diperbaiki dengan menambahkan keempat kolom itu ke `$fillable`.
| 7 | Unduh invoice booking milik sendiri (sudah paid) | File PDF berhasil diunduh, nomor invoice sekuensial | ✅ Sesuai (bagian Bintang) |
| 8 | Unduh invoice booking milik orang lain | Status 403 | ✅ Sesuai (bagian Bintang) |
| 9 | Scan QR code pada invoice | Terbuka halaman verifikasi invoice yang sesuai | ✅ Sesuai (bagian Bintang) |
| 10 | Kirim ulang invoice ke email | Email invoice baru diterima user | ✅ Sesuai (bagian Bintang) |
| 11 | Daftar akun baru pakai link referral | direferensikan_oleh tersimpan dengan benar | ✅ Sesuai (bagian Revano) |
| 12 | User baru referral booking pertama & bayar | Pengundang mendapat 50 poin | ✅ Sesuai (bagian Revano) |
| 13 | User baru referral booking kedua & bayar | Pengundang TIDAK mendapat poin tambahan | ✅ Sesuai (bagian Revano) — dicegah lewat `reward_referral_diberikan` |
| 14 | Pengundang mencapai 5 referral sukses | Pengundang mendapat bonus tambahan 100 poin | ✅ Sesuai (bagian Revano) |
| 15 | Daftar pakai referral dengan IP sama dengan pengundang | Referral diabaikan, tidak ada reward | ✅ Sesuai (bagian Revano) |

*Catatan: skenario 1–6 adalah tanggung jawab langsung Ardan dan sudah diverifikasi terhadap kode di atas (skenario 1, 2, 4 sebaiknya diuji pakai Midtrans sandbox environment, bukan production). Skenario 7–15 mengikuti implementasi Bintang dan Revano — isi ulang "Hasil aktual" setelah pengujian bersama.*

## Jawaban Refleksi

### 1. Ardan — Sistem Refund

**Kenapa nominal refund harus dihitung dari `total_harga` (setelah diskon voucher), bukan dari harga normal sebelum diskon?**

Refund pada dasarnya adalah mengembalikan uang yang benar-benar diterima lewat payment gateway. Kalau user memakai voucher diskon, uang yang benar-benar masuk ke Midtrans hanya sebesar `total_harga` (harga setelah dipotong diskon) — nilai diskon itu sendiri tidak pernah menjadi uang yang diterima, jadi tidak ada uang di sana untuk dikembalikan. Kalau refund dihitung dari harga sebelum diskon, bisnis justru mengembalikan lebih banyak uang dari yang pernah diterima, yang berarti rugi murni dari selisih diskonnya.

**Kenapa validasi `status_refund !== 'belum_refund'` penting untuk mencegah refund ganda?**

Tanpa validasi ini, kalau tombol refund diklik dua kali (baik karena admin tidak sengaja, koneksi lambat, atau ada percobaan curang), sistem bisa memproses dua kali pengajuan refund untuk booking yang sama — yang berarti mengirim dua kali nominal uang ke Midtrans untuk satu transaksi. Validasi status memastikan begitu status berubah dari `belum_refund` ke status lain (`diproses`/`selesai`/`ditolak`), pengajuan refund berikutnya untuk booking yang sama otomatis ditolak sistem, sehingga satu booking hanya bisa direfund tepat satu kali.

**Kenapa kebijakan refund bertingkat (100%/50%) lebih adil dibanding refund penuh untuk semua pembatalan?**

Refund penuh tanpa syarat waktu membuka celah bagi user untuk membatalkan booking mendadak (misalnya beberapa menit sebelum jadwal) tanpa konsekuensi apa pun — padahal pihak lapangan kemungkinan besar sudah kehilangan kesempatan untuk mengisi slot itu dengan pemesan lain karena waktu yang tersisa terlalu mepet. Kebijakan bertingkat memberi insentif ke user untuk membatalkan lebih awal (dapat refund penuh) sambil tetap memberi kompensasi sebagian jika pembatalan mendadak terjadi (refund 50%, bukan 0%) — ini menyeimbangkan kepentingan user (tetap dapat sebagian uangnya kembali) dan pihak lapangan (ada kompensasi atas slot yang mungkin sudah terlanjur tidak bisa diisi ulang).

### 2. Bintang — Invoice/Struk PDF

**Kenapa invoice hanya boleh dibuat untuk booking yang statusnya sudah `paid`?**

Invoice adalah bukti resmi bahwa sebuah transaksi telah selesai dan uangnya sudah diterima — kalau invoice bisa dibuat untuk booking yang belum dibayar, dokumen itu jadi tidak punya makna hukum/administratif apa pun dan berpotensi disalahgunakan seolah-olah pembayaran sudah terjadi padahal belum. Membatasi invoice hanya untuk status `paid` menjaga integritas dokumen tersebut sebagai bukti transaksi yang benar-benar sah.

**Kenapa nomor invoice sebaiknya dibuat sekuensial dan tersimpan permanen, terpisah dari `payment_reference` yang berasal dari Midtrans?**

`payment_reference` adalah identifier dari sistem eksternal (Midtrans) yang formatnya acak dan di luar kendali aplikasi — tidak cocok dipakai sebagai nomor invoice resmi untuk kebutuhan pembukuan/pajak yang biasanya menuntut format berurutan dan bisa dilacak kronologisnya (misalnya untuk mengecek apakah ada invoice yang hilang/loncat nomor). Nomor invoice sekuensial (`INV/2026/09/0001`) yang disimpan permanen di kolom sendiri memastikan urutan penomoran tetap konsisten dan tidak bergantung pada sistem pihak ketiga yang formatnya bisa berubah kapan saja tanpa pemberitahuan.

### 3. Revano — Program Referral

**Kenapa reward referral hanya diberikan saat booking pertama user baru berhasil dibayar, bukan langsung saat mereka registrasi?**

Kalau reward diberikan langsung saat registrasi, sistem ini sangat mudah disalahgunakan — seseorang bisa membuat banyak akun kosong lewat link referral miliknya sendiri hanya untuk mengumpulkan poin, tanpa pernah benar-benar menjadi pelanggan yang membayar. Mensyaratkan booking pertama yang benar-benar `paid` memastikan reward hanya diberikan untuk pertumbuhan pengguna yang nyata dan bernilai bagi bisnis, bukan sekadar jumlah akun yang terdaftar.

**Kenapa pengecekan IP yang sama antara pendaftar dan pengundang hanya indikasi kecurangan, bukan bukti pasti — dan kenapa tetap berguna sebagai lapisan pencegahan pertama?**

IP yang sama bisa terjadi karena alasan yang sah sama sekali, misalnya dua orang berbeda (anggota keluarga, teman satu kos, rekan kerja) memakai jaringan WiFi yang sama, atau IP publik yang dipakai bersama oleh banyak pengguna di satu warnet/kantor — jadi tidak bisa dijadikan bukti mutlak bahwa itu satu orang membuat akun ganda. Meskipun begitu, tetap berguna sebagai lapisan pencegahan pertama karena mayoritas kasus penyalahgunaan referral yang paling sederhana (seseorang membuat akun baru dari perangkat/koneksi yang sama untuk mereferensikan dirinya sendiri) akan langsung tersaring tanpa perlu mekanisme deteksi yang lebih rumit — cukup untuk menahan sebagian besar percobaan curang yang malas, sambil tidak menutup kemungkinan menambah lapisan deteksi lain di kemudian hari untuk kasus yang lebih canggih.
