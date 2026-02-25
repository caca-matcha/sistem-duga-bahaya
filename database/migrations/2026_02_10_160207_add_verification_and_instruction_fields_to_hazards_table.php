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
            $table->text('rencana_perbaikan')->nullable()->after('final_kategori_stop6');
            $table->text('feedback_verifikasi')->nullable()->after('foto_bukti_penyelesaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hazards', function (Blueprint $table) {
            $table->dropColumn(['rencana_perbaikan', 'feedback_verifikasi']);
        });
    }
};
