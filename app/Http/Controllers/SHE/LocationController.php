<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LocationsExport;
use App\Exports\LocationsTemplateExport;
use App\Imports\LocationsImport;

// <--- ADDED THIS LINE

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Location::with(['creator', 'map']); // Eager load map relation

        if ($request->has('search') && ! empty($request->search)) {
            $searchTerm = strtolower($request->search);
            $query->where(function ($q) use ($searchTerm) {
                $q->where(DB::raw('LOWER(name)'), 'LIKE', '%'.$searchTerm.'%')
                    ->orWhere(DB::raw('LOWER(location_id_string)'), 'LIKE', '%'.$searchTerm.'%')
                    ->orWhere(DB::raw('LOWER(type)'), 'LIKE', '%'.$searchTerm.'%') // Search by Type
                    ->orWhereHas('map', function ($mapQuery) use ($searchTerm) {
                        $mapQuery->where(DB::raw('LOWER(name)'), 'LIKE', '%'.$searchTerm.'%');
                    })
                    ->orWhereHas('creator', function ($userQuery) use ($searchTerm) { // Search by Creator
                        $userQuery->where(DB::raw('LOWER(name)'), 'LIKE', '%'.$searchTerm.'%');
                    });
            });
        }

        $locations = $query->get();

        // If it's an AJAX request, return only the table rows
        if ($request->ajax()) {
            return response()->json([
                'html' => view('she.locations._table_rows', compact('locations'))->render(),
                'total' => $locations->count(), // Add total count
            ]);
        }

        return view('she.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $maps = Map::where('type', 'Gedung')->get();

        return view('she.locations.create', compact('maps'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'location_id_string' => 'required|string|max:255|unique:locations,location_id_string',
            'type' => 'required|string|max:255|in:Area', // Type must be Area
            'map_id' => 'required|exists:maps,id', // Map ID is now required
        ]);

        Location::create([
            'name' => $validatedData['name'],
            'location_id_string' => $validatedData['location_id_string'],
            'type' => $validatedData['type'],
            'map_id' => $validatedData['map_id'],
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('she.locations.index')->with('success', 'Lokasi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        $maps = Map::where('type', 'Gedung')->get();

        return view('she.locations.edit', compact('location', 'maps'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'location_id_string' => 'required|string|max:255|unique:locations,location_id_string,'.$location->id,
            'type' => 'required|string|max:255|in:Area', // Type must be Area
            'map_id' => 'required|exists:maps,id', // Map ID is now required
        ]);

        $location->update([
            'name' => $validatedData['name'],
            'location_id_string' => $validatedData['location_id_string'],
            'type' => $validatedData['type'],
            'map_id' => $validatedData['map_id'],
        ]);

        return redirect()->route('she.locations.index')->with('success', 'Lokasi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('she.locations.index')->with('success', 'Lokasi berhasil dihapus!');
    }

    /**
     * Display a listing of the resource for API.
     */
    public function apiIndex(Request $request)
    {
        $query = Location::with('map');

        if ($request->has('map_id')) {
            $query->where('map_id', $request->input('map_id'));
        }

        $locations = $query->get();

        return response()->json($locations);
    }

    /**
     * Get all locations associated with a specific map.
     *
     * @param  \App\Models\Map  $map
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocationsForMap(Map $map)
    {
        $locations = Location::where('map_id', $map->id)->get();
        return response()->json($locations);
    }

    /**
     * Update the display order of locations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'locations' => 'required|array',
            'locations.*.id' => 'required|exists:locations,id',
            'locations.*.display_order' => 'required|integer',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['locations'] as $locationData) {
                Location::where('id', $locationData['id'])->update(['display_order' => $locationData['display_order']]);
            }
        });

        return response()->json(['message' => 'Location order updated successfully.']);
    }

    /**
     * Export locations to Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new LocationsExport, 'locations_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * Download import template.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadTemplate()
    {
        return Excel::download(new LocationsTemplateExport, 'template_import_locations.xlsx');
    }

    /**
     * Import locations from Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            $import = new LocationsImport();
            Excel::import($import, $request->file('file'));

            $messages = [];
            
            // Handle Validation Failures
            if ($import->failures()->isNotEmpty()) {
                foreach ($import->failures() as $failure) {
                    $row = $failure->row();
                    $attribute = $failure->attribute();
                    $errors = implode(', ', $failure->errors());
                    $messages[] = "Baris {$row} ({$attribute}): {$errors}";
                }
            }

            // Handle Exceptions (Database errors, etc)
            if ($import->errors()->isNotEmpty()) {
                foreach ($import->errors() as $error) {
                    // Normalize error message
                    $msg = $error->getMessage();
                    $messages[] = "Error Sistem: {$msg}";
                }
            }

            if (count($messages) > 0) {
                return redirect()->route('she.locations.index')
                    ->with('warning', 'Import selesai dengan catatan: ' . implode(' | ', $messages));
            }

            return redirect()->route('she.locations.index')
                ->with('success', 'Data lokasi berhasil diimport!');
                
        } catch (\Exception $e) {
            return redirect()->route('she.locations.index')
                ->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
