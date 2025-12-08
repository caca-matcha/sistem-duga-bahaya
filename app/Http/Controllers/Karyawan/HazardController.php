<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Hazard;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreHazardRequest;

class HazardController extends Controller
{
    /**
     * Menampilkan daftar laporan bahaya yang dibuat oleh Karyawan ini,
     * sekaligus menghitung statistik untuk dashboard.
     */
    public function index()
    {
        // Ambil semua laporan milik user yang sedang login
        $allHazards = Hazard::where('user_id', Auth::id())->get();

        // Hitung statistik
        $totalLaporan = $allHazards->count();
        // Laporan menunggu validasi/tinjauan awal
        $menungguValidasi = $allHazards->whereIn('status', ['menunggu validasi', 'diproses'])->count(); 
        
        // Laporan yang sudah ditindaklanjuti (Disetujui, Selesai)
        $sudahDivalidasi = $allHazards->whereIn('status', ['disetujui', 'selesai'])->count(); 
        
        // Laporan yang ditolak
        $ditolak = $allHazards->where('status', 'ditolak')->count();

        // Kirim variabel yang dibutuhkan ke view
        return view('karyawan.dashboard', [
            'hazards' => $allHazards,
            'totalLaporan' => $totalLaporan,
            'menungguValidasi' => $menungguValidasi,
            'sudahDivalidasi' => $sudahDivalidasi,
            'ditolak' => $ditolak,
        ]);
    }

    /**
     * Menampilkan formulir untuk membuat laporan bahaya baru.
     */
    public function create()
    {
        return view('karyawan.hazards.create');
    }

    /**
     * Menyimpan laporan bahaya baru ke database.
     */
    public function store(StoreHazardRequest $request)
    {
        // Data yang sudah dijamin valid
        $validated = $request->validated();
        $filePath = null;

        if ($request->hasFile('foto_bukti')) {
            // Simpan file ke storage (misalnya, 'public/hazard_photos')
            $filePath = $request->file('foto_bukti')->store('hazard_photos', 'public');
        }

        // Hitung skor risiko
        $riskScore = $validated['tingkat_keparahan'] * $validated['kemungkinan_terjadi'];

        Hazard::create([
            'user_id' => Auth::id(),
            'nama' => Auth::user()->name,
            'NPK' => $validated['NPK'],
            'dept' => $validated['dept'],
            'tgl_observasi' => $validated['tgl_observasi'],
            'area_gedung' => $validated['area_gedung'],
            'aktivitas_kerja' => $validated['aktivitas_kerja'],
            'deskripsi_bahaya' => $validated['deskripsi_bahaya'],
            'foto_bukti' => $filePath,
            'kategori_stop6' => $validated['kategori_stop6'],
            'tingkat_keparahan' => $validated['tingkat_keparahan'],
            'kemungkinan_terjadi' => $validated['kemungkinan_terjadi'],
            // Hitung skor risiko
            'risk_score' => $riskScore, 
            'kategori_resiko' => $validated['kategori_resiko'],
            'ide_penanggulangan' => $validated['ide_penanggulangan'], 
            'status' => 'menunggu validasi', // Status awal saat dikirim
        ]);
        
        // Redirect ke index/dashboard setelah berhasil
        return redirect()->route('karyawan.dashboard')
            ->with('success', 'Laporan Duga Bahaya berhasil dikirim. Menunggu tinjauan SHE.');
    }

    /**
     * Menampilkan detail laporan bahaya tertentu.
     */
    public function show(Hazard $hazard)
    {
        // Pastikan hanya pemilik laporan yang bisa melihat
        if ($hazard->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $hazard->load(['pelapor', 'ditanganiOleh']); // Eager load relationships
        return view('karyawan.hazards.show', compact('hazard'));
    }
}