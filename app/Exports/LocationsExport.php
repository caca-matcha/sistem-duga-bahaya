<?php

namespace App\Exports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LocationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Location::with(['map', 'parent'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Lokasi',
            'Location ID String',
            'Tipe',
            'Parent ID',
            'Nama Parent',
            'Map ID',
            'Nama Map',
            'Display Order',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param Location $location
     * @return array
     */
    public function map($location): array
    {
        return [
            $location->id,
            $location->name,
            $location->location_id_string,
            $location->type,
            $location->parent_id,
            $location->parent ? $location->parent->name : '-',
            $location->map_id,
            $location->map ? $location->map->name : '-',
            $location->display_order,
            $location->created_at ? $location->created_at->format('Y-m-d H:i:s') : '-',
            $location->updated_at ? $location->updated_at->format('Y-m-d H:i:s') : '-',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DC2626']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 30,
            'C' => 20,
            'D' => 15,
            'E' => 12,
            'F' => 25,
            'G' => 10,
            'H' => 25,
            'I' => 15,
            'J' => 20,
            'K' => 20,
        ];
    }
}
