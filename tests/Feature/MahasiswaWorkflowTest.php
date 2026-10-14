<?php

use App\Models\Bidang;
use App\Models\Kehadiran;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('mahasiswa can agree to rules and access dashboard', function () {
    $user = User::create([
        'username' => 'mhs_agree_test',
        'password' => 'password123',
        'role' => 'mahasiswa',
        'status' => 'active',
        'has_agreed_to_rules' => false,
    ]);

    $bidang = Bidang::create(['nama_bidang' => 'APTIKA', 'kuota' => 10]);

    Mahasiswa::create([
        'user_id' => $user->id,
        'bidang_id' => $bidang->id,
        'nama' => 'Test Mahasiswa',
        'asal_universitas' => 'ITS',
        'program_studi' => 'Informatika',
        'status_verifikasi' => 'disetujui',
        'tanggal_mulai' => now()->toDateString(),
        'tanggal_selesai' => now()->addMonths(3)->toDateString(),
    ]);

    $response = $this->actingAs($user)->post(route('mahasiswa.panduan.agree'));
    $response->assertRedirect(route('mahasiswa.dashboard'));

    expect($user->fresh()->has_agreed_to_rules)->toBeTrue();
});

test('mahasiswa can record attendance check-in and check-out', function () {
    $user = User::create([
        'username' => 'mhs_presensi_test',
        'password' => 'password123',
        'role' => 'mahasiswa',
        'status' => 'active',
        'has_agreed_to_rules' => true,
    ]);

    $bidang = Bidang::create(['nama_bidang' => 'IKP', 'kuota' => 10]);

    $mhs = Mahasiswa::create([
        'user_id' => $user->id,
        'bidang_id' => $bidang->id,
        'nama' => 'Budi Presensi',
        'asal_universitas' => 'UPN',
        'program_studi' => 'Sistem Informasi',
        'status_verifikasi' => 'disetujui',
        'tanggal_mulai' => now()->toDateString(),
        'tanggal_selesai' => now()->addMonths(3)->toDateString(),
    ]);

    // Check-in
    $responseCheckin = $this->actingAs($user)->post(route('mahasiswa.presensi.check-in'), [
        'foto_selfie' => 'data:image/jpeg;base64,'.base64_encode('fake-image-bytes'),
        'koordinat_lat' => -7.3156,
        'koordinat_lng' => 112.7291,
        'nama_lokasi' => 'Diskominfo Prov. Jatim',
    ]);

    $responseCheckin->assertRedirect(route('mahasiswa.presensi.index'));
    expect(Kehadiran::where('mahasiswa_id', $mhs->id)->whereDate('tanggal', today())->exists())->toBeTrue();

    // Check-out
    $responseCheckout = $this->actingAs($user)->post(route('mahasiswa.presensi.check-out'));
    $responseCheckout->assertRedirect(route('mahasiswa.presensi.index'));

    $kehadiran = Kehadiran::where('mahasiswa_id', $mhs->id)->whereDate('tanggal', today())->first();
    expect($kehadiran->waktu_keluar)->not->toBeNull();
});

test('mahasiswa can create a logbook entry', function () {
    $user = User::create([
        'username' => 'mhs_logbook_test',
        'password' => 'password123',
        'role' => 'mahasiswa',
        'status' => 'active',
        'has_agreed_to_rules' => true,
    ]);

    $bidang = Bidang::create(['nama_bidang' => 'Statistik', 'kuota' => 5]);

    $mhs = Mahasiswa::create([
        'user_id' => $user->id,
        'bidang_id' => $bidang->id,
        'nama' => 'Siti Logbook',
        'asal_universitas' => 'UNAIR',
        'program_studi' => 'Statistika',
        'status_verifikasi' => 'disetujui',
        'tanggal_mulai' => now()->toDateString(),
        'tanggal_selesai' => now()->addMonths(3)->toDateString(),
    ]);

    $response = $this->actingAs($user)->post(route('mahasiswa.logbook.store'), [
        'tanggal' => today()->toDateString(),
        'aktivitas_utama' => 'Analisis Data SPBE',
        'deskripsi_kegiatan' => 'Menyiapkan dataset indikator kematangan SPBE Pemprov Jatim.',
        'durasi_jam' => 8,
        'status_logbook' => 'submitted',
    ]);

    $response->assertRedirect(route('mahasiswa.logbook.index'));
    $this->assertDatabaseHas('tanggalan', [
        'mahasiswa_id' => $mhs->id,
        'aktivitas_utama' => 'Analisis Data SPBE',
        'status_logbook' => 'submitted',
    ]);
});

test('mahasiswa can submit survey and send issue report message', function () {
    $user = User::create([
        'username' => 'mhs_survey_test',
        'password' => 'password123',
        'role' => 'mahasiswa',
        'status' => 'active',
        'has_agreed_to_rules' => true,
    ]);

    $bidang = Bidang::create(['nama_bidang' => 'Aptika', 'kuota' => 5]);

    $mhs = Mahasiswa::create([
        'user_id' => $user->id,
        'bidang_id' => $bidang->id,
        'nama' => 'Survey Tester',
        'asal_universitas' => 'UB',
        'program_studi' => 'Informatika',
        'status_verifikasi' => 'disetujui',
        'tanggal_mulai' => now()->toDateString(),
        'tanggal_selesai' => now()->addMonths(3)->toDateString(),
    ]);

    // Survey
    $resSurvey = $this->actingAs($user)->post(route('mahasiswa.survey.store'), [
        'rating_fasilitas' => 5,
        'rating_pembimbing' => 5,
        'rating_pekerjaan' => 4,
        'rating_lingkungan' => 5,
        'saran' => 'Pertahankan program magang ini.',
        'kesan' => 'Pengalaman yang sangat berharga.',
    ]);
    $resSurvey->assertRedirect(route('mahasiswa.dashboard'));
    $this->assertDatabaseHas('survey', [
        'rating_fasilitas' => 5,
        'is_anonymous' => true,
    ]);

    $adminUser = User::create([
        'username' => 'admin_receiver',
        'password' => 'password123',
        'role' => 'admin',
        'status' => 'active',
        'has_agreed_to_rules' => true,
    ]);

    // Pesan Issue Report to Admin
    $resPesan = $this->actingAs($user)->post(route('mahasiswa.pesan.store'), [
        'penerima_id' => $adminUser->id,
        'tipe' => 'laporan_sistem',
        'isi_pesan' => 'Mohon bantuan koneksi WiFi di meja APTIKA terputus.',
    ]);
    $resPesan->assertRedirect();
    $this->assertDatabaseHas('pesan', [
        'pengirim_id' => $user->id,
        'penerima_id' => $adminUser->id,
        'tipe' => 'laporan_sistem',
        'isi_pesan' => 'Mohon bantuan koneksi WiFi di meja APTIKA terputus.',
    ]);
});
