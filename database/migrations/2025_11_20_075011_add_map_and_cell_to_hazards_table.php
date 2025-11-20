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
            $table->unsignedBigInteger('map_id')->nullable()->after('user_id');
            $table->foreign('map_id')->references('id')->on('maps')->onDelete('set null');
            $table->unsignedBigInteger('cell_id')->nullable()->after('map_id');
            $table->foreign('cell_id')->references('id')->on('cells')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hazards', function (Blueprint $table) {
            $table->dropForeign(['cell_id']);
            $table->dropColumn('cell_id');
            $table->dropForeign(['map_id']);
            $table->dropColumn('map_id');
        });
    }
};
