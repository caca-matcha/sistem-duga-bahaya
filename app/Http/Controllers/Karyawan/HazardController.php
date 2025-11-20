<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hazard;

class HazardController extends Controller
{
    public function index()
    {
        $hazards = Hazard::all();
        return view('karyawan.dashboard', compact('hazards'));
    }
}