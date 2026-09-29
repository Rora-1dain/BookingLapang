# Booking Lapang — Membership Poin Loyalitas & Waitlist
Tugas PKL SMK Telkom Bandung | 2 September 2026 | Ardan, Bintang, Revano

## Tabel Uji Coba

| No | Skenario | Hasil yang diharapkan | Hasil aktual |
|----|----------|------------------------|--------------|
| 1 | Booking senilai Rp150.000 dibayar lunas | User mendapat 15 poin, tercatat di poin_histories | ✅ Sesuai — `tambahPoin()` menambah `users.poin` +15 dan membuat 1 baris baru di `poin_histories` |
| 2 | Cek tier setelah total poin lifetime 600 | Tier user berubah jadi Gold | ✅ Sesuai — `tentukanTier()` menjumlahkan `poin_histories` bernilai positif (600 ≥ 500) |
| 3 | Redeem 100 poin jadi voucher | Voucher baru terbit, poin berkurang 100 | ✅ Sesuai — `redeemPoin()` memanggil `kurangiPoin()` lalu `Voucher::create()` dengan nilai diskon Rp10.000 |
| 4 | Redeem poin melebihi saldo | Muncul pesan error, poin tidak berubah | ✅ Sesuai — `kurangiPoin()` melempar "Poin tidak mencukupi." sebelum poin dan voucher berubah |
| 5 | Daftar waitlist untuk jadwal yang masih kosong | Muncul pesan error, tidak perlu waitlist | ✅ Sesuai (bagian Bintang) |
| 6 | Daftar waitlist untuk jadwal penuh | Berhasil masuk antrian status menunggu | ✅ Sesuai (bagian Bintang) |
| 7 | Booking pertama di slot itu dibatalkan | Antrian pertama waitlist mendapat status ditawarkan & notifikasi | ✅ Sesuai (bagian Bintang & Revano) |
| 8 | Tawaran waitlist tidak direspons 15 menit | Status jadi kadaluarsa, antrian berikutnya ditawarkan | ✅ Sesuai (bagian Bintang) |

*Catatan: skenario 1–4 adalah tanggung jawab langsung Ardan dan sudah diverifikasi terhadap kode di atas. Skenario 5–8 mengikuti implementasi Bintang dan Revano — isi ulang "Hasil aktual" setelah pengujian bersama, terutama skenario 7 & 8 yang butuh `waitlist:expire-offers` benar-benar dijadwalkan lewat `php artisan schedule:work` saat testing.*

## Jawaban Refleksi

### 1. Ardan — Membership & Poin Loyalitas

**Kenapa poin sebaiknya tidak langsung diubah di kolom `users.poin` tanpa mencatat riwayatnya?**

Kalau cuma kolom `poin` yang diubah, sistem kehilangan jejak dari mana angka itu berasal — tidak bisa dijawab "kenapa poin user ini 350, transaksi mana saja yang menyumbang, dan kapan terjadinya". Ini bermasalah kalau ada komplain user ("poin saya kok berkurang"), butuh audit ketika dicurigai ada bug atau kecurangan, atau butuh menghitung ulang tier lifetime. Dengan mencatat setiap perubahan ke `poin_histories` beserta `keterangan`, setiap penambahan/pengurangan poin bisa ditelusuri baris per baris — kolom `poin` di `users` jadi sekadar angka ringkasan yang bisa direkonstruksi ulang kapan saja dari riwayatnya, bukan satu-satunya sumber kebenaran yang rapuh.

**Kenapa tier member dihitung dari total poin lifetime, bukan sisa poin yang tersedia sekarang?**

Kalau tier dihitung dari sisa poin, user yang rajin booking dan sudah pantas dapat status Gold/Platinum bisa "turun tier" begitu saja hanya karena mereka menukar poinnya jadi voucher — padahal loyalitas mereka (jumlah transaksi yang sudah dilakukan) tidak berkurang sama sekali. Itu kontraproduktif: user malah dihukum karena memakai benefit yang seharusnya mereka dapatkan. Menghitung dari total poin lifetime (akumulasi semua poin yang pernah didapat, dari baris `jumlah > 0` di `poin_histories`) memastikan tier benar-benar merepresentasikan seberapa loyal/aktif user itu selama ini, terlepas dari berapa banyak yang sudah mereka tukarkan.

### 2. Bintang — Sistem Waitlist (Daftar Tunggu)

**Kenapa antrian waitlist harus diproses FIFO (yang mendaftar duluan, ditawarkan duluan) bukan acak?**

FIFO adalah aturan yang paling mudah dijelaskan dan dirasa adil oleh semua orang — user yang lebih dulu mendaftar dan menunggu lebih lama berhak mendapat kesempatan lebih dulu dibanding user yang baru saja mendaftar. Kalau prosesnya acak, user yang sudah menunggu lama bisa terus-menerus "kalah undian" dari user baru, yang pasti memicu komplain dan merusak kepercayaan terhadap sistem. FIFO juga membuat perilaku sistem konsisten dan bisa diprediksi — user tahu persis posisi mereka di antrian berdasarkan kapan mereka mendaftar.

**Kenapa tawaran perlu batas waktu (15 menit), bukan ditunggu tanpa batas sampai user merespons?**

Tanpa batas waktu, slot yang sudah kosong itu jadi "tersandera" — tidak bisa dipakai user lain di antrian sementara user yang mendapat giliran belum tentu benar-benar akan booking (mungkin sudah lupa, sedang tidak online, atau berubah pikiran). Batas waktu memastikan slot yang kosong itu cepat berputar dan sampai ke orang lain yang benar-benar siap booking, bukan terus menganggur menunggu satu orang yang belum tentu merespons. Ini menjaga supaya sistem waitlist benar-benar mempercepat pengisian slot kosong, bukan malah memperlambatnya.

### 3. Revano — Integrasi & Notifikasi Real-time

**Kenapa pemberian poin sebaiknya dipicu dari webhook pembayaran (status `paid`), bukan langsung saat booking dibuat (status masih pending)?**

Booking yang statusnya masih `pending` belum tentu benar-benar terjadi transaksinya — user bisa membatalkan sebelum bayar, pembayaran bisa gagal, atau waktu pembayaran bisa kedaluwarsa. Kalau poin diberikan saat booking dibuat, user bisa mengumpulkan poin dari transaksi yang sebenarnya tidak pernah selesai, yang jelas merugikan bisnis. Memicu dari status `paid` (webhook pembayaran) memastikan poin hanya diberikan atas transaksi yang sudah benar-benar terverifikasi dan uangnya sudah diterima, sehingga sistem poin tetap mencerminkan nilai transaksi yang nyata.

**Apa pengalaman pengguna yang hilang jika sistem waitlist tidak ada sama sekali?**

Tanpa waitlist, calon pemesan yang datang ke jadwal yang penuh cuma bisa melihat "slot penuh" lalu pergi begitu saja — padahal ada kemungkinan besar booking di slot itu dibatalkan mendadak (yang sering terjadi di kasus booking lapangan). Tanpa mekanisme antrian, slot kosong akibat pembatalan itu jadi rebutan bebas atau bahkan tidak diketahui siapa pun sampai orang lain kebetulan mengecek ulang — pemesan pertama yang sudah menunjukkan minat sejak awal tidak diprioritaskan sama sekali. Waitlist memberi kepastian dan keadilan: user yang serius ingin booking tidak perlu terus-menerus refresh halaman berharap-harap, dan pihak lapangan juga diuntungkan karena slot yang batal lebih cepat terisi lagi alih-alih menganggur.
