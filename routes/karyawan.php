<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Karyawan\HazardController as KaryawanHazardController;
use App\Http\Controllers\Karyawan\MapController as KaryawanMapController;

Route::middleware(['auth', 'role:karyawan,she'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        Route::get('maps', [KaryawanMapController::class, 'index'])->name('maps.index');
        Route::get('maps/{map}', [KaryawanMapController::class, 'show'])->name('maps.show');
        // Add other Karyawan routes here as needed, e.g., for hazard reporting
        Route::get('hazards', [KaryawanHazardController::class, 'index'])->name('hazards.index');
    });
