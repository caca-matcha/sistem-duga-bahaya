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
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Base query for all hazards of the logged-in user
        $baseHazardsQuery = Hazard::where('user_id', $userId)
                                ->latest(); // Apply latest() once for consistent ordering

        // Clone the base query to calculate statistics from ALL hazards (unfiltered)
        $allHazardsForStats = clone $baseHazardsQuery;
        $allHazardsCollection = $allHazardsForStats->get();

        // Calculate statistics for cards from all hazards
        $totalLaporan = $allHazardsCollection->count();
        // Assuming your database status values are 'menunggu validasi', 'diproses', 'selesai', 'ditolak', 'disetujui'
        $menungguValidasi = $allHazardsCollection->where('status', 'menunggu validasi')->count();
        $diproses = $allHazardsCollection->where('status', 'diproses')->count();
        $disetujui = $allHazardsCollection->where('status', 'disetujui')->count();
        $selesai = $allHazardsCollection->where('status', 'selesai')->count();
        $ditolak = $allHazardsCollection->where('status', 'ditolak')->count();

        // Sum for 'Disetujui / Selesai' card
        $sudahDivalidasi = $disetujui + $selesai;


        // Apply search and filter conditions to the query for the table display
        $hazardsForTable = $baseHazardsQuery; // Start with the base query again

        // Search by description or area
        if ($search = $request->query('search')) {
            $hazardsForTable->where(function ($query) use ($search) {
                $query->where('deskripsi_bahaya', 'like', "%{$search}%")
                      ->orWhere('area_gedung', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->query('status')) {
            $hazardsForTable->where('status', $status);
        }

        $hazards = $hazardsForTable->get()->map(function ($hazard) {
            // Hitung Skor Risiko
            $hazard->risk_score = $hazard->tingkat_keparahan * $hazard->kemungkinan_terjadi;
            return $hazard;
        });

        return view('karyawan.dashboard', compact(
            'hazards',
            'totalLaporan',
            'menungguValidasi',
            'sudahDivalidasi',
            'ditolak',
            'diproses',
            'selesai',
            'disetujui'
        ));
    }
}