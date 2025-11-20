<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Map;
use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Display a listing of the maps for employees.
     */
    public function index()
    {
        $maps = Map::all();
        return view('karyawan.maps.index', compact('maps'));
    }

    /**
     * Display the specified map for employees.
     */
    public function show(Map $map)
    {
        return view('karyawan.maps.show', compact('map'));
    }
}