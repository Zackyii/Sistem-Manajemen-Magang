<?php

use App\Models\Bidang;
use App\Models\Kehadiran;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Tanggalan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('mentor can approve attendance and logbook entries', function () {
    $bidang = Bidang::create(['nama_bidang' => 'APTIKA', 'kuota' => 10]);

    $mentorUser = User::create([
        'username' => 'mentor_test',
        'password' => 'password123',
        'role' => 'mentor',
        'status' => 'active',
        'has_agreed_to_rules' => true,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'bidang_id' => $bidang->id,
        'nama' => 'Mentor Lapangan',
        'nip' => '19850101',
    ]);

    $mhsUser = User::create([
        'username' => 'mhs_bimbingan',
        'password' => 'password123',
        'role' => 'mahasiswa',
        'status' => 'active',
        'has_agreed_to_rules' => true,
    ]);

    $mhs = Mahasiswa::create([
        'user_id' => $mhsUser->id,
        'bidang_id' => $bidang->id,
        'nama' => 'Mahasiswa Bimbingan',
        'asal_universitas' => 'ITS',
        'program_studi' => 'Informatika',
        'status_verifikasi' => 'disetujui',
        'tanggal_mulai' => now()->toDateString(),
        'tanggal_selesai' => now()->addMonths(3)->toDateString(),
    ]);

    // 1. Attendance Approval
    $kehadiran = Kehadiran::create([
        'mahasiswa_id' => $mhs->id,
        'tanggal' => today()->toDateString(),
        'waktu_masuk' => '07:50:00',
        'status' => 'hadir',
        'status_mentor' => 'pending',
    ]);

    $resAttendance = $this->actingAs($mentorUser)->post(route('mentor.presensi.approve', $kehadiran));
    $resAttendance->assertRedirect(route('mentor.presensi.index'));
    expect($kehadiran->fresh()->status_mentor)->toBe('disetujui');

    // 2. Logbook Approval
    $logbook = Tanggalan::create([
        'mahasiswa_id' => $mhs->id,
        'mentor_id' => $mentor->id,
        'tanggal' => today()->toDateString(),
        'aktivitas_utama' => 'Testing API Backend',
        'deskripsi_kegiatan' => 'Melakukan testing endpoint API SIMANG.',
        'durasi_jam' => 8,
        'status_logbook' => 'submitted',
    ]);

    $resLogbook = $this->actingAs($mentorUser)->post(route('mentor.logbook.approve', $logbook), [
        'catatan_mentor' => 'Hasil testing sangat baik.',
    ]);
    $resLogbook->assertRedirect(route('mentor.logbook.index'));
    expect($logbook->fresh()->status_logbook)->toBe('disetujui');
    expect($logbook->fresh()->catatan_mentor)->toBe('Hasil testing sangat baik.');

    // 3. Task Assignment
    $resTask = $this->actingAs($mentorUser)->post(route('mentor.tugas.store'), [
        'mahasiswa_id' => $mhs->id,
        'info_tugas' => 'Review Arsitektur Docker Container',
        'deadline_tugas' => today()->addDays(5)->toDateString(),
    ]);
    $resTask->assertRedirect(route('mentor.tugas.index'));
    $this->assertDatabaseHas('tanggalan', [
        'mahasiswa_id' => $mhs->id,
        'info_tugas' => 'Review Arsitektur Docker Container',
    ]);

    // 4. Grading / Final Evaluation
    $resGrade = $this->actingAs($mentorUser)->post(route('mentor.penilaian.store'), [
        'mahasiswa_id' => $mhs->id,
        'nilai_kehadiran' => 95,
        'nilai_tugas' => 90,
        'nilai_sikap' => 95,
        'catatan_evaluasi' => 'Sangat kompeten dan proaktif.',
    ]);
    $resGrade->assertRedirect(route('mentor.penilaian.index'));
    $this->assertDatabaseHas('penilaian', [
        'mahasiswa_id' => $mhs->id,
        'mentor_id' => $mentor->id,
        'nilai_akhir' => 93,
        'grade' => 'A',
    ]);

    // 5. Show Logbook Page
    $resShowLogbook = $this->actingAs($mentorUser)->get(route('mentor.logbook.show', $logbook));
    $resShowLogbook->assertStatus(200);
    $resShowLogbook->assertSee('Detail Logbook');
    $resShowLogbook->assertSee('Testing API Backend');

    // 6. Mentor Send Message
    $resMsg = $this->actingAs($mentorUser)->post(route('mentor.pesan.store'), [
        'tipe' => 'direct',
        'penerima_id' => $mhsUser->id,
        'isi_pesan' => 'Halo mahasiswa bimbingan, jangan lupa input logbook hari ini.',
    ]);
    $resMsg->assertRedirect();
    $this->assertDatabaseHas('pesan', [
        'pengirim_id' => $mentorUser->id,
        'penerima_id' => $mhsUser->id,
        'tipe' => 'direct',
    ]);

    // 7. Mentor Update Bidang Kuota
    $resQuota = $this->actingAs($mentorUser)->patch(route('mentor.bidang.update-kuota'), [
        'kuota' => 12,
    ]);
    $resQuota->assertRedirect();
    expect($bidang->fresh()->kuota)->toBe(12);
});
