<?php

use App\Http\Controllers\Api\MapApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Assuming this exists or will be created for /api/locations

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/maps', [MapApiController::class, 'index']);
Route::get('/maps/{map}/cells', [MapApiController::class, 'getCells']);
