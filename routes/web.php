<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Mahasiswa;
use App\Http\Controllers\Mentor;
use Illuminate\Support\Facades\Route;

// ─── Guest Routes ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Protected File Serving Routes ────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    // Presensi files: selfie check-in, bukti izin/sakit, foto check-out
    Route::get('/files/presensi/{kehadiran}/{type}', [FileController::class, 'presensi'])
        ->name('files.presensi')
        ->where('type', 'selfie|bukti|checkout');

    // Logbook attachments
    Route::get('/files/logbook/{tanggalan}', [FileController::class, 'logbook'])
        ->name('files.logbook');
});

// ─── Admin Routes ──────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Bidang management
    Route::get('/bidang', [Admin\BidangController::class, 'index'])->name('bidang.index');
    Route::post('/bidang', [Admin\BidangController::class, 'store'])->name('bidang.store');
    Route::put('/bidang/{bidang}', [Admin\BidangController::class, 'update'])->name('bidang.update');
    Route::delete('/bidang/{bidang}', [Admin\BidangController::class, 'destroy'])->name('bidang.destroy');

    // Mahasiswa management
    Route::get('/mahasiswa', [Admin\MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::get('/mahasiswa/create', [Admin\MahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::post('/mahasiswa', [Admin\MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::get('/mahasiswa/{mahasiswa}', [Admin\MahasiswaController::class, 'show'])->name('mahasiswa.show');
    Route::post('/mahasiswa/{mahasiswa}/approve', [Admin\MahasiswaController::class, 'approve'])->name('mahasiswa.approve');
    Route::post('/mahasiswa/{mahasiswa}/reject', [Admin\MahasiswaController::class, 'reject'])->name('mahasiswa.reject');

    // Mentor management
    Route::get('/mentor', [Admin\MentorController::class, 'index'])->name('mentor.index');
    Route::post('/mentor', [Admin\MentorController::class, 'store'])->name('mentor.store');
    Route::post('/mentor/{mentor}/assign-bidang', [Admin\MentorController::class, 'assignBidang'])->name('mentor.assign-bidang');
    Route::delete('/mentor/{mentor}', [Admin\MentorController::class, 'destroy'])->name('mentor.destroy');

    // Pengumuman
    Route::get('/pengumuman', [Admin\PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::post('/pengumuman', [Admin\PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::delete('/pengumuman/{pengumuman}', [Admin\PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    // FITUR CHAT / PESAN DINONAKTIFKAN SEMENTARA (COMMENT-OUT ONLY — jangan hapus)
    // Route::get('/pesan', [Admin\PesanController::class, 'index'])->name('pesan.index');
    // Route::patch('/pesan/{pesan}/mark-read', [Admin\PesanController::class, 'markRead'])->name('pesan.mark-read');
});

// ─── Mentor Routes ─────────────────────────────────────────────────────────────
Route::prefix('mentor')->name('mentor.')->middleware(['auth', 'role:mentor'])->group(function () {
    Route::get('/dashboard', [Mentor\DashboardController::class, 'index'])->name('dashboard');

    // Presensi monitoring (replaces approve/reject)
    Route::get('/presensi', [Mentor\PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/export', [Mentor\PresensiController::class, 'export'])->name('presensi.export');

    // Logbook review
    Route::get('/logbook', [Mentor\LogbookController::class, 'index'])->name('logbook.index');
    Route::get('/logbook/{tanggalan}', [Mentor\LogbookController::class, 'show'])->name('logbook.show');
    Route::post('/logbook/{tanggalan}/approve', [Mentor\LogbookController::class, 'approve'])->name('logbook.approve');
    Route::post('/logbook/{tanggalan}/reject', [Mentor\LogbookController::class, 'reject'])->name('logbook.reject');
    Route::post('/logbook/bulk-approve', [Mentor\LogbookController::class, 'bulkApprove'])->name('logbook.bulk-approve');

    // Task assignment
    Route::get('/tugas', [Mentor\TugasController::class, 'index'])->name('tugas.index');
    Route::post('/tugas', [Mentor\TugasController::class, 'store'])->name('tugas.store');

    // Grading
    Route::get('/penilaian', [Mentor\PenilaianController::class, 'index'])->name('penilaian.index');
    Route::post('/penilaian', [Mentor\PenilaianController::class, 'store'])->name('penilaian.store');

    // FITUR CHAT / PESAN DINONAKTIFKAN SEMENTARA (COMMENT-OUT ONLY — jangan hapus)
    // Route::get('/pesan', [Mentor\PesanController::class, 'index'])->name('pesan.index');
    // Route::post('/pesan', [Mentor\PesanController::class, 'store'])->name('pesan.store');
    // Route::patch('/pesan/{pesan}/mark-read', [Mentor\PesanController::class, 'markRead'])->name('pesan.mark-read');

    // Quota update (bidang kuota)
    Route::patch('/bidang/kuota', [Mentor\DashboardController::class, 'updateKuota'])->name('bidang.update-kuota');
});

// ─── Mahasiswa Routes ──────────────────────────────────────────────────────────
Route::prefix('mahasiswa')->name('mahasiswa.')->middleware(['auth', 'role:mahasiswa'])->group(function () {
    // Onboarding (no agreed middleware here — it IS the agreement page)
    Route::get('/panduan', [Mahasiswa\PanduanController::class, 'index'])->name('panduan.index');
    Route::post('/panduan/agree', [Mahasiswa\PanduanController::class, 'agree'])->name('panduan.agree');

    // All other routes require agreement
    Route::middleware('agreed')->group(function () {
        Route::get('/dashboard', [Mahasiswa\DashboardController::class, 'index'])->name('dashboard');

        // Presensi
        Route::get('/presensi', [Mahasiswa\PresensiController::class, 'index'])->name('presensi.index');
        Route::post('/presensi/check-in', [Mahasiswa\PresensiController::class, 'checkIn'])->name('presensi.check-in');
        Route::post('/presensi/check-out', [Mahasiswa\PresensiController::class, 'checkOut'])->name('presensi.check-out');

        // Logbook
        Route::get('/logbook', [Mahasiswa\LogbookController::class, 'index'])->name('logbook.index');
        Route::get('/logbook/create', [Mahasiswa\LogbookController::class, 'create'])->name('logbook.create');
        Route::post('/logbook', [Mahasiswa\LogbookController::class, 'store'])->name('logbook.store');
        Route::get('/logbook/export-pdf', [Mahasiswa\LogbookController::class, 'exportPdf'])->name('logbook.export-pdf');

        // Calendar / Tanggalan
        Route::get('/tanggalan', [Mahasiswa\TanggalanController::class, 'index'])->name('tanggalan.index');
        Route::get('/tanggalan/detail', [Mahasiswa\TanggalanController::class, 'show'])->name('tanggalan.show');

        // FITUR CHAT / PESAN DINONAKTIFKAN SEMENTARA (COMMENT-OUT ONLY — jangan hapus)
        // Route::get('/pesan', [Mahasiswa\PesanController::class, 'index'])->name('pesan.index');
        // Route::post('/pesan', [Mahasiswa\PesanController::class, 'store'])->name('pesan.store');

        // Survey
        Route::get('/survey', [Mahasiswa\SurveyController::class, 'index'])->name('survey.index');
        Route::post('/survey', [Mahasiswa\SurveyController::class, 'store'])->name('survey.store');
    });
});
