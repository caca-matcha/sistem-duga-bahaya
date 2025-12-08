<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('hazards', function (Blueprint $table) {
            if (!Schema::hasColumn('hazards', 'final_tingkat_keparahan')) {
                $table->tinyInteger('final_tingkat_keparahan')->nullable();
            }
            if (!Schema::hasColumn('hazards', 'final_kemungkinan_terjadi')) {
                $table->tinyInteger('final_kemungkinan_terjadi')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('hazards', function (Blueprint $table) {
            if (Schema::hasColumn('hazards', 'final_tingkat_keparahan')) {
                $table->dropColumn('final_tingkat_keparahan');
            }
            if (Schema::hasColumn('hazards', 'final_kemungkinan_terjadi')) {
                $table->dropColumn('final_kemungkinan_terjadi');
            }
        });
    }};