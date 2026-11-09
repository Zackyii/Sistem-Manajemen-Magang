<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds nullable columns for:
     *  - file_bukti: path to izin/sakit evidence file (PDF/image)
     *  - foto_checkout: path to checkout selfie (stored in private disk)
     *  - koordinat_lat_checkout / koordinat_lng_checkout: GPS at checkout
     *  - nama_lokasi_checkout: reverse-geocoded place name at checkout
     *
     * All columns are nullable so existing records are unaffected.
     */
    public function up(): void
    {
        Schema::table('kehadiran', function (Blueprint $table) {
            $table->string('file_bukti')->nullable()->after('foto_selfie');
            $table->string('foto_checkout')->nullable()->after('file_bukti');
            $table->decimal('koordinat_lat_checkout', 10, 7)->nullable()->after('foto_checkout');
            $table->decimal('koordinat_lng_checkout', 10, 7)->nullable()->after('koordinat_lat_checkout');
            $table->string('nama_lokasi_checkout')->nullable()->after('koordinat_lng_checkout');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kehadiran', function (Blueprint $table) {
            $table->dropColumn([
                'file_bukti',
                'foto_checkout',
                'koordinat_lat_checkout',
                'koordinat_lng_checkout',
                'nama_lokasi_checkout',
            ]);
        });
    }
};
