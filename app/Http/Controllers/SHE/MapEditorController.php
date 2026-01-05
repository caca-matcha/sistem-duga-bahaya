<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\Map;
use Illuminate\Http\Request;

class MapEditorController extends Controller
{
    /**
     * Menampilkan halaman editor area peta.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil semua peta untuk ditampilkan di dropdown pemilih peta
        $maps = Map::orderBy('name')->get();

        return view('she.maps.editor', [
            'maps' => $maps,
        ]);
    }
}
