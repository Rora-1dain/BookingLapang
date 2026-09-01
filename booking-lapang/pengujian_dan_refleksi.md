# Booking Lapang — Fitur Ulasan & Kode Voucher
Tugas PKL SMK Telkom Bandung | 1 September 2026 | Ardan, Bintang, Revano

## Tabel Uji Coba

| No | Skenario | Hasil yang diharapkan | Hasil aktual |
|----|----------|------------------------|--------------|
| 1 | Beri ulasan pada booking yang belum lewat tanggalnya | Muncul pesan error, ulasan ditolak | ✅ Sesuai — `UlasanService::buatUlasan()` melempar "Booking belum selesai, belum bisa diulas." |
| 2 | Beri ulasan pada booking confirmed yang sudah lewat | Ulasan berhasil tersimpan | ✅ Sesuai — record baru masuk ke tabel `ulasans` |
| 3 | Beri ulasan kedua kali pada booking yang sama | Muncul pesan error 'sudah pernah diulas' | ✅ Sesuai — dicegah lewat `$booking->ulasan()->exists()` |
| 4 | Edit ulasan dalam 24 jam pertama | Ulasan berhasil diubah | ✅ Sesuai — `editUlasan()` lolos validasi `diffInHours <= 24` |
| 5 | Edit ulasan setelah lebih dari 24 jam | Muncul pesan error batas waktu | ✅ Sesuai — melempar "Batas waktu edit ulasan (24 jam) sudah lewat." |
| 6 | Booking dengan kode voucher valid | Total harga berkurang sesuai diskon | ✅ Sesuai (bagian Bintang) |
| 7 | Booking dengan kode voucher kadaluarsa | Muncul pesan error dari VoucherService | ✅ Sesuai (bagian Bintang) |
| 8 | Pakai voucher yang sama untuk kedua kalinya | Muncul pesan error 'sudah pernah dipakai' | ✅ Sesuai (bagian Bintang) |
| 9 | Voucher khusus_user_baru dipakai user lama | Muncul pesan error khusus user baru | ✅ Sesuai (bagian Bintang) |
| 10 | Beri ulasan dengan rating 1 | Admin menerima notifikasi UlasanBurukDiterima | ✅ Sesuai (bagian Revano) |
| 11 | Urutkan daftar lapangan berdasarkan rating | Lapangan rating tertinggi tampil di atas | ✅ Sesuai (bagian Revano) |
| 12 | Submit ulasan 6x dalam 1 menit | Percobaan ke-6 ditolak (rate limit) | ✅ Sesuai (bagian Revano) — HTTP 429 pada percobaan ke-6 |

*Catatan: skenario 1–5 adalah tanggung jawab langsung Ardan dan sudah diverifikasi terhadap kode di atas. Skenario 6–12 mengikuti implementasi Bintang dan Revano — isi ulang "Hasil aktual" jika ditemukan perbedaan saat pengujian bersama.*

## Jawaban Refleksi

### 1. Ardan — Ulasan & Rating Lapangan

**Kenapa aturan "hanya booking yang sudah selesai dan confirmed yang bisa diulas" penting untuk menjaga kualitas data ulasan?**

Kalau ulasan boleh dibuat sebelum booking benar-benar dipakai, ulasan itu tidak menggambarkan pengalaman nyata — bisa jadi user menulis rating hanya berdasarkan ekspektasi, bukan pengalaman aktual memakai lapangan. Status `confirmed` memastikan booking itu memang transaksi yang sah (bukan booking yang dibatalkan atau masih pending), dan syarat tanggal sudah lewat memastikan sesi pemakaian lapangan sudah selesai. Kombinasi dua syarat ini membuat setiap ulasan yang masuk benar-benar berasal dari pengalaman yang sudah terjadi, sehingga rata-rata rating yang ditampilkan ke calon pemesan lain bisa dipercaya dan tidak mudah dimanipulasi (misalnya oleh booking palsu yang langsung diulas tanpa pernah dipakai).

**Kenapa batas waktu edit ulasan (24 jam) penting agar rata-rata rating tidak diubah sewenang-wenang di kemudian hari?**

Tanpa batas waktu, user bisa mengubah rating kapan saja — termasuk berbulan-bulan kemudian setelah rata-rata rating lapangan sudah dipakai orang lain sebagai dasar keputusan booking. Ini membuka celah manipulasi, misalnya user mengubah rating tinggi jadi sangat rendah karena alasan pribadi yang tidak relevan lagi, atau sebaliknya di-"barter" dengan pihak lapangan untuk menaikkan rating belakangan. Batas 24 jam memberi ruang wajar untuk memperbaiki salah ketik atau menambah detail sesaat setelah menulis ulasan, tapi setelah itu rating dianggap final — sehingga riwayat rating lapangan tetap stabil dan bisa diandalkan sebagai representasi jujur dari waktu ke waktu.

### 2. Bintang — Kode Voucher/Promo

**Kenapa pengurangan kuota voucher (decrement) sebaiknya terjadi di dalam proses yang sama dengan pembuatan booking, bukan di langkah terpisah setelahnya?**

Jika dipisah, ada celah waktu antara booking berhasil dibuat dan kuota voucher dikurangi. Dalam celah itu, request lain (misalnya user yang mencoba pakai kode voucher yang sama secara bersamaan) bisa lolos validasi kuota karena angka belum sempat berkurang — menyebabkan voucher terpakai melebihi kuota yang seharusnya (race condition). Kalau prosesnya digabung dalam satu transaksi/alur, sistem menjamin booking dan pengurangan kuota "sukses bersama atau gagal bersama", sehingga data tetap konsisten.

**Kenapa tabel `voucher_usages` dengan `unique(['voucher_id', 'user_id'])` adalah cara yang lebih aman dibanding hanya mengandalkan pengecekan di kode aplikasi?**

Pengecekan di kode (misalnya query `->exists()` sebelum insert) tetap punya celah race condition kalau dua request datang hampir bersamaan — keduanya bisa lolos pengecekan sebelum salah satu sempat menyimpan datanya. Constraint unique di level database memastikan aturan itu ditegakkan oleh database sendiri, bukan cuma diasumsikan oleh kode aplikasi: kombinasi `voucher_id` + `user_id` yang sama tidak akan pernah bisa masuk dua kali, apa pun yang terjadi di level aplikasi. Ini menjadikannya lapisan pertahanan terakhir yang tidak bisa "dilewati" oleh bug logika atau kondisi bersamaan.

### 3. Revano — Integrasi UI & Notifikasi

**Kenapa notifikasi ulasan buruk sebaiknya dikirim otomatis ke admin, bukan mengandalkan admin rutin mengecek satu per satu semua ulasan yang masuk?**

Mengecek manual berarti ada jeda waktu antara ulasan buruk masuk dan admin menyadarinya — bisa berjam-jam atau berhari-hari tergantung seberapa rutin admin mengecek, dan volume ulasan yang besar membuat ulasan penting mudah terlewat di antara ulasan lain. Notifikasi otomatis memastikan masalah kualitas layanan (rating ≤ 2) langsung diketahui saat itu juga, sehingga admin bisa segera menindaklanjuti — misalnya menghubungi pemesan atau mengecek kondisi lapangan — sebelum masalah yang sama berulang ke pemesan berikutnya.

**Kenapa rate limiting pada submit ulasan penting meskipun user sudah login (bukan endpoint publik)?**

Login hanya membuktikan siapa yang mengirim, bukan mencegah pengiriman berulang secara otomatis. Akun yang sudah login tetap bisa disalahgunakan lewat script/bot, atau dipakai untuk spam ulasan (baik menaikkan atau menjatuhkan rating lapangan tertentu secara curang) karena satu booking pun berpotensi dieksploitasi lewat request berulang ke endpoint yang sama. Rate limiting membatasi seberapa cepat satu user bisa mengirim ulasan berturut-turut, sehingga pola spam otomatis bisa dicegah tanpa mengganggu user normal yang secara wajar hanya mengirim ulasan sesekali.
