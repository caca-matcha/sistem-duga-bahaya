<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Map;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::with(['parent', 'creator'])->get();

        return view('she.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locations = Location::all();
        $maps = Map::all();

        return view('she.locations.create', compact('locations', 'maps'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'location_id_string' => 'required|string|max:255|unique:locations,location_id_string',
            'type' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
            'map_id' => 'nullable|exists:maps,id',
        ]);

        Location::create([
            'name' => $validatedData['name'],
            'location_id_string' => $validatedData['location_id_string'],
            'type' => $validatedData['type'],
            'parent_id' => $validatedData['parent_id'],
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
        $locations = Location::where('id', '!=', $location->id)->get();
        $maps = Map::all();

        return view('she.locations.edit', compact('location', 'locations', 'maps'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'location_id_string' => 'required|string|max:255|unique:locations,location_id_string,'.$location->id,
            'type' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
            'map_id' => 'nullable|exists:maps,id',
        ]);

        $location->update([
            'name' => $validatedData['name'],
            'location_id_string' => $validatedData['location_id_string'],
            'type' => $validatedData['type'],
            'parent_id' => $validatedData['parent_id'],
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
}
