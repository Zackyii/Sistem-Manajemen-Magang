# SIMANG — Status Manifest & Project Roadmap

Dokumen ini adalah manifes status teknis, audit kode, dan pencatatan riwayat sesi kerja untuk proyek **SIMANG (Sistem Manajemen Magang) Diskominfo Jatim** berbasis Laravel 12, PHP 8.2, Tailwind CSS v4, Docker, dan PostgreSQL.

Dokumen ini disimpan secara lokal di repositori (`docs/status_manifest.md`) untuk mengamankan konteks proyek dari risiko *session reset* atau limit AI IDE.

---

## 1. Pemetaan Kondisi Kode Saat Ini (Current State)

### A. Fitur Inti & Refactoring yang Telah Selesai
1. **Presensi Mandiri GPS & Kamera (Mahasiswa)**:
   - Pilihan jenis kehadiran 3 tombol: **Hadir**, **Izin**, **Sakit**.
   - Input unggah file bukti (`file_bukti`) untuk izin/sakit (tipe: JPG, JPEG, PNG, PDF maks 5MB).
   - Kamera selfie saat Check-in & Check-out serta deteksi koordinat GPS presisi.
   - Alur verifikasi presensi oleh Mentor telah dialihkan menjadi sistem **Monitoring Kehadiran Realtime** (approve/reject presensi ditiadakan sesuai spesifikasi terbaru).
2. **Logbook Harian Mahasiswa & Review Mentor**:
   - Unggah file lampiran (`file_lampiran`) pada form tambah logbook mahasiswa dengan `enctype="multipart/form-data"`.
   - Card informasi lampiran dokumen dan unduhan aman pada halaman detail logbook mentor (`mentor/logbook/show.blade.php`).
   - Fitur **Persetujuan Massal (Bulk Approve)** logbook oleh mentor dengan proteksi checkbox dan scope bidang.
3. **Penyimpanan Berkas Terproteksi (Private Storage)**:
   - Implementasi `FileController` (`/files/presensi/...` & `/files/logbook/...`) dengan proteksi otentikasi dan hak akses (hanya pemilik, mentor pembimbingnya, dan admin).
   - Fallback otomatis ke disk `public` untuk data lampiran lawas (*backward compatibility*).
4. **Pembersihan Visual & UI**:
   - Menghilangkan titik animasi (`animate-ping`) pada badge "Magang Aktif" di dashboard mahasiswa dan badge status di dashboard mentor.
   - Format kode PHP telah diverifikasi dan lolos Laravel Pint (`vendor/bin/pint --dirty`).

### B. Status Lingkungan & Migrasi Basis Data
- **File Migrasi Baru**:
  - `database/migrations/2026_10_15_030005_add_file_bukti_and_checkout_location_to_kehadiran.php`
  - `database/migrations/2026_10_15_030127_add_file_lampiran_to_tanggalan.php`
- **Catatan Operasional**: User telah mengaktifkan Docker. Migrasi perlu dijalankan di container (`docker compose exec app php artisan migrate`).

---

## 2. Analisis Fitur Chat / Pesan & Rencana Penonaktifan (Comment-Out)

Sesuai arahan, fitur Chat/Pesan **TIDAK DIHAPUS**, melainkan **DINONAKTIFKAN (Comment-Out Only)** agar kode tetap utuh dan dapat diaktifkan kembali sewaktu-waktu.

### A. Inventaris Berkas Terkait Fitur Chat / Pesan
| Kategori | File | Peran / Deskripsi |
|---|---|---|
| **Route** | `routes/web.php` | Rute inbox & kirim pesan untuk Admin (L59-61), Mentor (L87-90), dan Mahasiswa (L121-124). |
| **Model** | `app/Models/Pesan.php` | Model representasi tabel database `pesan`. |
| **Model Relasi** | `app/Models/User.php` | Relasi `pesanTerkirim()` dan `pesanDiterima()` (L71-80). |
| **Controller Mahasiswa** | `app/Http/Controllers/Mahasiswa/PesanController.php` | Method `index()` dan `store()` pengiriman pesan/laporan mahasiswa. |
| **Controller Mentor** | `app/Http/Controllers/Mentor/PesanController.php` | Method `index()`, `store()`, dan `markRead()` pesan mentor. |
| **Controller Admin** | `app/Http/Controllers/Admin/PesanController.php` | Method `index()` dan `markRead()` pesan admin. |
| **View Mahasiswa** | `resources/views/mahasiswa/pesan/index.blade.php` | Form kirim pesan & tabel riwayat pesan mahasiswa. |
| **View Mentor** | `resources/views/mentor/pesan/index.blade.php` | Tampilan kotak masuk & form kirim instruksi mentor. |
| **View Admin** | `resources/views/admin/pesan/index.blade.php` | Tampilan kotak masuk laporan admin. |
| **Layout Mahasiswa** | `resources/views/layouts/mahasiswa.blade.php` | Tombol notifikasi pesan topbar (L33-37), sidebar drawer (L96-99), bottom nav mobile (L159-163). |
| **Layout Mentor** | `resources/views/layouts/mentor.blade.php` | Navigasi mentor (saat ini belum menampilkan link eksplisit ke pesan). |
| **Layout Admin** | `resources/views/layouts/admin.blade.php` | Navigasi admin (saat ini belum menampilkan link eksplisit ke pesan). |
| **Dokumen Panduan** | `resources/views/mahasiswa/panduan/index.blade.php` | Teks penjelasan izin/sakit melalui menu pesan (L76). |

### B. Rencana & Langkah 'Comment-Out' Presisi
1. **Backend Routing (`routes/web.php`)**:
   - Berikan komentar multi-baris `/* ... */` atau `//` pada route group `pesan` di lingkup Admin, Mentor, dan Mahasiswa.
   - Contoh:
     ```php
     // FITUR CHAT / PESAN DINONAKTIFKAN SEMENTARA (COMMENT-OUT ONLY)
     // Route::get('/pesan', [Mahasiswa\PesanController::class, 'index'])->name('pesan.index');
     // Route::post('/pesan', [Mahasiswa\PesanController::class, 'store'])->name('pesan.store');
     ```
   - Dengan mengomentari route, akses langsung via URL browser akan dicegah secara aman tanpa merusak struktur class controller maupun database.

2. **Frontend UI Mahasiswa (`resources/views/layouts/mahasiswa.blade.php`)**:
   - **Topbar**: Komentari icon tautan `mahasiswa.pesan.index` menggunakan tag Blade `{{-- ... --}}`.
   - **Sidebar Drawer**: Komentari item navigasi "Pesan & Laporan" menggunakan `{{-- ... --}}`.
   - **Bottom Navigation Mobile**:
     - Semula terdapat 5 item: *Home*, *Presensi*, *Logbook*, *Kalender*, *Pesan*.
     - Sembunyikan item *Pesan* menggunakan `{{-- ... --}}` dan pastikan grid flex container tetap seimbang untuk 4 menu (*Home*, *Presensi*, *Logbook*, *Kalender*).
     - Desain visual bottom nav tetap rapi dan tombol tengah (*Presensi*) tetap menjadi focal point.

3. **Controller, Model, & View File**:
   - Seluruh berkas PHP Controller, Model `Pesan`, dan file Blade view `pesan/index.blade.php` dibiarkan utuh di direktori masing-masing.
   - Tidak ada migration penghapusan tabel `pesan` (data dan struktur schema database tetap dipertahankan).

---

## 3. Analisis Komponen Badge Warna & Rasio Kontras (Color Contrast)

### A. Komponen Utama
- `resources/views/components/badge.blade.php`: Komponen Blade reusable untuk menampilkan status (`hadir`, `izin`, `sakit`, `terlambat`, `alpha`, `disetujui`, `ditolak`, `pending`).
- `resources/css/app.css`: Utility CSS `.badge-*` (`.badge-hadir`, `.badge-izin`, `.badge-pending`, dll.).

### B. Area Perbaikan Kontras (A11y / WCAG AA)
1. **Status Menunggu / Pending / Terlambat**:
   - Saat ini: `bg-amber-50 text-amber-700` atau `bg-yellow-100 text-yellow-700`.
   - Isu: Pada monitor dengan saturasi atau *brightness* tinggi, teks `amber-700` atau `yellow-700` di atas latar kuning pucat dapat memiliki rasio kontras di bawah standar WCAG AA (4.5:1).
   - Solusi: Tingkatkan ketajaman teks ke `text-amber-900` atau `text-amber-800` pada light mode dengan border `border-amber-300`, serta penyesuaian dark mode (`dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800/60`).
2. **Status Izin & Info**:
   - Pastikan teks biru menggunakan `text-blue-800` atau `text-sky-900` pada latar cerah untuk keterbacaan optimal.
3. **Standardisasi Pemanggilan**:
   - Mengalihkan badge statis di tabel admin/mentor yang masih memakai class HTML manual agar konsisten menggunakan `<x-badge :status="..." />`.

---

## Catatan Sesi Chat Terkini

> **PENTING**: Bagian ini diperbarui secara berkala pada setiap akhir penanganan instruksi untuk menjaga catatan interaksi, keputusan teknis, dan langkah kerja yang disepakati bersama user.

### Sesi: 20 Oktober 2026 (Transisi ke Gemini)
- **Status Transisi**:
  - User berpindah dari Claude Sonnet 4.6 ke Gemini karena kendala batas kuota (limit).
  - Transkrip percakapan sebelumnya sempat ter-reset pada UI IDE.
- **Instruksi & Keputusan User**:
  1. Melakukan pemindaian menyeluruh terhadap file proyek Laravel, fokus pada modul Chat/Pesan dan badge warna.
  2. Membuat berkas manifes lokal `docs/status_manifest.md` sebagai *single source of truth* status proyek.
  3. Menegaskan aturan **"Comment-Out Only"** untuk fitur Chat/Pesan (tidak boleh menghapus kode backend maupun file view, cukup menonaktifkan eksekusi dan menyembunyikan elemen UI).
  4. Menyiapkan mitigasi tata letak UI (terutama mobile bottom nav) saat menu chat disembunyikan agar tampilan tidak rusak atau timpang.
  5. Memelihara section *"## Catatan Sesi Chat Terkini"* di dalam manifes ini secara berkelanjutan setiap kali ada instruksi atau perubahan kode baru.
- **Tindakan yang Telah Dilakukan**:
  - File `docs/status_manifest.md` dibuat dengan audit lengkap rute, controller, model, view, layout, dan rencana aksi teknis.
  - Struktur bottom navigation dan layout telah dianalisis untuk memastikan penonaktifan chat berjalan mulus.

### Sesi: 2 November 2026 (Lanjutan — Claude Sonnet 4.6)
- **Status**: Melanjutkan dari sesi sebelumnya. Docker tidak aktif (daemon tidak berjalan), Pint dijalankan langsung via local PHP.
- **Tindakan yang Diselesaikan**:
  1. ✅ **Comment-out route chat** di `routes/web.php` untuk scope Admin (L59-61), Mentor (L87-90), dan Mahasiswa (L121-123). Semua controller, model, dan view file dibiarkan utuh.
  2. ✅ **Sembunyikan UI chat** di `resources/views/layouts/mahasiswa.blade.php`:
     - Topbar: icon notifikasi pesan (bell) dibungkus `{{-- ... --}}`.
     - Sidebar Drawer: item navigasi "Pesan & Laporan" dibungkus `{{-- ... --}}`.
     - Bottom Nav Mobile: item "Pesan" dibungkus `{{-- ... --}}`. Bottom nav kini 4 item seimbang: *Home*, *Presensi* (focal point), *Logbook*, *Kalender*.
  3. ✅ **Perbaikan kontras badge** di `resources/views/components/badge.blade.php`:
     - Semua warna teks dinaikan ke `-800` untuk memenuhi WCAG AA (≥4.5:1).
     - `pending`/`terlambat`: `amber-700` → `amber-800` (≈5.8:1 ✓).
     - `izin`/`draft`: `blue-700` → `blue-800` (≈6.5:1 ✓).
     - `hadir`/`disetujui`: `emerald-700` → `emerald-800` (≈6.2:1 ✓).
     - `sakit`: `purple-700` → `purple-800` (≈6.1:1 ✓).
     - `ditolak`/`alpha`: `rose-700` → `rose-800`.
     - Ring amber untuk pending/terlambat disesuaikan agar tidak terlalu berat.
  4. ✅ **Laravel Pint** dijalankan → `passed` (0 perubahan diperlukan).
- **Catatan Migrasi**: Docker tidak aktif saat sesi ini. Migrasi yang sudah dibuat (`add_file_bukti_and_checkout_location_to_kehadiran`, `add_file_lampiran_to_tanggalan`) masih perlu dijalankan saat Docker aktif: `docker compose exec app php artisan migrate`.
- **Status Proyek Saat Ini**: **SEMUA ITEM DARI RENCANA AKSI TEKNIS TELAH SELESAI.** Tidak ada item pending yang tersisa dari roadmap manifes ini.
- **Langkah Selanjutnya (Atas Inisiatif User)**:
  - Jalankan migrasi saat Docker aktif.
  - Jalankan `npm run build` atau `composer run dev` jika ada perubahan frontend yang belum terefleksikan di browser.
  - Konfirmasi visual di browser untuk bottom nav 4-item dan badge kontras baru.
  - docker compose exec app php artisan migrate   jalankan command ini sebelum start


