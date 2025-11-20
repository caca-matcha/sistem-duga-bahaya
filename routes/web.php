<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SHE\HazardController as SHEHazardController;
use App\Http\Controllers\SHE\MapController;
use App\Http\Controllers\SHE\UserController;
use App\Http\Controllers\SHE\CellController;
use App\Http\Controllers\SHE\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ROUTE AUTH BAWAAN BREEZE (login, register, dll)
require __DIR__.'/auth.php';

// ROUTE WEB UNTUK KARYAWAN
require __DIR__.'/karyawan.php';

// HOME: langsung arahkan ke dashboard setelah login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// DASHBOARD UTAMA SETELAH LOGIN
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'she') {
        return redirect()->route('she.dashboard');
    }

    // fallback kalau role nggak dikenal
    return view('dashboard');
})->name('dashboard');


// ROUTE WEB UNTUK SHE
Route::middleware(['auth', 'role:she'])
    ->prefix('she')
    ->name('she.')
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('hazards', [SHEHazardController::class, 'index'])->name('hazards.index');
        Route::get('hazards/{hazard}', [SHEHazardController::class, 'show'])->name('hazards.show');

        // Terima & proses
        Route::post('hazards/{hazard}/update-status', [SHEHazardController::class, 'updateStatus'])->name('hazards.updateStatus');

        // Tolak
        Route::get('hazards/{hazard}/tolak', [SHEHazardController::class, 'tolakForm'])->name('hazards.tolakForm');
        Route::post('hazards/{hazard}/tolak', [SHEHazardController::class, 'tolak'])->name('hazards.tolak');

        // Selesai
        Route::get('hazards/{hazard}/selesai', [SHEHazardController::class, 'selesaiForm'])->name('hazards.selesaiForm');
        Route::post('hazards/{hazard}/selesai', [SHEHazardController::class, 'selesai'])->name('hazards.selesai');

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
