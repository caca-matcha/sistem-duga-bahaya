<?php

use App\Http\Controllers\Karyawan\DashboardController as KaryawanDashboardController;
use App\Http\Controllers\Karyawan\HazardController as KaryawanHazardController;
use App\Http\Controllers\Karyawan\MapController as KaryawanMapController;
use App\Http\Controllers\Karyawan\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| KARYAWAN WEB ROUTES
|--------------------------------------------------------------------------
|
| Rute-rute ini dilindungi oleh middleware 'auth' dan 'role:karyawan'.
| Mereka menangani semua fungsionalitas yang dapat diakses oleh karyawan.
|
*/
Route::middleware(['auth', 'role:karyawan']) // Middleware diubah menjadi hanya 'karyawan'
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {

        // 1. DASHBOARD ROUTE
        Route::get('/', [KaryawanDashboardController::class, 'index'])->name('dashboard');

        // 2. HAZARD REPORTING (CRUD Routes)

        // Form Pelaporan Hazard
        Route::get('hazards/create', [KaryawanHazardController::class, 'create'])->name('hazards.create');
        // Penyimpanan Laporan Hazard
        Route::post('hazards', [KaryawanHazardController::class, 'store'])->name('hazards.store');
        // Update Laporan Hazard (untuk PIC/Leader menyelesaikan tugas)
        Route::put('hazards/{hazard}', [KaryawanHazardController::class, 'update'])->name('hazards.update');

        // Melihat detail laporan hazard yang telah dibuat oleh user
        Route::get('hazards/{hazard}', [KaryawanHazardController::class, 'show'])->name('hazards.show');

        // Rute untuk melihat daftar laporan milik sendiri
        Route::get('hazards', [KaryawanHazardController::class, 'index'])->name('hazards.index');

        // 3. MAP/PETA BAHAYA ROUTES (Melihat Peta Bahaya)
        Route::get('maps', [KaryawanMapController::class, 'index'])->name('maps.index');
        Route::get('maps/{map}', [KaryawanMapController::class, 'show'])->name('maps.show');

        // 4. NOTIFICATION ROUTES
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

        // Tambahkan rute lain untuk Karyawan di sini.
    });

// Catatan: Route::get('/') di bagian bawah file ini sudah tidak diperlukan
// karena sudah ditangani di routes/web.php.

// <?php

// use Illuminate\Support\Facades\Route;
// Import yang tidak terpakai (DashboardController) akan dihapus,
// atau pastikan Anda menggunakannya jika memang memisahkannya.
// use App\Http\Controllers\Karyawan\HazardController as KaryawanHazardController;
// use App\Http\Controllers\Karyawan\MapController as KaryawanMapController;

/*
|--------------------------------------------------------------------------
| KARYAWAN WEB ROUTES
|--------------------------------------------------------------------------
|
| Rute-rute ini dilindungi oleh middleware 'auth' dan 'role:karyawan'.
| Mereka menangani semua fungsionalitas yang dapat diakses oleh karyawan.
|
*/
// Route::middleware(['auth', 'role:karyawan']) // Middleware diubah menjadi hanya 'karyawan'
//  ->prefix('karyawan')
// ->name('karyawan.')
// ->group(function () {

// 1. DASHBOARD ROUTE (Menggunakan HazardController@index untuk menampilkan laporan)
// KaryawanHazardController@index sudah mengambil data laporan dan statistik
// Route::get('/', [KaryawanHazardController::class, 'index'])->name('dashboard');

// 2. HAZARD REPORTING (Menggunakan Route::resource untuk membersihkan kode)
// Meliputi: index, create, store, dan show
// Route::resource('hazards', KaryawanHazardController::class)->only([
//  'index', 'create', 'store', 'show'
// ]);

// 3. MAP/PETA BAHAYA ROUTES (Melihat Peta Bahaya)
// Route::get('maps', [KaryawanMapController::class, 'index'])->name('maps.index');
// Route::get('maps/{map}', [KaryawanMapController::class, 'show'])->name('maps.show');

// Tambahkan rute lain untuk Karyawan di sini.
// });

// Catatan: Route::get('/') di bagian bawah file ini sudah tidak diperlukan
// karena sudah ditangani di routes/web.php.
