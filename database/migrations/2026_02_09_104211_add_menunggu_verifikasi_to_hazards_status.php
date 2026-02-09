<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * This migration adds 'menunggu verifikasi' to the status ENUM column.
     * This is needed for existing databases that were created before this status existed.
     * Note: Only runs on MySQL as SQLite doesn't support ENUM type.
     */
    public function up(): void
    {
        // Only run on MySQL - SQLite doesn't support ENUM
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE hazards MODIFY COLUMN status ENUM('menunggu validasi', 'ditolak', 'diproses', 'menunggu verifikasi', 'selesai') DEFAULT 'menunggu validasi'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only run on MySQL - SQLite doesn't support ENUM
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            // First, update any records with 'menunggu verifikasi' to 'diproses' before removing the enum value
            DB::statement("UPDATE hazards SET status = 'diproses' WHERE status = 'menunggu verifikasi'");

            // Revert to original ENUM without 'menunggu verifikasi'
            DB::statement("ALTER TABLE hazards MODIFY COLUMN status ENUM('menunggu validasi', 'ditolak', 'diproses', 'selesai') DEFAULT 'menunggu validasi'");
        }
    }
};
