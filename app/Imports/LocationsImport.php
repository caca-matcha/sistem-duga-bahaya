<?php

namespace App\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Auth;

class LocationsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Laravel Excel normalizes headers: lowercase, spaces to underscores, removes special chars
        // "Nama Lokasi *" becomes "nama_lokasi"
        // "Location ID String *" becomes "location_id_string"
        // "Tipe *" becomes "tipe"
        // "Parent ID (optional)" becomes "parent_id_optional"
        // "Map ID *" becomes "map_id"
        // "Display Order *" becomes "display_order"
        
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
            '*.nama_lokasi' => 'required|string|max:255',
            '*.location_id_string' => 'required|string|max:255|unique:locations,location_id_string',
            '*.tipe' => 'required|in:room,warehouse,production,office,corridor,stairs,elevator,parking,outdoor,other',
            '*.parent_id_optional' => 'nullable|exists:locations,id',
            '*.map_id' => 'required|exists:maps,id',
            '*.display_order' => 'required|integer|min:0',
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
}
