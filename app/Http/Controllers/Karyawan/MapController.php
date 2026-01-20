<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Map;

class MapController extends Controller
{
    /**
     * Display a listing of the maps for employees.
     */
    public function index()
    {
        $pabrikMap = Map::where('type', 'Pabrik')->first();
        $gedungMaps = Map::where('type', 'Gedung')->get();

        return view('karyawan.maps.index', compact('pabrikMap', 'gedungMaps'));
    }

    /**
     * Display the specified map for employees.
     */
    public function show(Map $map)
    {
        return view('karyawan.maps.show', compact('map'));
    }
}
