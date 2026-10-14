<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tanggalan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->foreignId('mentor_id')->nullable()->constrained('mentors')->nullOnDelete();
            $table->foreignId('kehadiran_id')->nullable()->constrained('kehadiran')->nullOnDelete();
            $table->date('tanggal');
            $table->string('aktivitas_utama')->nullable();
            $table->text('deskripsi_kegiatan')->nullable();
            $table->decimal('durasi_jam', 4, 1)->nullable();
            $table->enum('status_absen_harian', ['hadir', 'terlambat', 'izin', 'sakit', 'alpha', 'libur'])->nullable();
            $table->string('info_tugas')->nullable();
            $table->date('deadline_tugas')->nullable();
            $table->text('info_libur_event')->nullable();
            $table->enum('status_logbook', ['draft', 'submitted', 'disetujui', 'ditolak'])->default('draft');
            $table->text('catatan_mentor')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanggalan');
    }
};
