<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\Hazard;
use App\Http\Requests\SheUpdateHazardRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Cell; // Import Cell Model
use function App\Helpers\getRiskColor;

class HazardController extends Controller
{
    // DASHBOARD: tampilkan semua laporan
    public function index(Request $request)
    {
        // ... (Logika index tetap sama)
        $hazardsMenungguValidasi = Hazard::where('status', 'menunggu validasi')
            ->latest()
            ->with('pelapor', 'ditanganiOleh')
            ->get();

        $hazardsDiproses = Hazard::where('status', 'diproses')
            ->latest()
            ->with('pelapor', 'ditanganiOleh')
            ->paginate(10, ['*'], 'diproses_page');

        $hazardsSelesai = Hazard::whereIn('status', ['selesai', 'ditolak'])
            ->orderBy('ditangani_pada', 'desc')
            ->with('pelapor', 'ditanganiOleh')
            ->paginate(10, ['*'], 'selesai_page');

        return view('she.hazards.index', compact('hazardsMenungguValidasi', 'hazardsDiproses', 'hazardsSelesai'));
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
            'area_type' => ['required', 'string'],
            'area_name' => ['required', 'string'],
            'area_id' => ['required', 'string'],
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
    
    // SUBMIT VALIDASI (STEP 1 DARI FORM DIPROSES)
    public function submitValidasi(Request $request, Hazard $hazard)
    {
        // Validasi hanya bagian yang relevan dari form diproses
        $validated = $request->validate([
            'faktor_penyebab' => 'required|string|max:100',
            'final_tingkat_keparahan' => 'required|integer|in:1,3,5',
            'final_kemungkinan_terjadi' => 'required|integer|in:1,2,3,4,5',
            'final_kategori_stop6' => 'required|string|max:255',
        ]);

        // Simpan data yang divalidasi ke session untuk dibawa ke step berikutnya
        $request->session()->flash('validated_data', $validated);

        // Redirect ke form tindak lanjut
        return redirect()->route('she.hazards.denganTindakLanjut', $hazard);
    }

    // SUBMIT VALIDASI (STEP 1 DARI FORM DIPROSES, JALUR TANPA TINDAK LANJUT)
    public function submitValidasiTanpaTindakLanjut(Request $request, Hazard $hazard)
    {
        // Validasi hanya bagian yang relevan dari form diproses
        $validated = $request->validate([
            'faktor_penyebab' => 'required|string|max:100',
            'final_tingkat_keparahan' => 'required|integer|in:1,3,5',
            'final_kemungkinan_terjadi' => 'required|integer|in:1,2,3,4,5',
            'final_kategori_stop6' => 'required|string|max:255',
        ]);

        // Simpan data yang divalidasi ke session untuk dibawa ke step berikutnya
        $request->session()->flash('validated_data', $validated);

        // Redirect ke form tindak lanjut
        return redirect()->route('she.hazards.tanpaTindakLanjut', $hazard);
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
        $finalKategoriStop6 = $validated['final_kategori_stop6'] ?? $hazard->kategori_stop6;
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
        $hazard->final_kategori_stop6 = $validated['final_kategori_stop6']?? null;

        unset($validated['final_tingkat_keparahan'], $validated['final_kemungkinan_terjadi']);
        unset($validated['pic_penanggung_jawab']); // hapus jika ada
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

    // --- RECALCULATE AND UPDATE CELL RISK SCORE & ZONE COLOR ---
    if ($hazard->cell_id) { // Only proceed if the hazard is linked to a cell
        $cell = $hazard->cell; // Retrieve the associated Cell model (using the cell() relationship)

        if ($cell) {
            // Get all 'active' hazards (diproses or selesai) associated with this cell
            $activeHazards = Hazard::where('cell_id', $cell->id)
                                    ->whereIn('status', ['diproses', 'selesai'])
                                    ->get();

            if ($activeHazards->count() > 0) {
                // Calculate average risk score for the cell
                // We will use the final_tingkat_keparahan and final_kemungkinan_terjadi
                // of each hazard if available, otherwise fall back to initial.
                $totalRiskScore = 0;
                foreach ($activeHazards as $ah) {
                    $sev = $ah->final_tingkat_keparahan ?? $ah->tingkat_keparahan;
                    $prob = $ah->final_kemungkinan_terjadi ?? $ah->kemungkinan_terjadi;
                    $totalRiskScore += ($sev * $prob);
                }
                $averageRiskScore = round($totalRiskScore / $activeHazards->count());
                $cell->risk_score = $averageRiskScore;

                $cell->zone_color = getRiskColor($averageRiskScore);
            } else {
                // If no active hazards, reset cell risk
                $cell->risk_score = 0;
                $cell->zone_color = '#ffffff'; // White or default for no risk
            }

            $cell->save(); // Save the updated cell
        }
    }
    // Custom redirect for 'diproses' status
    if ($validated['status'] === 'diproses') {
        return redirect()
            ->route('she.hazards.index')
            ->with('success', 'Laporan dengan tindak lanjut telah berhasil disubmit.');
    }

    // Custom redirect for 'selesai' from 'tanpa tindak lanjut' form
    if ($validated['status'] === 'selesai' && isset($validated['tindakan_perbaikan']) && $validated['tindakan_perbaikan'] === 'Validasi tanpa tindak lanjut.') {
        return redirect()
            ->route('she.hazards.index')
            ->with('success', 'Laporan telah diselesaikan tanpa tindak lanjut.');
    }

    // Default redirect for other statuses
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
 // VIEW FORM DENGAN TINDAK LANJUT
    public function denganTindakLanjutForm(Request $request, Hazard $hazard)
    {
        // Ambil data dari session yang di-flash oleh submitValidasi
        $validatedData = $request->session()->get('validated_data');

        // Jika data tidak ada (misal, user akses URL langsung), redirect kembali
        if (!$validatedData) {
            return redirect()->route('she.hazards.diprosesForm', $hazard)->with('error', 'Silakan isi form validasi terlebih dahulu.');
        }

        $final_tingkat_keparahan = $validatedData['final_tingkat_keparahan'];
        $final_kemungkinan_terjadi = $validatedData['final_kemungkinan_terjadi'];
        $faktor_penyebab = $validatedData['faktor_penyebab'];
        $final_kategori_stop6 = $validatedData['final_kategori_stop6']; // Tambahkan ini

        // Hitung skor risiko di backend
        $final_risk_score = (int)$final_tingkat_keparahan * (int)$final_kemungkinan_terjadi;

        return view('she.hazards.dengan_tindaklanjut', compact(
            'hazard',
            'final_tingkat_keparahan',
            'final_kemungkinan_terjadi',
            'final_risk_score',
            'faktor_penyebab',
            'final_kategori_stop6' // Tambahkan ini
        ));
    }

    // VIEW FORM TANPA TINDAK LANJUT
    public function tanpaTindakLanjutForm(Request $request, Hazard $hazard)
    {
        // Ambil data dari session yang di-flash oleh submitValidasi
        $validatedData = $request->session()->get('validated_data');

        // Jika data tidak ada (misal, user akses URL langsung), redirect kembali
        if (!$validatedData) {
            return redirect()->route('she.hazards.diprosesForm', $hazard)->with('error', 'Silakan isi form validasi terlebih dahulu.');
        }

        $final_tingkat_keparahan = $validatedData['final_tingkat_keparahan'] ?? null;
        $final_kemungkinan_terjadi = $validatedData['final_kemungkinan_terjadi'] ?? null;
        $faktor_penyebab = $validatedData['faktor_penyebab'] ?? null;
        $final_kategori_stop6 = $validatedData['final_kategori_stop6'] ?? null;

        // Hitung skor risiko di backend
        $final_risk_score = (int)$final_tingkat_keparahan * (int)$final_kemungkinan_terjadi;

        return view('she.hazards.tanpa_tindaklanjut', compact(
            'hazard',
            'final_tingkat_keparahan',
            'final_kemungkinan_terjadi',
            'final_risk_score',
            'faktor_penyebab',
            'final_kategori_stop6'
        ));
    }
}