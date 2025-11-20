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

            //pelapor (karyawan)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            //data karyawan saat melapor
            $table->string('nama');
            $table->string('NPK');
            $table->string('dept');

            //detail observasi
            $table->date('tgl_observasi');
            $table->string('area_gedung');
            $table->string('line');

            //detail bahaya
            $table->text('deskripsi_bahaya');
            $table->string('foto_temuan')->nullable();

            $table->string('jenis_bahaya');
            $table->string('faktor_penyebab');

            $table->integer('tingkat_keparahan');
            $table->integer('kemungkinan_terjadi');
            $table->integer('skor_resiko');

            $table->text('ide_penanggulangan');

            // FLOW : baru-> ditolak/diproses -> selesai

            $table->enum('status', ['baru', 'ditolak', 'diproses', 'selesai'])
            ->default('baru');

            $table->text('alasan_penolakan')->nullable();
            $table->text('report_selesai')->nullable();

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
