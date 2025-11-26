<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hazard;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard Karyawan, yang berisi ringkasan dan daftar laporan bahaya yang dibuat oleh user ini.
     */
    public function index()
    {
        // Mendapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // 1. Data untuk Tabel Daftar Laporan
        // Ambil semua laporan milik user yang sedang login.
        $hazardsQuery = Hazard::where('user_id', $userId)->get();

        // Mengolah data hazards untuk menambahkan kolom yang dibutuhkan (Skor Risiko).
        // Hasilnya adalah Illuminate\Support\Collection.
        $hazards = $hazardsQuery->map(function ($hazard) {
            // Hitung Skor Risiko: Rank Keparahan * Kemungkinan Terjadi
            // Asumsi kolom ini adalah integer atau float
            $hazard->skor_resiko = $hazard->rank_keparahan * $hazard->kemungkinan_terjadi;
            return $hazard;
        });

        // 2. Data untuk Kartu (Cards) Statistik
        // PENTING: Karena $hazards sudah berupa Collection, kita bisa menggunakan filter/where 
        // pada Collection untuk menghitung statistik di memori.
        
        $totalLaporan = $hazards->count();
        
        // Menggunakan where() pada Collection untuk menghitung laporan berdasarkan status
        // Pastikan nama status (Pending, Divalidasi, Ditolak) sesuai dengan data di database.
        $menungguValidasi = $hazards->where('status', 'Pending')->count();
        $sudahDivalidasi = $hazards->where('status', 'Divalidasi')->count();
        $ditolak = $hazards->where('status', 'Ditolak')->count(); 

        // Mengarahkan ke view karyawan.dashboard dengan data yang diperlukan
        return view('karyawan.dashboard', compact(
            'hazards', 
            'totalLaporan', 
            'menungguValidasi', 
            'sudahDivalidasi', 
            'ditolak'
        ));
    }
}