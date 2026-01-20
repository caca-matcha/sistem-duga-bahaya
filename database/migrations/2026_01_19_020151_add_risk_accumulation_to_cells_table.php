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
        Schema::table('cells', function (Blueprint $table) {
            $table->bigInteger('total_hazard_risk_score')->default(0)->after('risk_score');
            $table->integer('hazard_count')->default(0)->after('total_hazard_risk_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cells', function (Blueprint $table) {
            $table->dropColumn(['total_hazard_risk_score', 'hazard_count']);
        });
    }
};
