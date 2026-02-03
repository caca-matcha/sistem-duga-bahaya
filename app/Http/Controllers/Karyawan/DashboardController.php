<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Hazard;
use Illuminate\Http\Request;
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

            // Translate Indonesian months to English for date search
            $indoMonths = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
            $engMonths = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
            $searchTermEnglish = str_replace($indoMonths, $engMonths, $searchTerm);

            $query->where(function ($q) use ($searchTerm, $searchTermEnglish) {
                $q->where('id', 'LIKE', "%{$searchTerm}%")
                    ->orWhereRaw('LOWER(tgl_observasi) LIKE ?', ["%{$searchTerm}%"]) // Matches 2026-02-03
                    ->orWhereRaw("LOWER(DATE_FORMAT(tgl_observasi, '%d %M %Y')) LIKE ?", ["%{$searchTermEnglish}%"]) // Matches 03 February 2026
                    ->orWhereRaw("LOWER(DATE_FORMAT(tgl_observasi, '%W, %d %M %Y')) LIKE ?", ["%{$searchTermEnglish}%"]) // Matches Tuesday, 03 February 2026
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
