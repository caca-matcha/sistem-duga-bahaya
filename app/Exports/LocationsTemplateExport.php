<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LocationsTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Return sample data for template
        return [
            [
                'Ruang Meeting A',
                'MEET-A-001',
                'area',
                'Gedung A'
            ],
            [
                'Gudang Material',
                'GUDANG-001',
                'area',
                'Gedung A'
            ],
            [
                'Area Produksi 1',
                'PROD-001',
                'area',
                'Gedung A'
            ],
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Lokasi *',
            'Location ID String *',
            'Tipe *',
            'Nama Map *',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC2626']
            ],
        ]);

        // Add instructions
        $sheet->setCellValue('A5', 'INSTRUKSI PENGISIAN:');
        $sheet->setCellValue('A6', '1. Nama Lokasi: Nama lokasi yang jelas dan deskriptif');
        $sheet->setCellValue('A7', '2. Location ID String: Kode unik lokasi (contoh: MEET-A-001)');
        $sheet->setCellValue('A8', '3. Tipe: Isi dengan "area"');
        $sheet->setCellValue('A9', '4. Nama Map: Nama dari map/gedung (wajib diisi, sesuai menu Master Map)');

        $sheet->getStyle('A5:A9')->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle('A5')->getFont()->setSize(12)->getColor()->setRGB('DC2626');

        return [];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 25,
            'C' => 15,
            'D' => 10,
        ];
    }
}
