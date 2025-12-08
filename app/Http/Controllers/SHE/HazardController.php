<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\Hazard;
use App\Http\Requests\SheUpdateHazardRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class HazardController extends Controller
{
    // DASHBOARD: tampilkan semua laporan
    public function index(Request $request)
    {
        // ... (Logika index tetap sama)
        $hazardsBaru = Hazard::where('status', 'menunggu validasi')
            ->latest()
            ->with('pelapor', 'ditanganiOleh')
            ->get();

        $hazardsDiproses = Hazard::where('status', 'diproses')
            ->latest()
            ->with('pelapor', 'ditanganiOleh')
            ->paginate(10, ['*'], 'diproses_page');

        $hazardsSelesai = Hazard::whereIn('status', ['selesai', 'ditolak'])
            ->latest()
            ->with('pelapor', 'ditanganiOleh')
            ->paginate(10, ['*'], 'selesai_page');

        return view('she.hazards.index', compact('hazardsBaru', 'hazardsDiproses', 'hazardsSelesai'));
    }

    /**
     * Menyimpan laporan duga bahaya baru yang dikirim oleh karyawan.
     * Metode ini menerima semua input termasuk risk_score dan kategori_resiko (dari JS).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. VALIDASI DATA
        // Pastikan nama input di sini sesuai dengan atribut 'name' di formulir create.blade.php
        $validatedData = $request->validate([
            'NPK' => ['required', 'string', 'max:255'],
            'dept' => ['required', 'string'],
            'tgl_observasi' => ['required', 'date'],
            'area_gedung' => ['required', 'string'],
            'aktivitas_kerja' => ['required', 'string'],
            'kategori_stop6' => ['required', 'string'],
            'ide_penanggulangan' => ['required', 'string'],
            'foto_bukti' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'faktor_penyebab' => ['required', 'string'],
            // Input yang terlihat oleh user
            'severity' => ['required', 'integer', 'min:1', 'max:5'],
            'probability' => ['required', 'integer', 'min:1', 'max:5'],
            'deskripsi_bahaya' => ['required', 'string', 'max:500'],
            
            // Input tersembunyi (dihitung oleh JS dan di POST)
            'risk_score' => ['required', 'integer', 'min:1', 'max:25'],
            'kategori_resiko' => ['required', 'string', 'in:Low,Medium,High,Belum Dihitung'],
        ]);

        // 2. PROSES PENYIMPANAN KE DATABASE
        try {
            $hazard = new Hazard();
            
            // Mengisi data dari request
            $hazard->nama = Auth::user()->name;
            $hazard->NPK = $validatedData['NPK'];
            $hazard->dept = $validatedData['dept'];
            $hazard->area_gedung = $validatedData['area_gedung'];
            $hazard->aktivitas_kerja = $validatedData['aktivitas_kerja'];
            $hazard->severity = $validatedData['severity'];
            $hazard->probability = $validatedData['probability'];
            $hazard->deskripsi_bahaya = $validatedData['deskripsi_bahaya'];
            $hazard->risk_score = $validatedData['risk_score'];
            $hazard->kategori_resiko = $validatedData['kategori_resiko'];
            $hazard->kategori_stop6 = $validatedData['kategori_stop6'];
            $hazard->ide_penanggulangan = $validatedData['ide_penanggulangan'];
            $hazard->faktor_penyebab = $validatedData['faktor_penyebab'];
            
            if ($request->hasFile('foto_bukti')) {
                $hazard->foto_bukti = $request->file('foto_bukti')->store('hazard_photos', 'public');
            }
            
            // Mengisi data otomatis oleh server
            $hazard->tgl_observasi = $validatedData['tgl_observasi'];
            $hazard->status = 'menunggu validasi'; // Semua laporan baru memiliki status 'menunggu validasi'
            $hazard->user_id = Auth::id(); // User yang sedang login adalah pelapor
            
            $hazard->save();

            // 3. REDIRECT DAN PESAN SUKSES
            return redirect()
                ->route('she.hazards.index')
                ->with('success', 'Laporan Duga Bahaya berhasil dikirim. Menunggu review SHE.');

        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah saat menyimpan
            // Catatan: Dalam produksi, log error daripada menampilkan pesan error sensitif.
            return back()->withInput()->with('error', 'Gagal menyimpan laporan. Silakan coba lagi.');
        }
    }


    // DETAIL
    public function show(Hazard $hazard)
    {
        $hazard->load(['pelapor', 'ditanganiOleh']);
        return view('she.hazards.show', compact('hazard'));
    }

    // UPDATE LAPORAN OLEH SHE (Menangani Tolak, Proses, Selesai)
public function updateStatus(SheUpdateHazardRequest $request, Hazard $hazard)
{
    $validated = $request->validated();

    // --- 1. OTOMATISASI DATA PENANGANAN ---
    $validated['ditangani_oleh'] = Auth::id(); // ID User SHE yang memproses
    $validated['ditangani_pada'] = now();

    /* ----------------------------------------------------------
     * 2. HANDLE LOGIKA VALIDASI (Status = diproses)
     * - Hitung Final Risk Score
     * - Gunakan final_tingkat_keparahan & final_kemungkinan_terjadi
     *   jika ada, jika tidak gunakan nilai awal.
     * ---------------------------------------------------------- */
    if ($validated['status'] === 'diproses') {
        $finalSeverity = $validated['final_tingkat_keparahan'] ?? $hazard->tingkat_keparahan;
        $finalProbability = $validated['final_kemungkinan_terjadi'] ?? $hazard->kemungkinan_terjadi;

        $validated['risk_score'] = $finalSeverity * $finalProbability;

        // Tentukan kategori risiko berdasarkan risk_score
        $risk = $validated['risk_score'];
        if ($risk <= 4) $validated['kategori_resiko'] = 'Low';
        elseif ($risk <= 9) $validated['kategori_resiko'] = 'Medium';
        elseif ($risk <= 15) $validated['kategori_resiko'] = 'Medium-High';
        elseif ($risk <= 20) $validated['kategori_resiko'] = 'High';
        else $validated['kategori_resiko'] = 'Extreme';

        // Simpan final values ke DB jika kolom ada
        $hazard->final_tingkat_keparahan = $validated['final_tingkat_keparahan'] ?? null;
        $hazard->final_kemungkinan_terjadi = $validated['final_kemungkinan_terjadi'] ?? null;

        unset($validated['final_tingkat_keparahan'], $validated['final_kemungkinan_terjadi']);
    }

    /* ----------------------------------------------------------
     * 3. HANDLE LOGIKA PENOLAKAN (Status = ditolak)
     * ---------------------------------------------------------- */
    if ($validated['status'] === 'ditolak') {
        $hazard->update(array_merge($validated, [
            'status' => 'ditolak',
        ]));

        return redirect()
            ->route('she.hazards.show', $hazard)
            ->with('success', 'Laporan berhasil ditolak.');
    }

    /* ----------------------------------------------------------
     * 4. HANDLE LOGIKA SELESAI (Status = selesai)
     * ---------------------------------------------------------- */
    if ($validated['status'] === 'selesai') {
        $validated['report_selesai'] = now();

        if ($request->hasFile('foto_bukti_penyelesaian')) {
            if ($hazard->foto_bukti_penyelesaian) {
                Storage::disk('public')->delete($hazard->foto_bukti_penyelesaian);
            }

            $validated['foto_bukti_penyelesaian'] = $request
                ->file('foto_bukti_penyelesaian')
                ->store('completion_photos', 'public');
        }
    }

    /* ----------------------------------------------------------
     * 5. UPDATE GENERAL (Status: diproses atau selesai)
     * ---------------------------------------------------------- */
    $hazard->update($validated);
    $hazard->save(); // pastikan final values ikut tersimpan

    return redirect()
        ->route('she.hazards.show', $hazard)
        ->with('success', 'Laporan berhasil diperbarui ke status: ' . ucfirst($validated['status']) . '.');
}

    // ===============================================
    // METODE VIEW FORM UNTUK UPDATE STATUS
    // ===============================================

    // VIEW FORM DIPROSES (Validasi dan Rencana Tindakan)
    public function diprosesForm(Hazard $hazard)
    {
        // Pengecekan stabilitas: Form ini hanya boleh diakses jika status 'baru'.
        if ($hazard->status !== 'menunggu validasi') {
            return redirect()->route('she.hazards.show', $hazard)
                             ->with('error', 'Laporan harus berstatus BARU untuk diproses.');
        }
        return view('she.hazards.diproses', compact('hazard'));
    }

    // VIEW FORM PENOLAKAN
    public function tolakForm(Hazard $hazard)
    {
        // Pengecekan stabilitas: Penolakan hanya bisa dilakukan jika status masih 'baru'.
        if ($hazard->status !== 'menunggu validasi') {
            return redirect()->route('she.hazards.show', $hazard)
                             ->with('error', 'Penolakan hanya bisa dilakukan pada laporan berstatus BARU.');
        }
        return view('she.hazards.tolak', compact('hazard'));
    }

    // VIEW FORM SELESAI
    public function selesaiForm(Hazard $hazard)
    {
        // Pengecekan stabilitas: Penyelesaian hanya bisa dilakukan jika status sudah 'diproses'.
        if ($hazard->status !== 'diproses') {
            return redirect()->route('she.hazards.show', $hazard)
                             ->with('error', 'Laporan harus berstatus DIPROSES untuk diselesaikan.');
        }
        return view('she.hazards.selesai', compact('hazard'));
    }
}