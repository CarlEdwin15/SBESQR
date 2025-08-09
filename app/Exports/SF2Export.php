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
        return 'SF2';
    }

    // replace your array() method with this
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

        foreach ($this->students as $student) {
            $fullName = strtoupper(
                $student->student_lName . ', ' .
                    $student->student_fName . ' ' .
                    ($student->student_mName ? strtoupper(substr($student->student_mName, 0, 1)) . '.' : '')
            );

            $row = [$no++, $fullName];

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

        // Build final rows + totals. Compute indices (0-based array)
        $rows = array_merge($rows, $maleRows);

        $absentIndex = 2 + $dateCount;   // 0:No,1:Name, 2..(2+dateCount-1):dates, then absent
        $presentIndex = $absentIndex + 1;

        $rows[] = array_merge(
            [count($maleRows), '<== MALE | TOTAL PER DAY ==>'],
            $maleTotals,
            [
                array_sum(array_column($maleRows, $absentIndex)),
                array_sum(array_column($maleRows, $presentIndex)),
                ''
            ]
        );

        $rows = array_merge($rows, $femaleRows);

        $rows[] = array_merge(
            [count($femaleRows), '<== FEMALE | TOTAL PER DAY ==>'],
            $femaleTotals,
            [
                array_sum(array_column($femaleRows, $absentIndex)),
                array_sum(array_column($femaleRows, $presentIndex)),
                ''
            ]
        );

        $rows[] = array_merge(
            [count($maleRows) + count($femaleRows), '<== Combined TOTAL PER DAY ==>'],
            $combinedTotals,
            [
                array_sum(array_column($rows, $absentIndex)),
                array_sum(array_column($rows, $presentIndex)),
                ''
            ]
        );

        return $rows;
    }


    // replace your headings() method with this
    public function headings(): array
    {
        $displayDates = $this->getDisplayWeekDates(); // 25 Carbon dates
        $dateCount = count($displayDates);

        $weekdaysShort = ['M', 'T', 'W', 'Th', 'F'];
        $days = [];
        $dates = [];

        foreach ($displayDates as $d) {
            // Show numeric day only if it belongs to the selected month
            $dates[] = ($d->format('Y-m') === $this->monthParam) ? $d->format('j') : '';
            // Use weekday short label
            $dow = $d->format('D');
            $days[] = match ($dow) {
                'Mon' => 'M',
                'Tue' => 'T',
                'Wed' => 'W',
                'Thu' => 'Th',
                'Fri' => 'F',
                default => ''
            };
        }

        return [
            ['School Form 2 (SF2) Daily Attendance Report of Learners'],
            [
                'School ID: 112828',
                '',
                '',
                'School Year: ' . $this->selectedYear,
                '',
                '',
                '',
                '',
                'Grade Level: ' . $this->class->formatted_grade_level,
                '',
                '',
                '',
                'Section: ' . $this->class->section
            ],
            ['Name of School: Sta. Barbara ES'],
            ['Report for the Month of: ' . \Carbon\Carbon::createFromFormat('Y-m', $this->monthParam)->format('F')],

            // Row 5
            array_merge(
                ['No.', 'NAME (Last Name, First Name, Middle Name)', '(1st row for date)'],
                array_fill(0, $dateCount - 1, ''),
                ['Total for the Month', '', 'REMARKS (If NLS, state reason, please refer to legend number 2. If TRANSFERRED IN/OUT, write the name of School.)']
            ),

            // Row 6
            array_merge(
                ['', ''],
                $dates,
                ['', '', '']
            ),

            // Row 7
            array_merge(
                ['', ''],
                $days,
                ['ABSENT', 'PRESENT', '']
            )
        ];
    }


    public function columnWidths(): array
    {
        return [
            'A' => 5, // Merged A+B
            'C' => 30, // Name column
            // Add as needed for dynamic calendar date columns
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Always use fixed 25 weekday columns (M–F × 5 weeks)
        $displayDates = $this->getDisplayWeekDates(); // should return array of 25 items
        $dateCount = count($displayDates); // 25

        // Column indexes
        $fixedCols = 2; // No. + Name
        $firstDateIndex = $fixedCols + 1; // C
        $lastDateIndex = $fixedCols + $dateCount; // last weekday col
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

        // Merge header rows
        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->mergeCells("A2:C2");
        $sheet->mergeCells("D2:H2");
        $sheet->mergeCells("I2:L2");
        $sheet->mergeCells("M2:{$lastColumn}2");
        $sheet->mergeCells("A3:D3");
        $sheet->mergeCells("F3:I3");
        $sheet->mergeCells("J3:L3");
        $sheet->mergeCells("A4:{$lastColumn}4");

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

        $sheet->getStyle("A1")->getFont()->setSize(13);

        // Align school ID & year cells
        $sheet->getStyle("A2")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("D2")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // Data row range
        $startRow = 8;
        $totalExtraRows = 3; // Male, Female, Total
        $endRow = $startRow + count($this->students) + $totalExtraRows - 1;

        // Column alignments
        $sheet->getStyle("A{$startRow}:A{$endRow}")
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B{$startRow}:B{$endRow}")
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("{$firstDateCol}{$startRow}:{$lastDateCol}{$endRow}")
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

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
        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(25);
        for ($ci = $firstDateIndex; $ci <= $lastDateIndex; $ci++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci);
            $sheet->getColumnDimension($colLetter)->setWidth(4);
        }
        $sheet->getColumnDimension($absentCol)->setWidth(7);
        $sheet->getColumnDimension($presentCol)->setWidth(7);
        $sheet->getColumnDimension($remarksCol)->setWidth(25);

        return [];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle($sheet->calculateWorksheetDimension())->getFont()->setName('Aptos Display')->setSize(9);;
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
                $sheet->getPageSetup()->setHorizontalCentered(true);
                $sheet->getPageSetup()->setVerticalCentered(false); // optional

                $sheet->getPageMargins()->setTop(0.2);
                $sheet->getPageMargins()->setRight(0.2);
                $sheet->getPageMargins()->setLeft(0.2);
                $sheet->getPageMargins()->setBottom(0.2);

                // $sheet->getSheetView()->setZoomScale(80); // try 70–90 if needed


                // Also ensure "No." numbers align the same as column AB here, just in case
                $sheet->getStyle("A6:A" . (count($this->students) + 8))
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }

    // Helper method to display week dates
    private function getDisplayWeekDates(): array
    {
        // Start from the Monday of the week that contains the 1st of the selected month
        $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $this->monthParam)->startOfMonth();
        $startMonday = $monthStart->copy()->startOfWeek(\Carbon\Carbon::MONDAY);

        $displayDates = [];
        for ($week = 0; $week < 5; $week++) {
            for ($dow = 0; $dow < 5; $dow++) { // Mon-Fri
                $displayDates[] = $startMonday->copy()->addWeeks($week)->addDays($dow);
            }
        }

        return $displayDates; // array of Carbon instances, length = 25
    }
}
