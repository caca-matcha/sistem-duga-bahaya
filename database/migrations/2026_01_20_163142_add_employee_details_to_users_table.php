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
        Schema::table('users', function (Blueprint $table) {
            $table->string('npk')->unique()->nullable()->after('id');
            $table->string('division')->nullable()->after('remember_token');
            $table->string('department')->nullable()->after('division');
            $table->string('organization_unit')->nullable()->after('department');
            $table->string('job_family')->nullable()->after('organization_unit');
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['npk', 'division', 'department', 'organization_unit', 'job_family']);
            $table->string('email')->nullable(false)->change();
        });
    }
};
