# SIMANG — UI IMPROVEMENT, NAVIGATION FIX & ATTENDANCE CSV ENHANCEMENT

## ROLE

Act as a Senior Laravel Developer, Frontend Developer, and UI/UX Specialist.

Saya sedang mengembangkan aplikasi **SIMANG (Sistem Manajemen Magang)** menggunakan Laravel, PHP 8.2, PostgreSQL, Docker Compose, Nginx, dan PHP-FPM.

Saya ingin menyempurnakan tampilan dan beberapa fitur yang sudah ada tanpa merusak struktur maupun fungsionalitas aplikasi.

Project ini sudah berjalan. Oleh karena itu, prioritas utama adalah mempertahankan seluruh fungsi yang sudah stabil.

**JANGAN langsung melakukan perubahan. Lakukan audit source code, tampilkan temuan dan rekomendasi, kemudian tunggu persetujuan saya sebelum mengedit file.**

---

# BAGIAN 1 — RUANG LINGKUP PEKERJAAN

Terdapat empat kelompok pekerjaan:

1. Perbaikan konsistensi warna Dark Mode dan Light Mode.
2. Perbaikan tampilan halaman login dan keterbacaan teks.
3. Penyempurnaan navigasi desktop serta tombol logout.
4. Penambahan informasi persentase status kehadiran pada ekspor CSV yang sudah tersedia.

Semua pekerjaan harus mengikuti implementasi aktual yang ditemukan di dalam project.

Jangan membuat fitur baru yang tidak disebutkan dalam instruksi ini.

---

# BAGIAN 2 — PERBAIKAN DARK MODE DAN LIGHT MODE

## A. Keluhan Dark Mode

Saat Dark Mode aktif, beberapa komponen masih menggunakan warna Light Mode.

Contoh masalah yang ditemukan:

- Card masih berwarna putih.
- Form penugasan Mentor masih memiliki background putih.
- Riwayat penugasan masih memiliki background putih.
- Input dan textarea belum mengikuti tema.
- Teks pada beberapa background gelap masih berwarna gelap.
- Label, placeholder, dan deskripsi sulit dibaca.
- Nested card atau panel di dalam card belum mengikuti Dark Mode.
- Beberapa komponen dashboard masih putih.
- Tabel dan elemen pendukung perlu diperiksa.

### Target perbaikan

| Komponen | Dark Mode |
|---|---|
| Background utama | Gelap |
| Navbar | Gelap dan konsisten |
| Card utama | Dark slate/navy |
| Nested card | Sedikit lebih terang |
| Input | Gelap |
| Textarea | Gelap |
| Dropdown | Gelap |
| Primary text | Putih/hampir putih |
| Secondary text | Abu-abu terang |
| Placeholder | Abu-abu yang terbaca |
| Border | Abu-abu gelap |
| Button | Memiliki kontras yang jelas |
| Badge | Tetap mudah dibedakan |

Jangan menggunakan hitam murni pada seluruh komponen karena akan menghilangkan hierarki visual.

## B. Keluhan Light Mode

Tampilan Light Mode juga perlu diperbaiki karena beberapa warna terlihat kurang harmonis.

Pastikan:

- Background utama nyaman dilihat.
- Card tidak seluruhnya terlihat menyatu dengan background.
- Nested card memiliki perbedaan tingkat warna.
- Teks utama berwarna gelap.
- Teks sekunder tetap terbaca.
- Border tidak terlalu kontras tetapi tetap terlihat.
- Tombol memiliki warna yang konsisten.
- Gradien banner lebih harmonis.
- Tidak ada teks yang menghilang karena warna foreground dan background terlalu mirip.

## C. Referensi Palet Warna

Gunakan palet berikut sebagai referensi awal.

**Light Mode:**

| Elemen | Warna |
|---|---|
| Background | #F8FAFC |
| Card | #FFFFFF |
| Nested card | #F1F5F9 |
| Input | #FFFFFF |
| Primary text | #0F172A |
| Secondary text | #475569 |
| Muted text | #64748B |
| Border | #E2E8F0 |
| Primary button | #2563EB |
| Button hover | #1D4ED8 |

**Dark Mode:**

| Elemen | Warna |
|---|---|
| Background | #020617 |
| Navbar | #0F172A |
| Card | #0F172A |
| Nested card | #1E293B |
| Input | #1E293B |
| Primary text | #F8FAFC |
| Secondary text | #CBD5E1 |
| Muted text | #94A3B8 |
| Border | #334155 |
| Primary button | #2563EB |
| Button hover | #1D4ED8 |

Palet ini bukan kewajiban mutlak. Periksa identitas visual SIMANG dan implementasi tema yang sudah ada sebelum memutuskan warna akhir.

Pertahankan warna status seperti:

- Hadir
- Terlambat
- Izin
- Sakit
- Pending
- Disetujui
- Ditolak
- Tidak Hadir

Jangan menghilangkan warna identitas status yang memang memiliki fungsi informatif.

---

# BAGIAN 3 — PERBAIKAN LOGIN

Terdapat keluhan bahwa tulisan pada halaman login tidak terlihat karena kombinasi warna teks dan background yang kurang sesuai.

Audit seluruh komponen halaman login.

Periksa:

- Background halaman.
- Card login.
- Judul login.
- Label input.
- Placeholder.
- Input username.
- Input password.
- Link bantuan atau lupa password jika tersedia.
- Pesan validasi.
- Alert error.
- Tombol Masuk.
- Hover dan focus state.

### Target

Teks harus terlihat jelas pada kedua tema.

Secara khusus, perbaiki tombol Masuk.

Jangan pernah menggunakan teks putih pada tombol dengan background putih atau warna yang terlalu terang.

Gunakan warna tombol yang solid dengan kontras memadai.

Pertahankan seluruh proses authentication, route, validasi, dan mekanisme login.

**Jangan mengubah AuthController, middleware, authentication flow, ataupun business logic.**

Pekerjaan ini hanya berkaitan dengan tampilan.

---

# BAGIAN 4 — PENYEMPURNAAN NAVIGASI DESKTOP

## A. Masalah Saat Ini

Tampilan navigation bar pada layar desktop terlihat kurang rapi dan kurang nyaman digunakan.

Saya ingin pola navigasi desktop dibuat menyerupai referensi visual yang saya lampirkan, yaitu halaman Portal ITSA.

Referensi tersebut memiliki karakteristik:

- Sidebar vertikal di sebelah kiri.
- Sidebar tetap berada pada posisinya.
- Logo dan nama aplikasi berada di bagian atas.
- Menu navigasi tersusun vertikal.
- Menu aktif memiliki background yang jelas.
- Icon dan teks menu terlihat.
- Informasi profil pengguna berada di bagian bawah.
- Tombol logout berada di area profil bagian bawah.
- Konten utama ditampilkan di sisi kanan sidebar.

## B. Perilaku yang Diinginkan

Pada desktop:

1. Sidebar harus menggunakan posisi **fixed atau sticky** sehingga tetap terlihat ketika pengguna melakukan scroll.
2. Sidebar tidak ikut bergeser bersama konten utama.
3. Konten utama tidak boleh tertutup oleh sidebar.
4. Sidebar memiliki tinggi yang mengikuti viewport.
5. Area menu tetap dapat digunakan ketika daftar menu panjang.
6. Area profil pengguna dan logout tetap berada di bagian bawah sidebar jika struktur yang sekarang memungkinkan.
7. Menu aktif memiliki penanda visual yang jelas.
8. Transisi hover tetap nyaman.
9. Tampilan sidebar mengikuti identitas visual SIMANG.

**Referensi visual adalah pola navigasi Portal ITSA yang saya lampirkan, bukan instruksi untuk menyalin desain atau merek Portal ITSA.**

Pertahankan logo, nama aplikasi, menu, icon, dan identitas SIMANG yang sudah ada.

## C. Responsive Design

Perubahan fixed sidebar hanya berlaku untuk desktop.

Untuk ukuran tablet dan mobile:

- Pertahankan mekanisme hamburger/collapsible yang sudah ada jika berfungsi dengan baik.
- Jangan membuat sidebar fixed yang menutupi seluruh halaman.
- Jangan menghilangkan fungsi buka/tutup menu.
- Jangan mengganggu responsive layout yang sudah berjalan.

Sebelum implementasi, periksa breakpoint dan mekanisme responsive project.

## D. Batasan Perubahan Navigasi

Perubahan layout hanya diperbolehkan pada area navigation/sidebar yang memang menjadi ruang lingkup pekerjaan ini.

Perubahan yang diizinkan:

- Mengatur posisi fixed/sticky sidebar.
- Mengatur tinggi dan perilaku scroll sidebar.
- Mengatur agar konten utama tidak tertutup sidebar.
- Penyesuaian ruang konten utama yang secara teknis diperlukan akibat sidebar fixed.
- Penyesuaian responsive sidebar.
- Warna background, teks, border, dan active state sidebar.

Jangan merombak layout dashboard, form, tabel, atau komponen lain.

Jangan mengubah susunan menu atau route navigasi.

---

# BAGIAN 5 — MENYAMAKAN TOMBOL LOGOUT

## A. Masalah Saat Ini

Tampilan tombol logout pada role Mentor dan Super Admin terlihat kurang rapi atau tidak konsisten.

Sementara itu, tampilan tombol logout pada role Mahasiswa sudah terlihat bagus dan ingin dijadikan referensi.

## B. Instruksi Perbaikan

Cari implementasi tombol logout di:

- Dashboard/layout Mahasiswa.
- Dashboard/layout Mentor.
- Dashboard/layout Super Admin.

Gunakan tombol logout Mahasiswa yang sudah ada sebagai acuan desain.

Samakan karakteristik visualnya, termasuk:

- Ukuran tombol.
- Icon.
- Alignment.
- Padding.
- Border radius.
- Background.
- Warna icon.
- Warna teks.
- Hover state.
- Focus state.
- Responsive behavior.

Gunakan pola desain yang sama di Mentor dan Super Admin.

**Jangan mengganti fungsi logout.**

Pertahankan:

- Route logout.
- HTTP method.
- CSRF protection.
- Middleware.
- Authentication handling.
- Session invalidation.
- Redirect setelah logout.

Jika tombol logout Mahasiswa menggunakan komponen Blade bersama, evaluasi kemungkinan menggunakan komponen tersebut tanpa melakukan refactor yang tidak diperlukan.

Jangan mengubah logic hanya untuk menyamakan tampilan.

Pastikan istilah role dan nama route mengikuti struktur aktual project. Jangan mengasumsikan penamaan Super Admin jika ternyata project menggunakan nama role yang berbeda.

---

# BAGIAN 6 — PENAMBAHAN FITUR PERSENTASE KEHADIRAN PADA EKSPOR CSV

## A. Tujuan

Saat ini project sudah memiliki fitur ekspor CSV untuk data kehadiran.

Saya ingin menambahkan informasi statistik persentase kehadiran mahasiswa selama masa magang.

Informasi yang diinginkan:

1. Persentase Hadir.
2. Persentase Tidak Hadir.
3. Persentase Izin.
4. Persentase Sakit.

Tujuannya agar hasil ekspor CSV tidak hanya menampilkan catatan kehadiran, tetapi juga memberikan gambaran tingkat kehadiran masing-masing mahasiswa selama magang.

**Gunakan fitur ekspor CSV yang sudah tersedia. Jangan membuat sistem ekspor baru apabila fitur saat ini dapat dikembangkan.**

## B. Audit Sebelum Implementasi

Temukan implementasi ekspor CSV yang sebenarnya.

Periksa:

- Route export.
- Controller yang menjalankan export.
- Service/export class jika ada.
- Model Kehadiran.
- Relasi Mahasiswa.
- Field status kehadiran.
- Field tanggal kehadiran.
- Data tanggal mulai dan selesai magang.
- Data jadwal/hari kerja jika tersedia.
- Filter export.
- Format CSV saat ini.
- Scope data berdasarkan role pengguna.

Jangan menebak nama tabel, kolom, relasi, atau enum.

Gunakan struktur aktual project.

## C. Definisi Status

Identifikasi status yang benar-benar digunakan oleh aplikasi.

Kelompokkan menjadi kategori berikut:

| Kategori Statistik | Makna |
|---|---|
| Hadir | Mahasiswa tercatat hadir |
| Tidak Hadir | Mahasiswa tidak masuk tanpa keterangan yang sah |
| Izin | Mahasiswa tidak masuk dengan status izin |
| Sakit | Mahasiswa tidak masuk karena sakit |

Periksa apakah sistem memiliki status tambahan, misalnya:

- Terlambat.
- Pending.
- Ditolak.
- Alpha.
- Dispensasi.
- Status lain.

Jangan menganggap semua status dapat langsung dimasukkan ke kategori di atas.

Tentukan pemetaannya berdasarkan business logic aktual.

Contohnya, jika Terlambat tetap dianggap sebagai hari hadir, masukkan ke kelompok Hadir dan jelaskan keputusan tersebut dalam laporan.

Status Pending atau status yang belum tervalidasi tidak boleh asal dimasukkan sebagai Hadir atau Tidak Hadir.

## D. Definisi Total Hari sebagai Penyebut Persentase

Ini adalah bagian yang sangat penting.

Persentase harus menggunakan denominator yang benar.

Periksa terlebih dahulu apakah aplikasi:

1. Membuat record kehadiran untuk setiap hari kerja, termasuk ketika mahasiswa tidak hadir.
2. Hanya membuat record ketika mahasiswa melakukan check-in.
3. Memiliki jadwal kerja atau kalender hari kerja.
4. Menyimpan periode mulai dan selesai magang.
5. Memiliki mekanisme penentuan hari libur.

Gunakan dasar perhitungan yang benar-benar tersedia dalam sistem.

**Jangan menghitung persentase Tidak Hadir hanya berdasarkan record yang tidak ditemukan tanpa memastikan bahwa tidak adanya record memang berarti tidak hadir.**

Apabila data kehadiran hanya menyimpan hari saat mahasiswa melakukan presensi, maka absensi yang tidak tercatat tidak dapat langsung dianggap sebagai Tidak Hadir tanpa mengetahui jadwal hari kerja yang seharusnya.

Jika belum tersedia informasi yang cukup untuk menentukan total hari kerja:

- Jangan mengarang jumlah hari kerja.
- Jangan menggunakan jumlah seluruh hari kalender secara otomatis.
- Jangan menganggap Sabtu dan Minggu sebagai hari libur tanpa bukti konfigurasi.
- Laporkan keterbatasan data.
- Ajukan metode perhitungan yang paling tepat untuk disetujui sebelum implementasi.

Sebelum mengubah kode, tampilkan definisi denominator yang akan digunakan.

## E. Rumus Persentase

Setelah denominator disetujui dan sumber datanya tervalidasi, gunakan prinsip berikut:

Persentase Hadir:

`(Jumlah Hari Hadir / Total Hari yang Diperhitungkan) × 100%`

Persentase Tidak Hadir:

`(Jumlah Hari Tidak Hadir / Total Hari yang Diperhitungkan) × 100%`

Persentase Izin:

`(Jumlah Hari Izin / Total Hari yang Diperhitungkan) × 100%`

Persentase Sakit:

`(Jumlah Hari Sakit / Total Hari yang Diperhitungkan) × 100%`

Bulatkan hasil menjadi dua angka desimal.

Tangani denominator nol agar tidak terjadi division by zero.

Jika seluruh kategori sudah saling eksklusif dan mencakup semua hari yang diperhitungkan, total persentase seharusnya mendekati 100%, dengan kemungkinan selisih sangat kecil akibat pembulatan.

Jangan memaksakan total 100% jika terdapat status yang belum dapat diklasifikasikan. Laporkan status tersebut secara terpisah.

## F. Informasi yang Ditambahkan ke CSV

Pertahankan seluruh kolom ekspor CSV yang sudah ada.

Usulkan penambahan kolom statistik seperti:

- Total Hari Diperhitungkan.
- Jumlah Hadir.
- Persentase Hadir.
- Jumlah Tidak Hadir.
- Persentase Tidak Hadir.
- Jumlah Izin.
- Persentase Izin.
- Jumlah Sakit.
- Persentase Sakit.

Sesuaikan nama kolom dengan format aplikasi.

Periksa apakah format export sekarang berupa:

- Satu baris untuk setiap record kehadiran.
- Satu baris ringkasan per mahasiswa.
- Format lain.

Jangan mengubah granularitas atau menghapus kolom yang telah digunakan sebelumnya tanpa persetujuan.

Jika kolom ringkasan mahasiswa perlu ditambahkan pada export yang berisi banyak baris per mahasiswa, berikan rekomendasi format yang paling mudah digunakan dan dianalisis.

Pastikan hasilnya tetap kompatibel dengan Excel dan aplikasi spreadsheet umum.

Gunakan format angka yang konsisten, misalnya:

`85.50%`

Jangan mengubah tanggal, nama mahasiswa, atau data lama yang sudah diekspor.

## G. Filter dan Hak Akses

Pastikan perhitungan statistik mengikuti filter export yang saat ini digunakan.

Jika export memiliki filter:

- Mahasiswa.
- Bidang.
- Periode.
- Tanggal.
- Status.

Periksa bagaimana filter tersebut berinteraksi dengan data persentase.

Jangan sampai jumlah statistik dihitung dari periode penuh sementara export menampilkan periode yang berbeda tanpa penjelasan.

Jika laporan ditujukan untuk satu periode tertentu, statistik harus menggunakan periode yang sama.

Pertahankan batasan akses berdasarkan role.

Jangan membuat Mentor dapat mengekspor data mahasiswa dari bidang lain.

Jangan memberikan Super Admin atau Admin akses baru yang tidak ada sebelumnya hanya karena penambahan fitur.

---

# BAGIAN 7 — AUDIT SOURCE CODE WAJIB

Sebelum implementasi, lakukan audit terhadap:

### A. Struktur frontend

- package.json.
- Konfigurasi Tailwind jika ada.
- vite.config.js.
- resources/css/.
- resources/js/.
- resources/views/layouts/.
- resources/views/components/.

### B. Halaman

- Login.
- Dashboard Mahasiswa.
- Dashboard Mentor.
- Dashboard Admin.
- Dashboard Super Admin jika tersedia.
- Halaman Penugasan Mentor.
- Presensi Mentor.
- Logbook Mentor.
- Halaman ekspor kehadiran.
- Layout sidebar dan navbar.
- Komponen profil pengguna/logout.

Sesuaikan dengan struktur aktual.

### C. Backend ekspor

- routes/web.php.
- Controller export.
- Export class/service.
- Model Kehadiran.
- Model Mahasiswa.
- Model Mentor.
- Model User.
- Migration terkait.
- Seeder terkait.
- Relationship dan scope yang relevan.

### D. Mekanisme tema

Identifikasi cara aplikasi mengelola Dark Mode dan Light Mode.

Jangan mengubah mekanisme theme switching yang sudah berfungsi.

---

# BAGIAN 8 — TAHAP PERTAMA: LAPORAN SEBELUM IMPLEMENTASI

Setelah audit, tampilkan laporan dengan struktur berikut.

## 1. Temuan UI

| Halaman | File | Komponen | Masalah | Usulan |
|---|---|---|---|---|

Masukkan masalah aktual yang ditemukan.

## 2. Temuan Navigasi

Jelaskan:

- Struktur sidebar sekarang.
- Kenapa tampilannya kurang baik di desktop.
- File yang mengatur navigasi.
- Rekomendasi fixed/sticky sidebar.
- Dampak terhadap area konten utama.
- Perilaku mobile yang perlu dipertahankan.

## 3. Temuan Tombol Logout

Jelaskan:

- File logout Mahasiswa.
- File logout Mentor.
- File logout Super Admin.
- Perbedaan desain yang ditemukan.
- Cara paling minimal untuk menyamakan desain tanpa mengubah fungsinya.

## 4. Temuan Ekspor CSV

Jelaskan:

- Route export.
- Controller/export class.
- Struktur kolom CSV sekarang.
- Status kehadiran yang tersedia.
- Relasi dan sumber data.
- Periode magang yang tersedia.
- Apakah data Tidak Hadir bisa dihitung secara akurat.
- Denominator yang direkomendasikan.
- Rumus untuk setiap kategori.
- Usulan kolom baru.

## 5. Daftar File yang Akan Dimodifikasi

Tampilkan seluruh file yang diperkirakan perlu diubah beserta alasan perubahan.

Pisahkan antara:

- File UI.
- File navigasi.
- File logout.
- File export.
- File CSS/JavaScript jika memang diperlukan.

Jangan memasukkan file yang tidak relevan.

## 6. Rekomendasi Tambahan

Jika ada kekurangan desain atau fitur lain yang terlihat selama audit, sampaikan sebagai rekomendasi opsional.

Jangan langsung menerapkannya.

---

# BAGIAN 9 — PERSETUJUAN SEBELUM IMPLEMENTASI

Setelah laporan selesai, **BERHENTI**.

Jangan mengedit file apa pun.

Tunggu persetujuan eksplisit dari saya.

Saya akan memberikan instruksi:

`SETUJU, LANJUTKAN IMPLEMENTASI`

Jika saya belum memberikan persetujuan tersebut, jangan melakukan perubahan.

Jika definisi persentase Tidak Hadir belum dapat dipastikan, jelaskan masalahnya dan minta keputusan saya mengenai metode yang akan digunakan. Jangan mengimplementasikan asumsi sendiri.

---

# BAGIAN 10 — BATASAN IMPLEMENTASI

Setelah disetujui, implementasikan hanya perubahan yang sudah disepakati.

## A. Perubahan UI yang diizinkan

- Background color.
- Text color.
- Border color.
- Shadow color.
- Placeholder color.
- Button color.
- Icon color.
- Badge color.
- Hover/focus color.
- Gradient.
- Dark Mode class.
- Light Mode class.

## B. Perubahan layout yang diizinkan secara khusus

Hanya untuk navigasi desktop:

- Fixed/sticky sidebar.
- Penyesuaian tinggi sidebar.
- Penyesuaian scroll sidebar.
- Penyesuaian ruang konten utama agar tidak tertutup.
- Penyesuaian responsive sidebar.

Tidak boleh merombak layout halaman lainnya.

## C. Perubahan fungsional yang diizinkan secara khusus

Hanya untuk fitur statistik pada ekspor CSV yang sudah ada:

- Menghitung jumlah Hadir.
- Menghitung jumlah Tidak Hadir.
- Menghitung jumlah Izin.
- Menghitung jumlah Sakit.
- Menghitung persentase setiap kategori.
- Menambahkan kolom statistik ke output CSV.

Perubahan hanya boleh dilakukan pada logic export dan query/data retrieval yang memang diperlukan.

Jangan mengubah alur presensi harian atau aturan validasi presensi hanya demi membuat angka persentase.

Jika membutuhkan sumber data yang belum tersedia, laporkan dan tunggu persetujuan.

---

# BAGIAN 11 — LARANGAN KERAS

DILARANG melakukan perubahan di luar ruang lingkup.

Jangan:

- Mengubah layout dashboard selain navigasi desktop yang disepakati.
- Mengubah struktur database.
- Mengubah migration.
- Menghapus tabel.
- Membuat tabel baru tanpa persetujuan.
- Mengubah Model yang tidak berkaitan langsung.
- Mengubah Authentication.
- Mengubah Authorization.
- Mengubah Middleware.
- Mengubah route yang tidak berkaitan.
- Mengubah data lama.
- Mengubah validasi form yang sudah ada.
- Mengubah alur check-in/check-out.
- Mengubah aturan persetujuan kehadiran.
- Mengubah logic tugas atau logbook.
- Mengubah nama field.
- Menghapus kolom CSV lama.
- Mengubah mekanisme theme switching.
- Menginstal dependency baru tanpa izin.
- Melakukan refactor besar.
- Melakukan redesign keseluruhan aplikasi.

Jangan sekadar membuat aplikasi terlihat bagus tetapi merusak fitur yang sudah berfungsi.

---

# BAGIAN 12 — BATASAN DOCKER DAN DATABASE

Project menggunakan Docker Compose.

Jangan melakukan operasi destruktif.

DILARANG:

`docker compose down -v`

`docker volume rm`

`docker system prune`

`docker compose rm`

`migrate:fresh`

`migrate:refresh`

`db:wipe`

`DROP DATABASE`

Jangan menghapus atau membuat ulang container maupun volume.

Jangan melakukan restart atau recreate container tanpa alasan teknis yang jelas dan persetujuan saya.

Gunakan container yang sudah berjalan.

Perubahan Blade/CSS pada bind mount biasanya dapat langsung terlihat. Jika membutuhkan cache clear atau build, jelaskan alasannya terlebih dahulu.

---

# BAGIAN 13 — VERIFIKASI

Setelah implementasi, lakukan pemeriksaan sesuai ruang lingkup.

## A. UI

Periksa:

- Login Light Mode dan Dark Mode.
- Dashboard Mahasiswa Light Mode dan Dark Mode.
- Dashboard Mentor Light Mode dan Dark Mode.
- Dashboard Admin/Super Admin jika tersedia.
- Form penugasan Mentor.
- Card dan tabel yang sebelumnya masih putih.
- Input, dropdown, textarea, tombol, badge, dan alert.

## B. Navigasi

Periksa:

- Sidebar tetap terlihat ketika halaman di-scroll pada desktop.
- Tidak menutupi konten.
- Konten utama tidak bergeser secara salah.
- Menu aktif terlihat.
- Profil dan logout tetap di posisi yang benar.
- Mobile tidak terganggu.

## C. Logout

Periksa tampilan tombol logout pada:

- Mahasiswa.
- Mentor.
- Super Admin.

Pastikan desainnya seragam dan proses logout tetap berfungsi.

## D. CSV

Lakukan pengujian dengan data yang tersedia.

Periksa:

- Jumlah Hadir.
- Jumlah Tidak Hadir.
- Jumlah Izin.
- Jumlah Sakit.
- Denominator.
- Persentase.
- Pembulatan.
- Kondisi denominator nol.
- Filter periode.
- Filter mahasiswa.
- Hak akses berdasarkan role.
- Kompatibilitas output CSV.

Jangan mengklaim hasil persentase akurat apabila sumber data atau denominator belum terbukti benar.

---

# BAGIAN 14 — AUDIT PERUBAHAN SETELAH IMPLEMENTASI

Gunakan Git diff jika tersedia.

Periksa seluruh perubahan file dan setiap baris yang dimodifikasi.

Pastikan tidak ada perubahan tidak relevan yang ikut masuk.

Tampilkan daftar:

`CREATED:`
- File yang benar-benar dibuat.

`MODIFIED:`
- File yang benar-benar diubah.

`NOT MODIFIED:`
- Controller lain.
- Model.
- Routes yang tidak terkait.
- Migration.
- Seeder.
- Database.
- Authentication.
- Middleware.

Jika terdapat modifikasi di luar persetujuan, hentikan dan laporkan.

Jangan menghapus perubahan lama milik saya saat melakukan cleanup.

---

# BAGIAN 15 — FORMAT LAPORAN AKHIR

Setelah implementasi, berikan:

1. Ringkasan pekerjaan.
2. Perbaikan Dark Mode.
3. Perbaikan Light Mode.
4. Perbaikan halaman login.
5. Perubahan navigasi desktop.
6. Penyamaan tombol logout.
7. Perubahan fitur ekspor CSV.
8. Definisi dan rumus persentase yang benar-benar digunakan.
9. Daftar file yang dimodifikasi.
10. Hasil pemeriksaan syntax/build.
11. Hasil pengujian UI dan CSV.
12. Hal yang belum dapat diverifikasi.
13. Rekomendasi tambahan yang belum dikerjakan.

Jangan mengklaim sebuah fitur berhasil apabila belum diuji.

---

# INSTRUKSI PENUTUP

Prioritas utama adalah menyempurnakan SIMANG tanpa merusak implementasi yang sudah ada.

Urutan pekerjaan:

1. Audit source code.
2. Identifikasi masalah warna.
3. Identifikasi masalah navigasi desktop.
4. Identifikasi perbedaan tombol logout.
5. Audit fitur ekspor CSV.
6. Verifikasi struktur dan data kehadiran.
7. Berikan usulan warna, layout, dan perhitungan persentase.
8. Tampilkan daftar file yang akan diubah.
9. BERHENTI dan tunggu persetujuan.
10. Implementasikan hanya perubahan yang telah disetujui.
11. Verifikasi seluruh perubahan.

**Jangan langsung mengedit file pada tahap audit.**

**Jangan melakukan redesign keseluruhan aplikasi.**

**Jangan mengubah file di luar ruang lingkup yang disetujui.**

Mulai dari tahap audit dan berikan laporan terlebih dahulu.