<?php

namespace App\Exports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class LocationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
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
            'Tipe (Area)',
            'Nama Map (Gedung)',
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
            $location->map ? $location->map->name : '-',
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
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F3F4F6'], // Light grey (Tailwind grey-100)
                ],
                'font' => ['bold' => true],
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID
            'B' => 35,  // Nama Lokasi
            'C' => 25,  // Location ID String
            'D' => 15,  // Tipe
            'E' => 25,  // Nama Map
            'G' => 60,  // Instructions Column
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Set Instructions Header
                $sheet->setCellValue('G1', '📖 PANDUAN PENGISIAN MODUL LOKASI');
                $sheet->getStyle('G1')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('G1')->getFont()->getColor()->setRGB('DC2626'); // Red
    
                // Add Instruction Content
                $sheet->setCellValue('G2', '1. UPDATE DATA: Jangan ubah angka di kolom ID (A) agar data lama ter-update otomatis.');
                $sheet->setCellValue('G3', '2. TAMBAH BARU: Sisipkan baris baru dan KOSONGKAN kolom ID (A). ID akan dibuat otomatis oleh sistem.');
                $sheet->setCellValue('G4', '3. GANTI KODE: Anda boleh ganti Location ID String (C) selama kolom ID (A) tetap ada angkanya.');
                $sheet->setCellValue('G5', '4. PINDAH GEDUNG: Cukup ganti Nama Map (E). Histori laporan bahaya akan otomatis ikut pindah.');
                $sheet->setCellValue('G6', '5. HAPUS DATA: Menghapus baris di Excel TIDAK akan menghapus data di sistem (Gunakan tombol Hapus di Web).');

                // Style instructions
                $sheet->getStyle('G2:G6')->getFont()->setItalic(true)->setSize(10);
                $sheet->getStyle('G2:G6')->getFont()->getColor()->setRGB('4B5563'); // Gray-600
    
                // Add a badge-like background to instructions area
                $sheet->getStyle('G1:G6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle('G1:G6')->getFill()->getStartColor()->setRGB('F9FAFB'); // Very light grey
    
                // Add borders to instruction area
                $sheet->getStyle('G1:G6')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('G1:G6')->getBorders()->getAllBorders()->getColor()->setRGB('E5E7EB');
            },
        ];
    }
}
