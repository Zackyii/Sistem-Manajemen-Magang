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
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->foreignId('bidang_id')->nullable()->constrained('bidang')->nullOnDelete();
            $table->string('judul');
            $table->text('isi_pengumuman');
            $table->timestamp('tanggal_publikasi')->useCurrent();
            $table->enum('tipe', ['penting', 'info', 'sistem'])->default('info');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
