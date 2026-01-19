<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hazard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // IMPORT DB FACADE

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard Karyawan, yang berisi ringkasan dan daftar laporan bahaya yang dibuat oleh user ini.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // --- 1. STATISTIK (Dihitung dari semua data user tanpa filter) ---
        $allUserHazards = Hazard::where('user_id', $userId)->get();
        $totalLaporan = $allUserHazards->count();
        $menungguValidasi = $allUserHazards->where('status', 'menunggu validasi')->count();
        $diproses = $allUserHazards->where('status', 'diproses')->count();
        $selesai = $allUserHazards->where('status', 'selesai')->count();
        $ditolak = $allUserHazards->where('status', 'ditolak')->count();
        $sudahDivalidasi = $allUserHazards->where('status', 'selesai')->count(); // Or adjust logic as needed

        // --- 2. QUERY UTAMA DENGAN FILTER UNTUK TABEL ---
        $query = Hazard::where('user_id', $userId);

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Search Term
        if ($request->filled('search')) {
            $searchTerm = strtolower($request->search);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'LIKE', "%{$searchTerm}%")
                  ->orWhereRaw('LOWER(tgl_observasi) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(deskripsi_bahaya) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(area_gedung) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(area_name) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(status) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        // Ambil hasil yang sudah difilter dan paginasi
        $hazards = $query->latest()->paginate(10)->withQueryString();

        return view('karyawan.dashboard', [
            'hazards' => $hazards,
            'totalLaporan' => $totalLaporan,
            'menungguValidasi' => $menungguValidasi,
            'sudahDivalidasi' => $sudahDivalidasi,
            'ditolak' => $ditolak,
            'searchTerm' => $request->search ?? null, // Pass search term to view
        ]);
    }
}