<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hazards', function (Blueprint $table) {
            $table->string('foto_bukti')->nullable()->after('deskripsi_bahaya');
        });
    }

    public function down(): void
    {
        Schema::table('hazards', function (Blueprint $table) {
            $table->dropColumn('foto_bukti');
        });
    }
};