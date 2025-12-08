<?php

use Illuminate\Support\Facades\Route;
// Import Controllers SHE dengan alias
use App\Http\Controllers\SHE\HazardController as SHEHazardController;
use App\Http\Controllers\SHE\MapController;
use App\Http\Controllers\SHE\UserController;
use App\Http\Controllers\SHE\CellController;
use App\Http\Controllers\SHE\DashboardController as SHEDashboardController; // Ditambahkan Alias
use App\Http\Controllers\SHE\ReportController; // PENTING: Import ReportController

// Import Controller Karyawan untuk logika redirect yang lebih aman
use App\Http\Controllers\Karyawan\DashboardController as KaryawanDashboardController; // Ditambahkan

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
        Route::get('hazards/{hazard}', [SHEHazardController::class, 'show'])->name('hazards.show');
        
        // ===========================================
        // 🚀 PENAMBAHAN ROUTE FORM BARU UNTUK SHE
        // ===========================================

        // Rute untuk menampilkan formulir Rencana Tindakan (menunggu validasi -> diproses)
        Route::get('hazards/{hazard}/diproses-form', [SHEHazardController::class, 'diprosesForm'])->name('hazards.diprosesForm');

        // Rute untuk menampilkan formulir Penolakan (menunggu validasi -> ditolak)
        Route::get('hazards/{hazard}/tolak-form', [SHEHazardController::class, 'tolakForm'])->name('hazards.tolakForm');

        // Rute untuk menampilkan formulir Penyelesaian (diproses -> selesai)
        Route::get('hazards/{hazard}/selesai-form', [SHEHazardController::class, 'selesaiForm'])->name('hazards.selesaiForm');
        
        // ROUTE UTAMA UPDATE STATUS: Menangani semua status update (POST/PUT)
        Route::put('hazards/{hazard}/update-status', [SHEHazardController::class, 'updateStatus'])->name('hazards.updateStatus');

        // Kelola Peta
        Route::resource('maps', MapController::class);
        Route::resource('users', UserController::class);
        Route::get('maps/{map}/export', [MapController::class, 'export'])->name('maps.export');
        Route::post('maps/import', [MapController::class, 'import'])->name('maps.import');
        Route::get('maps/{map}/export-risk-excel', [MapController::class, 'exportRiskDataExcel'])->name('maps.export-risk-excel');
    });

// ROUTE API UNTUK SHE (Grid Editor)
Route::middleware(['auth', 'role:she'])
    ->prefix('she/api')
    ->name('she.api.')
    ->group(function () {
        Route::get('maps/{map_id}/cells', [CellController::class, 'index'])->name('maps.cells.index');
        Route::post('cells', [CellController::class, 'store'])->name('cells.store');
        Route::put('cells/{cell}', [CellController::class, 'update'])->name('cells.update');
        Route::delete('cells/{cell}', [CellController::class, 'destroy'])->name('cells.destroy');
    });