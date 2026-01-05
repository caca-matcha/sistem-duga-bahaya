<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cells', function (Blueprint $table) {
            // Tambahkan kolom location_id
            $table->foreignId('location_id')->nullable()->after('col_index')->constrained('locations')->onDelete('set null');

            // Jadikan kolom area_id, area_name, area_type nullable
            $table->string('area_id')->nullable()->change();
            $table->string('area_name')->nullable()->change();
            $table->string('area_type')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cells', function (Blueprint $table) {
            // Hapus foreign key dan kolom location_id
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');

            // Untuk saat ini, kita tidak akan mengembalikan kolom area_id, area_name, area_type
            // ke status non-nullable untuk menghindari potensi error jika sudah ada data null
            // $table->string('area_id')->nullable(false)->change();
            // $table->string('area_name')->nullable(false)->change();
            // $table->string('area_type')->nullable(false)->change();
        });
    }
};
