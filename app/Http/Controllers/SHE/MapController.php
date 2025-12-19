<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; // Added for logging

class MapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maps = Map::with('parent')->get();
        return view('she.maps.index', compact('maps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $maps = Map::all(); // Fetch all maps to populate parent_id dropdown
        return view('she.maps.create', compact('maps'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:maps,id', // Added parent_id validation
            'rows' => 'required|integer|min:1',
            'cols' => 'required|integer|min:1',
            'background_image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $imagePath = null;
        if ($request->hasFile('background_image')) {
            $imagePath = $request->file('background_image')->store('map_backgrounds', 'public');
        }

        Map::create([
            'name' => $validatedData['name'],
            'type' => $validatedData['type'],
            'parent_id' => $validatedData['parent_id'] ?? null, // Added parent_id
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
    public function show(Map $map)
    {
        $map->load(['cells.riskParameters']); // Eager load relations
        Log::info('Map data for show view: ' . json_encode($map->toArray())); // Log the map data
        return view('she.maps.show', compact('map'));
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

        $filename = 'map-' . Str::slug($map->name) . '-' . $map->id . '.json';

        return response()->json($mapData)->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Import a map from a JSON file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'map_file' => 'required|file|mimes:json|max:2048', // Max 2MB, JSON file
        ]);

        try {
            $jsonContent = file_get_contents($request->file('map_file')->getRealPath());
            $importedData = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw ValidationException::withMessages(['map_file' => 'Invalid JSON file.']);
            }

            // Basic validation for the imported data structure
            $request->merge($importedData); // Merge for easier validation
            $validatedImportData = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'rows' => 'required|integer|min:1',
                'cols' => 'required|integer|min:1',
                'background_image' => 'nullable|string', // Path, not file
                'cells' => 'nullable|array',
                'cells.*.row_index' => 'required|integer|min:0',
                'cells.*.col_index' => 'required|integer|min:0',
                'cells.*.area_id' => 'nullable|string|max:255',
                'cells.*.area_name' => 'nullable|string|max:255',
                'cells.*.area_type' => 'nullable|string|max:255',
                'cells.*.risk_score' => 'nullable|integer|min:0|max:10',
                'cells.*.zone_color' => 'nullable|string|max:255',
                'cells.*.metadata' => 'nullable|array',
                'cells.*.risk_parameters' => 'nullable|array',
                'cells.*.risk_parameters.*.parameter_name' => 'required|string|max:255',
                'cells.*.risk_parameters.*.value' => 'required|numeric',
            ]);

            DB::transaction(function () use ($validatedImportData) {
                $map = Map::create([
                    'name' => $validatedImportData['name'] . ' (Imported)', // Append to avoid name conflicts
                    'type' => $validatedImportData['type'],
                    'rows' => $validatedImportData['rows'],
                    'cols' => $validatedImportData['cols'],
                    'background_image' => $validatedImportData['background_image'] ?? null,
                    'created_by' => Auth::id(),
                ]);

                if (isset($validatedImportData['cells'])) {
                    foreach ($validatedImportData['cells'] as $cellData) {
                        $cell = $map->cells()->create([
                            'row_index' => $cellData['row_index'],
                            'col_index' => $cellData['col_index'],
                            'area_id' => $cellData['area_id'] ?? null,
                            'area_name' => $cellData['area_name'] ?? null,
                            'area_type' => $cellData['area_type'] ?? null,
                            'risk_score' => $cellData['risk_score'] ?? null,
                            'zone_color' => $cellData['zone_color'] ?? null,
                            'metadata' => $cellData['metadata'] ?? null,
                        ]);

                        if (isset($cellData['risk_parameters'])) {
                            $cell->riskParameters()->createMany($cellData['risk_parameters']);
                        }
                    }
                }
            });

            return redirect()->route('she.maps.index')->with('success', 'Map imported successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing map: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Export risk data for a map as an Excel (CSV) file.
     */
    public function exportRiskDataExcel(Map $map)
    {
        $map->load(['cells.riskParameters']); // Eager load relations

        $filename = 'risk_data_' . Str::slug($map->name) . '_' . $map->id . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($map) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8 compatibility in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Headers
            $columns = [
                'Map Name', 'Map Type', 'Row Index', 'Col Index', 'Area ID', 'Area Name', 'Area Type',
                'Risk Score', 'Zone Color', 'Metadata'
            ];
            // Dynamically add risk parameter columns
            $allRiskParamNames = $map->cells->flatMap(fn($cell) => $cell->riskParameters->pluck('parameter_name'))->unique()->sort()->toArray();
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
        $gedung = Map::where('type', 'Gedung')->orWhereNull('parent_id')->get(['id', 'name']);
        return response()->json($gedung);
    }
}
