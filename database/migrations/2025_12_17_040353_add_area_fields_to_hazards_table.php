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
            $table->string('area_id')->nullable()->after('area_gedung');
            $table->string('area_name')->nullable()->after('area_id');
            $table->string('area_type')->nullable()->after('area_name');
            $table->dropColumn('aktivitas_kerja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hazards', function (Blueprint $table) {
            $table->dropColumn(['area_id', 'area_name', 'area_type']);
            $table->string('aktivitas_kerja')->nullable()->after('area_gedung');
        });
    }
};
