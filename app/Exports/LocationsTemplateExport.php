<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LocationsTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Return sample data for template
        return [
            [
                'Ruang Meeting A',
                'MEET-A-001',
                'room',
                '',
                1,
                1
            ],
            [
                'Gudang Material',
                'GUDANG-001',
                'warehouse',
                '',
                2,
                2
            ],
            [
                'Area Produksi 1',
                'PROD-001',
                'production',
                '',
                3,
                3
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
            'Parent ID (optional)',
            'Map ID *',
            'Display Order *',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:F1')->applyFromArray([
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
        $sheet->setCellValue('A8', '3. Tipe: Pilih salah satu: room, warehouse, production, office, corridor, stairs, elevator, parking, outdoor, other');
        $sheet->setCellValue('A9', '4. Parent ID: Kosongkan jika tidak ada parent, atau isi dengan ID parent location');
        $sheet->setCellValue('A10', '5. Map ID: ID dari map/gedung (wajib diisi)');
        $sheet->setCellValue('A11', '6. Display Order: Urutan tampilan (angka, semakin kecil semakin atas)');
        
        $sheet->getStyle('A5:A11')->getFont()->setBold(true)->setSize(10);
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
            'B' => 20,
            'C' => 15,
            'D' => 20,
            'E' => 10,
            'F' => 15,
        ];
    }
}
