<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\Cell;
use App\Models\Hazard;
use App\Models\Location; // Import the Location model
use App\Models\Map; // Import the Hazard model
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
            // Muat relasi 'location' dan 'riskParameters'
            $cells = $map->cells()->with(['location', 'riskParameters'])->paginate(500);

            $cells->getCollection()->transform(function ($cell) {
                $currentRiskScore = $cell->risk_score; // Start with the cell's current stored risk score

                // If cell has a location and current risk score is null, recalculate from hazards
                if ($cell->location_id !== null && ($currentRiskScore === null || $currentRiskScore === 0)) {
                    $associatedHazards = Hazard::where('location_id', $cell->location_id)
                        ->whereIn('status', ['diproses', 'selesai'])
                        ->get();

                    if ($associatedHazards->isNotEmpty()) {
                        $calculatedRiskScore = (int) round($associatedHazards->avg(function ($hazard) {
                            return $hazard->risk_score ?? 0;
                        }));
                        $currentRiskScore = $calculatedRiskScore;
                    } else {
                        $currentRiskScore = 0; // Default to 0 if no hazards found
                    }
                } elseif ($cell->location_id === null && ($currentRiskScore === null || $currentRiskScore === 0)) {
                    // If no location and no risk score, it's an empty cell
                    $currentRiskScore = null;
                }

                $cell->risk_score = $currentRiskScore;
                $cell->zone_color = getRiskColor($currentRiskScore);

                return $cell;
            });

            return response()->json($cells);

        } catch (\Exception $e) {
            Log::error('Error fetching and processing cells for map ID '.$map_id.': '.$e->getMessage());

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
            if (! empty($riskParameters)) {
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
            if (! empty($riskParameters)) {
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
            if (! empty($riskParameters)) {
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
            if (! empty($riskParameters)) {
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
            'location_id' => 'nullable|exists:locations,id',
            'risk_score' => 'nullable|integer|min:0|max:10',
        ]);

        try {
            $locationId = $validatedData['location_id'] ?? null;
            $requestRiskScore = $validatedData['risk_score'] ?? null; // Risk score explicitly sent from request

            Log::debug("BatchUpdate: Initial locationId = {$locationId}, requestRiskScore = {$requestRiskScore}");

            $cellsAfterUpdate = DB::transaction(function () use ($validatedData, $locationId, $requestRiskScore) {
                $updatedCells = [];
                foreach ($validatedData['cells'] as $cellCoord) {
                    $currentRiskScore = $requestRiskScore; // Start with riskScore from request for this cell

                    // If a location is assigned but no risk_score is given from request, determine it.
                    if ($locationId !== null && $currentRiskScore === null) {
                        // Find existing hazards for this location
                        $associatedHazards = Hazard::where('location_id', $locationId)
                            ->whereIn('status', ['diproses', 'selesai']) // Only validated hazards
                            ->get(); // Fetch filtered hazards for location
                        if ($associatedHazards->isNotEmpty()) {
                            // Calculate the average risk_score, treating null risk_score as 0
                            $calculatedRiskScore = (int) round($associatedHazards->avg(function ($hazard) {
                                return $hazard->risk_score ?? 0;
                            }));
                            $currentRiskScore = $calculatedRiskScore;
                            Log::debug("BatchUpdate: Calculated riskScore ({$currentRiskScore}) from associated hazards for cell ({$cellCoord['row_index']},{$cellCoord['col_index']}).");
                        } else {
                            // If no associated hazards, then the risk is 0 (or null if we want white)
                            $currentRiskScore = 0; // Default to 0 if no hazards found for this location
                            Log::debug("BatchUpdate: No associated hazards for locationId {$locationId}, defaulting riskScore to 0.");
                        }
                    } elseif ($locationId === null && $currentRiskScore === null) {
                        // If no location is assigned AND no risk_score is given, it means it's an 'empty' cell.
                        // We should retain null for risk_score to represent 'white' zone.
                        $currentRiskScore = null;
                        Log::debug("BatchUpdate: No location and no riskScore provided, setting riskScore to null for cell ({$cellCoord['row_index']},{$cellCoord['col_index']}).");
                    }

                    // Build update data for this specific cell
                    $cellUpdateData = [
                        'location_id' => $locationId,
                        'risk_score' => $currentRiskScore,
                        'zone_color' => getRiskColor($currentRiskScore),
                        'area_id' => null, // These remain null as per spec from logspek.txt
                        'area_name' => null,
                        'area_type' => null,
                    ];

                    $cell = Cell::updateOrCreate(
                        [
                            'map_id' => $validatedData['map_id'],
                            'row_index' => $cellCoord['row_index'],
                            'col_index' => $cellCoord['col_index'],
                        ],
                        $cellUpdateData
                    );
                    $updatedCells[] = $cell;
                }
                foreach ($updatedCells as $cell) {
                    $cell->load('location');
                }

                return $updatedCells;
            });

            return response()->json($cellsAfterUpdate);

        } catch (\Exception $e) {
            Log::error('Error during batch cell update: '.$e->getMessage());

            return response()->json(['error' => 'Failed to update cells.', 'message' => $e->getMessage()], 500);
        }
    }
}
