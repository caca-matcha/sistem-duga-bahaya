<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Jobs\RecalculateMapCellsJob;

class MapApiController extends Controller
{
    /**
     * Display a listing of the maps.
     */
    public function index()
    {
        $maps = Map::select('id', 'name', 'background_image', 'type', 'parent_id')
            ->where('type', 'Gedung')
            ->get();

        return response()->json($maps);
    }

    /**
     * Get the cells for a specific map.
     */
    public function getCells(Map $map)
    {
        $perPage = request()->get('per_page', 100);

        // Dispatch the job for background recalculation
        RecalculateMapCellsJob::dispatch($map);

        // Return the current state of cells immediately to avoid timeout
        $cells = $map->cells()->with('location')->paginate($perPage);

        return response()->json($cells);
    }
}
