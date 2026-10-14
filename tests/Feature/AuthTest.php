<?php

use App\Models\Admin;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('login page is accessible for guests', function () {
    $response = $this->get(route('login'));
    $response->assertStatus(200);
    $response->assertSee('SIMANG');
    $response->assertSee('Diskominfo');
});

test('admin can authenticate and is redirected to admin dashboard', function () {
    $user = User::create([
        'username' => 'admin_test',
        'password' => 'password123',
        'role' => 'admin',
        'status' => 'active',
        'has_agreed_to_rules' => true,
    ]);

    Admin::create([
        'user_id' => $user->id,
        'nama' => 'Admin Testing',
        'nip' => '1234567890',
    ]);

    $response = $this->post(route('login.post'), [
        'username' => 'admin_test',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('inactive or pending users cannot login', function () {
    $user = User::create([
        'username' => 'pending_user',
        'password' => 'password123',
        'role' => 'mahasiswa',
        'status' => 'pending',
    ]);

    $response = $this->post(route('login.post'), [
        'username' => 'pending_user',
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

test('mahasiswa who has not agreed to rules is redirected to panduan onboarding', function () {
    $user = User::create([
        'username' => 'mhs_fresh',
        'password' => 'password123',
        'role' => 'mahasiswa',
        'status' => 'active',
        'has_agreed_to_rules' => false,
    ]);

    Mahasiswa::create([
        'user_id' => $user->id,
        'nama' => 'Mahasiswa Baru',
        'asal_universitas' => 'UNAIR',
        'program_studi' => 'Ilkom',
        'status_verifikasi' => 'disetujui',
        'tanggal_mulai' => now()->toDateString(),
        'tanggal_selesai' => now()->addMonths(3)->toDateString(),
    ]);

    $response = $this->actingAs($user)->get(route('mahasiswa.dashboard'));
    $response->assertRedirect(route('mahasiswa.panduan.index'));
});
