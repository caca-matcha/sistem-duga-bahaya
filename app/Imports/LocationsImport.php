<?php

namespace App\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LocationsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Debug: Log the raw row data
        Log::info('Import Row Data:', $row);
        
        // Skip empty rows
        if (empty($row['nama_lokasi']) && empty($row['nama_lokasi_'])) {
            return null;
        }
        
        return new Location([
            'name' => $row['nama_lokasi'] ?? $row['nama_lokasi_'] ?? null,
            'location_id_string' => $row['location_id_string'] ?? $row['location_id_string_'] ?? null,
            'type' => $row['tipe'] ?? $row['tipe_'] ?? null,
            'parent_id' => !empty($row['parent_id_optional']) ? $row['parent_id_optional'] : null,
            'map_id' => $row['map_id'] ?? $row['map_id_'] ?? null,
            'display_order' => $row['display_order'] ?? $row['display_order_'] ?? 0,
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.nama_lokasi' => 'nullable|string|max:255',
            '*.location_id_string' => 'nullable|string|max:255',
            '*.tipe' => 'nullable|string',
            '*.parent_id_optional' => 'nullable',
            '*.map_id' => 'nullable',
            '*.display_order' => 'nullable',
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
