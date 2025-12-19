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
            if (!Schema::hasColumn('hazards', 'final_kategori_stop6')) {
                $table->string('final_kategori_stop6')->nullable()->after('ide_penanggulangan');
            }
            if (!Schema::hasColumn('hazards', 'final_tingkat_keparahan')) {
                $table->integer('final_tingkat_keparahan')->nullable()->after('final_kategori_stop6');
            }
            if (!Schema::hasColumn('hazards', 'final_kemungkinan_terjadi')) {
                $table->integer('final_kemungkinan_terjadi')->nullable()->after('final_tingkat_keparahan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hazards', function (Blueprint $table) {
            if (Schema::hasColumn('hazards', 'final_kategori_stop6')) {
                $table->dropColumn('final_kategori_stop6');
            }
            if (Schema::hasColumn('hazards', 'final_tingkat_keparahan')) {
                $table->dropColumn('final_tingkat_keparahan');
            }
            if (Schema::hasColumn('hazards', 'final_kemungkinan_terjadi')) {
                $table->dropColumn('final_kemungkinan_terjadi');
            }
        });
    }
};
