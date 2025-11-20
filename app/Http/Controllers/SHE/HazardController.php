<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\Hazard;
use Illuminate\Http\Request;

class HazardController extends Controller
{
    // DASHBOARD: tampilkan semua laporan
    public function index(Request $request)
    {
        $query = Hazard::query();

        // Filtering by status
        if ($request->has('status') && $request->status !== 'semua') {
            if ($request->status === 'menunggu') {
                $query->whereNotIn('status', ['disetujui', 'ditolak', 'selesai']);
            } elseif ($request->status === 'divalidasi') {
                $query->whereIn('status', ['disetujui', 'diproses']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filtering by area
        if ($request->has('area') && !empty($request->area)) {
            $query->where('area_gedung', 'like', '%' . $request->area . '%');
        }

        // Searching
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', $searchTerm)
                  ->orWhere('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('area_gedung', 'like', '%' . $searchTerm . '%');
            });
        }

        $hazards = $query->latest()->with('pelapor')->paginate(10); // Paginate results

        return view('she.hazards.index', compact('hazards'));
    }

    // DETAIL LAPORAN
    public function show(Hazard $hazard)
    {
        return view('she.hazards.show', compact('hazard'));
    }

    // UPDATE STATUS UMUM (diproses / disetujui / ditolak / selesai)
    public function updateStatus(Request $request, Hazard $hazard)
    {
        $request->validate([
            'status' => 'required|in:diproses,disetujui,ditolak,selesai',
        ]);

        $hazard->status = $request->status;
        $hazard->ditangani_oleh = auth()->id();
        $hazard->ditangani_pada = now();
        $hazard->save();

        return redirect()->route('she.dashboard')
            ->with('success', 'Status berhasil diperbarui');
    }

    // FORM TOLAK
    public function tolakForm(Hazard $hazard)
    {
        return view('she.hazards.tolak', compact('hazard'));
    }

    // PROSES TOLAK
    public function tolak(Request $request, Hazard $hazard)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        $hazard->status = 'ditolak';
        $hazard->alasan_penolakan = $request->alasan_penolakan;
        $hazard->ditangani_oleh = auth()->id();
        $hazard->ditangani_pada = now();
        $hazard->save();

        return redirect()->route('she.dashboard')
            ->with('success', 'Laporan berhasil ditolak.');
    }

    // FORM SELESAI
    public function selesaiForm(Hazard $hazard)
    {
        return view('she.hazards.selesai', compact('hazard'));
    }

    // PROSES SELESAI
    public function selesai(Request $request, Hazard $hazard)
    {
        $hazard->status = 'selesai';
        $hazard->report_selesai = now();
        $hazard->ditangani_oleh = auth()->id();
        $hazard->ditangani_pada = now();
        $hazard->save();

        return redirect()->route('she.dashboard')
            ->with('success', 'Laporan selesai ditindaklanjuti.');
    }
}