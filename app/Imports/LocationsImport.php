<?php

namespace App\Imports;

use App\Models\Location;
use App\Models\Map;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LocationsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Debug: Log the raw row data
        Log::info('Import Row Data:', $row);

        // MAPPING: Handle headers (slugified by Laravel Excel)
        // Nama Lokasi * -> nama_lokasi
        $name = $row['nama_lokasi'] ?? $row['nama_area'] ?? null;

        // Location ID String * -> location_id_string
        $locId = $row['location_id_string'] ?? $row['area_id'] ?? null;

        // Tipe * -> tipe
        $type = $row['tipe'] ?? $row['type'] ?? null;

        // Nama Map * -> nama_map
        // Try to find Map ID from Map Name
        $mapName = $row['nama_map_gedung'] ?? $row['nama_map'] ?? $row['gedung'] ?? null;
        $mapId = null;

        if ($mapName && $mapName !== '-') {
            $map = Map::where('name', $mapName)->first();
            if ($map) {
                $mapId = $map->id;
            }
        }

        // Use explicit map_id if provided (for advanced users)
        if (isset($row['map_id']) && !empty($row['map_id'])) {
            $mapId = $row['map_id'];
        }

        $locIdString = $row['location_id_string'] ?? $row['area_id'] ?? null;
        $systemId = $row['id'] ?? null;

        $updateData = [
            'name' => $row['nama_lokasi'] ?? $row['nama_area'] ?? 'Tanpa Nama',
            'type' => isset($row['tipe']) || isset($row['type']) ? strtolower($row['tipe'] ?? $row['type']) : 'area',
            'map_id' => $mapId,
            'created_by' => Auth::id() ?? 1,
        ];

        // Also update location_id_string if it's provided (important for renaming via System ID)
        if ($locIdString) {
            $updateData['location_id_string'] = $locIdString;
        }

        // PRIMITIVE LOGIC: Priority 1: Use System ID for finding existing record
        if ($systemId) {
            $existing = Location::find($systemId);
            if ($existing) {
                $existing->update($updateData);
                return $existing;
            }
        }

        // Priority 2: Fallback to location_id_string (Backward compatibility & new entries)
        if ($locIdString) {
            $existing = Location::where('location_id_string', $locIdString)->first();
            if ($existing) {
                $existing->update($updateData);
                return $existing;
            }

            // New record - calculate order
            $maxOrder = Location::where('map_id', $mapId)->max('display_order') ?? 0;
            $updateData['display_order'] = $maxOrder + 1;

            return new Location($updateData);
        }

        // Priority 3: Create new if neither provided
        $maxOrder = Location::where('map_id', $mapId)->max('display_order') ?? 0;
        $updateData['display_order'] = $maxOrder + 1;
        return new Location($updateData);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            // Standard Template
            '*.nama_lokasi' => 'nullable',
            '*.location_id_string' => 'nullable',
            '*.tipe' => 'nullable',
            '*.nama_map' => 'nullable',
            '*.map_id' => 'nullable',

            // Custom Format / Backward compatibility
            '*.nama_area' => 'nullable',
            '*.area_id' => 'nullable',
            '*.type' => 'nullable',
            '*.gedung' => 'nullable',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'nama_lokasi.required' => 'Nama lokasi wajib diisi',
            'location_id_string.required' => 'Location ID String wajib diisi',
            'location_id_string.unique' => 'Location ID String sudah digunakan',
            'tipe.required' => 'Tipe lokasi wajib diisi',
            'tipe.in' => 'Tipe lokasi tidak valid. Pilih: room, warehouse, production, office, corridor, stairs, elevator, parking, outdoor, atau other',
            'map_id.required' => 'Map ID wajib diisi',
            'map_id.exists' => 'Map ID tidak ditemukan',
            'display_order.required' => 'Display order wajib diisi',
            'display_order.integer' => 'Display order harus berupa angka',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
