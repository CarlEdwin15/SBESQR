<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StudentTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithCustomCsvSettings
{
    public function array(): array
    {
        return [
            [
                "112828123456",
                'Juan',
                'Santos',
                'Dela Cruz',
                'Jr.',
                '2010-05-21',
                'male',
                'Quezon City',
                '123',
                'Rizal St.',
                'Barangay 1',
                'Quezon City',
                'Metro Manila',
                '1100',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'lrn',
            'first_name',
            'middle_name',
            'last_name',
            'extension_name',
            'dob',
            'sex',
            'place_of_birth',
            'house_no',
            'street_name',
            'barangay',
            'municipality_city',
            'province',
            'zip_code',
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => "\r\n",
            'use_bom' => true,
            'include_separator_line' => false,
            'excel_compatibility' => true,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Insert only ONE new row before the first data row
        $sheet->insertNewRowBefore(1, 1);

        // Merge A1:N1 for the title
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A1', 'STUDENT IMPORT TEMPLATE — Do NOT change column headers below. Fill in your data starting from Row 3.');

        // Style for Title Row (Row 1)
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FF1B5E20'],
                'name' => 'Aptos Display',
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Style for Header Row (Row 2)
        $sheet->getStyle('2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'name' => 'Aptos Display',
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE8F5E9'], // light green bg
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFBBBBBB'],
                ],
            ],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // Extend style for the rest of the sheet
        $highestColumn = $sheet->getHighestColumn();
        $maxRows = 200; // adjust if needed
        $styleRange = "A1:{$highestColumn}{$maxRows}";

        $sheet->getStyle($styleRange)->applyFromArray([
            'font' => ['name' => 'Aptos Display', 'size' => 10],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);

        // Format certain columns
        $sheet->getStyle("A3:A{$maxRows}")->getNumberFormat()->setFormatCode('@'); // LRN as text
        $sheet->getStyle("F3:F{$maxRows}")->getNumberFormat()->setFormatCode('yyyy-mm-dd'); // Date format

        // Auto-fit columns
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set default row height for clean layout
        for ($i = 3; $i <= $maxRows; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }

        return [];
    }
}
