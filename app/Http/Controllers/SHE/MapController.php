<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Added for logging

class MapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pabrikMap = Map::where('type', 'Pabrik')->with('parent')->first();
        $gedungMaps = Map::where('type', 'Gedung')->with('parent')->get();
        $existingPabrikMap = (bool) $pabrikMap;

        return view('she.maps.index', compact('pabrikMap', 'gedungMaps', 'existingPabrikMap'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $maps = Map::all(); // Fetch all maps to populate parent_id dropdown
        $existingPabrikMap = Map::where('type', 'Pabrik')->exists(); // Check if Pabrik map exists
        $type = $request->query('type', 'Gedung'); // Default to 'Gedung' if not provided

        return view('she.maps.create', compact('maps', 'existingPabrikMap', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Pabrik,Gedung', // Validate type input
            'rows' => 'required|integer|min:1',
            'cols' => 'required|integer|min:1',
            'background_image' => 'nullable|image|max:2048',
        ];

        // Conditional validation for parent_id based on map type
        if ($request->input('type') === 'Gedung') {
            $rules['parent_id'] = 'nullable|exists:maps,id';
        }

        $messages = [
            'parent_id.exists' => 'Parent Map yang dipilih tidak valid.',
            'type.in' => 'Tipe peta tidak valid.',
        ];

        $validatedData = $request->validate($rules, $messages);

        // Custom validation for 'Pabrik' type uniqueness
        if ($validatedData['type'] === 'Pabrik' && Map::where('type', 'Pabrik')->exists()) {
            return back()->withInput()->withErrors(['type' => 'Peta dengan tipe Pabrik sudah ada. Anda hanya dapat membuat satu Peta Risiko Pabrik.']);
        }

        $imagePath = null;
        if ($request->hasFile('background_image')) {
            $imagePath = $request->file('background_image')->store('map_backgrounds', 'public');
        }

        // Set parent_id to null if type is Pabrik, otherwise use validated data
        $parentId = ($validatedData['type'] === 'Pabrik') ? null : ($validatedData['parent_id'] ?? null);

        Map::create([
            'name' => $validatedData['name'],
            'type' => $validatedData['type'],
            'parent_id' => $parentId,
            'rows' => $validatedData['rows'],
            'cols' => $validatedData['cols'],
            'background_image' => $imagePath,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('she.maps.index')->with('success', 'Map created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Map $map, Request $request)
    {
        $map->load(['cells.riskParameters']); // Eager load relations

        $searchQuery = $request->query('search_query'); // Get search query from request

        Log::info('Map data for show view: '.json_encode($map->toArray())); // Log the map data

        return view('she.maps.show', compact('map', 'searchQuery')); // Pass searchQuery to view
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Map $map)
    {
        $maps = Map::all(); // Fetch all maps to populate parent_id dropdown

        return view('she.maps.edit', compact('map', 'maps'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Map $map)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:maps,id', // Added parent_id validation
            'rows' => 'required|integer|min:1',
            'cols' => 'required|integer|min:1',
            'background_image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $updateData = $validatedData;

        if ($request->hasFile('background_image')) {
            // Delete old image if it exists
            if ($map->background_image) {
                Storage::disk('public')->delete($map->background_image);
            }
            $updateData['background_image'] = $request->file('background_image')->store('map_backgrounds', 'public');
        }

        $map->update($updateData);

        return redirect()->route('she.maps.index')->with('success', 'Map updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Map $map)
    {
        // Delete associated background image
        if ($map->background_image) {
            Storage::disk('public')->delete($map->background_image);
        }
        $map->delete();

        return redirect()->route('she.maps.index')->with('success', 'Map deleted successfully.');
    }

    /**
     * Export a map as a JSON file.
     */
    public function export(Map $map)
    {
        $mapData = $map->load(['cells.riskParameters']); // Eager load relations

        $filename = 'map-'.Str::slug($map->name).'-'.$map->id.'.json';

        return response()->json($mapData)->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    /**
     * Export risk data for a map as an Excel (CSV) file.
     */
    public function exportRiskDataExcel(Map $map)
    {
        $map->load(['cells.riskParameters']); // Eager load relations

        $filename = 'risk_data_'.Str::slug($map->name).'_'.$map->id.'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($map) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8 compatibility in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Headers
            $columns = [
                'Map Name', 'Map Type', 'Row Index', 'Col Index', 'Area ID', 'Area Name', 'Area Type',
                'Risk Score', 'Zone Color', 'Metadata',
            ];
            // Dynamically add risk parameter columns
            $allRiskParamNames = $map->cells->flatMap(fn ($cell) => $cell->riskParameters->pluck('parameter_name'))->unique()->sort()->toArray();
            $columns = array_merge($columns, $allRiskParamNames);

            fputcsv($file, $columns);

            // CSV Data
            foreach ($map->cells as $cell) {
                $rowData = [
                    $map->name,
                    $map->type,
                    $cell->row_index,
                    $cell->col_index,
                    $cell->area_id,
                    $cell->area_name,
                    $cell->area_type,
                    $cell->risk_score,
                    $cell->zone_color,
                    json_encode($cell->metadata), // JSON encode metadata for single column
                ];

                // Add dynamic risk parameter values
                $riskParamValues = [];
                foreach ($allRiskParamNames as $paramName) {
                    $param = $cell->riskParameters->firstWhere('parameter_name', $paramName);
                    $riskParamValues[] = $param ? $param->value : '';
                }
                $rowData = array_merge($rowData, $riskParamValues);

                fputcsv($file, $rowData);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * API Endpoint to get top-level maps (Gedung).
     */
    public function getGedung()
    {
        $gedung = Map::where('type', 'Gedung')->get(['id', 'name']);

        return response()->json($gedung);
    }
}
