<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
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

    public function columnWidths(): array
    {
        return [
            // 'A' => 5, // Merged A+B
            // 'C' => 30, // Name column
        ];
    }

    public function array(): array
    {
        $rows = [];
        $maleRows = [];
        $femaleRows = [];

        $displayDates = $this->getDisplayWeekDates();
        $dateCount = count($displayDates);

        // Separate per-day totals for PRESENT and ABSENT
        $malePresentTotals = array_fill(0, $dateCount, 0);
        $femalePresentTotals = array_fill(0, $dateCount, 0);
        $combinedPresentTotals = array_fill(0, $dateCount, 0);

        $maleAbsentTotals = array_fill(0, $dateCount, 0);
        $femaleAbsentTotals = array_fill(0, $dateCount, 0);
        $combinedAbsentTotals = array_fill(0, $dateCount, 0);

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
                    $symbolStr = '';
                }

                $dailySymbols[] = $symbolStr;

                // Count only if date is in selected month
                if ($inMonth) {
                    $isMale = strtolower($student->student_sex) === 'male';
                    if (str_contains($symbolStr, '✓')) {
                        if ($isMale) $malePresentTotals[$i]++;
                        else $femalePresentTotals[$i]++;
                        $combinedPresentTotals[$i]++;
                    } elseif (str_contains(strtolower($symbolStr), 'a')) {
                        if ($isMale) $maleAbsentTotals[$i]++;
                        else $femaleAbsentTotals[$i]++;
                        $combinedAbsentTotals[$i]++;
                    }
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

        // Index positions for month totals
        $absentIndex = 2 + $dateCount;
        $presentIndex = $absentIndex + 1;

        // Merge male rows + male total
        $rows = array_merge($rows, $maleRows);
        $rows[] = array_merge(
            [count($maleRows), 'MALE | TOTAL PER DAY'],
            $malePresentTotals, // Only showing PRESENT per day
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
            $femalePresentTotals, // Only showing PRESENT per day
            [
                array_sum(array_column($femaleRows, $absentIndex)),
                array_sum(array_column($femaleRows, $presentIndex)),
                ''
            ]
        );

        // Combined total (sum only from student rows)
        $combinedRows = array_merge($maleRows, $femaleRows);
        $rows[] = array_merge(
            [count($combinedRows), 'COMBINED | TOTAL PER DAY'],
            $combinedPresentTotals, // Only showing PRESENT per day
            [
                array_sum(array_column($combinedRows, $absentIndex)),
                array_sum(array_column($combinedRows, $presentIndex)),
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
                ['No.', "NAME\n(Last Name, First Name, Middle Name)"],
                array_fill(0, $dateCount, ''), // merged date block
                ['Total for the Month', '', 'REMARKS (If NLS, state reason, please refer to legend number 2. If TRANSFERRED IN/OUT, write the name of School.)', '', '', '', '']
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
        $displayDates = $this->getDisplayWeekDates();
        $dateCount = count($displayDates);

        $fixedCols = 2; // No. + Name
        $firstDateIndex = $fixedCols + 1;
        $lastDateIndex = $fixedCols + $dateCount;
        $absentIndex  = $lastDateIndex + 1;
        $presentIndex = $absentIndex + 1;
        $remarksIndex = $presentIndex + 5;

        $lastColIndex = $remarksIndex;

        $lastColumn  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColIndex);
        $firstDateCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($firstDateIndex);
        $lastDateCol  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastDateIndex);
        $absentCol    = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($absentIndex);
        $presentCol   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($presentIndex);
        $remarksCol   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($remarksIndex);

        // --- Merge heading rows ---
        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->mergeCells("A2:{$lastColumn}2");

        $sheet->mergeCells("A3:B3");
        $sheet->mergeCells("C3:E3");
        $sheet->mergeCells("F3:H3");
        $sheet->mergeCells("I3:M3");
        $sheet->mergeCells("N3:R3");
        $sheet->mergeCells("S3:X3");
        $sheet->mergeCells("Y3:{$lastColumn}3");

        $sheet->mergeCells("A4:B4");
        $sheet->mergeCells("C4:M4");
        $sheet->mergeCells("N4:R4");
        $sheet->mergeCells("S4:X4");
        $sheet->mergeCells("Y4:AA4");
        $sheet->mergeCells("AB4:{$lastColumn}4");

        $sheet->mergeCells("A5:A7");
        $sheet->mergeCells("B5:B7");

        $sheet->mergeCells("{$absentCol}5:{$presentCol}6");
        // Merge Remarks from AD5 to AH7
        $sheet->mergeCells('AD5:AH7');

        $highestRow = $sheet->getHighestRow();

        // Find start and end rows for the data
        // Assuming your data starts after the header (row 8)
        $startRow = 8;

        // Loop from startRow to end of sheet, merging Remarks per row until 'Combined Total Per Day'
        $remarksStart = 'AD';
        $remarksEnd   = 'AH';

        for ($row = $startRow; $row <= $highestRow; $row++) {
            $val = trim((string) $sheet->getCell("B{$row}")->getValue());

            if (stripos($val, 'Combined Total Per Day') !== false) {
                break; // stop merging further
            }

            $sheet->mergeCells("{$remarksStart}{$row}:{$remarksEnd}{$row}");
        }

        $sheet->mergeCells("{$firstDateCol}5:{$lastDateCol}5");
        $sheet->setCellValue("C5", "(1st row for date)");

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

        $sheet->getStyle('A3')->getFont()->setBold(false);
        $sheet->getStyle('F3')->getFont()->setBold(false);
        $sheet->getStyle('N3')->getFont()->setBold(false);

        $sheet->getStyle('A4')->getFont()->setBold(false);
        $sheet->getStyle('N4')->getFont()->setBold(false);
        $sheet->getStyle('Y4')->getFont()->setBold(false);

        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
            'font' => [
                'size' => 16,
                'bold' => true,
                'name' => 'Aptos Display',
            ]
        ]);

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

        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('N3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('N4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('Y4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $startRow = 8;
        $totalExtraRows = 3;
        $endRow = $startRow + count($this->students) + $totalExtraRows - 1;

        $sheet->getStyle("A{$startRow}:A{$endRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("B{$startRow}:B{$endRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("{$firstDateCol}{$startRow}:{$lastDateCol}{$endRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("{$absentCol}{$startRow}:{$absentCol}{$endRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("{$presentCol}{$startRow}:{$presentCol}{$endRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("A5:{$lastColumn}{$endRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ]
        ]);

        // Row 8 Starts
        foreach (range($startRow, $endRow) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(23);
        }
        foreach (range(1, 7) as $headerRow) {
            $sheet->getRowDimension($headerRow)->setRowHeight(25);
        }
        $sheet->getRowDimension(5)->setRowHeight(10);

        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(25);

        $sheet->getStyle("B{$startRow}:B{$endRow}")
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("B1:B7")->getAlignment()->setWrapText(true);

        for ($ci = $firstDateIndex; $ci <= $lastDateIndex; $ci++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci);
            $sheet->getColumnDimension($colLetter)->setWidth(4);
        }

        $sheet->getColumnDimension($absentCol)->setWidth(8);
        $sheet->getColumnDimension($presentCol)->setWidth(8);
        $sheet->getColumnDimension($remarksCol)->setWidth(5);
        // Set Remarks columns (AD to AH) width
        // Set widths for Remarks columns (AD to AH)
        for (
            $ci = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($remarksCol);
            $ci <= \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString('AH');
            $ci++
        ) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci);
            $sheet->getColumnDimension($colLetter)->setWidth(8);
        }
        $sheet->getColumnDimension('AD')->setWidth(8);
        $sheet->getColumnDimension('AE')->setWidth(8);
        $sheet->getColumnDimension('AF')->setWidth(4);
        $sheet->getColumnDimension('AG')->setWidth(4);

        // ===== GUIDELINES & SUMMARY BLOCK =====
        $guidelinesRow = $sheet->getHighestRow() + 2;
        $leftEndRow = $guidelinesRow + 24;

        // LEFT BLOCK GUIDELINES
        $sheet->mergeCells("A{$guidelinesRow}:Q{$guidelinesRow}");
        $sheet->setCellValue("A{$guidelinesRow}", "GUIDELINES");
        $sheet->getStyle("A{$guidelinesRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$guidelinesRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // 1–3 guidelines
        $cell1Text =
            "1. The attendance shall be accomplished daily. Refer to the codes for checking learners’ attendance.\n" .
            "2. Dates shall be written in the columns after Learner’s Name.\n" .
            "3. Compute the following:";
        $sheet->mergeCells("A" . ($guidelinesRow + 1) . ":Q" . ($guidelinesRow + 3));
        $sheet->setCellValue("A" . ($guidelinesRow + 1), $cell1Text);
        $sheet->getStyle("A" . ($guidelinesRow + 1) . ":Q" . ($guidelinesRow + 3))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // a. Percentage of Enrolment =
        $sheet->mergeCells("B" . ($guidelinesRow + 4) . ":D" . ($guidelinesRow + 5));
        $sheet->setCellValue("B" . ($guidelinesRow + 4), "a. Percentage of Enrolment =");
        $sheet->getStyle("B" . ($guidelinesRow + 4) . ":D" . ($guidelinesRow + 5))
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Registered Learners as of end of the month
        $sheet->mergeCells("E" . ($guidelinesRow + 4) . ":N" . ($guidelinesRow + 4));
        $sheet->setCellValue("E" . ($guidelinesRow + 4), "Registered Learners as of end of the month");
        $sheet->getStyle("E" . ($guidelinesRow + 4) . ":N" . ($guidelinesRow + 4))
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Enrolment as of 1st Friday...
        $sheet->mergeCells("E" . ($guidelinesRow + 5) . ":N" . ($guidelinesRow + 5));
        $sheet->setCellValue("E" . ($guidelinesRow + 5), "Enrolment as of 1st Friday of the school year");
        $sheet->getStyle("E" . ($guidelinesRow + 5) . ":N" . ($guidelinesRow + 5))
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // x 100
        $sheet->mergeCells("O" . ($guidelinesRow + 4) . ":P" . ($guidelinesRow + 5));
        $sheet->setCellValue("O" . ($guidelinesRow + 4), "x 100");
        $sheet->getStyle("O" . ($guidelinesRow + 4) . ":P" . ($guidelinesRow + 5))
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // b. Average Daily Attendance =
        $sheet->mergeCells("B" . ($guidelinesRow + 6) . ":D" . ($guidelinesRow + 7));
        $sheet->setCellValue("B" . ($guidelinesRow + 6), "b. Average Daily Attendance =");
        $sheet->getStyle("B" . ($guidelinesRow + 6) . ":D" . ($guidelinesRow + 7))
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Total Daily Attendance
        $sheet->mergeCells("E" . ($guidelinesRow + 6) . ":N" . ($guidelinesRow + 6));
        $sheet->setCellValue("E" . ($guidelinesRow + 6), "Total Daily Attendance");
        $sheet->getStyle("E" . ($guidelinesRow + 6) . ":N" . ($guidelinesRow + 6))
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Number of School Days...
        $sheet->mergeCells("E" . ($guidelinesRow + 7) . ":N" . ($guidelinesRow + 7));
        $sheet->setCellValue("E" . ($guidelinesRow + 7), "Number of School Days in reporting month");
        $sheet->getStyle("E" . ($guidelinesRow + 7) . ":N" . ($guidelinesRow + 7))
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // c. Percentage of Attendance =
        $sheet->mergeCells("B" . ($guidelinesRow + 8) . ":D" . ($guidelinesRow + 9));
        $sheet->setCellValue("B" . ($guidelinesRow + 8), "c. Percentage of Attendance for the month =");
        $sheet->getStyle("B" . ($guidelinesRow + 8) . ":D" . ($guidelinesRow + 9))
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Average Daily Attendance
        $sheet->mergeCells("E" . ($guidelinesRow + 8) . ":N" . ($guidelinesRow + 8));
        $sheet->setCellValue("E" . ($guidelinesRow + 8), "Average Daily Attendance");
        $sheet->getStyle("E" . ($guidelinesRow + 8) . ":N" . ($guidelinesRow + 8))
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Registered Learners as of end of month
        $sheet->mergeCells("E" . ($guidelinesRow + 9) . ":N" . ($guidelinesRow + 9));
        $sheet->setCellValue("E" . ($guidelinesRow + 9), "Registered Learners as of end of the month");
        $sheet->getStyle("E" . ($guidelinesRow + 9) . ":N" . ($guidelinesRow + 9))
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // x 100
        $sheet->mergeCells("O" . ($guidelinesRow + 8) . ":P" . ($guidelinesRow + 9));
        $sheet->setCellValue("O" . ($guidelinesRow + 8), "x 100");
        $sheet->getStyle("O" . ($guidelinesRow + 8) . ":P" . ($guidelinesRow + 9))
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 4–6 guidelines
        // cell5Text
        $cell5Text =
            "4. Every end of the month, the class adviser will submit this form to the office of the principal for recording of summary table into School Form 4. Once signed by the principal, this form should be returned to the adviser.\n" .
            "5. The adviser will provide neccessary interventions including but not limited to home visitation to learner/s who were absent for 5 consecutive days and/or those at risk of dropping out.\n" .
            "6. Attendance performance of learners will be reflected in Form 137 and Form 138 every grading period.\n\n" .
            "                   *Beginning of School Year cut-off report is every 1st Friday of the School Year";
        $sheet->mergeCells("A" . ($guidelinesRow + 10) . ":Q" . ($guidelinesRow + 15));
        $sheet->setCellValue("A" . ($guidelinesRow + 10), $cell5Text);
        $sheet->getStyle("A" . ($guidelinesRow + 10) . ":Q" . ($guidelinesRow + 15))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->mergeCells("A" . ($guidelinesRow + 4) . ":A" . ($guidelinesRow + 9));

        // Merge N-O for each formula group
        $sheet->mergeCells("O" . ($guidelinesRow + 6) . ":P" . ($guidelinesRow + 7));

        // Merge P-Q for each formula group
        $sheet->mergeCells("Q" . ($guidelinesRow + 4) . ":Q" . ($guidelinesRow + 9));

        // Style merged P-Q cells
        $sheet->getStyle("Q" . ($guidelinesRow + 4) . ":Q" . ($guidelinesRow + 9))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // MID-BLOCK
        $sheet->mergeCells("R{$guidelinesRow}:Z{$guidelinesRow}");
        $sheet->setCellValue("R{$guidelinesRow}", "1. CODES FOR CHECKING ATTENDANCE");
        $sheet->getStyle("R{$guidelinesRow}")->getFont()->setBold(true);
        $sheet->getStyle("R{$guidelinesRow}")
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // midCell1Text
        $midCell1Text =
            "(blank) - Present; (x)- Absent; Tardy (half shaded= Upper for Late Commer, Lower for Cutting Classes)";
        $sheet->mergeCells("R" . ($guidelinesRow + 1) . ":Z" . ($guidelinesRow + 3));
        $sheet->setCellValue("R" . ($guidelinesRow + 1), $midCell1Text);
        $sheet->getStyle("R" . ($guidelinesRow + 1) . ":Z" . ($guidelinesRow + 3))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // midCell2Text
        $midCell2Text =
            "2. REASONS/CAUSES FOR NLS";
        $sheet->getStyle("R" . ($guidelinesRow + 4) . ":Z" . ($guidelinesRow + 4))->getFont()->setBold(true);
        $sheet->mergeCells("R" . ($guidelinesRow + 4) . ":Z" . ($guidelinesRow + 4));
        $sheet->setCellValue("R" . ($guidelinesRow + 4), $midCell2Text);
        $sheet->getStyle("R" . ($guidelinesRow + 4) . ":Z" . ($guidelinesRow + 4))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // midCell3Text
        $midCell3Text =
            "a. Domestic-Related Factors";
        $sheet->getStyle("R" . ($guidelinesRow + 5) . ":Z" . ($guidelinesRow + 5))->getFont()->setBold(true);
        $sheet->mergeCells("R" . ($guidelinesRow + 5) . ":Z" . ($guidelinesRow + 5));
        $sheet->setCellValue("R" . ($guidelinesRow + 5), $midCell3Text);
        $sheet->getStyle("R" . ($guidelinesRow + 5) . ":Z" . ($guidelinesRow + 5))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // midCell4Text
        $midCell4Text =
            "a.1. Had to take care of siblings\n" .
            "a.2. Early marriage/pregnancy\n" .
            "a.3. Parents' attitude toward schooling\n" .
            "a.4. Family problems";
        $sheet->mergeCells("R" . ($guidelinesRow + 6) . ":Z" . ($guidelinesRow + 9));
        $sheet->setCellValue("R" . ($guidelinesRow + 6), $midCell4Text);
        $sheet->getStyle("R" . ($guidelinesRow + 6) . ":Z" . ($guidelinesRow + 9))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // midCell5Text
        $midCell5Text =
            "b. Individual-Related Factors";
        $sheet->getStyle("R" . ($guidelinesRow + 10) . ":Z" . ($guidelinesRow + 10))->getFont()->setBold(true);
        $sheet->mergeCells("R" . ($guidelinesRow + 10) . ":Z" . ($guidelinesRow + 10));
        $sheet->setCellValue("R" . ($guidelinesRow + 10), $midCell5Text);
        $sheet->getStyle("R" . ($guidelinesRow + 10) . ":Z" . ($guidelinesRow + 10))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // midCell6Text
        $midCell6Text =
            "b.1. Illness\n" .
            "b.2. Overage\n" .
            "b.3. Death\n" .
            "b.4. Drug Abuse\n" .
            "b.5. Poor academic performance\n" .
            "b.6. Lack of interest/Distractions\n" .
            "b.7. Hunger/Malnutrition";
        $sheet->mergeCells("R" . ($guidelinesRow + 11) . ":Z" . ($guidelinesRow + 15));
        $sheet->setCellValue("R" . ($guidelinesRow + 11), $midCell6Text);
        $sheet->getStyle("R" . ($guidelinesRow + 11) . ":Z" . ($guidelinesRow + 15))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // midCell7Text
        $midCell7Text =
            "c. School-Related Factors";
        $sheet->getStyle("R" . ($guidelinesRow + 16) . ":Z" . ($guidelinesRow + 16))->getFont()->setBold(true);
        $sheet->mergeCells("R" . ($guidelinesRow + 16) . ":Z" . ($guidelinesRow + 16));
        $sheet->setCellValue("R" . ($guidelinesRow + 16), $midCell7Text);
        $sheet->getStyle("R" . ($guidelinesRow + 16) . ":Z" . ($guidelinesRow + 16))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // midCell8Text
        $midCell8Text =
            "c.1. Teacher Factor\n" .
            "c.2. Physical condition of classroom\n" .
            "c.3. Peer influence";
        $sheet->mergeCells("R" . ($guidelinesRow + 17) . ":Z" . ($guidelinesRow + 20));
        $sheet->setCellValue("R" . ($guidelinesRow + 17), $midCell8Text);
        $sheet->getStyle("R" . ($guidelinesRow + 17) . ":Z" . ($guidelinesRow + 20))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // midCell9Text
        $midCell9Text =
            "e. Financial-Related";
        $sheet->getStyle("R" . ($guidelinesRow + 21) . ":Z" . ($guidelinesRow + 21))->getFont()->setBold(true);
        $sheet->mergeCells("R" . ($guidelinesRow + 21) . ":Z" . ($guidelinesRow + 21));
        $sheet->setCellValue("R" . ($guidelinesRow + 21), $midCell9Text);
        $sheet->getStyle("R" . ($guidelinesRow + 21) . ":Z" . ($guidelinesRow + 21))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // midCell10Text
        $midCell10Text =
            "e.1. Child labor, work";
        $sheet->mergeCells("R" . ($guidelinesRow + 22) . ":Z" . ($guidelinesRow + 22));
        $sheet->setCellValue("R" . ($guidelinesRow + 22), $midCell10Text);
        $sheet->getStyle("R" . ($guidelinesRow + 22) . ":Z" . ($guidelinesRow + 22))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // midCell11Text
        $midCell11Text =
            "f. Others (Specify)";
        $sheet->getStyle("R" . ($guidelinesRow + 23) . ":Z" . ($guidelinesRow + 24))->getFont()->setBold(true);
        $sheet->mergeCells("R" . ($guidelinesRow + 23) . ":Z" . ($guidelinesRow + 24));
        $sheet->setCellValue("R" . ($guidelinesRow + 23), $midCell11Text);
        $sheet->getStyle("R" . ($guidelinesRow + 23) . ":Z" . ($guidelinesRow + 24))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->getStyle("R{$guidelinesRow}:Z{$leftEndRow}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // RIGHT-BLOCK
        // Month
        $rightCell1Text =
            "Month:";
        $sheet->getStyle("AB" . ($guidelinesRow) . ":AC" . ($guidelinesRow + 1))->getFont()->setBold(true);
        $sheet->mergeCells("AB" . ($guidelinesRow) . ":AC" . ($guidelinesRow + 1));
        $sheet->setCellValue("AB" . ($guidelinesRow), $rightCell1Text);
        $sheet->getStyle("AB" . ($guidelinesRow) . ":AC" . ($guidelinesRow + 1))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // No. of Days of Classes
        $rightCell2Text =
            "No. of Days of Classes:";
        $sheet->getStyle("AD" . ($guidelinesRow) . ":AE" . ($guidelinesRow + 1))->getFont()->setBold(true);
        $sheet->mergeCells("AD" . ($guidelinesRow) . ":AE" . ($guidelinesRow + 1));
        $sheet->setCellValue("AD" . ($guidelinesRow), $rightCell2Text);
        $sheet->getStyle("AD" . ($guidelinesRow) . ":AE" . ($guidelinesRow + 1))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Summary
        $rightCell3Text =
            "Summary";
        $sheet->getStyle("AF" . ($guidelinesRow) . ":AH" . ($guidelinesRow))->getFont()->setBold(true);
        $sheet->mergeCells("AF" . ($guidelinesRow) . ":AH" . ($guidelinesRow));
        $sheet->setCellValue("AF" . ($guidelinesRow), $rightCell3Text);
        $sheet->getStyle("AF" . ($guidelinesRow) . ":AH" . ($guidelinesRow))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // rightCell3Text "Male"(M)
        $rightCell3Text =
            "M";
        $sheet->getStyle("AF" . ($guidelinesRow + 1) . ":AF" . ($guidelinesRow + 1))->getFont()->setBold(true);
        $sheet->mergeCells("AF" . ($guidelinesRow + 1) . ":AF" . ($guidelinesRow + 1));
        $sheet->setCellValue("AF" . ($guidelinesRow + 1), $rightCell3Text);
        $sheet->getStyle("AF" . ($guidelinesRow + 1) . ":AF" . ($guidelinesRow + 1))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // rightCell3Text "Female"(F)
        $rightCell3Text =
            "F";
        $sheet->getStyle("AG" . ($guidelinesRow + 1) . ":AG" . ($guidelinesRow + 1))->getFont()->setBold(true);
        $sheet->mergeCells("AG" . ($guidelinesRow + 1) . ":AG" . ($guidelinesRow + 1));
        $sheet->setCellValue("AG" . ($guidelinesRow + 1), $rightCell3Text);
        $sheet->getStyle("AG" . ($guidelinesRow + 1) . ":AG" . ($guidelinesRow + 1))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // rightCell3Text (Total)
        $rightCell3Text =
            "Total";
        $sheet->getStyle("AH" . ($guidelinesRow + 1) . ":AH" . ($guidelinesRow + 1))->getFont()->setBold(true);
        $sheet->mergeCells("AH" . ($guidelinesRow + 1) . ":AH" . ($guidelinesRow + 1));
        $sheet->setCellValue("AH" . ($guidelinesRow + 1), $rightCell3Text);
        $sheet->getStyle("AH" . ($guidelinesRow + 1) . ":AH" . ($guidelinesRow + 1))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // * Enrolment as of (1st Friday of the SY)
        $rightCell4Text = "* Enrolment as of (1st Friday of the SY)";
        $rowIndex = $guidelinesRow + 2;

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getFont();
        $sheet->getRowDimension($rowIndex)->setRowHeight(20);
        $sheet->mergeCells("AB{$rowIndex}:AE{$rowIndex}");
        $sheet->setCellValue("AB{$rowIndex}", $rightCell4Text);

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Get counts dynamically
        $maleCount = collect($this->students)->where('student_sex', 'male')->count();
        $femaleCount = collect($this->students)->where('student_sex', 'female')->count();
        $totalCount = $maleCount + $femaleCount;

        // MALE RESULT
        $sheet->mergeCells("AF" . ($guidelinesRow + 2) . ":AF" . ($guidelinesRow + 2));
        $sheet->setCellValue("AF" . ($guidelinesRow + 2), $maleCount);
        $sheet->getStyle("AF" . ($guidelinesRow + 2) . ":AF" . ($guidelinesRow + 2))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FEMALE RESULT
        $sheet->mergeCells("AG" . ($guidelinesRow + 2) . ":AG" . ($guidelinesRow + 2));
        $sheet->setCellValue("AG" . ($guidelinesRow + 2), $femaleCount);
        $sheet->getStyle("AG" . ($guidelinesRow + 2) . ":AG" . ($guidelinesRow + 2))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // TOTAL RESULT
        $sheet->mergeCells("AH" . ($guidelinesRow + 2) . ":AH" . ($guidelinesRow + 2));
        $sheet->setCellValue("AH" . ($guidelinesRow + 2), $totalCount);
        $sheet->getStyle("AH" . ($guidelinesRow + 2) . ":AH" . ($guidelinesRow + 2))
            ->getFont()->setBold(true);
        $sheet->getStyle("AH" . ($guidelinesRow + 2) . ":AH" . ($guidelinesRow + 2))
            ->getAlignment()->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Create rich text object
        $richText = new RichText();
        // Right cell 6 text
        // Late Enrolment during the month (beyond cut-off)"
        $part1 = $richText->createTextRun("Late Enrolment ");
        $part1->getFont()->setItalic(true)->setName('Aptos Display')->setSize('9'); // italic only
        $part2 = $richText->createTextRun("during the month");
        $part2->getFont()->setBold(true)->setItalic(true)->setName('Aptos Display')->setSize('9'); // bold + italic
        $richText->createText("\n");
        $part3 = $richText->createTextRun("(beyond cut-off)");
        $part3->getFont()->setItalic(true)->setName('Aptos Display')->setSize('9'); // italic only

        // Merge cells and set rich text
        $sheet->mergeCells("AB" . ($guidelinesRow + 3) . ":AE" . ($guidelinesRow + 4));
        $sheet->setCellValue("AB" . ($guidelinesRow + 3), $richText);

        // Alignment
        $sheet->getStyle("AB" . ($guidelinesRow + 3) . ":AE" . ($guidelinesRow + 4))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // MALE RESULT for Late Enrolment during the month
        $rightCell5Text =
            "";
        $sheet->getStyle("AF" . ($guidelinesRow + 3) . ":AF" . ($guidelinesRow + 4));
        $sheet->mergeCells("AF" . ($guidelinesRow + 3) . ":AF" . ($guidelinesRow + 4));
        $sheet->setCellValue("AF" . ($guidelinesRow + 3), $rightCell5Text);
        $sheet->getStyle("AF" . ($guidelinesRow + 3) . ":AF" . ($guidelinesRow + 4))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FEMALE RESULT for Late Enrolment during the month
        $rightCell5Text =
            "";
        $sheet->getStyle("AG" . ($guidelinesRow + 3) . ":AG" . ($guidelinesRow + 4));
        $sheet->mergeCells("AG" . ($guidelinesRow + 3) . ":AG" . ($guidelinesRow + 4));
        $sheet->setCellValue("AG" . ($guidelinesRow + 3), $rightCell5Text);
        $sheet->getStyle("AG" . ($guidelinesRow + 3) . ":AG" . ($guidelinesRow + 4))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // TOTAL RESULT for Late Enrolment during the month
        $rightCell5Text =
            "";
        $sheet->getStyle("AH" . ($guidelinesRow + 3) . ":AH" . ($guidelinesRow + 4))
            ->getFont()
            ->setBold(true);
        $sheet->mergeCells("AH" . ($guidelinesRow + 3) . ":AH" . ($guidelinesRow + 4));
        $sheet->setCellValue("AH" . ($guidelinesRow + 3), $rightCell5Text);
        $sheet->getStyle("AH" . ($guidelinesRow + 3) . ":AH" . ($guidelinesRow + 4))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Registered Learners as of
        $rightCell7Text =
            "Registered Learners as of";
        $sheet->getStyle("AB" . ($guidelinesRow + 5) . ":AE" . ($guidelinesRow + 5))
            ->getFont()
            ->setItalic(true); // <-- Added italic
        $sheet->mergeCells("AB" . ($guidelinesRow + 5) . ":AE" . ($guidelinesRow + 5));
        $sheet->setCellValue("AB" . ($guidelinesRow + 5), $rightCell7Text);
        $sheet->getStyle("AB" . ($guidelinesRow + 5) . ":AE" . ($guidelinesRow + 5))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_BOTTOM)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // end of month
        $rightCell8Text =
            "end of month";
        $sheet->getStyle("AB" . ($guidelinesRow + 6) . ":AE" . ($guidelinesRow + 6))
            ->getFont()
            ->setBold(true)
            ->setItalic(true); // <-- Added italic
        $sheet->mergeCells("AB" . ($guidelinesRow + 6) . ":AE" . ($guidelinesRow + 6));
        $sheet->setCellValue("AB" . ($guidelinesRow + 6), $rightCell8Text);
        $sheet->getStyle("AB" . ($guidelinesRow + 6) . ":AE" . ($guidelinesRow + 6))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // MALE RESULT for Registered Learners as of end of month
        $rightCell7Text =
            "";
        $sheet->getStyle("AF" . ($guidelinesRow + 5) . ":AF" . ($guidelinesRow + 6));
        $sheet->mergeCells("AF" . ($guidelinesRow + 5) . ":AF" . ($guidelinesRow + 6));
        $sheet->setCellValue("AF" . ($guidelinesRow + 5), $rightCell7Text);
        $sheet->getStyle("AF" . ($guidelinesRow + 5) . ":AF" . ($guidelinesRow + 6))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FEMALE RESULT for Registered Learners as of end of month
        $rightCell7Text =
            "";
        $sheet->getStyle("AG" . ($guidelinesRow + 5) . ":AG" . ($guidelinesRow + 6));
        $sheet->mergeCells("AG" . ($guidelinesRow + 5) . ":AG" . ($guidelinesRow + 6));
        $sheet->setCellValue("AG" . ($guidelinesRow + 5), $rightCell7Text);
        $sheet->getStyle("AG" . ($guidelinesRow + 5) . ":AG" . ($guidelinesRow + 6))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // TOTAL RESULT for Registered Learners as of end of month
        $rightCell7Text =
            "";
        $sheet->getStyle("AH" . ($guidelinesRow + 5) . ":AH" . ($guidelinesRow + 6))
            ->getFont()
            ->setBold(true);
        $sheet->mergeCells("AH" . ($guidelinesRow + 5) . ":AH" . ($guidelinesRow + 6));
        $sheet->setCellValue("AH" . ($guidelinesRow + 5), $rightCell7Text);
        $sheet->getStyle("AH" . ($guidelinesRow + 5) . ":AH" . ($guidelinesRow + 6))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Percentage of Enrolment as of
        $rightCell9Text =
            "Percentage of Enrolment as of";
        $sheet->getStyle("AB" . ($guidelinesRow + 7) . ":AE" . ($guidelinesRow + 7))
            ->getFont()
            ->setItalic(true); // <-- Added italic
        $sheet->mergeCells("AB" . ($guidelinesRow + 7) . ":AE" . ($guidelinesRow + 7));
        $sheet->setCellValue("AB" . ($guidelinesRow + 7), $rightCell9Text);
        $sheet->getStyle("AB" . ($guidelinesRow + 7) . ":AE" . ($guidelinesRow + 7))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_BOTTOM)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // end of month
        $rightCell10Text =
            "end of month";
        $sheet->getStyle("AB" . ($guidelinesRow + 8) . ":AE" . ($guidelinesRow + 8))
            ->getFont()
            ->setBold(true)
            ->setItalic(true); // <-- Added italic
        $sheet->mergeCells("AB" . ($guidelinesRow + 8) . ":AE" . ($guidelinesRow + 8));
        $sheet->setCellValue("AB" . ($guidelinesRow + 8), $rightCell10Text);
        $sheet->getStyle("AB" . ($guidelinesRow + 8) . ":AE" . ($guidelinesRow + 8))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // MALE RESULT for Percentage of Enrolment as of end of month
        $rightCell10Text =
            "";
        $sheet->getStyle("AF" . ($guidelinesRow + 7) . ":AF" . ($guidelinesRow + 8));
        $sheet->mergeCells("AF" . ($guidelinesRow + 7) . ":AF" . ($guidelinesRow + 8));
        $sheet->setCellValue("AF" . ($guidelinesRow + 7), $rightCell10Text);
        $sheet->getStyle("AF" . ($guidelinesRow + 7) . ":AF" . ($guidelinesRow + 8))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FEMALE RESULT for Percentage of Enrolment as of end of month
        $rightCell10Text =
            "";
        $sheet->getStyle("AG" . ($guidelinesRow + 7) . ":AG" . ($guidelinesRow + 8));
        $sheet->mergeCells("AG" . ($guidelinesRow + 7) . ":AG" . ($guidelinesRow + 8));
        $sheet->setCellValue("AG" . ($guidelinesRow + 7), $rightCell10Text);
        $sheet->getStyle("AG" . ($guidelinesRow + 7) . ":AG" . ($guidelinesRow + 8))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // TOTAL RESULT for Percentage of Enrolment as of end of month
        $rightCell10Text =
            "";
        $sheet->getStyle("AH" . ($guidelinesRow + 7) . ":AH" . ($guidelinesRow + 8))
            ->getFont()
            ->setBold(true);
        $sheet->mergeCells("AH" . ($guidelinesRow + 7) . ":AH" . ($guidelinesRow + 8));
        $sheet->setCellValue("AH" . ($guidelinesRow + 7), $rightCell10Text);
        $sheet->getStyle("AH" . ($guidelinesRow + 7) . ":AH" . ($guidelinesRow + 8))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Average Daily Attendance
        $rightCell11Text = "Average Daily Attendance";
        $rowIndex = $guidelinesRow + 9;

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getFont()
            ->setItalic(true);
        $sheet->getRowDimension($rowIndex)->setRowHeight(20);
        $sheet->mergeCells("AB{$rowIndex}:AE{$rowIndex}");
        $sheet->setCellValue("AB{$rowIndex}", $rightCell11Text);

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // MALE RESULT for Average Daily Attendance
        $rightCell11Text =
            "";
        $sheet->getStyle("AF" . ($guidelinesRow + 9) . ":AF" . ($guidelinesRow + 9));
        $sheet->mergeCells("AF" . ($guidelinesRow + 9) . ":AF" . ($guidelinesRow + 9));
        $sheet->setCellValue("AF" . ($guidelinesRow + 9), $rightCell11Text);
        $sheet->getStyle("AF" . ($guidelinesRow + 9) . ":AF" . ($guidelinesRow + 9))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FEMALE RESULT for Average Daily Attendance
        $rightCell11Text =
            "";
        $sheet->getStyle("AG" . ($guidelinesRow + 9) . ":AG" . ($guidelinesRow + 9));
        $sheet->mergeCells("AG" . ($guidelinesRow + 9) . ":AG" . ($guidelinesRow + 9));
        $sheet->setCellValue("AG" . ($guidelinesRow + 9), $rightCell11Text);
        $sheet->getStyle("AG" . ($guidelinesRow + 9) . ":AG" . ($guidelinesRow + 9))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // TOTAL RESULT for Average Daily Attendance
        $rightCell11Text =
            "";
        $sheet->getStyle("AH" . ($guidelinesRow + 9) . ":AH" . ($guidelinesRow + 9))
            ->getFont()
            ->setBold(true);
        $sheet->mergeCells("AH" . ($guidelinesRow + 9) . ":AH" . ($guidelinesRow + 9));
        $sheet->setCellValue("AH" . ($guidelinesRow + 9), $rightCell11Text);
        $sheet->getStyle("AH" . ($guidelinesRow + 9) . ":AH" . ($guidelinesRow + 9))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Percentage of Attendance for the month
        $rightCell12Text = "Percentage of Attendance for the month";
        $rowIndex = $guidelinesRow + 10;

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getFont()
            ->setItalic(true);
        $sheet->getRowDimension($rowIndex)->setRowHeight(20);
        $sheet->mergeCells("AB{$rowIndex}:AE{$rowIndex}");
        $sheet->setCellValue("AB{$rowIndex}", $rightCell12Text);

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // MALE RESULT for Percentage of Attendance for the month
        $rightCell12Text =
            "";
        $sheet->getStyle("AF" . ($guidelinesRow + 10) . ":AF" . ($guidelinesRow + 10));
        $sheet->mergeCells("AF" . ($guidelinesRow + 10) . ":AF" . ($guidelinesRow + 10));
        $sheet->setCellValue("AF" . ($guidelinesRow + 10), $rightCell12Text);
        $sheet->getStyle("AF" . ($guidelinesRow + 10) . ":AF" . ($guidelinesRow + 10))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FEMALE RESULT for Percentage of Attendance for the month
        $rightCell12Text =
            "";
        $sheet->getStyle("AG" . ($guidelinesRow + 10) . ":AG" . ($guidelinesRow + 10));
        $sheet->mergeCells("AG" . ($guidelinesRow + 10) . ":AG" . ($guidelinesRow + 10));
        $sheet->setCellValue("AG" . ($guidelinesRow + 10), $rightCell12Text);
        $sheet->getStyle("AG" . ($guidelinesRow + 10) . ":AG" . ($guidelinesRow + 10))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // TOTAL RESULT for Percentage of Attendance for the month
        $rightCell12Text =
            "";
        $sheet->getStyle("AH" . ($guidelinesRow + 10) . ":AH" . ($guidelinesRow + 10))
            ->getFont()
            ->setBold(true);
        $sheet->mergeCells("AH" . ($guidelinesRow + 10) . ":AH" . ($guidelinesRow + 10));
        $sheet->setCellValue("AH" . ($guidelinesRow + 10), $rightCell12Text);
        $sheet->getStyle("AH" . ($guidelinesRow + 10) . ":AH" . ($guidelinesRow + 10))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Number of students absent for 5 consecutive days
        $rightCell13Text = "Number of students absent for 5 consecutive days";
        $rowIndex = $guidelinesRow + 11;

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getFont()
            ->setItalic(true);
        $sheet->getRowDimension($rowIndex)->setRowHeight(30);
        $sheet->mergeCells("AB{$rowIndex}:AE{$rowIndex}");
        $sheet->setCellValue("AB{$rowIndex}", $rightCell13Text);

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Apply Borders from AB to AH
        $sheet->getStyle("AB{$guidelinesRow}:AH{$leftEndRow}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // MALE RESULT for Number of students absent for 5 consecutive days
        $rightCell13Text =
            "";
        $sheet->getStyle("AF" . ($guidelinesRow + 11) . ":AF" . ($guidelinesRow + 11));
        $sheet->mergeCells("AF" . ($guidelinesRow + 11) . ":AF" . ($guidelinesRow + 11));
        $sheet->setCellValue("AF" . ($guidelinesRow + 11), $rightCell13Text);
        $sheet->getStyle("AF" . ($guidelinesRow + 11) . ":AF" . ($guidelinesRow + 11))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FEMALE RESULT for Number of students absent for 5 consecutive days
        $rightCell13Text =
            "";
        $sheet->getStyle("AG" . ($guidelinesRow + 11) . ":AG" . ($guidelinesRow + 11));
        $sheet->mergeCells("AG" . ($guidelinesRow + 11) . ":AG" . ($guidelinesRow + 11));
        $sheet->setCellValue("AG" . ($guidelinesRow + 11), $rightCell13Text);
        $sheet->getStyle("AG" . ($guidelinesRow + 11) . ":AG" . ($guidelinesRow + 11))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // TOTAL RESULT for Number of students absent for 5 consecutive days
        $rightCell13Text =
            "";
        $sheet->getStyle("AH" . ($guidelinesRow + 11) . ":AH" . ($guidelinesRow + 11))
            ->getFont()
            ->setBold(true);
        $sheet->mergeCells("AH" . ($guidelinesRow + 11) . ":AH" . ($guidelinesRow + 11));
        $sheet->setCellValue("AH" . ($guidelinesRow + 11), $rightCell13Text);
        $sheet->getStyle("AH" . ($guidelinesRow + 11) . ":AH" . ($guidelinesRow + 11))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // NLS
        $rightCell14Text = "NLS";
        $rowIndex = $guidelinesRow + 12;

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getFont()
            ->setBold(true);
        $sheet->getRowDimension($rowIndex)->setRowHeight(20);
        $sheet->mergeCells("AB{$rowIndex}:AE{$rowIndex}");
        $sheet->setCellValue("AB{$rowIndex}", $rightCell14Text);

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // MALE RESULT for NLS
        $rightCell14Text =
            "";
        $sheet->getStyle("AF" . ($guidelinesRow + 12) . ":AF" . ($guidelinesRow + 12));
        $sheet->mergeCells("AF" . ($guidelinesRow + 12) . ":AF" . ($guidelinesRow + 12));
        $sheet->setCellValue("AF" . ($guidelinesRow + 12), $rightCell14Text);
        $sheet->getStyle("AF" . ($guidelinesRow + 12) . ":AF" . ($guidelinesRow + 12))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FEMALE RESULT for NLS
        $rightCell14Text =
            "";
        $sheet->getStyle("AG" . ($guidelinesRow + 12) . ":AG" . ($guidelinesRow + 12));
        $sheet->mergeCells("AG" . ($guidelinesRow + 12) . ":AG" . ($guidelinesRow + 12));
        $sheet->setCellValue("AG" . ($guidelinesRow + 12), $rightCell14Text);
        $sheet->getStyle("AG" . ($guidelinesRow + 12) . ":AG" . ($guidelinesRow + 12))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // TOTAL RESULT for NLS
        $rightCell14Text =
            "";
        $sheet->getStyle("AH" . ($guidelinesRow + 12) . ":AH" . ($guidelinesRow + 12))
            ->getFont()
            ->setBold(true);
        $sheet->mergeCells("AH" . ($guidelinesRow + 12) . ":AH" . ($guidelinesRow + 12));
        $sheet->setCellValue("AH" . ($guidelinesRow + 12), $rightCell14Text);
        $sheet->getStyle("AH" . ($guidelinesRow + 12) . ":AH" . ($guidelinesRow + 12))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Transfered Out
        $rightCell15Text = "Transfered Out";
        $rowIndex = $guidelinesRow + 13;

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getFont()
            ->setBold(true);
        $sheet->getRowDimension($rowIndex)->setRowHeight(20);
        $sheet->mergeCells("AB{$rowIndex}:AE{$rowIndex}");
        $sheet->setCellValue("AB{$rowIndex}", $rightCell15Text);

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // MALE RESULT for Transfered Out
        $rightCell15Text =
            "";
        $sheet->getStyle("AF" . ($guidelinesRow + 13) . ":AF" . ($guidelinesRow + 13));
        $sheet->mergeCells("AF" . ($guidelinesRow + 13) . ":AF" . ($guidelinesRow + 13));
        $sheet->setCellValue("AF" . ($guidelinesRow + 13), $rightCell15Text);
        $sheet->getStyle("AF" . ($guidelinesRow + 13) . ":AF" . ($guidelinesRow + 13))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FEMALE RESULT for Transfered Out
        $rightCell15Text =
            "";
        $sheet->getStyle("AG" . ($guidelinesRow + 13) . ":AG" . ($guidelinesRow + 13));
        $sheet->mergeCells("AG" . ($guidelinesRow + 13) . ":AG" . ($guidelinesRow + 13));
        $sheet->setCellValue("AG" . ($guidelinesRow + 13), $rightCell15Text);
        $sheet->getStyle("AG" . ($guidelinesRow + 13) . ":AG" . ($guidelinesRow + 13))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // TOTAL RESULT for Transfered Out
        $rightCell15Text =
            "";
        $sheet->getStyle("AH" . ($guidelinesRow + 13) . ":AH" . ($guidelinesRow + 13))
            ->getFont()
            ->setBold(true);
        $sheet->mergeCells("AH" . ($guidelinesRow + 13) . ":AH" . ($guidelinesRow + 13));
        $sheet->setCellValue("AH" . ($guidelinesRow + 13), $rightCell15Text);
        $sheet->getStyle("AH" . ($guidelinesRow + 13) . ":AH" . ($guidelinesRow + 13))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Transfered In
        $rightCell16Text = "Transfered In";
        $rowIndex = $guidelinesRow + 14;

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getFont()
            ->setBold(true);
        $sheet->getRowDimension($rowIndex)->setRowHeight(20);
        $sheet->mergeCells("AB{$rowIndex}:AE{$rowIndex}");
        $sheet->setCellValue("AB{$rowIndex}", $rightCell16Text);

        $sheet->getStyle("AB{$rowIndex}:AE{$rowIndex}")
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // MALE RESULT for Transfered Out
        $rightCell16Text =
            "";
        $sheet->getStyle("AF" . ($guidelinesRow + 14) . ":AF" . ($guidelinesRow + 14));
        $sheet->mergeCells("AF" . ($guidelinesRow + 14) . ":AF" . ($guidelinesRow + 14));
        $sheet->setCellValue("AF" . ($guidelinesRow + 14), $rightCell16Text);
        $sheet->getStyle("AF" . ($guidelinesRow + 14) . ":AF" . ($guidelinesRow + 14))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // FEMALE RESULT for Transfered Out
        $rightCell16Text =
            "";
        $sheet->getStyle("AG" . ($guidelinesRow + 14) . ":AG" . ($guidelinesRow + 14));
        $sheet->mergeCells("AG" . ($guidelinesRow + 14) . ":AG" . ($guidelinesRow + 14));
        $sheet->setCellValue("AG" . ($guidelinesRow + 14), $rightCell16Text);
        $sheet->getStyle("AG" . ($guidelinesRow + 14) . ":AG" . ($guidelinesRow + 14))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // TOTAL RESULT for Transfered Out
        $rightCell16Text =
            "";
        $sheet->getStyle("AH" . ($guidelinesRow + 14) . ":AH" . ($guidelinesRow + 14))
            ->getFont()
            ->setBold(true);
        $sheet->mergeCells("AH" . ($guidelinesRow + 14) . ":AH" . ($guidelinesRow + 14));
        $sheet->setCellValue("AH" . ($guidelinesRow + 14), $rightCell16Text);
        $sheet->getStyle("AH" . ($guidelinesRow + 14) . ":AH" . ($guidelinesRow + 14))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Apply Borders from AB to AH
        $rightEndRow = $guidelinesRow + 14; // last row of Transfered In

        // 2) If you can't remove earlier calls that used $leftEndRow, clear borders below "Transfered In"
        if ($leftEndRow > $rightEndRow) {
            $sheet->getStyle("AB" . ($rightEndRow + 1) . ":AH{$leftEndRow}")
                ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);
        }

        // 3) Now apply borders only up to "Transfered In"
        $sheet->getStyle("AB{$guidelinesRow}:AH{$rightEndRow}")
            ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [];
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
                $sheet->getPageMargins()->setHeader(0.2);
                $sheet->getPageMargins()->setFooter(0.2);

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
                $sheet->getStyle("B{$startRow}:B{$endRow}")->getFont()->setSize(9);

                // Wrap & center vertically for names
                $sheet->getStyle("B{$startRow}:B{$endRow}")
                    ->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Title (A1) → font size 16
                $sheet->getStyle("A1")->getFont()->setSize(16);

                // Row 2 (A2) → font size 16
                $sheet->getStyle("A2")->getFont()->setSize(6);

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
                $sheet->getStyle('AB4:AH4')->applyFromArray($borders); // Section
            }
        ];
    }

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
