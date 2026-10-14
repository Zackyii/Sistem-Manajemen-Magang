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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->foreignId('mentor_id')->constrained('mentors')->cascadeOnDelete();
            $table->decimal('nilai_kehadiran', 5, 2)->nullable();
            $table->decimal('nilai_tugas', 5, 2)->nullable();
            $table->decimal('nilai_sikap', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('grade', 5)->nullable();
            $table->text('catatan_evaluasi')->nullable();
            $table->timestamp('tanggal_penilaian')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'mentor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
