<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StudentTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                '112828123456',
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

    public function styles(Worksheet $sheet)
    {
        // Insert title/instructions row
        $sheet->insertNewRowBefore(1, 2);
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A1', 'STUDENT IMPORT TEMPLATE — Do NOT change column headers below. Fill in your data starting from Row 3.');

        // Title styling
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FF1B5E20'],
                'name' => 'Aptos Display', // Sans-serif
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Header row styling
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
                'startColor' => ['argb' => 'FFE8F5E9'], // Light green
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFBBBBBB'],
                ],
            ],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // Apply consistent alignment, wrapping, and font to all data cells
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
            'font' => [
                'name' => 'Aptos Display',
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);

        // Borders for first few rows (title + headers + sample row)
        $sheet->getStyle('A1:N3')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFAAAAAA'],
                ],
            ],
        ]);

        // Date column formatting
        $sheet->getStyle('F3:F100')->getNumberFormat()->setFormatCode('yyyy-mm-dd');

        return [];
    }
}
