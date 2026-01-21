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
        
        // Laravel Excel normalizes headers. We handle both underscore and dash variations.
        $name = $row['nama_lokasi'] ?? $row['nama_lokasi_'] ?? $row['nama-lokasi'] ?? null;
        $locId = $row['location_id_string'] ?? $row['location_id_string_'] ?? $row['location-id-string'] ?? null;
        $type = $row['tipe'] ?? $row['tipe_'] ?? null;
        $parentId = $row['parent_id_optional'] ?? $row['parent-id-optional'] ?? null;
        $mapId = $row['map_id'] ?? $row['map_id_'] ?? $row['map-id'] ?? null;
        $order = $row['display_order'] ?? $row['display_order_'] ?? $row['display-order'] ?? 0;

        // Skip if mandatory fields are missing
        if (!$name && !$locId && !$mapId) {
            return null;
        }
        
        return new Location([
            'name' => $name,
            'location_id_string' => $locId,
            'type' => $type,
            'parent_id' => !empty($parentId) ? $parentId : null,
            'map_id' => $mapId,
            'display_order' => $order,
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.nama_lokasi' => 'nullable',
            '*.location_id_string' => 'nullable',
            '*.tipe' => 'nullable',
            '*.map_id' => 'nullable',
            '*.display_order' => 'nullable',
            
            // Allow dash variations to pass validation if they exist
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
