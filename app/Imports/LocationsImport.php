<?php

namespace App\Imports;

use App\Models\Location;
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
        
        // MAPPING: Handle various header formats
        // Name: nama_lokasi OR nama_area
        $name = $row['nama_lokasi'] ?? $row['nama_lokasi_'] ?? $row['nama-lokasi'] ?? $row['nama_area'] ?? null;
        
        // ID: location_id_string OR area_id
        $locId = $row['location_id_string'] ?? $row['location_id_string_'] ?? $row['location-id-string'] ?? $row['area_id'] ?? null;
        
        // Type: tipe OR type
        $type = $row['tipe'] ?? $row['tipe_'] ?? $row['type'] ?? null;
        
        // Parent: parent_id
        $parentId = $row['parent_id_optional'] ?? $row['parent-id-optional'] ?? null;
        
        // Map: map_id OR gedung
        $mapId = $row['map_id'] ?? $row['map_id_'] ?? $row['map-id'] ?? null;
        $gedungName = $row['gedung'] ?? $row['gedung_'] ?? null;

        // Auto-Create/Find Map if 'Gedung' is provided but map_id is missing
        if (!$mapId && $gedungName) {
            $map = \App\Models\Map::firstOrCreate(
                ['name' => $gedungName],
                [
                    'type' => 'pabrik', // Default type
                    'rows' => 10,       // Default rows
                    'cols' => 10,       // Default cols
                    'created_by' => Auth::id() ?? 1
                ]
            );
            $mapId = $map->id;
        }
        
        // Order: display_order OR default 0
        $order = $row['display_order'] ?? $row['display_order_'] ?? $row['display-order'] ?? 0;

        // Skip if mandatory fields are missing
        if (!$name && !$locId && !$mapId) {
            return null;
        }

        return Location::updateOrCreate(
            ['location_id_string' => $locId],
            [
                'name' => $name,
                'type' => $type ? strtolower($type) : null,
                'parent_id' => !empty($parentId) ? $parentId : null,
                'map_id' => $mapId,
                'display_order' => $order,
                'created_by' => Auth::id() ?? 1,
            ]
        );
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
            '*.map_id' => 'nullable',
            
            // Custom Format (Master_Lokasi_AutoID)
            '*.nama_area' => 'nullable',
            '*.area_id' => 'nullable',
            '*.type' => 'nullable',
            '*.gedung' => 'nullable',
            
            // Dash variations
            '*.nama-lokasi' => 'nullable',
            '*.location-id-string' => 'nullable',
            '*.map-id' => 'nullable',
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
