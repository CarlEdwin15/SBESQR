<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class TeachersExport implements FromView, WithStyles, WithEvents, WithColumnWidths
{
    public function view(): View
    {
        $teachers = User::where('role', 'teacher')->get();

        return view('exports.sf2_teachers', [
            'teachers' => $teachers,
            'school_name' => 'Sta. Barbara Elementary School',
            'school_year' => '2024-2025',
            'grade_level' => 'Grade 6',
            'section' => 'DAZA',
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Title
            'A1:H1' => ['font' => ['bold' => true, 'size' => 14]],
            // Headers
            'A5:I5' => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 25,
            'F' => 10,
            'G' => 15,
            'H' => 15,
            'I' => 30,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge title cell
                $event->sheet->mergeCells('A1:I1');

                // Apply borders to the table
                $rowCount = 5 + User::where('role', 'teacher')->count();
                $cellRange = "A5:I{$rowCount}";

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                    'alignment' => [
                        'vertical' => 'center',
                        'horizontal' => 'left',
                    ]
                ]);
            },
        ];
    }
}
