<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\Cell;
use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Added for logging

class CellController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($map_id)
    {
        try {
            $map = Map::findOrFail($map_id);
            $cells = $map->cells; // Get all cells for the map



            return response()->json($cells);

        } catch (\Exception $e) {
            Log::error('Error fetching and processing cells for map ID ' . $map_id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load and process map cells.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'map_id' => 'required|exists:maps,id',
            'row_index' => 'required|integer|min:0',
            'col_index' => 'required|integer|min:0',
            'area_id' => 'nullable|string|max:255',
            'area_name' => 'nullable|string|max:255',
            'area_type' => 'nullable|string|max:255',
            'metadata' => 'nullable|array',
            'risk_parameters' => 'nullable|array',
            'risk_parameters.*.parameter_name' => 'required|string|max:255',
            'risk_parameters.*.value' => 'required|numeric|min:0',
        ]);

        $cell = null;
        DB::transaction(function () use ($validatedData, &$cell, $request) {
            $riskScore = 0;
            $riskParameters = $request->risk_parameters ?? [];
            if (!empty($riskParameters)) {
                $riskScore = array_sum(array_column($riskParameters, 'value'));
            }

            $zoneColor = 'green'; // Default
            if ($riskScore >= 4 && $riskScore <= 7) {
                $zoneColor = 'yellow';
            } elseif ($riskScore >= 8) {
                $zoneColor = 'red';
            }

            $cellData = $validatedData;
            $cellData['risk_score'] = $riskScore;
            $cellData['zone_color'] = $zoneColor;

            $cell = Cell::updateOrCreate(
                [
                    'map_id' => $validatedData['map_id'],
                    'row_index' => $validatedData['row_index'],
                    'col_index' => $validatedData['col_index'],
                ],
                $cellData
            );

            // Delete old parameters and create new ones
            $cell->riskParameters()->delete();
            if (!empty($riskParameters)) {
                $cell->riskParameters()->createMany($riskParameters);
            }
        });

        // Load the relations to return them in the response
        $cell->load('riskParameters');

        return response()->json($cell, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cell $cell)
    {
        $validatedData = $request->validate([
            'area_id' => 'nullable|string|max:255',
            'area_name' => 'nullable|string|max:255',
            'area_type' => 'nullable|string|max:255',
            'metadata' => 'nullable|array',
            'risk_parameters' => 'nullable|array',
            'risk_parameters.*.parameter_name' => 'required_with:risk_parameters|string|max:255',
            'risk_parameters.*.value' => 'required_with:risk_parameters|numeric|min:0',
        ]);

        DB::transaction(function () use ($validatedData, $cell, $request) {
            $riskScore = 0;
            $riskParameters = $request->risk_parameters ?? [];
            if (!empty($riskParameters)) {
                $riskScore = array_sum(array_column($riskParameters, 'value'));
            }

            $zoneColor = 'green'; // Default
            if ($riskScore >= 4 && $riskScore <= 7) {
                $zoneColor = 'yellow';
            } elseif ($riskScore >= 8) {
                $zoneColor = 'red';
            }

            $cellDataToUpdate = [
                'area_id' => $validatedData['area_id'] ?? null,
                'area_name' => $validatedData['area_name'] ?? null,
                'area_type' => $validatedData['area_type'] ?? null,
                'metadata' => $validatedData['metadata'] ?? null,
                'risk_score' => $riskScore,
                'zone_color' => $zoneColor,
            ];

            $cell->update($cellDataToUpdate);

            // Delete old parameters and create new ones
            $cell->riskParameters()->delete();
            if (!empty($riskParameters)) {
                $cell->riskParameters()->createMany($riskParameters);
            }
        });

        $cell->load('riskParameters');

        return response()->json($cell, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cell $cell)
    {
        $cell->delete();

        return response()->json(null, 204);
    }

    /**
     * Batch update multiple cells.
     */
    public function batchUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'map_id' => 'required|exists:maps,id',
            'cells' => 'required|array',
            'cells.*.row_index' => 'required|integer|min:0',
            'cells.*.col_index' => 'required|integer|min:0',
            'area_id' => 'nullable|string|max:255',
            'area_name' => 'nullable|string|max:255',
            'area_type' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                $areaData = [
                    'area_id' => $validatedData['area_id'],
                    'area_name' => $validatedData['area_name'],
                    'area_type' => $validatedData['area_type'],
                ];

                foreach ($validatedData['cells'] as $cellCoord) {
                    Cell::updateOrCreate(
                        [
                            'map_id' => $validatedData['map_id'],
                            'row_index' => $cellCoord['row_index'],
                            'col_index' => $cellCoord['col_index'],
                        ],
                        $areaData // Apply the same area data to all selected cells
                    );
                }
            });

            return response()->json(['message' => 'Cells updated successfully.']);

        } catch (\Exception $e) {
            Log::error('Error during batch cell update: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update cells.', 'message' => $e->getMessage()], 500);
        }
    }
}