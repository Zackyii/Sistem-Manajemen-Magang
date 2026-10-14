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
        Schema::create('survey', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_id')->nullable()->constrained('bidang')->nullOnDelete();
            $table->integer('rating_fasilitas')->nullable();
            $table->integer('rating_pembimbing')->nullable();
            $table->integer('rating_pekerjaan')->nullable();
            $table->integer('rating_lingkungan')->nullable();
            $table->text('saran')->nullable();
            $table->text('kesan')->nullable();
            $table->boolean('is_anonymous')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey');
    }
};
