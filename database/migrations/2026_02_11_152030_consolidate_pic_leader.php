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
            // 1. Migrate data: If pic_id is null but leader_id exists, move leader_id to pic_id
            DB::statement('UPDATE hazards SET pic_id = leader_id WHERE pic_id IS NULL AND leader_id IS NOT NULL');

            // 2. Drop constraint first if exists (though usually it's just an index, better safe)
            // On SQLite/some MySQL configs dropping foreign key might be needed
            $table->dropForeign(['leader_id']);

            // 3. Drop column
            $table->dropColumn('leader_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hazards', function (Blueprint $table) {
            $table->foreignId('leader_id')->nullable()->constrained('users');
        });
    }
};
