<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SF2Export implements FromArray, WithHeadings, WithStyles, WithTitle, WithColumnWidths, WithEvents
{
    protected $students;
    protected $calendarDates;
    protected $attendanceData;
    protected $class;
    protected $selectedYear;
    protected $monthParam;

    public function __construct($data)
    {
        $this->students = $data['students'];
        $this->calendarDates = $data['calendarDates'];
        $this->attendanceData = $data['attendanceData'];
        $this->class = $data['class'];
        $this->selectedYear = $data['selectedYear'];
        $this->monthParam = $data['monthParam'];
    }

    public function title(): string
    {
        return 'SF2LS';
    }

    public function array(): array
    {
        $rows = [];
        $no = 1;
        $maleRows = [];
        $femaleRows = [];

        $displayDates = $this->getDisplayWeekDates();
        $dateCount = count($displayDates); // should be 25

        $maleTotals = array_fill(0, $dateCount, 0);
        $femaleTotals = array_fill(0, $dateCount, 0);
        $combinedTotals = array_fill(0, $dateCount, 0);

        $maleNo = 1;
        $femaleNo = 1;

        foreach ($this->students as $student) {
            $fullName = strtoupper(
                $student->student_lName . ', ' .
                    $student->student_fName . ' ' .
                    $student->student_mName . ' ' .
                    $student->student_extName
            );

            $row = [
                strtolower($student->student_sex) === 'male' ? $maleNo++ : $femaleNo++,
                $fullName
            ];

            $dailySymbols = [];
            foreach ($displayDates as $i => $carbonDate) {
                $dateKey = $carbonDate->format('Y-m-d');
                $inMonth = $carbonDate->format('Y-m') === $this->monthParam;

                if ($inMonth) {
                    $symbols = $this->attendanceData[$student->id]['by_date'][$dateKey] ?? [];
                    $symbolStr = collect($symbols)->pluck('status')->implode(' ') ?: '-';
                } else {
                    // dates outside selected month — keep blank (or put '-' if you prefer)
                    $symbolStr = '';
                }

                $dailySymbols[] = $symbolStr;

                // only count totals for dates that belong to the selected month
                if ($inMonth && str_contains($symbolStr, '✓')) {
                    if (strtolower($student->student_sex) === 'male') {
                        $maleTotals[$i]++;
                    } else {
                        $femaleTotals[$i]++;
                    }
                    $combinedTotals[$i]++;
                }
            }

            $row = array_merge($row, $dailySymbols);
            $row[] = $this->attendanceData[$student->id]['absent'] ?? 0;
            $row[] = $this->attendanceData[$student->id]['present'] ?? 0;
            $row[] = ''; // Remarks

            if (strtolower($student->student_sex) === 'male') {
                $maleRows[] = $row;
            } else {
                $femaleRows[] = $row;
            }
        }

        // Merge male rows + male total
        $rows = array_merge($rows, $maleRows);

        $absentIndex = 2 + $dateCount;   // 0:No,1:Name, 2..(2+dateCount-1):dates, then absent
        $presentIndex = $absentIndex + 1;

        $rows[] = array_merge(
            [count($maleRows), 'MALE | TOTAL PER DAY'],
            $maleTotals,
            [
                array_sum(array_column($maleRows, $absentIndex)),
                array_sum(array_column($maleRows, $presentIndex)),
                ''
            ]
        );

        // Merge female rows + female total
        $rows = array_merge($rows, $femaleRows);

        $rows[] = array_merge(
            [count($femaleRows), 'FEMALE | TOTAL PER DAY'],
            $femaleTotals,
            [
                array_sum(array_column($femaleRows, $absentIndex)),
                array_sum(array_column($femaleRows, $presentIndex)),
                ''
            ]
        );

        // Combined total
        $rows[] = array_merge(
            [count($maleRows) + count($femaleRows), 'COMBINED | TOTAL PER DAY'],
            $combinedTotals,
            [
                array_sum(array_column($rows, $absentIndex)),
                array_sum(array_column($rows, $presentIndex)),
                ''
            ]
        );

        return $rows;
    }

    public function headings(): array
    {
        $displayDates = $this->getDisplayWeekDates(); // 25 Carbon dates
        $dateCount = count($displayDates);

        $dates = [];
        $days = [];

        foreach ($displayDates as $d) {
            $dates[] = ($d->format('Y-m') === $this->monthParam) ? $d->format('j') : '';
            $dow = $d->format('D');
            $days[] = match ($dow) {
                'Mon' => 'M',
                'Tue' => 'T',
                'Wed' => 'W',
                'Thu' => 'TH',
                'Fri' => 'F',
                default => ''
            };
        }

        return [
            // Row 1
            ['School Form 2 (SF2) Daily Attendance Report of Learners'],

            // Row 2
            ['(This replaces Form 1, Form 2 & STS Form 4 - Absenteeism and Dropout Profile)'],

            // Row 3
            [
                'School ID',
                '',
                '112828',
                '',
                '',
                'School Year',
                '',
                '',
                $this->selectedYear,
                '',
                '',
                '',
                '',
                'Report for the Month of',
                '',
                '',
                '',
                '',
                \Carbon\Carbon::createFromFormat('Y-m', $this->monthParam)->format('F'),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],

            // Row 4
            [
                'Name of School',
                '',
                'Sta. Barbara ES',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'Grade Level',
                '',
                '',
                '',
                '',
                $this->class->formatted_grade_level,
                '',
                '',
                '',
                '',
                '',
                'Section',
                '',
                '',
                $this->class->section
            ],

            // Row 5 - Top date header row
            array_merge(
                ['No.', 'NAME (Last Name, First Name, Middle Name)'],
                array_fill(0, $dateCount, ''), // merged date block
                ['Total for the Month', '', 'REMARKS (If NLS, state reason, please refer to legend number 2. If TRANSFERRED IN/OUT, write the name of School.)'] // Empty for AB5, AC5, Remarks
            ),

            // Row 6 - Dates
            array_merge(
                ['', ''],
                $dates,
                ['', '', ''] // empty for AB6, AC6, Remarks
            ),

            // Row 7 - Weekdays + "ABSENT" in AB7, "PRESENT" in AC7, remarks label in AD7
            array_merge(
                ['', ''],
                $days,
                ['ABSENT', 'PRESENT']
            )

        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Always use fixed 25 weekday columns (M–F × 5 weeks)
        $displayDates = $this->getDisplayWeekDates();
        $dateCount = count($displayDates);

        // Column indexes
        $fixedCols = 2; // No. + Name
        $firstDateIndex = $fixedCols + 1;
        $lastDateIndex = $fixedCols + $dateCount;
        $absentIndex  = $lastDateIndex + 1;
        $presentIndex = $absentIndex + 1;
        $remarksIndex = $presentIndex + 1;

        $lastColIndex = $remarksIndex;

        // Convert indexes to letters
        $lastColumn  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColIndex);
        $firstDateCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($firstDateIndex);
        $lastDateCol  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastDateIndex);
        $absentCol    = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($absentIndex);
        $presentCol   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($presentIndex);
        $remarksCol   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($remarksIndex);

        // --- Merge new heading rows ---
        $sheet->mergeCells("A1:{$lastColumn}1"); // Row 1 - Title
        $sheet->mergeCells("A2:{$lastColumn}2"); // Row 2 - This replaces...

        // Row 3
        $sheet->mergeCells("A3:B3"); // School ID
        $sheet->mergeCells("C3:E3"); // 112828
        $sheet->mergeCells("F3:H3"); // School Year
        $sheet->mergeCells("I3:M3"); // eg.,2025-2026
        $sheet->mergeCells("N3:R3"); // Report for the Month of
        $sheet->mergeCells("S3:X3"); // eg., August
        $sheet->mergeCells("Y3:{$lastColumn}3"); // Blank row

        // Row 4
        $sheet->mergeCells("A4:B4"); // Name of School
        $sheet->mergeCells("C4:M4"); // SBES
        $sheet->mergeCells("N4:R4"); // Grade Level
        $sheet->mergeCells("S4:X4"); // eg., Grade 6
        $sheet->mergeCells("Y4:AA4"); // Section
        $sheet->mergeCells("AB4:{$lastColumn}4"); // Section

        // Merge vertically for fixed columns (Row 5–7)
        $sheet->mergeCells("A5:A7"); // No.
        $sheet->mergeCells("B5:B7"); // Name

        // Merge summary columns vertically
        $sheet->mergeCells("{$absentCol}5:{$presentCol}6");
        $sheet->mergeCells("{$remarksCol}5:{$remarksCol}7");

        // Merge date header row 5
        $sheet->mergeCells("{$firstDateCol}5:{$lastDateCol}5");
        $sheet->setCellValue("C5", "(1st row for date)");

        // Style headers
        $sheet->getStyle("A1:{$lastColumn}7")->applyFromArray([
            'font' => [
                'bold' => true,
                'name' => 'Aptos Display',
                'size' => 10
            ],
            'alignment' => [
                'wrapText'   => true,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ]
        ]);

        // Make Row 1 Title align TOP vertically, keep horizontal center & wrap text
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
            'font' => [
                'size' => 15,
                'bold' => true,
                'name' => 'Aptos Display',
            ]
        ]);

        // Make Row 2 italic, not bold, align TOP vertically & center horizontally
        $sheet->getStyle("A2:{$lastColumn}2")->applyFromArray([
            'font' => [
                'italic' => true,
                'bold'   => false,
                'name'   => 'Aptos Display',
                'size'   => 10
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ]
        ]);

        // Right-align specific heading labels
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // School ID
        $sheet->getStyle('F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // School Year
        $sheet->getStyle('N3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Report for the Month

        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Name of School
        $sheet->getStyle('N4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Grade Level
        $sheet->getStyle('Y4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Section

        // Data row range
        $startRow = 8;
        $totalExtraRows = 3; // Male, Female, Total
        $endRow = $startRow + count($this->students) + $totalExtraRows - 1;

        // Column alignments
        $sheet->getStyle("A{$startRow}:A{$endRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("B{$startRow}:B{$endRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // Attendance date columns → center both horizontally & vertically
        $sheet->getStyle("{$firstDateCol}{$startRow}:{$lastDateCol}{$endRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // ABSENT & PRESENT → center both horizontally & vertically
        $sheet->getStyle("{$absentCol}{$startRow}:{$absentCol}{$endRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("{$presentCol}{$startRow}:{$presentCol}{$endRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // Borders
        $sheet->getStyle("A5:{$lastColumn}{$endRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ]
        ]);

        // Row heights
        foreach (range($startRow, $endRow) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(20);
        }
        foreach (range(1, 7) as $headerRow) {
            $sheet->getRowDimension($headerRow)->setRowHeight(25);
        }

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(4); // No.
        $sheet->getColumnDimension('B')->setWidth(25); // Name

        // Wrap text for names
        $sheet->getStyle("B{$startRow}:B{$endRow}")
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("B1:B7")->getAlignment()->setWrapText(true);

        // Date columns
        for ($ci = $firstDateIndex; $ci <= $lastDateIndex; $ci++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci);
            $sheet->getColumnDimension($colLetter)->setWidth(4);
        }

        $sheet->getColumnDimension($absentCol)->setWidth(8);
        $sheet->getColumnDimension($presentCol)->setWidth(8);
        $sheet->getColumnDimension($remarksCol)->setWidth(25);

        return [];
    }


    public function columnWidths(): array
    {
        return [
            'A' => 5, // Merged A+B
            'C' => 30, // Name column
            // Add as needed for dynamic calendar date columns
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Global font (default for all cells)
                $sheet->getStyle($sheet->calculateWorksheetDimension())
                    ->getFont()->setName('Aptos Display')->setSize(9);

                // Page setup
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
                $sheet->getPageSetup()->setHorizontalCentered(true);
                $sheet->getPageSetup()->setVerticalCentered(false);

                $sheet->getPageMargins()->setTop(0.2);
                $sheet->getPageMargins()->setRight(0.2);
                $sheet->getPageMargins()->setLeft(0.2);
                $sheet->getPageMargins()->setBottom(0.2);

                // Align "No." column
                $sheet->getStyle("A6:A" . (count($this->students) + 8))
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Row index setup
                $startRow = 8;
                $totalExtraRows = 3; // Male, Female, Total
                $endRow = $startRow + count($this->students) + $totalExtraRows - 1;

                // Column indexes
                $displayDates = $this->getDisplayWeekDates();
                $dateCount = count($displayDates);
                $fixedCols = 2;
                $firstDateIndex = $fixedCols + 1;
                $lastDateIndex = $fixedCols + $dateCount;
                $absentIndex = $lastDateIndex + 1;
                $presentIndex = $absentIndex + 1;

                // Column letters
                $firstDateCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($firstDateIndex);
                $lastDateCol  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastDateIndex);
                $absentCol    = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($absentIndex);
                $presentCol   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($presentIndex);

                // Center ABSENT / PRESENT
                $sheet->getStyle("{$absentCol}{$startRow}:{$absentCol}{$endRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("{$presentCol}{$startRow}:{$presentCol}{$endRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Bold & center "TOTAL PER DAY" rows
                for ($r = $startRow; $r <= $endRow; $r++) {
                    $val = trim((string) $sheet->getCell("B{$r}")->getValue());
                    if ($val === '') {
                        continue;
                    }

                    if (
                        (stripos($val, 'MALE') !== false ||
                            stripos($val, 'FEMALE') !== false ||
                            stripos($val, 'COMBINED') !== false)
                        && stripos($val, 'TOTAL PER DAY') !== false
                    ) {
                        $sheet->getStyle("A{$r}")->getFont()->setBold(true);
                        $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                        $sheet->getStyle("B{$r}")->getFont()->setBold(true);
                        $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                        $sheet->getStyle("{$firstDateCol}{$r}:{$lastDateCol}{$r}")->getFont()->setBold(true);
                        $sheet->getStyle("{$firstDateCol}{$r}:{$lastDateCol}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                        $sheet->getStyle("{$absentCol}{$r}")->getFont()->setBold(true);
                        $sheet->getStyle("{$absentCol}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                        $sheet->getStyle("{$presentCol}{$r}")->getFont()->setBold(true);
                        $sheet->getStyle("{$presentCol}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }

                // Name column (B) → font size 8
                $sheet->getStyle("B{$startRow}:B{$endRow}")->getFont()->setSize(8);

                // Wrap & center vertically for names
                $sheet->getStyle("B{$startRow}:B{$endRow}")
                    ->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Title (A1) → font size 15
                $sheet->getStyle("A1")->getFont()->setSize(15);

                // Add borders to heading cells
                $borders = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];

                $sheet->getStyle('C3:E3')->applyFromArray($borders); // 112828
                $sheet->getStyle('I3:M3')->applyFromArray($borders); // School Year
                $sheet->getStyle('S3:X3')->applyFromArray($borders); // Month
                $sheet->getStyle('C4:M4')->applyFromArray($borders); // School Name
                $sheet->getStyle('S4:X4')->applyFromArray($borders); // Grade Level
                $sheet->getStyle('AB4:AD4')->applyFromArray($borders); // Section
            }
        ];
    }

    // Helper method to display week dates
    private function getDisplayWeekDates(): array
    {
        $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $this->monthParam)->startOfMonth();

        // Find the first weekday (Mon–Fri) inside the month
        $firstWeekday = $monthStart->copy();
        while (!$firstWeekday->isWeekday()) {
            $firstWeekday->addDay();
        }

        // If the first weekday inside the month is Monday, start from that Monday.
        if ($firstWeekday->dayOfWeek === \Carbon\Carbon::MONDAY) {
            $startMonday = $firstWeekday->copy();
        } else {
            // Otherwise keep the original behaviour (start from the Monday of the week that contains the 1st).
            $startMonday = $monthStart->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        }

        $displayDates = [];
        for ($week = 0; $week < 5; $week++) {
            for ($dow = 0; $dow < 5; $dow++) { // Mon-Fri
                $displayDates[] = $startMonday->copy()->addWeeks($week)->addDays($dow);
            }
        }

        return $displayDates; // array of Carbon instances, length = 25
    }
}
