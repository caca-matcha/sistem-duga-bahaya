<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hazard extends Model
{
    use HasFactory;

    /**
     * Field yang diizinkan untuk Mass Assignment.
     * Sudah termasuk field dari Karyawan dan SHE.
     */
    protected $fillable = [
        // Fields dari Karyawan
        'user_id',
        'nama',
        'NPK',
        'dept',
        'tgl_observasi',
        'area_gedung',
        'aktivitas_kerja',
        'deskripsi_bahaya',
        'foto_bukti',
        'tingkat_keparahan',
        'kemungkinan_terjadi',
        'skor_resiko',
        'kategori_resiko',
        'ide_penanggulangan',
        'status', // Status awal
        'status', 
        'alasan_penolakan', // Untuk penolakan
        'ditangani_oleh',
        'ditangani_pada',
        'report_selesai',
        // --- FIELDS BARU SHE ---
        'jenis_bahaya',
        'faktor_penyebab',
        'upaya_penanggulangan',
        'catatan_penanggulangan',
        'resiko_residual',
        'pic_penanggung_jawab',
        'target_penyelesaian',
        'foto_bukti_penyelesaian',
        ];

    /**
     * Casts untuk field yang bertipe array/JSON.
     */
    protected $casts = [
        'upaya_penanggulangan' => 'json',
        'catatan_penanggulangan' => 'json',
        'tgl_observasi' => 'date',
        'target_penyelesaian' => 'date',
    ];
}
