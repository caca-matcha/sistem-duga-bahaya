<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $latestReports = Hazard::latest()->take(5)->get(); // Get the 5 latest reports

        // Logic for "Notifikasi area berbahaya"
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $dangerousAreas = Hazard::select('area_gedung', DB::raw('count(*) as hazard_count'))
                                ->where('created_at', '>=', $sevenDaysAgo)
                                ->groupBy('area_gedung')
                                ->having('hazard_count', '>', 3) // More than 3 hazards in the last 7 days
                                ->orderByDesc('hazard_count')
                                ->get();

        // Logic for "Grafik tingkat risiko (high, medium, low)"
        // MASIH BELOM DI COCOKKAN DENGAN KARYAWAN
        $riskCounts = [
            'low' => Hazard::where('risk_score', '<', 4)->count(),
            'medium' => Hazard::whereBetween('risk_score', [4, 7])->count(),
            'high' => Hazard::where('risk_score', '>=', 8)->count(),
        ];

        // Logic for "Top lokasi dengan risiko tertinggi"
        $topRiskLocations = Hazard::select('area_gedung', DB::raw('SUM(risk_score) as total_risk_score'))
                                  ->groupBy('area_gedung')
                                  ->orderByDesc('total_risk_score')
                                  ->take(5) // Get the top 5 locations
                                  ->get();


        dd($riskCounts, $topRiskLocations);

        return view('she.dashboard', compact('totalReports', 'validatedReports', 'latestReports', 'dangerousAreas', 'riskCounts', 'topRiskLocations'));
    }
}

