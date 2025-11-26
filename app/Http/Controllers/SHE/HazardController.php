<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\Hazard;
use App\Http\Requests\SheUpdateHazardRequest; // Import Form Request SHE
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class HazardController extends Controller
{
    // DASHBOARD: tampilkan semua laporan
    public function index(Request $request)
    {
        $query = Hazard::query();

        // Asumsi: Anda juga perlu menampilkan informasi penanggung jawab/pelapor
        $hazards = $query->latest()->with('pelapor', 'ditanganiOleh')->paginate(10); 

        return view('she.hazards.index', compact('hazards'));
    }

    // DETAIL LAPORAN
    public function show(Hazard $hazard)
    {
        return view('she.hazards.show', compact('hazard'));
    }

    /**
     * Memproses pembaruan (review, tindak lanjut, penolakan, atau penyelesaian)
     * laporan bahaya oleh SHE.
     */
    public function update(SheUpdateHazardRequest $request, Hazard $hazard)
    {
        // Data sudah divalidasi, termasuk array upaya penanggulangan dan catatan
        $validatedData = $request->validated();

        $nilai = $request->tingkat_keparahan * $request->kemungkinan_terjadi;

        // Data Sistem
        $validatedData['ditangani_oleh'] = Auth::id();
        $validatedData['ditangani_pada'] = now();
        
        // Jika status = 'selesai', catat waktu penyelesaian
        if ($validatedData['status'] === 'selesai') {
             $validatedData['report_selesai'] = now();
        }

        // 1. --- HANDLE FILE UPLOAD (FOTO BUKTI PENYELESAIAN) ---
        if ($request->hasFile('foto_bukti_penyelesaian')) {
            
            // Hapus foto lama jika ada
            if ($hazard->foto_bukti_penyelesaian) {
                Storage::disk('public')->delete($hazard->foto_bukti_penyelesaian);
            }

            // Simpan file baru ke storage
            $path = $request->file('foto_bukti_penyelesaian')
                            ->store('completion_photos', 'public');
            
            $validatedData['foto_bukti_penyelesaian'] = $path;
        }

        // Jika status ditolak, kita hanya perlu menyimpan status dan alasan penolakan
        if ($validatedData['status'] === 'ditolak') {
            // Kita hanya perlu mengambil alasan_penolakan dan status saja
            $dataToUpdate = [
                'status' => 'ditolak',
                'alasan_penolakan' => $validatedData['alasan_penolakan'] ?? null,
                'ditangani_oleh' => Auth::id(),
                'ditangani_pada' => now(),
            ];
            // Semua field lain akan diabaikan
            $hazard->update($dataToUpdate);
            
            return redirect()->route('she.dashboard')
                ->with('success', 'Laporan berhasil ditolak.');
        } 
        
        // 2. --- UPDATE RECORD DI DATABASE UNTUK STATUS LAIN (diproses, disetujui, selesai) ---
        // Karena data array (upaya_penanggulangan, catatan_penanggulangan) 
        // sudah ada di $validatedData, Hazard Model dengan JSON casts akan menanganinya.
        
        $hazard->update($validatedData);

        // 3. --- REDIRECT ATAU RESPON ---
        return redirect()->route('she.hazards.show', $hazard)
            ->with('success', 'Laporan Duga Bahaya berhasil ditinjau dan diperbarui.');
    }


    // --- METHODS YANG SEKARANG REDUNDAN, BISA DIHAPUS DARI ROUTE ---
    
    // FORM TOLAK (Tetap dipertahankan karena ini adalah view)
    public function tolakForm(Hazard $hazard)
    {
        return view('she.hazards.tolak', compact('hazard'));
    }

    // PROSES TOLAK (Telah digabung ke method update)
    /*
    public function tolak(Request $request, Hazard $hazard)
    {
        // LOGIC PINDAH KE METHOD UPDATE
    }
    */
    
    // FORM SELESAI (Tetap dipertahankan karena ini adalah view)
    public function selesaiForm(Hazard $hazard)
    {
        return view('she.hazards.selesai', compact('hazard'));
    }

    // PROSES SELESAI (Telah digabung ke method update)
    /*
    public function selesai(Request $request, Hazard $hazard)
    {
        // LOGIC PINDAH KE METHOD UPDATE
    }
    */
}