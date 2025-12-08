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
            $table->text('tindakan_perbaikan')->nullable()->after('final_kemungkinan_terjadi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hazards', function (Blueprint $table) {
            $table->dropColumn('tindakan_perbaikan');
        });
    }
};
