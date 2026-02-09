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
        Schema::table('locations', function (Blueprint $table) {
            $table->unsignedBigInteger('pic_id')->nullable()->after('map_id');
            $table->unsignedBigInteger('leader_id')->nullable()->after('pic_id');

            $table->foreign('pic_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('leader_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('hazards', function (Blueprint $table) {
            $table->unsignedBigInteger('pic_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('leader_id')->nullable()->after('pic_id');

            $table->foreign('pic_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('leader_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['pic_id']);
            $table->dropForeign(['leader_id']);
            $table->dropColumn(['pic_id', 'leader_id']);
        });

        Schema::table('hazards', function (Blueprint $table) {
            $table->dropForeign(['pic_id']);
            $table->dropForeign(['leader_id']);
            $table->dropColumn(['pic_id', 'leader_id']);
        });
    }
};
