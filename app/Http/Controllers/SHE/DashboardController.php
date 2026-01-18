<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\Hazard; // Import the Hazard model
use Carbon\Carbon; // Import Carbon for date manipulation
use Illuminate\Support\Facades\DB; // Import DB facade

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalReports = Hazard::count();
        $validatedReports = Hazard::where('status', 'selesai')->count();
        $latestReports = Hazard::latest()->take(20)->get(); // Get the 20 latest reports for scrolling

        // Logic for "Grafik tingkat risiko (high, medium, low)" - Grouping by stored category for accuracy
        $riskCounts = Hazard::select('kategori_resiko', DB::raw('count(*) as count'))
            ->whereNotNull('kategori_resiko') // Exclude hazards with no risk category
            ->groupBy('kategori_resiko')
            ->pluck('count', 'kategori_resiko');


        // Logic for "Top lokasi dengan risiko tertinggi"
        $topRiskLocations = Hazard::select('area_gedung', DB::raw('SUM(risk_score) as total_risk_score'))
            ->groupBy('area_gedung')
            ->orderByDesc('total_risk_score')
            ->take(5) // Get the top 5 locations
            ->get();

        // Logic for "Area Perlu Perhatian"
        $hazardsPerluPerhatian = Hazard::where('risk_score', '>', 15)
            ->whereIn('status', ['menunggu validasi', 'diproses'])
            ->latest()
            ->with('pelapor')
            ->get();

        return view('she.dashboard', compact('totalReports', 'validatedReports', 'latestReports', 'riskCounts', 'topRiskLocations', 'hazardsPerluPerhatian'));
    }
}
