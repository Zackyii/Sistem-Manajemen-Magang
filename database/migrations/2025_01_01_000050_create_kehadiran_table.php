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
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('waktu_masuk')->nullable();
            $table->time('waktu_keluar')->nullable();
            $table->string('foto_selfie')->nullable();
            $table->decimal('koordinat_lat', 10, 7)->nullable();
            $table->decimal('koordinat_lng', 10, 7)->nullable();
            $table->string('nama_lokasi')->nullable();
            $table->enum('status', ['hadir', 'terlambat', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->enum('status_mentor', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran');
    }
};
