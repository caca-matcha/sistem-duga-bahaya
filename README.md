# Manual Book: SIDUBA (Sistem Duga Bahaya)

Selamat datang di **SIDUBA (Sistem Duga Bahaya)**. Sistem ini dirancang untuk mempermudah pelaporan, pemantauan, dan penyelesaian temuan bahaya (hazard) di lingkungan kerja demi menciptakan lingkungan kerja yang aman dan sehat.

Panduan ini ditujukan bagi dua aktor utama dalam sistem:
1. **Karyawan / Magang** (Sebagai Pelapor & PIC Tindak Lanjut)
2. **Tim SHE (Safety, Health, and Environment)** (Sebagai Validator & Administrator)

---

## 1. Panduan untuk Karyawan / Magang

Sebagai Karyawan/Magang, Anda berperan sebagai pelapor langsung jika menemukan bahaya di lapangan, dan juga dapat bertindak sebagai PIC (Person In Charge) untuk menyelesaikan suatu temuan bahaya.

### A. Login ke Sistem
1. Buka tautan aplikasi SIDUBA di browser Anda.
2. Masukkan **Email** dan **Password** yang telah didaftarkan.
3. Klik tombol **Login**. Anda akan otomatis diarahkan ke Dashboard Karyawan.

### B. Dashboard Karyawan
Pada halaman utama (Dashboard), Anda dapat melihat statistik laporan Anda:
- Jumlah laporan yang Anda buat.
- Status laporan (Ditinjau, Diproses, Selesai, Ditolak).
- Notifikasi terbaru terkait pembaruan status laporan Anda.

### C. Membuat Laporan Duga Bahaya Baru
Jika Anda menemukan potensi bahaya di lokasi kerja:
1. Klik menu **Lapor Bahaya** (`Lapor Duga Bahaya / Create`).
2. Isi formulir yang disediakan:
   - **Judul/Deskripsi Bahaya**: Jelaskan bahaya yang Anda temukan secara spesifik.
   - **Potensi Risiko**: Risiko apa yang dapat terjadi (misalnya: terpeleset, kebakaran, dll).
   - **Lokasi Utama (Gedung/Area)**: Pilih gedung atau area kejadian (mis: Gedung A, Pabrik B).
   - **Titik Lokasi Detail (Grid/Peta)**: Tentukan titik lokasi spesifik berdasarkan peta.
   - **Foto Bukti**: Unggah foto kondisi bahaya.
3. Klik **Simpan/Kirim Laporan**. Laporan Anda akan masuk dalam status **Menunggu Validasi** oleh SHE.

### D. Memantau Riwayat Laporan
1. Masuk ke menu **Daftar Laporan Saya** (`Riwayat Laporan`).
2. Di sini Anda bisa melihat semua laporan yang pernah Anda buat statusnya saat ini.
3. Klik detail pada salah satu laporan untuk melihat riwayat tindak lanjut atau alasan penolakan (jika ditolak).

### E. Melakukan Tindak Lanjut (Jika Ditunjuk sebagai PIC)
Jika tim SHE menugaskan Anda sebagai PIC penyelesaian masalah:
1. Anda akan mendapat instruksi melalui notifikasi/sistem.
2. Buka detail laporan tersebut.
3. Setelah Anda melakukan tindakan perbaikan, isi **Form Tindak Lanjut**:
   - Jelaskan langkah penyelesaian yang sudah dilakukan.
   - Unggah **Foto Setelah Perbaikan**.
4. Ajukan kembali agar divalidasi dan ditutup (Selesai) oleh tim SHE.

### F. Melihat Peta Bahaya (Hazard Mapping)
1. Buka menu **Peta Bahaya**.
2. Anda dapat melihat denah perusahaan/gedung lengkap dengan titik-titik (grid) bahaya yang dilaporkan atau area yang berisiko tinggi saat ini.

---

## 2. Panduan untuk Tim SHE (Administrator)

Tim SHE memiliki tanggung jawab penuh untuk memvalidasi, menugaskan PIC, dan mengelola master data pada sistem SIDUBA.

### A. Dashboard SHE
Pada dashboard, tim SHE akan disuguhkan:
- Statistik total Duga Bahaya masuk, selesai, dan yang masih berstatus terbuka (open).
- Grafik pelaporan berdasarkan kategori atau periode.
- Akses cepat ke pelaporan yang **Butuh Tindak Lanjut Cepat**.

### B. Validasi & Manajemen Laporan Duga Bahaya
Setiap laporan dari karyawan akan masuk ke menu **Manajemen Hazard**. Tim SHE harus memprosesnya dengan alur berikut:

1. **Laporan Masuk (Menunggu Validasi)**
   - Buka menu `Laporan -> Hazards`.
   - Pilih laporan dengan status *Menunggu Validasi*.
   - **Validasi Teruskan ke PIC**: Jika laporan valid, tim SHE dapat membuat **Rencana Tindakan** dan menugaskan PIC terkait (`Karyawan tertentu atau Departemen`). Status berubah menjadi **Diproses**.
   - **Validasi Langsung (Tanpa Tindak Lanjut)**: Jika bahaya sangat minor dan langsung diselesaikan oleh pelapor / tim yang nemu saat itu juga.
   - **Tolak (Reject)**: Jika laporan tidak valid, tidak masuk akal, atau duplikat. Tim SHE wajib mengisi alasan penolakan.

2. **Memantau Laporan Diproses**
   - PIC akan melaporkan foto perbaikan (After) setelah tugas selesai.
   - Tim SHE memverifikasi foto bukti perbaikan tersebut.
   - Jika sudah sesuai, tim SHE dapat mengubah status menjadi **Selesai**.

### C. Manajemen Peta dan Lokasi (Mapping)
1. **Master Denah/Peta (`Maps`)**: Tim SHE dapat mengunggah file gambar denah gedung.
2. **Konfigurasi Grid**: Peta dapat dibagi dalam beberapa area/grid yang bisa diatur risiko atau ketersediaan aksesnya.
3. **Master Lokasi (`Locations`)**: Tim SHE dapat menambah, mengedit, Import Bulk Excel, atau Export data lokasi spesifik (misal: "Toilet Lantai 1", "Mesin Boiler").

### D. Export Laporan ke Excel
Kapan daja tim SHE membutuhkan report untuk manajemen:
1. Masuk ke halaman **Hazards**.
2. Tersedia fitur **Export Excel Bulk** untuk mengunduh semua data Duga Bahaya (termasuk status, lokasi, tanggal, dan PIC) dalam bentuk Sheet yang siap direkapitulasi.

### E. Manajemen Data Perusahaan & Karyawan
1. **Evaluasi SHE / Company Data**: Mengelola parameter SHE (Kecelakaan kerja, jam kerja aman, dll.) sebagai dashboard manajemen.
2. **Kelola User (Karyawan & Magang)**:
   - Tambah user individu atau Import User sekaligus.
   - Penyesuaian Role (Memberi akses SHE kepada user tertentu bila dibutuhkan, atau sebaliknya).
   
---

### Tips dan Catatan Penting
- **Validasi Segera**: Tim SHE dianjurkan untuk rutin mengecek menu "Need Follow-Up" agar setiap potensi hazard tidak berlarut-larut.
- **Kualitas Foto**: Karyawan dimohon mengunggah foto yang jelas agar tim SHE dapat menganalisis bahaya dengan akurat.
- **Notifikasi**: Selalu cek lonceng sistem (Notifikasi) untuk mengetahui update secara real-time.

---
*Manual Book Update: April 2026*
