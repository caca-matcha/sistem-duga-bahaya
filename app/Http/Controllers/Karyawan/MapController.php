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
        $pabrikMap = Map::where('type', 'Pabrik')
            ->orderBy('is_primary', 'desc')
            ->orderBy('sort_order')
            ->first();
        $gedungMaps = Map::where('type', 'Gedung')->orderBy('sort_order')->orderBy('id')->get();

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
