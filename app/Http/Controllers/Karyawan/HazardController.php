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
        // --- 1. STATISTIK (Dihitung dari semua data user tanpa filter) ---
        $allUserHazards = Hazard::where('user_id', Auth::id())->get();
        $totalLaporan = $allUserHazards->count();
        $menungguValidasi = $allUserHazards->whereIn('status', ['menunggu validasi', 'diproses'])->count();
        $sudahDivalidasi = $allUserHazards->whereIn('status', ['disetujui', 'selesai'])->count();
        $ditolak = $allUserHazards->where('status', 'ditolak')->count();

        // --- 2. QUERY UTAMA DENGAN FILTER ---
        $query = Hazard::where('user_id', Auth::id());

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

        // Ambil hasil yang sudah difilter dan paginasi
        $hazards = $query->latest()->paginate(10)->withQueryString();

        // Kirim semua variabel yang dibutuhkan ke view
        return view('karyawan.dashboard', [
            'hazards' => $hazards,
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
            $filePath = $request->file('foto_bukti')->store('hazard_photos', 'public');
        }

        // Hitung skor risiko di backend
        $riskScore = $validated['tingkat_keparahan'] * $validated['kemungkinan_terjadi'];

        // Ambil data Location Master
        $location = Location::with('map')->find($validated['location_id']);

        $hazard = Hazard::create([
            'user_id' => Auth::id(),
            'nama' => Auth::user()->name,
            'NPK' => $validated['NPK'],
            'dept' => $validated['dept'],
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
        // Pastikan hanya pemilik laporan yang bisa melihat
        if ($hazard->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $hazard->load(['pelapor', 'ditanganiOleh']); // Eager load relationships

        // Persiapan data untuk timeline
        $timelineData = [];

        // 1. Status: Laporan Dibuat (menunggu validasi)
        $timelineData[] = [
            'status' => 'Laporan Dibuat',
            'date' => $hazard->created_at,
            'is_active' => true,
            'is_current' => $hazard->status === 'menunggu validasi',
            'details' => 'Laporan telah dikirim dan menunggu tinjauan dari Tim SHE.',
        ];

        // 2. Status: Diproses oleh SHE
        $isDiproses = in_array($hazard->status, ['diproses', 'selesai']);
        $timelineData[] = [
            'status' => 'Diproses',
            'date' => $hazard->ditangani_pada,
            'is_active' => $isDiproses,
            'is_current' => $hazard->status === 'diproses',
            'details' => $isDiproses
                ? 'Laporan sedang ditangani. Target penyelesaian: '.($hazard->target_penyelesaian ? \Carbon\Carbon::parse($hazard->target_penyelesaian)->format('d M Y') : 'Belum ditentukan')
                : 'Menunggu laporan divalidasi dan diterima oleh Tim SHE.',
        ];

        // 3. Status: Selesai atau Ditolak
        if ($hazard->status === 'selesai') {
            $timelineData[] = [
                'status' => 'Selesai',
                'date' => $hazard->report_selesai,
                'is_active' => true,
                'is_current' => true,
                'details' => 'Tindak lanjut untuk laporan ini telah selesai.',
            ];
        } elseif ($hazard->status === 'ditolak') {
            // Jika ditolak, ganti 'Diproses' dan 'Selesai' dengan 'Ditolak'
            // Kita hapus dulu 'Diproses' dan placeholder 'Selesai'
            array_pop($timelineData); // Hapus placeholder 'Diproses'

            $timelineData[] = [
                'status' => 'Ditolak',
                'date' => $hazard->updated_at, // Asumsi tanggal ditolak adalah saat record di-update terakhir
                'is_active' => true,
                'is_current' => true,
                'details' => 'Laporan ditolak. Alasan: '.($hazard->alasan_penolakan ?? 'Tidak ada alasan spesifik.'),
            ];
        } else {
            // Placeholder untuk status Selesai jika belum tercapai
            $timelineData[] = [
                'status' => 'Selesai',
                'date' => null,
                'is_active' => false,
                'is_current' => false,
                'details' => 'Menunggu proses penanganan dari Tim SHE selesai.',
            ];
        }

        return view('karyawan.hazards.show', compact('hazard', 'timelineData'));
    }
}
