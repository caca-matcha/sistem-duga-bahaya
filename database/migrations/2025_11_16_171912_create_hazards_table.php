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
    Schema::create('hazards', function (Blueprint $table) {
        $table->id();

        // Pelapor (karyawan)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // Data karyawan saat melapor
        $table->string('nama');
        $table->string('NPK');
        $table->string('dept');

        // Detail observasi
        $table->date('tgl_observasi');
        $table->string('area_gedung')->nullable();
        $table->string('area_id')->nullable();
        $table->string('aktivitas_kerja')->nullable();

        // Detail bahaya
        $table->text('deskripsi_bahaya');
        $table->string('foto_bukti')->nullable();
        $table->string('kategori_stop6')->nullable();
        $table->string('faktor_penyebab')->nullable();

        // Severity & Probability
        $table->integer('tingkat_keparahan')->nullable();
        $table->integer('kemungkinan_terjadi')->nullable();
        $table->integer('risk_score')->nullable();
        $table->string('kategori_resiko')->nullable();

        $table->text('ide_penanggulangan')->nullable();

        $table->integer('final_tingkat_keparahan')->nullable()->after('kategori_resiko');                                
        $table->integer(column: 'final_kemungkinan_terjadi')->nullable()->after('final_tingkat_keparahan');                      
        $table->string('final_kategori_stop6')->nullable()->after('final_kemungkinan_terjadi');   

        // Flow status
        $table->enum('status', ['menunggu validasi', 'ditolak', 'diproses', 'selesai'])
            ->default('menunggu validasi');

        $table->text('alasan_penolakan')->nullable();
        $table->timestamp('report_selesai')->nullable()->default(null);

        // SHE yang menangani
        $table->foreignId('ditangani_oleh')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamp('ditangani_pada')->nullable();
        $table->timestamps();

    });

    }

    public function down(): void
    {
            /**
     * Run the migrations.
     */
        Schema::dropIfExists('hazards');
    }
};
