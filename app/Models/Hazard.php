<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hazard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'NPK',
        'dept',
        'tgl_observasi',
        'area_gedung',
        'line',
        'deskripsi_bahaya',
        'foto_temuan',
        'jenis_bahaya',
        'faktor_penyebab',
        'tingkat_keparahan',
        'kemungkinan_terjadi',
        'skor_resiko',
        'ide_penanggulangan',
        'status',
        'alasan_penolakan',
        'report_selesai',
        'ditangani_oleh',
        'ditangani_pada',
        'map_id', // Added for map linkage
        'cell_id', // Added for cell linkage
    ];

    // Relationship to Map
    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    // Relationship to Cell
    public function cell()
    {
        return $this->belongsTo(Cell::class);
    }

    //pelapor (karyawan)
    public function pelapor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //SHE yang menangani
    public function penanganan()
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }
}
