<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class Form10Export implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $student;
    protected $classHistory;
    protected $gradesByClass;
    protected $generalAverages;
    protected $schoolYears;

    public function __construct($student, $classHistory, $gradesByClass, $generalAverages, $schoolYears)
    {
        $this->student = $student;
        $this->classHistory = $classHistory;
        $this->gradesByClass = $gradesByClass;
        $this->generalAverages = $generalAverages;
        $this->schoolYears = $schoolYears;
    }

    public function collection()
    {
        // We'll build data manually in map()
        return collect([1]);
    }

    public function headings(): array
    {
        return [
            ['Republic of the Philippines'],
            ['Department of Education'],
            ['LEARNER\'S PERMANENT ACADEMIC RECORD FOR ELEMENTARY SCHOOL (SF10-ES)'],
            ['Formerly Form 137'],
            [],
            ['LEARNER\'S PERSONAL INFORMATION'],
            []
        ];
    }

    public function map($row): array
    {
        $data = [];

        // Student Information
        $data[] = ['LAST NAME:', strtoupper($this->student->student_lName), '', 'FIRST NAME:', strtoupper($this->student->student_fName)];
        $data[] = ['NAME EXTENSION (Jr,I,II):', strtoupper($this->student->student_extName ?? ''), '', 'MIDDLE NAME:', strtoupper($this->student->student_mName)];
        $data[] = ['LEARNER REFERENCE NUMBER (LRN):', $this->student->student_lrn, '', 'BIRTHDATE (mm/dd/yyyy):', \Carbon\Carbon::parse($this->student->student_dob)->format('m/d/Y')];
        $data[] = ['SEX:', ucfirst($this->student->student_sex), '', '', ''];

        $data[] = [];
        $data[] = ['ELIGIBILITY FOR ELEMENTARY SCHOOL ENROLMENT'];
        $data[] = ['Credential Presented for Grade 1: [ ] Kinder Progress Report  [ ] ECCD Checklist  [ ] Kindergarten Certificate of Completion'];
        $data[] = ['Name of School: STA. BARBARA ELEMENTARY SCHOOL', '', 'School ID: 112828'];
        $data[] = ['Address of School: STA. BARBARA, NABUA, CAMARINES SUR'];

        $data[] = [];
        $data[] = ['SCHOLASTIC RECORD'];
        $data[] = [];

        // Grades for each class
        foreach ($this->classHistory as $classItem) {
            $schoolYear = $this->schoolYears[$classItem->pivot->school_year_id] ?? 'N/A';
            $gradeLevel = strtoupper($classItem->formatted_grade_level);
            $subjects = $this->gradesByClass[$classItem->id] ?? [];

            $data[] = ['SCHOOL: STA. BARBARA ELEMENTARY SCHOOL', '', 'SCHOOL ID: 112828', '', 'SCHOOL YEAR: ' . $schoolYear];
            $data[] = ['CLASSIFIED AS GRADE: ' . $gradeLevel, '', 'ADVISER: __________________'];
            $data[] = [];

            // Table headers
            $data[] = ['LEARNING AREAS', 'Q1', 'Q2', 'Q3', 'Q4', 'FINAL GRADE', 'REMARKS'];

            // Subject grades
            foreach ($subjects as $subject) {
                $q1 = $subject['quarters']->firstWhere('quarter', 1)['grade'] ?? '';
                $q2 = $subject['quarters']->firstWhere('quarter', 2)['grade'] ?? '';
                $q3 = $subject['quarters']->firstWhere('quarter', 3)['grade'] ?? '';
                $q4 = $subject['quarters']->firstWhere('quarter', 4)['grade'] ?? '';
                $final = $subject['final_average'] ? round($subject['final_average']) : '';
                $remarks = strtoupper($subject['remarks'] ?? '');

                $data[] = [
                    $subject['subject'],
                    $q1 !== '' ? $q1 : '',
                    $q2 !== '' ? $q2 : '',
                    $q3 !== '' ? $q3 : '',
                    $q4 !== '' ? $q4 : '',
                    $final !== '' ? $final : '',
                    $remarks
                ];
            }

            // General Average
            if (isset($this->generalAverages[$classItem->id])) {
                $ga = $this->generalAverages[$classItem->id];
                $data[] = ['', '', '', '', 'GENERAL AVERAGE', round($ga['general_average']), strtoupper($ga['remarks'])];
            }

            $data[] = [];
            $data[] = [];
        }

        $data[] = [];
        $data[] = ['This record is confidential and should be handled according to DepEd policy.'];

        return $data;
    }

    public function title(): string
    {
        return 'SF10-' . $this->student->full_name;
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for headers
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');
        $sheet->mergeCells('A4:E4');
        $sheet->mergeCells('A6:E6');
        $sheet->mergeCells('A8:E8');
        $sheet->mergeCells('A9:E9');
        $sheet->mergeCells('A10:E10');
        $sheet->mergeCells('A12:E12');

        // Center align headers
        $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A6')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A8')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A12')->getAlignment()->setHorizontal('center');

        // Bold headers
        $sheet->getStyle('A1:A4')->getFont()->setBold(true);
        $sheet->getStyle('A6')->getFont()->setBold(true);
        $sheet->getStyle('A8')->getFont()->setBold(true);
        $sheet->getStyle('A12')->getFont()->setBold(true);

        // Add borders for grade tables
        $row = 13; // Starting row for first grade table
        foreach ($this->classHistory as $classItem) {
            $subjects = $this->gradesByClass[$classItem->id] ?? [];
            if (count($subjects) > 0) {
                $tableStart = $row + 3; // Header row after school info
                $tableEnd = $tableStart + count($subjects);

                // Apply borders to grade table
                $range = 'A' . $tableStart . ':G' . ($tableEnd);
                $sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // Bold header row
                $sheet->getStyle('A' . $tableStart . ':G' . $tableStart)->getFont()->setBold(true);
                $sheet->getStyle('A' . $tableStart . ':G' . $tableStart)->getAlignment()->setHorizontal('center');

                $row = $tableEnd + 3; // Move to next section
            }
        }

        return [
            1 => ['font' => ['size' => 14]],
            2 => ['font' => ['size' => 12]],
            3 => ['font' => ['size' => 12, 'bold' => true]],
            4 => ['font' => ['size' => 11]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 10,
            'C' => 10,
            'D' => 10,
            'E' => 10,
            'F' => 15,
            'G' => 15,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge eligibility row
                $event->sheet->mergeCells('A9:E9');

                // Merge school info rows
                $event->sheet->mergeCells('A10:C10');
                $event->sheet->mergeCells('D10:E10');
                $event->sheet->mergeCells('A11:E11');

                // Center align school info
                $event->sheet->getStyle('A9:E11')->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
