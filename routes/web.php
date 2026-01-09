<?php

use App\Http\Controllers\SHE\CellController;
// Import Controllers SHE dengan alias
use App\Http\Controllers\SHE\DashboardController as SHEDashboardController;
use App\Http\Controllers\SHE\HazardController as SHEHazardController;
use App\Http\Controllers\SHE\LocationController;
use App\Http\Controllers\SHE\MapController;
use App\Http\Controllers\SHE\ReportController; // Ditambahkan Alias
use App\Http\Controllers\SHE\UserController; // PENTING: Import ReportController
use Illuminate\Support\Facades\Route; // Tambahkan untuk Master Lokasi

// Import Controller Karyawan untuk logika redirect yang lebih aman
// Ditambahkan

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ROUTE AUTH BAWAAN BREEZE (login, register, dll)
require __DIR__.'/auth.php';

// ROUTE WEB UNTUK KARYAWAN (Menggunakan route() Karyawan)
require __DIR__.'/karyawan.php';

// HOME: langsung arahkan ke dashboard setelah login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// DASHBOARD UTAMA SETELAH LOGIN (Logic Redirect)
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'she') {
        return redirect()->route('she.dashboard');
    }

    // REDIRECT KE DASHBOARD KARYAWAN
    if ($user->role === 'karyawan') {
        return redirect()->route('karyawan.dashboard');
    }

    // fallback kalau role nggak dikenal
    return view('dashboard');
})->name('dashboard');

// ROUTE WEB UNTUK SHE (Hanya rute yang tidak dimasukkan ke she.php)
Route::middleware(['auth', 'role:she'])
    ->prefix('she')
    ->name('she.')
    ->group(function () {
        // Menggunakan alias SHEDashboardController
        Route::get('dashboard', [SHEDashboardController::class, 'index'])->name('dashboard');

        // HAZARD ROUTES
        Route::get('hazards', [SHEHazardController::class, 'index'])->name('hazards.index');
        Route::get('hazards/export-excel-bulk', [SHEHazardController::class, 'exportExcelBulk'])->name('hazards.exportExcelBulk');
        Route::get('hazards/{hazard}', [SHEHazardController::class, 'show'])->name('hazards.show');

        // ===========================================
        // 🚀 PENAMBAHAN ROUTE FORM BARU UNTUK SHE
        // ===========================================

        // Rute untuk menampilkan formulir Rencana Tindakan (menunggu validasi -> diproses)
        Route::get('hazards/{hazard}/diproses-form', [SHEHazardController::class, 'diprosesForm'])->name('hazards.diprosesForm');

        // Rute untuk menampilkan formulir Penolakan (menunggu validasi -> ditolak)
        Route::get('hazards/{hazard}/tolak-form', [SHEHazardController::class, 'tolakForm'])->name('hazards.tolakForm');
        // Rute untuk menampilkan formulir Penyelesaian tanpa rencana tindakan (menunggu validasi -> validasi)
        Route::get('hazards/{hazard}/validasi-form', [SHEHazardController::class, 'validasiForm'])->name('hazards.validasiForm');

        // Rute untuk menampilkan formulir Penyelesaian (diproses -> selesai)
        Route::get('hazards/{hazard}/selesai-form', [SHEHazardController::class, 'selesaiForm'])->name('hazards.selesaiForm');

        // Rute untuk menampilkan formulir tindak lanjut
        Route::get('hazards/{hazard}/dengan-tindak-lanjut', [SHEHazardController::class, 'denganTindakLanjutForm'])->name('hazards.denganTindakLanjut');

        // Rute untuk menampilkan formulir validasi tanpa tindak lanjut
        Route::get('hazards/{hazard}/tanpa-tindak-lanjut', [SHEHazardController::class, 'tanpaTindakLanjutForm'])->name('hazards.tanpaTindakLanjut');

        // Rute untuk submit validasi awal dari form diproses
        Route::post('hazards/{hazard}/validasi-submit', [SHEHazardController::class, 'submitValidasi'])->name('hazards.validasi.submit');

        // Rute untuk submit validasi awal dari form diproses (untuk jalur tanpa tindak lanjut)
        Route::post('hazards/{hazard}/validasi-submit-tanpa-tindak-lanjut', [SHEHazardController::class, 'submitValidasiTanpaTindakLanjut'])->name('hazards.validasi.submitTanpaTindakLanjut');

        // ROUTE UTAMA UPDATE STATUS: Menangani semua status update (POST/PUT)
        Route::put('hazards/{hazard}/update-status', [SHEHazardController::class, 'updateStatus'])->name('hazards.updateStatus');
        Route::get('hazards/export-excel-bulk', [SHEHazardController::class, 'exportExcelBulk'])->name('hazards.exportExcelBulk');

        // Kelola Peta
        Route::resource('maps', MapController::class);

        Route::resource('users', UserController::class);
        Route::resource('locations', LocationController::class); // Tambahkan untuk Master Lokasi
        Route::get('maps/{map}/export', [MapController::class, 'export'])->name('maps.export');

        Route::get('maps/{map}/export-risk-excel', [MapController::class, 'exportRiskDataExcel'])->name('maps.export-risk-excel');
    });

// =========================================================================
// API ROUTES
// =========================================================================

// API UMUM (untuk Karyawan & SHE)
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    // Endpoint untuk mendapatkan daftar gedung (Map top-level)
    Route::get('maps/gedung', [MapController::class, 'getGedung'])->name('maps.gedung');
    // Endpoint untuk mendapatkan semua Master Lokasi, difilter berdasarkan map_id
    Route::get('locations', [LocationController::class, 'apiIndex'])->name('locations.index');
    // Endpoint untuk mendapatkan semua cell dari sebuah map
    Route::get('maps/{map_id}/cells', [CellController::class, 'index'])->name('maps.cells');
});

// API KHUSUS SHE (untuk Grid Editor & Aksi Administratif)
Route::middleware(['auth', 'role:she'])->prefix('she/api')->name('she.api.')->group(function () {
    Route::post('cells', [CellController::class, 'store'])->name('cells.store');
    Route::post('cells/batch-update', [CellController::class, 'batchUpdate'])->name('cells.batchUpdate');
    Route::put('cells/{cell}', [CellController::class, 'update'])->name('cells.update');
    Route::delete('cells/{cell}', [CellController::class, 'destroy'])->name('cells.destroy');
});
