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
            // Drop the foreign key constraint first
            $table->dropForeign(['parent_id']);
            // Then drop the column
            $table->dropColumn('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Re-add the column and the foreign key constraint
            $table->foreignId('parent_id')->nullable()->constrained('locations')->onDelete('cascade')->after('type');
        });
    }
};
