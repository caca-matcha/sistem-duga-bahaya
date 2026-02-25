<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHazardRequest;
use App\Models\Hazard; // Import Location Model
use App\Models\Location;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HazardController extends Controller
{
    /**
     * Menampilkan daftar laporan bahaya yang dibuat oleh Karyawan ini,
     * sekaligus menghitung statistik untuk dashboard.
     */
    public function index(Request $request)
    {
        // --- 1. STATISTIK (Dihitung dari laporannya sendiri ATAU tugasnya sebagai PIC) ---
        $allUserHazards = Hazard::where(function ($q) {
            $q->where('user_id', Auth::id())
                ->orWhere('pic_id', Auth::id());
        })->get();

        $totalLaporan = $allUserHazards->count();
        $menungguValidasi = $allUserHazards->whereIn('status', ['menunggu validasi', 'diproses', 'menunggu verifikasi'])->count();
        $sudahDivalidasi = $allUserHazards->whereIn('status', ['disetujui', 'selesai'])->count();
        $ditolak = $allUserHazards->where('status', 'ditolak')->count();

        // --- 2. QUERY UTAMA DENGAN FILTER (Laporan sendiri OR PIC) ---
        $query = Hazard::where(function ($q) {
            $q->where('user_id', Auth::id())
                ->orWhere('pic_id', Auth::id());
        });

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Search Term
        if ($request->filled('search')) {
            $searchTerm = strtolower($request->search);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'LIKE', "%{$searchTerm}%")
                    ->orWhere(DB::raw('LOWER(tgl_observasi)'), 'LIKE', "%{$searchTerm}%")
                    ->orWhere(DB::raw('LOWER(deskripsi_bahaya)'), 'LIKE', "%{$searchTerm}%")
                    ->orWhere(DB::raw('LOWER(area_gedung)'), 'LIKE', "%{$searchTerm}%")
                    ->orWhere(DB::raw('LOWER(area_name)'), 'LIKE', "%{$searchTerm}%")
                    ->orWhere(DB::raw('LOWER(status)'), 'LIKE', "%{$searchTerm}%");
            });
        }

        // --- 3. QUERY TUGAS SAYA (SEBAGAI PIC - Hanya yang aktif/perlu tindakan) ---
        $assignedHazards = Hazard::where('pic_id', Auth::id())
            ->whereIn('status', ['diproses', 'menunggu verifikasi'])
            ->latest()
            ->get();

        // Ambil hasil yang sudah difilter dan paginasi
        $hazards = $query->latest('updated_at')->paginate(10)->withQueryString();

        // Kirim semua variabel yang dibutuhkan ke view
        return view('karyawan.dashboard', [
            'hazards' => $hazards,
            'assignedHazards' => $assignedHazards,
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
        // Data yang sudah dijamin valid oleh StoreHazardRequest
        $validated = $request->validated();
        $filePath = null;

        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $filename = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('hazard_photos', $filename, 'public');
        }

        // Hitung skor risiko di backend
        $riskScore = $validated['tingkat_keparahan'] * $validated['kemungkinan_terjadi'];

        // Ambil data Location Master
        $location = Location::with('map')->find($validated['location_id']);

        $hazard = Hazard::create([
            'user_id' => Auth::id(),
            'nama' => $validated['nama'] ?? Auth::user()->name,
            'NPK' => $validated['NPK'] ?? Auth::user()->npk,
            'dept' => $validated['dept'] ?? Auth::user()->department,
            'position' => Auth::user()->job_family,
            'tgl_observasi' => $validated['tgl_observasi'],

            // --- Mengisi data lokasi dari Master Location ---
            'location_id' => $location->id, // Gunakan location_id
            'area_gedung' => $location->map ? $location->map->name : null, // Ambil nama gedung dari relasi map
            'area_name' => $location->name,
            'area_id' => $location->location_id_string,
            'area_type' => $location->type,
            'map_id' => $location->map_id, // Simpan map_id yang benar
            'cell_id' => $validated['cell_id'] ?? null,
            // ----------------------------------------------------

            'lokasi_detail_manual' => $validated['lokasi_detail_manual'],
            'deskripsi_bahaya' => $validated['deskripsi_bahaya'],
            'foto_bukti' => $filePath,
            'kategori_stop6' => $validated['kategori_stop6'],
            'tingkat_keparahan' => $validated['tingkat_keparahan'],
            'kemungkinan_terjadi' => $validated['kemungkinan_terjadi'],
            'risk_score' => $riskScore,
            'kategori_resiko' => ($riskScore <= 4) ? 'Low' : (($riskScore <= 9) ? 'Medium' : (($riskScore <= 15) ? 'Medium-High' : (($riskScore <= 20) ? 'High' : 'Extreme'))),
            'ide_penanggulangan' => $validated['ide_penanggulangan'],
            'status' => 'menunggu validasi', // Status awal saat dikirim
        ]);

        // Create notification for the user
        Notification::create([
            'user_id' => Auth::id(),
            'report_id' => $hazard->id,
            'title' => 'Laporan Diterima',
            'message' => 'Laporan bahaya #'.$hazard->id.' berhasil dikirim dan sedang menunggu review SHE.',
            'type' => 'success',
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
        // Pastikan hanya pemilik laporan atau PIC yang bisa melihat
        if ($hazard->user_id !== Auth::id() && $hazard->pic_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $hazard->load(['pelapor', 'ditanganiOleh']); // Eager load relationships

        // Persiapan data untuk timeline
        $timelineData = [];

        // CEK APAKAH USER ADALAH PIC (Untuk Alur Timeline Khusus)
        $isUserPic = (Auth::id() === $hazard->pic_id);

        if ($isUserPic && in_array($hazard->status, ['diproses', 'menunggu verifikasi', 'selesai'])) {
            // --- ALUR TIMELINE KHUSUS PIC ---

            // 1. Laporan Masuk (Tgl SHE kirim ke PIC)
            $timelineData[] = [
                'status' => 'Laporan Masuk',
                'date' => $hazard->ditangani_pada,
                'is_active' => true,
                'is_current' => ($hazard->status === 'diproses' && ! $hazard->target_penyelesaian),
                'details' => 'Laporan bahaya telah divalidasi SHE dan masuk ke antrean Anda.',
            ];

            // 2. Diproses (Keluar) - (Tgl PIC input bukti & klik selesai)
            $hasSubmitted = in_array($hazard->status, ['menunggu verifikasi', 'selesai']);
            $timelineData[] = [
                'status' => 'Proses / Keluar',
                'date' => $hazard->report_selesai,
                'is_active' => $hasSubmitted,
                'is_current' => ($hazard->status === 'menunggu verifikasi' || ($hazard->status === 'diproses' && $hazard->target_penyelesaian)),
                'details' => $hasSubmitted
                    ? 'Laporan "Keluar" dari PIC (Selesai diperbaiki pada '.$hazard->report_selesai->format('H:i').').'
                    : ($hazard->target_penyelesaian
                        ? 'Sedang dikerjakan. Target: '.\Carbon\Carbon::parse($hazard->target_penyelesaian)->format('d M Y')
                        : 'Menunggu Anda melakukan perbaikan dan mengunggah bukti.'),
            ];

            // 3. Selesai (Tgl Verifikasi SHE Selesai)
            $isSelesai = ($hazard->status === 'selesai');
            $timelineData[] = [
                'status' => 'Selesai',
                'date' => $isSelesai ? $hazard->updated_at : null,
                'is_active' => $isSelesai,
                'is_current' => $isSelesai,
                'details' => $isSelesai
                    ? 'Tim SHE telah memvalidasi perbaikan Anda. Laporan ditutup.'
                    : 'Menunggu verifikasi akhir dari tim SHE setelah perbaikan selesai.',
            ];
        } else {
            // --- ALUR TIMELINE STANDAR (PELAPOR / UMUM) ---

            // 1. Status: Laporan Dibuat (menunggu validasi)
            $timelineData[] = [
                'status' => 'Laporan Dibuat',
                'date' => $hazard->created_at,
                'is_active' => true,
                'is_current' => $hazard->status === 'menunggu validasi',
                'details' => 'Laporan telah dikirim dan menunggu tinjauan dari Tim SHE.',
            ];

            // 2. Status: Diproses oleh SHE
            $isDiproses = in_array($hazard->status, ['diproses', 'menunggu verifikasi', 'selesai']);
            $timelineData[] = [
                'status' => 'Diproses',
                'date' => $hazard->ditangani_pada,
                'is_active' => $isDiproses,
                'is_current' => $hazard->status === 'diproses',
                'details' => $isDiproses
                    ? 'Laporan sedang ditangani oleh PIC terkait.'
                    : 'Menunggu laporan divalidasi dan diterima oleh Tim SHE.',
            ];

            // 3. Status: Selesai atau Ditolak
            if ($hazard->status === 'selesai') {
                $timelineData[] = [
                    'status' => 'Selesai',
                    'date' => $hazard->report_selesai,
                    'is_active' => true,
                    'is_current' => true,
                    'details' => 'Tindak lanjut untuk laporan ini telah selesai diverifikasi.',
                ];
            } elseif ($hazard->status === 'ditolak') {
                array_pop($timelineData); // Hapus 'Diproses'
                $timelineData[] = [
                    'status' => 'Ditolak',
                    'date' => $hazard->updated_at,
                    'is_active' => true,
                    'is_current' => true,
                    'details' => 'Laporan ditolak. Alasan: '.($hazard->alasan_penolakan ?? 'Tidak ada alasan spesifik.'),
                ];
            } else {
                $timelineData[] = [
                    'status' => 'Selesai',
                    'date' => null,
                    'is_active' => false,
                    'is_current' => false,
                    'details' => 'Menunggu proses penanganan dan verifikasi selesai.',
                ];
            }
        }

        return view('karyawan.hazards.show', compact('hazard', 'timelineData'));
    }

    /**
     * Memperbarui laporan bahaya (Khusus untuk PIC/Leader).
     * Menangani penetapan deadline dan penyelesaian tugas.
     */
    public function update(Request $request, Hazard $hazard)
    {
        // 1. AUTHORIZATION CHECK
        $isPic = ($hazard->pic_id === Auth::id());
        if (! $isPic) {
            abort(403, 'Anda tidak memiliki akses untuk menindaklanjuti laporan ini.');
        }

        // 2. VALIDATION
        $validated = $request->validate([
            'action' => 'required|string|in:set_deadline,complete',
            'target_penyelesaian' => 'nullable|date',
            'tindakan_perbaikan' => 'nullable|string|required_if:action,complete',
            'foto_bukti_penyelesaian' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240|required_if:action,complete',
        ]);

        // 3. HANDLE ACTIONS - SET DEADLINE
        if ($validated['action'] === 'set_deadline') {
            $hazard->update([
                'target_penyelesaian' => $validated['target_penyelesaian'],
            ]);

            return back()->with('success', 'Target penyelesaian berhasil ditetapkan.');
        }

        // 4. HANDLE ACTIONS - COMPLETE TASK
        if ($validated['action'] === 'complete') {
            $dataToUpdate = [
                'tindakan_perbaikan' => $validated['tindakan_perbaikan'],
                'status' => 'menunggu verifikasi', // Update status to waiting for verification
                'report_selesai' => now(),
            ];

            if ($request->hasFile('foto_bukti_penyelesaian')) {
                $file = $request->file('foto_bukti_penyelesaian');
                $filename = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('completion_photos', $filename, 'public');
                $dataToUpdate['foto_bukti_penyelesaian'] = $filePath;
            }

            $hazard->update($dataToUpdate);

            // Kirim notifikasi ke semua user SHE agar tahu ada laporan yang perlu diverifikasi
            $sheUsers = \App\Models\User::where('role', 'she')->pluck('id');
            foreach ($sheUsers as $sheUserId) {
                Notification::create([
                    'user_id' => $sheUserId,
                    'report_id' => $hazard->id,
                    'title' => 'Laporan #'.$hazard->id.' Menunggu Verifikasi',
                    'message' => 'PIC telah menyelesaikan perbaikan. Silakan verifikasi laporan bahaya #'.$hazard->id.'.',
                    'type' => 'info',
                ]);
            }

            return redirect()->route('karyawan.hazards.show', $hazard)
                ->with('success', 'Laporan berhasil diperbarui. Menunggu verifikasi SHE.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }
}
