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
        Schema::table('hazards', function (Blueprint $table) {
            // Tambahkan kolom location_id
            $table->foreignId('location_id')->nullable()->after('area_id')->constrained('locations')->onDelete('set null');

            // Jadikan kolom area_gedung dan area_id nullable
            $table->string('area_gedung')->nullable()->change();
            $table->string('area_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('hazards', function (Blueprint $table) {
            // Hapus foreign key dan kolom location_id
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');

            // Untuk saat ini, kita tidak akan mengembalikan kolom area_gedung dan area_id
            // ke status non-nullable untuk menghindari potensi error jika sudah ada data null
            // $table->string('area_gedung')->nullable(false)->change();
            // $table->string('area_id')->nullable(false)->change();
        });
    }
};
