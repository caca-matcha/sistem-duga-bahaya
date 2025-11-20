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
        Schema::create('cells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('map_id')->constrained()->onDelete('cascade');
            $table->integer('row_index');
            $table->integer('col_index');
            $table->string('area_id')->nullable();
            $table->string('area_name')->nullable();
            $table->string('area_type')->nullable();
            $table->integer('risk_score')->nullable();
            $table->string('zone_color')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cells');
    }
};
