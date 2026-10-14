<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Bidang;
use App\Models\Kehadiran;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Pengumuman;
use App\Models\Penilaian;
use App\Models\Pesan;
use App\Models\Tanggalan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Bidang (Divisions in Diskominfo Jatim)
        $bidangAptika = Bidang::create([
            'nama_bidang' => 'Aplikasi Informatika (APTIKA)',
            'deskripsi' => 'Pengembangan perangkat lunak, sistem informasi, integrasi data, dan keamanan informasi pemerintah provinsi.',
            'kuota' => 10,
        ]);

        $bidangIkp = Bidang::create([
            'nama_bidang' => 'Informasi & Komunikasi Publik (IKP)',
            'deskripsi' => 'Pengelolaan opini publik, media sosial, publikasi informasi resmi pemerintah Jawa Timur, dan liputan berita.',
            'kuota' => 8,
        ]);

        $bidangStatistik = Bidang::create([
            'nama_bidang' => 'Statistik & Tata Kelola SPBE',
            'deskripsi' => 'Pengelolaan satu data Jawa Timur, analitik statistik daerah, dan standarisasi tata kelola pemerintahan digital.',
            'kuota' => 6,
        ]);

        // 2. Create Admin
        $adminUser = User::create([
            'username' => 'admin',
            'password' => 'admin123',
            'role' => 'admin',
            'status' => 'active',
            'has_agreed_to_rules' => true,
        ]);

        $admin = Admin::create([
            'user_id' => $adminUser->id,
            'nama' => 'Budi Santoso, S.Kom., M.T.',
            'nip' => '198205142008011005',
            'no_telepon' => '081234567890',
        ]);

        // 3. Create Mentors
        $mentorUser1 = User::create([
            'username' => 'mentor_aptika',
            'password' => 'password123',
            'role' => 'mentor',
            'status' => 'active',
            'has_agreed_to_rules' => true,
        ]);

        $mentorAptika = Mentor::create([
            'user_id' => $mentorUser1->id,
            'bidang_id' => $bidangAptika->id,
            'nama' => 'Rahmat Hidayat, S.T., M.Kom.',
            'nip' => '198709232010011012',
            'no_telepon' => '081398765432',
        ]);

        $mentorUser2 = User::create([
            'username' => 'mentor_ikp',
            'password' => 'password123',
            'role' => 'mentor',
            'status' => 'active',
            'has_agreed_to_rules' => true,
        ]);

        $mentorIkp = Mentor::create([
            'user_id' => $mentorUser2->id,
            'bidang_id' => $bidangIkp->id,
            'nama' => 'Dra. Siti Rahayu, M.Si.',
            'nip' => '197904122005012003',
            'no_telepon' => '081277665544',
        ]);

        // 4. Create Mahasiswa (Student 1: Active & Agreed)
        $mhsUser1 = User::create([
            'username' => 'mahasiswa',
            'password' => 'mahasiswa123',
            'role' => 'mahasiswa',
            'status' => 'active',
            'has_agreed_to_rules' => true,
        ]);

        $mhs1 = Mahasiswa::create([
            'user_id' => $mhsUser1->id,
            'bidang_id' => $bidangAptika->id,
            'nama' => 'Ahmad Zacky Pratama',
            'nim' => '21081010150',
            'asal_universitas' => 'UPN Veteran Jawa Timur',
            'program_studi' => 'Informatika',
            'no_telepon' => '085712345678',
            'status_verifikasi' => 'disetujui',
            'tanggal_mulai' => Carbon::now()->subDays(15)->toDateString(),
            'tanggal_selesai' => Carbon::now()->addDays(75)->toDateString(),
        ]);

        // Student 2: Active but Needs Onboarding Agreement
        $mhsUser2 = User::create([
            'username' => 'mhs_baru',
            'password' => 'mahasiswa123',
            'role' => 'mahasiswa',
            'status' => 'active',
            'has_agreed_to_rules' => false,
        ]);

        Mahasiswa::create([
            'user_id' => $mhsUser2->id,
            'bidang_id' => $bidangIkp->id,
            'nama' => 'Nabila Putri Cahyani',
            'nim' => '21051204088',
            'asal_universitas' => 'Universitas Airlangga',
            'program_studi' => 'Ilmu Komunikasi',
            'no_telepon' => '082133445566',
            'status_verifikasi' => 'disetujui',
            'tanggal_mulai' => Carbon::now()->toDateString(),
            'tanggal_selesai' => Carbon::now()->addDays(90)->toDateString(),
        ]);

        // Student 3: Pending Approval
        $mhsUser3 = User::create([
            'username' => 'mhs_pending',
            'password' => 'mahasiswa123',
            'role' => 'mahasiswa',
            'status' => 'pending',
            'has_agreed_to_rules' => false,
        ]);

        Mahasiswa::create([
            'user_id' => $mhsUser3->id,
            'bidang_id' => $bidangStatistik->id,
            'nama' => 'Dimas Arya Wijaya',
            'nim' => '22091020102',
            'asal_universitas' => 'Institut Teknologi Sepuluh Nopember',
            'program_studi' => 'Sains Data',
            'no_telepon' => '081299887766',
            'status_verifikasi' => 'pending',
            'tanggal_mulai' => Carbon::now()->addDays(7)->toDateString(),
            'tanggal_selesai' => Carbon::now()->addDays(97)->toDateString(),
        ]);

        // 5. Seed Attendance & Logbooks for Mhs 1 (past 10 working days)
        for ($i = 10; $i >= 1; $i--) {
            $date = Carbon::now()->subDays($i);
            if ($date->isWeekend()) {
                continue;
            }

            $checkinTime = $i % 4 === 0 ? '08:15:00' : '07:45:00';
            $status = $i % 4 === 0 ? 'terlambat' : 'hadir';

            $kehadiran = Kehadiran::create([
                'mahasiswa_id' => $mhs1->id,
                'tanggal' => $date->toDateString(),
                'waktu_masuk' => $checkinTime,
                'waktu_keluar' => '16:05:00',
                'koordinat_lat' => -7.3156,
                'koordinat_lng' => 112.7291,
                'nama_lokasi' => 'Kantor Diskominfo Prov. Jatim, Jl. A. Yani Surabaya',
                'status' => $status,
                'status_mentor' => 'disetujui',
                'keterangan' => $status === 'terlambat' ? 'Harap datang tepat waktu sebelum pukul 08:00.' : 'Bagus, tepat waktu.',
            ]);

            Tanggalan::create([
                'mahasiswa_id' => $mhs1->id,
                'mentor_id' => $mentorAptika->id,
                'kehadiran_id' => $kehadiran->id,
                'tanggal' => $date->toDateString(),
                'aktivitas_utama' => 'Pengembangan Modul Sistem Layanan Internal',
                'deskripsi_kegiatan' => 'Menganalisis kebutuhan modul, merancang antarmuka Tailwind CSS, serta menguji endpoint API bersama tim developer Diskominfo Jatim.',
                'durasi_jam' => 8.0,
                'status_logbook' => 'disetujui',
                'catatan_mentor' => 'Pekerjaan terstruktur dengan baik.',
            ]);
        }

        // Today's attendance for Mhs 1 (active check-in)
        $todayKehadiran = Kehadiran::create([
            'mahasiswa_id' => $mhs1->id,
            'tanggal' => Carbon::today()->toDateString(),
            'waktu_masuk' => '07:42:15',
            'waktu_keluar' => null,
            'koordinat_lat' => -7.3156,
            'koordinat_lng' => 112.7291,
            'nama_lokasi' => 'Kantor Diskominfo Prov. Jatim',
            'status' => 'hadir',
            'status_mentor' => 'pending',
        ]);

        // Upcoming Tasks for Mhs 1
        Tanggalan::create([
            'mahasiswa_id' => $mhs1->id,
            'mentor_id' => $mentorAptika->id,
            'tanggal' => Carbon::today()->addDays(2)->toDateString(),
            'info_tugas' => 'Slicing UI Dashboard Pelayanan Publik',
            'deadline_tugas' => Carbon::today()->addDays(2)->toDateString(),
            'status_logbook' => 'draft',
        ]);

        Tanggalan::create([
            'mahasiswa_id' => $mhs1->id,
            'mentor_id' => $mentorAptika->id,
            'tanggal' => Carbon::today()->addDays(5)->toDateString(),
            'info_tugas' => 'Testing & Dokumentasi API Integrasi Satu Data Jatim',
            'deadline_tugas' => Carbon::today()->addDays(5)->toDateString(),
            'status_logbook' => 'draft',
        ]);

        // 6. Seed Announcements
        Pengumuman::create([
            'admin_id' => $admin->id,
            'bidang_id' => null, // Global
            'judul' => 'Instruksi Pakaian Dinas dan Jam Kerja Pemprov Jatim',
            'isi_pengumuman' => 'Seluruh peserta magang diwajibkan mematuhi jam kehadiran 07.30 - 16.00 WIB, mengenakan kemeja putih rapi berkerah, celana/rok hitam bahan, serta wajib mengenakan ID Card resmi selama di lingkungan Diskominfo Jatim.',
            'tanggal_publikasi' => Carbon::now()->subDays(2),
            'tipe' => 'penting',
        ]);

        Pengumuman::create([
            'admin_id' => $admin->id,
            'bidang_id' => $bidangAptika->id,
            'judul' => 'Workshop Git & CI/CD Pipeline APTIKA',
            'isi_pengumuman' => 'Diharapkan seluruh mahasiswa magang Bidang APTIKA menghadiri sharing session mengenai arsitektur serverless dan Docker container pada hari Jumat pukul 09.00 WIB di Ruang Rapat Lt. 3.',
            'tanggal_publikasi' => Carbon::now()->subDay(),
            'tipe' => 'info',
        ]);

        // 7. Seed Messages
        Pesan::create([
            'pengirim_id' => $mentorUser1->id,
            'penerima_id' => $mhsUser1->id,
            'isi_pesan' => 'Halo Zacky, progres sprint minggu ini sudah sangat baik. Jangan lupa upload laporan mingguan ke logbook sebelum hari Jumat sore.',
            'waktu_kirim' => Carbon::now()->subHours(5),
            'tipe' => 'direct',
            'is_read' => true,
        ]);

        // 8. Seed Evaluation
        Penilaian::create([
            'mahasiswa_id' => $mhs1->id,
            'mentor_id' => $mentorAptika->id,
            'nilai_kehadiran' => 95,
            'nilai_tugas' => 92,
            'nilai_sikap' => 96,
            'nilai_akhir' => 94.1,
            'grade' => 'A',
            'catatan_evaluasi' => 'Menunjukkan dedikasi dan kemampuan teknis luar biasa dalam menyelesaikan problem solving di bidang aplikasi.',
        ]);
    }
}
