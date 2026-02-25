<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // WAJIB: Import untuk relasi BelongsTo

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
        'position',
        'tgl_observasi',
        'location_id',
        'area_gedung',
        'area_type',
        'area_name',
        'area_id',
        'map_id',
        'cell_id',
        'lokasi_detail_manual',
        'aktivitas_kerja',
        'deskripsi_bahaya',
        'foto_bukti',
        'kategori_stop6',
        'tingkat_keparahan',
        'kemungkinan_terjadi',
        'risk_score',
        'kategori_resiko',
        'ide_penanggulangan',
        'status', // Status awal
        'alasan_penolakan', // Untuk penolakan
        'ditangani_oleh',
        'ditangani_pada',
        'report_selesai',
        'target_penyelesaian',
        'pic_id',
        'pic_id',

        // --- FIELDS BARU SHE ---
        'faktor_penyebab',
        'upaya_penanggulangan',
        // 'catatan_penanggulangan',
        // 'pic_penanggung_jawab', (disamakan dengan ditangani_oleh)
        'final_tingkat_keparahan',
        'final_kemungkinan_terjadi',
        'final_kategori_stop6',
        'rencana_perbaikan',
        'feedback_verifikasi',
        'tindakan_perbaikan',
        'foto_bukti_penyelesaian',
    ];

    /**
     * Casts untuk field yang bertipe array/JSON.
     */
    protected $casts = [
        'upaya_penanggulangan' => 'json',
        //  	'catatan_penanggulangan' => 'json',
        'tgl_observasi' => 'date',
        'target_penyelesaian' => 'date',
        'ditangani_pada' => 'datetime',
        'report_selesai' => 'datetime',
    ];

    /**
     * Relasi ke User yang melaporkan bahaya (Pelapor).
     * Kolom foreign key: user_id
     */
    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke User (SHE Officer) yang memproses/menangani laporan.
     * Kolom foreign key: ditangani_oleh
     */
    public function ditanganiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }

    /**
     * Relasi ke User (PIC) yang bertanggung jawab.
     */
    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    /**
     * Relasi ke Cell.
     */
    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class);
    }

    /**
     * Relasi ke Location (Master Data Lokasi).
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Relasi ke Map.
     */
    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }
}
