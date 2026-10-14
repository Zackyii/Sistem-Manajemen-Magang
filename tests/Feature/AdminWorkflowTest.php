<?php

use App\Models\Admin;
use App\Models\Bidang;
use App\Models\Mahasiswa;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can access dashboard and view stats', function () {
    $adminUser = User::create([
        'username' => 'admin_wk',
        'password' => 'password123',
        'role' => 'admin',
        'status' => 'active',
        'has_agreed_to_rules' => true,
    ]);

    Admin::create([
        'user_id' => $adminUser->id,
        'nama' => 'Admin Workflow',
        'nip' => '19800101',
    ]);

    $response = $this->actingAs($adminUser)->get(route('admin.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('Dashboard Administrator');
});

test('admin can create and manage bidang', function () {
    $adminUser = User::create([
        'username' => 'admin_bidang',
        'password' => 'password123',
        'role' => 'admin',
        'status' => 'active',
        'has_agreed_to_rules' => true,
    ]);

    $response = $this->actingAs($adminUser)->post(route('admin.bidang.store'), [
        'nama_bidang' => 'APTIKA Diskominfo',
        'kuota' => 15,
        'deskripsi' => 'Pengembangan aplikasi dan tata kelola SPBE.',
    ]);

    $response->assertRedirect(route('admin.bidang.index'));
    $this->assertDatabaseHas('bidang', [
        'nama_bidang' => 'APTIKA Diskominfo',
        'kuota' => 15,
    ]);
});

test('admin can approve pending mahasiswa and assign bidang', function () {
    $adminUser = User::create([
        'username' => 'admin_approver',
        'password' => 'password123',
        'role' => 'admin',
        'status' => 'active',
        'has_agreed_to_rules' => true,
    ]);

    $bidang = Bidang::create(['nama_bidang' => 'IKP Publik', 'kuota' => 8]);

    $mhsUser = User::create([
        'username' => 'mhs_pending_test',
        'password' => 'password123',
        'role' => 'mahasiswa',
        'status' => 'pending',
    ]);

    $mhs = Mahasiswa::create([
        'user_id' => $mhsUser->id,
        'nama' => 'Mahasiswa Belum Diverifikasi',
        'asal_universitas' => 'UPN Jatim',
        'program_studi' => 'Komunikasi',
        'status_verifikasi' => 'pending',
        'tanggal_mulai' => now()->toDateString(),
        'tanggal_selesai' => now()->addMonths(3)->toDateString(),
    ]);

    $response = $this->actingAs($adminUser)->post(route('admin.mahasiswa.approve', $mhs), [
        'bidang_id' => $bidang->id,
    ]);

    $response->assertRedirect(route('admin.mahasiswa.index'));

    expect($mhs->fresh()->status_verifikasi)->toBe('disetujui');
    expect($mhs->fresh()->bidang_id)->toBe($bidang->id);
    expect($mhsUser->fresh()->status)->toBe('active');
});

test('admin can view inbox issue reports and mark them as read', function () {
    $adminUser = User::create([
        'username' => 'admin_inbox_test',
        'password' => 'password123',
        'role' => 'admin',
        'status' => 'active',
        'has_agreed_to_rules' => true,
    ]);

    Admin::create([
        'user_id' => $adminUser->id,
        'nama' => 'Admin Inbox',
        'nip' => '19800202',
    ]);

    $sender = User::create([
        'username' => 'mhs_sender',
        'password' => 'password123',
        'role' => 'mahasiswa',
        'status' => 'active',
        'has_agreed_to_rules' => true,
    ]);

    $pesan = Pesan::create([
        'pengirim_id' => $sender->id,
        'penerima_id' => $adminUser->id,
        'tipe' => 'laporan_sistem',
        'isi_pesan' => 'Database staging mengalami kendala timeout.',
        'waktu_kirim' => now(),
        'is_read' => false,
    ]);

    $resInbox = $this->actingAs($adminUser)->get(route('admin.pesan.index'));
    $resInbox->assertStatus(200);
    $resInbox->assertSee('Database staging mengalami kendala timeout.');

    $resMark = $this->actingAs($adminUser)->patch(route('admin.pesan.mark-read', $pesan));
    $resMark->assertRedirect(route('admin.pesan.index'));
    expect($pesan->fresh()->is_read)->toBeTrue();
});
