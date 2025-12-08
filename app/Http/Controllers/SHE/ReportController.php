<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\Hazard;
use App\Http\Requests\SheUpdateHazardRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $hazardsBaru = Hazard::where('status', 'menunggu validasi')->latest()->with('pelapor', 'ditanganiOleh')->get();
        $hazardsDiproses = Hazard::where('status', 'diproses')->latest()->with('pelapor', 'ditanganiOleh')->paginate(10, ['*'], 'diproses_page');
        $hazardsSelesai = Hazard::whereIn('status', ['selesai', 'ditolak'])->latest()->with('pelapor', 'ditanganiOleh')->paginate(10, ['*'], 'selesai_page');

        return view('she.reports.index', compact('hazardsBaru', 'hazardsDiproses', 'hazardsSelesai'));
    }

    public function show(Hazard $hazard)
    {
        return view('she.reports.show', compact('hazard'));
    }

    public function update(SheUpdateHazardRequest $request, Hazard $hazard)
    {
        $validated = $request->validated();

        if ($request->filled(['tingkat_keparahan', 'kemungkinan_terjadi'])) {
            $validated['risk_score'] = $request->tingkat_keparahan * $request->kemungkinan_terjadi;
        }

        $validated['ditangani_oleh'] = Auth::id();
        $validated['ditangani_pada'] = now();

        if ($validated['status'] === 'ditolak') {
            $hazard->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $validated['alasan_penolakan'] ?? null,
                'ditangani_oleh' => Auth::id(),
                'ditangani_pada' => now(),
            ]);

            return redirect()->route('she.reports.index')->with('success', 'Laporan berhasil ditolak.');
        }

        if ($request->hasFile('foto_bukti_penyelesaian')) {
            if ($hazard->foto_bukti_penyelesaian) {
                Storage::disk('public')->delete($hazard->foto_bukti_penyelesaian);
            }

            $validated['foto_bukti_penyelesaian'] = $request->file('foto_bukti_penyelesaian')->store('completion_photos', 'public');
        }

        if ($validated['status'] === 'selesai') {
            $validated['report_selesai'] = now();
        }

        $hazard->update($validated);

        return redirect()->route('she.reports.show', $hazard)->with('success', 'Laporan berhasil diperbarui.');
    }

    public function tolakForm(Hazard $hazard)
    {
        return view('she.reports.tolak', compact('hazard'));
    }

    public function selesaiForm(Hazard $hazard)
    {
        return view('she.reports.selesai', compact('hazard'));
    }
}
