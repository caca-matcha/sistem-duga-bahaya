<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Http\Requests\SheUpdateHazardRequest;
use App\Models\Cell;
use App\Models\Hazard;
use App\Models\Notification;
use Carbon\Carbon; // Import Carbon for date manipulation
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Import Cell Model
use Illuminate\Support\Facades\Log; // Import Log Facade
use Illuminate\Support\Facades\Storage; // Import DB Facade

class HazardController extends Controller
{
    // DASHBOARD: tampilkan semua laporan
    public function index(Request $request)
    {
        $baseQuery = Hazard::query()->with('pelapor', 'ditanganiOleh', 'location', 'pic');

        // Apply Month and Year Filters
        $month = $request->input('month');
        $year = $request->input('year');

        if (! empty($month)) {
            $baseQuery->whereMonth('tgl_observasi', $month);
        }
        if (! empty($year)) {
            $baseQuery->whereYear('tgl_observasi', $year);
        }

        // Apply Search Filter
        if ($request->has('search') && ! empty($request->search)) {
            $searchTerm = strtolower($request->search);
            $baseQuery->where(function ($q) use ($searchTerm) {
                $q->where('hazards.id', 'LIKE', '%'.$searchTerm.'%') // Search ID
                    ->orWhere(DB::raw('LOWER(tgl_observasi)'), 'LIKE', '%'.$searchTerm.'%') // Search Date
                    ->orWhereHas('pelapor', function ($userQuery) use ($searchTerm) { // Search Reporter Name
                        $userQuery->where(DB::raw('LOWER(name)'), 'LIKE', '%'.$searchTerm.'%');
                    })
                    ->orWhere(DB::raw('LOWER(deskripsi_bahaya)'), 'LIKE', '%'.$searchTerm.'%'); // Search Short Description

                // Risk Level (Low, Medium, High, Medium-High, Extreme) - Case-insensitive
                if (strtolower($searchTerm) === 'low') {
                    $q->orWhere('risk_score', '<=', 4);
                } elseif (strtolower($searchTerm) === 'medium') {
                    $q->orWhereBetween('risk_score', [5, 9]);
                } elseif (strtolower($searchTerm) === 'medium-high') {
                    $q->orWhereBetween('risk_score', [10, 15]);
                } elseif (strtolower($searchTerm) === 'high') {
                    $q->orWhereBetween('risk_score', [16, 20]);
                } elseif (strtolower($searchTerm) === 'extreme') {
                    $q->orWhere('risk_score', '>', 20);
                }
                // Also allow direct search on 'kategori_resiko' string itself
                $q->orWhere(DB::raw('LOWER(kategori_resiko)'), 'LIKE', '%'.$searchTerm.'%');
            });
        }

        // Split by status after applying global filters
        $hazardsMenungguValidasi = (clone $baseQuery)->where('status', 'menunggu validasi')->latest()->paginate(10, ['*'], 'baru_page')->appends(['tab' => 'baru'])->withQueryString();
        $hazardsDiproses = (clone $baseQuery)->whereIn('status', ['diproses', 'menunggu verifikasi'])->latest()->paginate(10, ['*'], 'diproses_page')->appends(['tab' => 'diproses'])->withQueryString();
        $hazardsSelesai = (clone $baseQuery)->whereIn('status', ['selesai', 'ditolak'])->orderBy('ditangani_pada', 'desc')->paginate(10, ['*'], 'selesai_page')->appends(['tab' => 'selesai'])->withQueryString();

        // Specific query for 'Semua' tab with optional status filter
        $semuaQuery = clone $baseQuery;
        if ($request->filled('status')) {
            $statusFilter = $request->status;
            if ($statusFilter === 'selesai') {
                $semuaQuery->whereIn('status', ['selesai', 'ditolak']);
            } elseif ($statusFilter === 'menunggu') {
                $semuaQuery->where('status', 'menunggu validasi');
            } else {
                $semuaQuery->where('status', $statusFilter);
            }
        }
        $hazardsSemua = $semuaQuery->latest()->paginate(10, ['*'], 'semua_page')->appends(['tab' => 'semua'])->withQueryString(); // Fetch ALL reports with status filter if any

        // If it's an AJAX request, return JSON with rendered partials
        if ($request->ajax()) {
            $search = $request->search;

            return response()->json([
                'menunggu_validasi_html' => view('she.hazards._table_menunggu_validasi_rows', compact('hazardsMenungguValidasi', 'search'))->render(),
                'menunggu_validasi_pagination' => $hazardsMenungguValidasi->links('vendor.pagination.custom')->toHtml(),

                'diproses_html' => view('she.hazards._table_diproses_rows', compact('hazardsDiproses', 'search'))->render(),
                'diproses_pagination' => $hazardsDiproses->links('vendor.pagination.custom')->toHtml(),

                'selesai_html' => view('she.hazards._table_selesai_rows', compact('hazardsSelesai', 'search'))->render(),
                'selesai_pagination' => $hazardsSelesai->links('vendor.pagination.custom')->toHtml(),

                'semua_html' => view('she.hazards._table_semua_rows', compact('hazardsSemua', 'search'))->render(),
                'semua_pagination' => $hazardsSemua->links('vendor.pagination.custom')->toHtml(),
            ]);
        }

        $search = $request->search;

        return view('she.hazards.index', compact('hazardsMenungguValidasi', 'hazardsDiproses', 'hazardsSelesai', 'hazardsSemua', 'search'));
    }

    /**
     * Menyimpan laporan duga bahaya baru yang dikirim oleh karyawan.
     * Metode ini menerima semua input termasuk risk_score dan kategori_resiko (dari JS).
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. VALIDASI DATA
        // Pastikan nama input di sini sesuai dengan atribut 'name' di formulir create.blade.php
        $validatedData = $request->validate([
            'NPK' => ['required', 'string', 'max:255'],
            'dept' => ['required', 'string'],
            'tgl_observasi' => ['required', 'date'],
            'map_id' => ['required', 'exists:maps,id'], // Added map_id validation
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
            $hazard = new Hazard;

            // Mengisi data dari request
            $hazard->nama = Auth::user()->name;
            $hazard->NPK = $validatedData['NPK'];
            $hazard->dept = $validatedData['dept'];
            Log::info('Hazard store: Validated map_id before assignment', ['map_id_validated' => $validatedData['map_id']]);
            $hazard->map_id = $validatedData['map_id']; // Save map_id
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
        $hazard->load(['pelapor', 'ditanganiOleh', 'map', 'pic']);
        Log::info('Hazard show: ', [
            'hazard_id' => $hazard->id,
            'hazard_map_id' => $hazard->map_id,
            'hazard_map_relation_loaded' => $hazard->relationLoaded('map'),
            'hazard_map_object' => $hazard->map ? $hazard->map->toArray() : null,
            'hazard_map_name' => $hazard->map->name ?? null,
            'hazard_area_gedung' => $hazard->area_gedung,
        ]);

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
            'pic_id' => 'nullable|exists:users,id',

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
            'pic_id' => 'nullable|exists:users,id',

        ]);

        // Simpan data yang divalidasi ke session untuk dibawa ke step berikutnya
        $request->session()->flash('validated_data', $validated);

        // Redirect ke form tindak lanjut
        return redirect()->route('she.hazards.tanpaTindakLanjut', $hazard);
    }

    // FORWARD TO PIC (BYPASS RENCANA TINDAK LANJUT)
    public function forwardToPic(Request $request, Hazard $hazard)
    {
        // 1. Validasi Input (sama seperti submitValidasi)
        $validated = $request->validate([
            'faktor_penyebab' => 'required|string|max:100',
            'final_tingkat_keparahan' => 'required|integer|in:1,3,5',
            'final_kemungkinan_terjadi' => 'required|integer|in:1,2,3,4,5',
            'final_kategori_stop6' => 'required|string|max:255',
            'pic_id' => 'nullable|exists:users,id',

        ]);

        // 2. Hitung Risk Score Baru
        $riskScore = $validated['final_tingkat_keparahan'] * $validated['final_kemungkinan_terjadi'];
        $kategoriResiko = 'Low';
        if ($riskScore <= 4) {
            $kategoriResiko = 'Low';
        } elseif ($riskScore <= 12) {
            $kategoriResiko = 'Medium';
        } else {
            $kategoriResiko = 'High';
        }

        // 3. Update Hazard
        $hazard->update([
            'status' => 'diproses', // Langsung diproses (menunggu PIC)
            'faktor_penyebab' => $validated['faktor_penyebab'],
            'final_tingkat_keparahan' => $validated['final_tingkat_keparahan'],
            'final_kemungkinan_terjadi' => $validated['final_kemungkinan_terjadi'],
            'final_kategori_stop6' => $validated['final_kategori_stop6'],
            'risk_score' => $riskScore,
            'kategori_resiko' => $kategoriResiko,
            'pic_id' => $validated['pic_id'] ?? $hazard->pic_id,

            'ditangani_oleh' => Auth::id(),
            'ditangani_pada' => now(),
            // Kosongkan rencana tindakan karena ini tanggung jawab PIC
            'tindakan_perbaikan' => null,
            'target_penyelesaian' => null,
        ]);

        // 4. Notifikasi ke PIC
        $targetUsers = collect([$hazard->pic_id])->filter()->unique();
        foreach ($targetUsers as $userId) {
            Notification::create([
                'user_id' => $userId,
                'report_id' => $hazard->id,
                'title' => 'Tugas Baru: Laporan Bahaya #'.$hazard->id,
                'message' => 'Anda telah ditunjuk sebagai PIC untuk menindaklanjuti laporan ini. Silakan tentukan tenggat waktu penyelesaian.',
                'type' => 'info',
            ]);
        }

        return redirect()->route('she.hazards.index')
            ->with('success', 'Laporan berhasil divalidasi dan diteruskan ke PIC/Leader untuk tindak lanjut.');
    }

    // UPDATE LAPORAN OLEH SHE (Menangani Tolak, Proses, Selesai)
    public function updateStatus(SheUpdateHazardRequest $request, Hazard $hazard)
    {
        $validated = $request->validated();

        // --- 1. OTOMATISASI DATA PENANGANAN ---
        $validated['ditangani_oleh'] = Auth::id(); // ID User SHE yang memproses
        if (! $hazard->ditangani_pada) {
            $validated['ditangani_pada'] = now();
        }

        // --- 2. LOGIKA VALIDASI (Hitung Final Risk Score) ---
        $finalSeverity = $validated['final_tingkat_keparahan'] ?? $hazard->final_tingkat_keparahan ?? $hazard->tingkat_keparahan;
        $finalProbability = $validated['final_kemungkinan_terjadi'] ?? $hazard->final_kemungkinan_terjadi ?? $hazard->kemungkinan_terjadi;
        $finalKategoriStop6 = $validated['final_kategori_stop6'] ?? $hazard->final_kategori_stop6 ?? $hazard->kategori_stop6;

        if ($finalSeverity && $finalProbability) {
            $validated['risk_score'] = $finalSeverity * $finalProbability;

            // Tentukan kategori risiko berdasarkan risk_score
            $risk = $validated['risk_score'];
            if ($risk <= 4) {
                $validated['kategori_resiko'] = 'Low';
            } elseif ($risk <= 12) {
                $validated['kategori_resiko'] = 'Medium';
            } else {
                $validated['kategori_resiko'] = 'High';
            }
        }

        // Simpan final values ke DB jika kolom ada
        if (isset($validated['final_tingkat_keparahan'])) {
            $hazard->final_tingkat_keparahan = $validated['final_tingkat_keparahan'];
        }
        if (isset($validated['final_kemungkinan_terjadi'])) {
            $hazard->final_kemungkinan_terjadi = $validated['final_kemungkinan_terjadi'];
        }
        if (isset($validated['final_kategori_stop6'])) {
            $hazard->final_kategori_stop6 = $validated['final_kategori_stop6'];
        }

        if (isset($validated['final_kategori_stop6'])) {
            $hazard->final_kategori_stop6 = $validated['final_kategori_stop6'];
        }

        if (isset($validated['pic_id'])) {
            $hazard->pic_id = $validated['pic_id'];
        }

        unset($validated['final_tingkat_keparahan'], $validated['final_kemungkinan_terjadi'], $validated['final_kategori_stop6']);
        unset($validated['pic_id']); // Unset to avoid mass assignment issues if not safely handled (though fillable protects it, clean up is good)
        unset($validated['pic_penanggung_jawab']); // hapus jika ada

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
            if (! $hazard->report_selesai) {
                $validated['report_selesai'] = now();
            }

            if ($request->hasFile('foto_bukti_penyelesaian')) {
                if ($hazard->foto_bukti_penyelesaian) {
                    Storage::disk('public')->delete($hazard->foto_bukti_penyelesaian);
                }

                $file = $request->file('foto_bukti_penyelesaian');

                // Simpan dengan nama asli (disanitasi oleh Laravel storeAs)
                $filename = time().'_'.$file->getClientOriginalName();

                $validated['foto_bukti_penyelesaian'] = $file->storeAs('completion_photos', $filename, 'public');
            }
        }

        /* ----------------------------------------------------------
         * 5. UPDATE GENERAL (Status: diproses atau selesai)
         * ---------------------------------------------------------- */
        $previousStatus = $hazard->status;
        $hazard->update($validated);
        $hazard->save(); // pastikan final values ikut tersimpan

        // --- CREATE NOTIFICATION FOR THE ORIGINAL REPORTER & PIC/LEADER ---
        if (isset($validated['status'])) {
            $notificationTitle = '';
            $notificationMessage = '';
            $notificationType = 'info';

            switch ($validated['status']) {
                case 'diproses':
                    if ($previousStatus === 'menunggu verifikasi') {
                        // REJECTION CASE: Notify PIC
                        $notificationTitle = 'Perbaikan Perlu Revisi';
                        $notificationMessage = 'SHE meminta perbaikan ulang untuk Laporan #'.$hazard->id.'. Catatan: '.$hazard->feedback_verifikasi;
                        $notificationType = 'warning';

                        // Notify PIC if exists
                        $notifiableUsers = array_filter([$hazard->pic_id]);
                        foreach (array_unique($notifiableUsers) as $uid) {
                            Notification::create([
                                'user_id' => $uid,
                                'report_id' => $hazard->id,
                                'title' => $notificationTitle,
                                'message' => $notificationMessage,
                                'type' => $notificationType,
                            ]);
                        }

                        // Also notify reporter about the delay
                        $notificationTitle = 'Update Perbaikan Laporan';
                        $notificationMessage = 'Laporan #'.$hazard->id.' sedang diperbaiki ulang oleh tim terkait.';
                    } else {
                        $notificationTitle = 'Laporan Anda Diproses';
                        $notificationMessage = 'Laporan bahaya #'.$hazard->id.' sedang ditindaklanjuti oleh tim SHE.';
                    }
                    $notificationType = 'info';
                    break;
                case 'selesai':
                    $notificationTitle = 'Laporan Anda Selesai';
                    $notificationMessage = 'Laporan bahaya #'.$hazard->id.' telah diselesaikan. Terima kasih atas partisipasi Anda.';
                    $notificationType = 'success';

                    // Also notify PIC that their work is done
                    $notifiableUsers = array_filter([$hazard->pic_id]);
                    foreach (array_unique($notifiableUsers) as $uid) {
                        Notification::create([
                            'user_id' => $uid,
                            'report_id' => $hazard->id,
                            'title' => 'Tugas Selesai',
                            'message' => 'Laporan #'.$hazard->id.' telah diverifikasi dan dinyatakan selesai.',
                            'type' => 'success',
                        ]);
                    }
                    break;
                case 'ditolak':
                    $notificationTitle = 'Laporan Anda Ditolak';
                    $notificationMessage = 'Laporan bahaya #'.$hazard->id.' ditolak. Silakan periksa detailnya.';
                    $notificationType = 'warning';
                    break;
            }

            if ($notificationTitle && $hazard->user_id) {
                Notification::create([
                    'user_id' => $hazard->user_id,
                    'report_id' => $hazard->id,
                    'title' => $notificationTitle,
                    'message' => $notificationMessage,
                    'type' => $notificationType,
                ]);
            }
        }

        // --- RECALCULATE AND UPDATE CELL RISK SCORE & ZONE COLOR ---
        if ($hazard->cell_id) { // Only proceed if the hazard is linked to a cell
            $cell = $hazard->cell; // Retrieve the associated Cell model (using the cell() relationship)

            if ($cell) {
                // Get all 'active' hazards (diproses or selesai) associated with this cell
                $activeHazards = Hazard::where('cell_id', $cell->id)
                    ->whereIn('status', ['diproses', 'selesai'])
                    ->get();

                if ($activeHazards->count() > 0) {
                    $cell->recalculateRiskScore();
                } else {
                    $cell->risk_score = 0;
                    $cell->zone_color = '#ffffff';
                    $cell->save();
                }
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
            ->with('success', 'Laporan berhasil diperbarui ke status: '.ucfirst($validated['status']).'.');
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

        $users = \App\Models\User::orderBy('name')->get();

        return view('she.hazards.diproses', compact('hazard', 'users'));
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
        // Pengecekan stabilitas: Penyelesaian bisa dilakukan dari status 'diproses' (SHE tandai selesai langsung)
        // atau 'menunggu verifikasi' (SHE verifikasi hasil PIC).
        if (! in_array($hazard->status, ['diproses', 'menunggu verifikasi'])) {
            return redirect()->route('she.hazards.show', $hazard)
                ->with('error', 'Laporan harus berstatus DIPROSES atau MENUNGGU VERIFIKASI untuk diselesaikan.');
        }

        return view('she.hazards.selesai', compact('hazard'));
    }

    // VIEW FORM DENGAN TINDAK LANJUT
    public function denganTindakLanjutForm(Request $request, Hazard $hazard)
    {
        // Ambil data dari session yang di-flash oleh submitValidasi
        $validatedData = $request->session()->get('validated_data');

        // Jika data tidak ada (misal, user akses URL langsung), redirect kembali
        if (! $validatedData) {
            return redirect()->route('she.hazards.diprosesForm', $hazard)->with('error', 'Silakan isi form validasi terlebih dahulu.');
        }

        $final_tingkat_keparahan = $validatedData['final_tingkat_keparahan'];
        $final_kemungkinan_terjadi = $validatedData['final_kemungkinan_terjadi'];
        $faktor_penyebab = $validatedData['faktor_penyebab'];
        $final_kategori_stop6 = $validatedData['final_kategori_stop6'];

        // Hitung skor risiko di backend
        $final_risk_score = (int) $final_tingkat_keparahan * (int) $final_kemungkinan_terjadi;

        // Get users for PIC/Leader dropdown
        $users = \App\Models\User::orderBy('name')->get();

        return view('she.hazards.dengan_tindaklanjut', compact(
            'hazard',
            'final_tingkat_keparahan',
            'final_kemungkinan_terjadi',
            'final_risk_score',
            'faktor_penyebab',
            'final_kategori_stop6',
            'users'
        ));
    }

    // VIEW FORM TANPA TINDAK LANJUT
    public function tanpaTindakLanjutForm(Request $request, Hazard $hazard)
    {
        // Ambil data dari session yang di-flash oleh submitValidasi
        $validatedData = $request->session()->get('validated_data');

        // Jika data tidak ada (misal, user akses URL langsung), redirect kembali
        if (! $validatedData) {
            return redirect()->route('she.hazards.diprosesForm', $hazard)->with('error', 'Silakan isi form validasi terlebih dahulu.');
        }

        $final_tingkat_keparahan = $validatedData['final_tingkat_keparahan'] ?? null;
        $final_kemungkinan_terjadi = $validatedData['final_kemungkinan_terjadi'] ?? null;
        $faktor_penyebab = $validatedData['faktor_penyebab'] ?? null;
        $final_kategori_stop6 = $validatedData['final_kategori_stop6'] ?? null;

        // Hitung skor risiko di backend
        $final_risk_score = (int) $final_tingkat_keparahan * (int) $final_kemungkinan_terjadi;

        return view('she.hazards.tanpa_tindaklanjut', compact(
            'hazard',
            'final_tingkat_keparahan',
            'final_kemungkinan_terjadi',
            'final_risk_score',
            'faktor_penyebab',
            'final_kategori_stop6'
        ));
    }

    /**
     * Ekspor laporan yang dipilih ke dalam format CSV.
     */
    public function exportExcelBulk(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:hazards,id',
        ]);

        $hazards = Hazard::whereIn('id', $validated['ids'])
            ->with(['pelapor', 'ditanganiOleh', 'location'])
            ->latest()
            ->get();

        $filename = 'hazard_reports_export_'.now()->format('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($hazards) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8 Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Define headers
            $columns = [
                'ID Laporan',
                'Status',
                'Tanggal Observasi',
                'Nama Pelapor',
                'NPK Pelapor',
                'Departemen Pelapor',
                'Gedung',
                'Lokasi Spesifik',
                'Detail Lokasi (Manual)',
                'Deskripsi Bahaya',
                'Kategori STOP6',
                'Faktor Penyebab',
                'Ide Penanggulangan',
                'Severity',
                'Probability',
                'Risk Score',
                'Kategori Risiko',
                'Status',
                'Validator',
                'Tgl Validasi',
                'Tindakan Perbaikan',
                'Tgl Selesai',
            ];

            fputcsv($file, $columns);

            // Add data rows
            foreach ($hazards as $hazard) {
                fputcsv($file, [
                    $hazard->id,
                    $hazard->NPK,
                    $hazard->nama,
                    $hazard->dept,
                    $hazard->position ?? '-',
                    $hazard->tgl_observasi->format('d/m/Y'),
                    $hazard->area_type,
                    $hazard->area_name,
                    $hazard->area_id,
                    $hazard->lokasi_detail_manual,
                    $hazard->aktivitas_kerja,
                    $hazard->deskripsi_bahaya,
                    $hazard->kategori_stop6,
                    $hazard->faktor_penyebab,
                    $hazard->ide_penanggulangan,
                    $hazard->tingkat_keparahan,
                    $hazard->kemungkinan_terjadi,
                    $hazard->risk_score,
                    $hazard->kategori_resiko,
                    $hazard->status,
                    $hazard->ditanganiOleh->name ?? '-',
                    $hazard->ditangani_pada ? $hazard->ditangani_pada->format('d/m/Y') : '-',
                    $hazard->tindakan_perbaikan,
                    $hazard->foto_bukti_penyelesaian ? asset('storage/'.$hazard->foto_bukti_penyelesaian) : 'N/A',
                    $hazard->report_selesai ? \Carbon\Carbon::parse($hazard->report_selesai)->format('d/m/Y') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Menghapus laporan duga bahaya dari sistem.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Hazard $hazard)
    {
        // Delete associated files from storage
        if ($hazard->foto_bukti) {
            Storage::disk('public')->delete($hazard->foto_bukti);
        }
        if ($hazard->foto_bukti_penyelesaian) {
            Storage::disk('public')->delete($hazard->foto_bukti_penyelesaian);
        }

        $hazard->delete();

        return redirect()->route('she.hazards.index')->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * Display a listing of hazards that are overdue or due soon for follow-up.
     */
    public function needsFollowUpReports()
    {
        $allPendingActionHazards = Hazard::where('status', 'diproses')
            ->whereNotNull('target_penyelesaian')
            ->with('pelapor') // Eager load pelapor for display
            ->get();

        $overdueHazards = $allPendingActionHazards->filter(function ($hazard) {
            return Carbon::parse($hazard->target_penyelesaian)->isPast();
        });

        $dueSoonHazards = $allPendingActionHazards->filter(function ($hazard) {
            return Carbon::parse($hazard->target_penyelesaian)->isFuture() &&
                Carbon::parse($hazard->target_penyelesaian)->diffInDays(Carbon::now()) <= 3;
        });

        return view('she.hazards.needs-follow-up', compact('overdueHazards', 'dueSoonHazards'));
    }

    /**
     * Get hazard-related notifications (overdue and due soon) as an API endpoint.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotificationsApi()
    {
        $allPendingActionHazards = Hazard::where('status', 'diproses')
            ->whereNotNull('target_penyelesaian')
            ->with('pelapor')
            ->get();

        $notifications = collect();

        // --- 1. OVERDUE VALIDATION (Menunggu Validasi > 7 Hari) ---
        $overdueValidationHazards = Hazard::where('status', 'menunggu validasi')
            ->where('created_at', '<=', Carbon::now()->subDays(7))
            ->with(['pelapor', 'location'])
            ->get();

        foreach ($overdueValidationHazards as $hazard) {
            $notifications->push([
                'id' => $hazard->id,
                'title' => 'Belum Divalidasi: #'.$hazard->id,
                'description' => $hazard->deskripsi_bahaya.' (Tertahan '.$hazard->created_at->diffForHumans(null, true).')',
                'time_ago' => $hazard->created_at->diffForHumans(),
                'link' => route('she.hazards.show', $hazard),
                'type' => 'overdue',
                'is_read' => false,
            ]);
        }

        // Add overdue hazards to notifications
        $overdueHazards = $allPendingActionHazards->filter(function ($hazard) {
            return Carbon::parse($hazard->target_penyelesaian)->isPast();
        });

        foreach ($overdueHazards as $hazard) {
            $notifications->push([
                'id' => $hazard->id,
                'title' => 'Laporan Overdue: #'.$hazard->id,
                'description' => $hazard->deskripsi_bahaya.' (Terlambat '.Carbon::parse($hazard->target_penyelesaian)->diffForHumans(null, true).')',
                'time_ago' => $hazard->created_at->diffForHumans(),
                'link' => route('she.hazards.show', $hazard),
                'type' => 'overdue',
                'is_read' => false, // For now, assume all these are "unread" as they are critical
            ]);
        }

        // Add due soon hazards to notifications
        $dueSoonHazards = $allPendingActionHazards->filter(function ($hazard) {
            return Carbon::parse($hazard->target_penyelesaian)->isFuture() &&
                Carbon::parse($hazard->target_penyelesaian)->diffInDays(Carbon::now()) <= 3;
        });

        foreach ($dueSoonHazards as $hazard) {
            $notifications->push([
                'id' => $hazard->id,
                'title' => 'Laporan Jatuh Tempo: #'.$hazard->id,
                'description' => $hazard->deskripsi_bahaya.' (Jatuh tempo '.Carbon::parse($hazard->target_penyelesaian)->diffForHumans().')',
                'time_ago' => $hazard->created_at->diffForHumans(),
                'link' => route('she.hazards.show', $hazard),
                'type' => 'due_soon',
                'is_read' => false, // For now, assume all these are "unread" as they are critical
            ]);
        }

        // We don't have a persistent 'read' status for these specific notifications yet,
        // so for simplicity, the 'unread count' will be the total count of these critical hazards.
        $unreadCount = $notifications->count();

        return response()->json([
            'notifications' => $notifications->sortByDesc('id')->values()->all(), // Sort by ID descending for newest first
            'unread_count' => $unreadCount,
        ]);
    }
}
