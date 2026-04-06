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
        $processedReports = Hazard::where('status', 'diproses')->count();
        $pendingReports = Hazard::where('status', 'menunggu validasi')->count();
        $latestReports = Hazard::where('created_at', '>=', Carbon::now()->subDays(7))
            ->latest()
            ->take(20)
            ->get();

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

        // Logic for Overdue and Due Soon Hazards
        $allPendingActionHazards = Hazard::where('status', 'diproses')
            ->whereNotNull('target_penyelesaian')
            ->with('pelapor')
            ->get();

        $overdueHazards = $allPendingActionHazards->filter(function ($hazard) {
            return Carbon::parse($hazard->target_penyelesaian)->isPast();
        });

        $dueSoonHazards = $allPendingActionHazards->filter(function ($hazard) {
            return Carbon::parse($hazard->target_penyelesaian)->isFuture() &&
                Carbon::parse($hazard->target_penyelesaian)->diffInDays(Carbon::now()) <= 3;
        });

        // Logic for "Distribusi Kategori STOP6" (Top 5)
        $stop6Counts = Hazard::select('kategori_stop6', DB::raw('count(*) as count'))
            ->whereNotNull('kategori_stop6')
            ->groupBy('kategori_stop6')
            ->orderByDesc('count')
            ->take(5)
            ->pluck('count', 'kategori_stop6');

        return view('she.dashboard', compact(
            'totalReports', 'validatedReports', 'processedReports', 'pendingReports', 
            'latestReports', 'riskCounts', 'topRiskLocations', 'hazardsPerluPerhatian', 
            'overdueHazards', 'dueSoonHazards', 'stop6Counts'
        ));
    }
}
