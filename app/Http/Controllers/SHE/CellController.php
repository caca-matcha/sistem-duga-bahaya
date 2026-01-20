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
            Log::info('CellController@index method was hit for map ID: ' . $map_id);

            $map = Map::findOrFail($map_id);
            // Muat relasi 'location' dan 'riskParameters'
            $cells = $map->cells()->with(['location.parent', 'riskParameters'])->paginate(500);

            Log::info('Found ' . $cells->count() . ' cells for map ID: ' . $map_id);

            $cells->getCollection()->transform(function ($cell) {
                $currentRiskScore = $cell->risk_score; // Start with the cell's current stored risk score

                // Determine building_id from location hierarchy
                $buildingId = null;
                if ($cell->location) {
                    $currentLocation = $cell->location;
                    while ($currentLocation) {
                        if ($currentLocation->type === 'building') {
                            $buildingId = $currentLocation->id;
                            break;
                        }
                        if ($currentLocation->type === 'factory') { // Stop if we reach a factory without finding a building
                            break;
                        }
                        $currentLocation = $currentLocation->parent;
                    }
                }
                $cell->building_id = $buildingId;

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
            'gedung_map_id' => 'sometimes|exists:maps,id', // Added for Pabrik map type
            'risk_parameters' => 'nullable|array',
            'risk_parameters.*.parameter_name' => 'nullable|string|max:255', // Changed to nullable
            'risk_parameters.*.value' => 'nullable|numeric|min:0', // Changed to nullable
        ]);

        $cell = null;
        DB::transaction(function () use ($validatedData, &$cell, $request) {
            $map = Map::find($validatedData['map_id']);
            $riskScore = 0;

            $cellData = $validatedData;

            if ($map && $map->type === 'Pabrik') {
                $gedungMapId = $validatedData['gedung_map_id'] ?? null;
                $aggregatedRiskScore = 0;

                if ($gedungMapId) {
                    $gedungMapCells = Cell::where('map_id', $gedungMapId)
                        ->whereNotNull('risk_score')
                        ->get();

                    if ($gedungMapCells->isNotEmpty()) {
                        $aggregatedRiskScore = (int) round($gedungMapCells->avg('risk_score'));
                    }
                }

                $riskScore = $aggregatedRiskScore;
                $cellData['metadata'] = array_merge($validatedData['metadata'] ?? [], ['gedung_map_id' => $gedungMapId]);

            } else { // Existing logic for non-'Pabrik' map types
                $riskParameters = $request->risk_parameters ?? [];
                if (! empty($riskParameters)) {
                    $riskScore = array_sum(array_column($riskParameters, 'value'));
                }
            }

            $cellData['risk_score'] = $riskScore;

            $zoneColor = 'green'; // Default
            if ($riskScore >= 4 && $riskScore <= 7) {
                $zoneColor = 'yellow';
            } elseif ($riskScore >= 8) {
                $zoneColor = 'red';
            }
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
            'gedung_map_id' => 'sometimes|exists:maps,id', // Added for Pabrik map type
            'risk_parameters' => 'nullable|array',
            'risk_parameters.*.parameter_name' => 'nullable|string|max:255', // Changed to nullable
            'risk_parameters.*.value' => 'nullable|numeric|min:0', // Changed to nullable
        ]);

        DB::transaction(function () use ($validatedData, $cell, $request) {
            // Retrieve the map associated with the cell
            $map = $cell->map; // Assuming cell has a 'map' relationship

            $riskScore = 0;
            $riskParameters = $request->risk_parameters ?? []; // Define here for wider scope

            $cellDataToUpdate = [
                'area_id' => $validatedData['area_id'] ?? null,
                'area_name' => $validatedData['area_name'] ?? null,
                'area_type' => $validatedData['area_type'] ?? null,
                'metadata' => $validatedData['metadata'] ?? null,
            ];

            if ($map && $map->type === 'Pabrik') {
                $gedungMapId = $validatedData['gedung_map_id'] ?? null;
                $aggregatedRiskScore = 0;

                if ($gedungMapId) {
                    $gedungMapCells = Cell::where('map_id', $gedungMapId)
                        ->whereNotNull('risk_score')
                        ->get();

                    if ($gedungMapCells->isNotEmpty()) {
                        $aggregatedRiskScore = (int) round($gedungMapCells->avg('risk_score'));
                    }
                }

                $riskScore = $aggregatedRiskScore;
                $cellDataToUpdate['metadata'] = array_merge($validatedData['metadata'] ?? [], ['gedung_map_id' => $gedungMapId]);

            } else { // Existing logic for non-'Pabrik' map types
                if (! empty($riskParameters)) {
                    $riskScore = array_sum(array_column($riskParameters, 'value'));
                }
            }

            $cellDataToUpdate['risk_score'] = $riskScore;

            $zoneColor = 'green'; // Default
            if ($riskScore >= 4 && $riskScore <= 7) {
                $zoneColor = 'yellow';
            } elseif ($riskScore >= 8) {
                $zoneColor = 'red';
            }
            $cellDataToUpdate['zone_color'] = $zoneColor;

            $cell->update($cellDataToUpdate);

            // Only delete/create riskParameters if not a 'Pabrik' map
            if ($map && $map->type !== 'Pabrik') {
                $cell->riskParameters()->delete();
                if (! empty($riskParameters)) {
                    $cell->riskParameters()->createMany($riskParameters);
                }
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
        Log::info('CellController@batchUpdate: Request received.', $request->all());

        $validatedData = $request->validate([
            'map_id' => 'required|exists:maps,id',
            'cells' => 'required|array',
            'cells.*.row_index' => 'required|integer|min:0',
            'cells.*.col_index' => 'required|integer|min:0',
            'location_id' => 'nullable|exists:locations,id',
            'gedung_map_id' => 'nullable|exists:maps,id', // Added for Pabrik map type
        ]);

        try {
            $map = Map::find($validatedData['map_id']);
            Log::info('CellController@batchUpdate: Processing for map.', ['map_id' => $map->id, 'map_type' => $map->type]);

            $cellsAfterUpdate = DB::transaction(function () use ($validatedData, $map) {
                $updatedCells = [];

                if ($map && $map->type === 'Pabrik') {
                    Log::info('CellController@batchUpdate: Executing "Pabrik" logic path.');

                    // Pabrik Map Logic
                    $gedungMapId = $validatedData['gedung_map_id'] ?? null;
                    $aggregatedRiskScore = null;

                    if ($gedungMapId) {
                        // Find all location_ids within the selected Gedung map's cells
                        $locationIdsInGedung = Cell::where('map_id', $gedungMapId)
                            ->whereNotNull('location_id')
                            ->pluck('location_id')
                            ->unique();

                        if ($locationIdsInGedung->isNotEmpty()) {
                            // Find all approved hazards associated with those locations
                            $approvedHazards = Hazard::whereIn('location_id', $locationIdsInGedung)
                                ->whereIn('status', ['diproses', 'selesai'])
                                ->get();

                            if ($approvedHazards->isNotEmpty()) {
                                // Calculate the average risk from the approved hazards
                                $aggregatedRiskScore = (int) round($approvedHazards->avg('risk_score'));
                            } else {
                                $aggregatedRiskScore = 0; // No approved reports, so risk is 0
                            }
                        } else {
                            $aggregatedRiskScore = 0; // No locations assigned in the building, so risk is 0
                        }
                    }

                    foreach ($validatedData['cells'] as $cellCoord) {
                        $cell = Cell::updateOrCreate(
                            [
                                'map_id' => $validatedData['map_id'],
                                'row_index' => $cellCoord['row_index'],
                                'col_index' => $cellCoord['col_index'],
                            ],
                            [
                                'risk_score' => $aggregatedRiskScore,
                                'zone_color' => getRiskColor($aggregatedRiskScore),
                                'metadata' => ['gedung_map_id' => $gedungMapId],
                                'location_id' => null, // Ensure location_id is cleared
                            ]
                        );
                        $updatedCells[] = $cell;
                    }

                } else {
                    Log::info('CellController@batchUpdate: Executing "Non-Pabrik" logic path.');
                    $locationId = $validatedData['location_id'] ?? null;
                    Log::info('CellController@batchUpdate: Location ID to be applied.', ['location_id' => $locationId]);

                    // Non-Pabrik Map Logic (existing logic)
                    foreach ($validatedData['cells'] as $cellCoord) {
                        $currentRiskScore = null;

                        if ($locationId !== null) {
                            $associatedHazards = Hazard::where('location_id', $locationId)
                                ->whereIn('status', ['diproses', 'selesai'])
                                ->get();

                            if ($associatedHazards->isNotEmpty()) {
                                $calculatedRiskScore = (int) round($associatedHazards->avg(fn ($h) => $h->risk_score ?? 0));
                                $currentRiskScore = $calculatedRiskScore;
                            } else {
                                $currentRiskScore = 0;
                            }
                        }

                        $cell = Cell::updateOrCreate(
                            [
                                'map_id' => $validatedData['map_id'],
                                'row_index' => $cellCoord['row_index'],
                                'col_index' => $cellCoord['col_index'],
                            ],
                            [
                                'location_id' => $locationId,
                                'risk_score' => $currentRiskScore,
                                'zone_color' => getRiskColor($currentRiskScore),
                                'metadata' => null, // Clear metadata for non-pabrik
                            ]
                        );
                        Log::info('CellController@batchUpdate: Cell updated.', ['map_id' => $cell->map_id, 'row' => $cell->row_index, 'col' => $cell->col_index, 'applied_location_id' => $cell->location_id]);
                        $updatedCells[] = $cell;
                    }
                }

                foreach ($updatedCells as $cell) {
                    $cell->load('location'); // Still useful for non-pabrik maps
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
