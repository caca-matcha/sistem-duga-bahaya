<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHazardRequest;
use App\Models\Hazard; // Import Location Model
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

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
        // Data yang sudah dijamin valid oleh StoreHazardRequest
        $validated = $request->validated();
        $filePath = null;

        if ($request->hasFile('foto_bukti')) {
            $filePath = $request->file('foto_bukti')->store('hazard_photos', 'public');
        }

        // Hitung skor risiko di backend
        $riskScore = $validated['tingkat_keparahan'] * $validated['kemungkinan_terjadi'];

        // Ambil data Location Master
        $location = Location::find($validated['location_id']);

        Hazard::create([
            'user_id' => Auth::id(),
            'nama' => Auth::user()->name,
            'NPK' => $validated['NPK'],
            'dept' => $validated['dept'],
            'tgl_observasi' => $validated['tgl_observasi'],

            // --- Mengisi data lokasi dari Master Location ---
            'location_id' => $location->id, // Gunakan location_id
            'area_gedung' => $location->parent ? $location->parent->name : $location->name, // Ambil nama gedung dari parent jika ada, atau nama lokasi itu sendiri
            'area_name' => $location->name,
            'area_id' => $location->location_id_string,
            'area_type' => $location->type,
            // --- Kolom cell_id dan map_id bisa dikosongkan/null jika tidak relevan lagi ---
            'cell_id' => null, // Set null karena sekarang menggunakan location_id
            'map_id' => null, // Set null karena sekarang menggunakan location_id
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
