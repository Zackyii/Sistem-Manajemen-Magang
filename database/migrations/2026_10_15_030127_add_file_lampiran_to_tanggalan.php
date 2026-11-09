<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds nullable file_lampiran column for logbook attachments (PDF/image).
     * Nullable so existing records are unaffected.
     */
    public function up(): void
    {
        Schema::table('tanggalan', function (Blueprint $table) {
            $table->string('file_lampiran')->nullable()->after('catatan_mentor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tanggalan', function (Blueprint $table) {
            $table->dropColumn('file_lampiran');
        });
    }
};
