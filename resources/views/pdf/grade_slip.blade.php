<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Slips - {{ $class->formatted_grade_level }} - {{ $class->section }}</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        @page {
            margin: 0.5mm;
            size: A4 portrait;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            font-size: 15px;
        }

        .page {
            width: 100%;
            margin: auto;
            page-break-after: always;
        }

        .page:last-of-type {
            page-break-after: avoid;
        }

        .page:empty {
            display: none;
        }

        .page:has(.grade-card:only-child.empty) {
            display: none;
        }

        /* Additional safety measure */
        .grid-table:has(> tr > td.empty:only-child) {
            display: none;
        }

        .grid-table {
            width: 100%;
            border-spacing: 0.4cm;
            /* adds visible gap between cards */
            border-collapse: separate;
            /* prevent borders from merging */
            text-align: center;
        }

        .grade-card {
            width: 50%;
            vertical-align: top;
            border: 1px solid #000;
            padding: 0.3cm;
            box-sizing: border-box;
            height: 13.5cm;
            page-break-inside: avoid;
            background-color: #fff;
            /* ensure consistent background */
            position: relative;
            /* Added for date positioning */
        }

        .grade-card.empty {
            visibility: hidden;
            border: none;
        }

        /* --- Card Contents --- */
        .header {
            position: relative;
            margin-bottom: 4px;
            text-align: center;
        }

        .header img {
            height: 60px;
            width: auto;
            position: absolute;
            top: 0;
        }

        .header img.left-logo {
            left: 0;
            height: 100px;
            width: auto;
        }

        .header img.right-logo {
            right: 0;
            height: 100px;
            width: auto;
        }

        .header .title {
            text-align: center;
            font-size: 18px;
            line-height: 1.3;
            /* margin-bottom: 15px; */
        }

        .header .bold {
            font-weight: bold;
        }

        .school-year {
            font-size: 18px;
            margin-top: 2px;
            margin-bottom: 10px;
            text-align: center;
        }

        .grade-slip-title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        .student-info {
            font-size: 20px;
            margin-bottom: 5px;
            line-height: 1.3;
        }

        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-info td {
            text-align: center;
        }

        .grade-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 17px;
        }

        .grade-table th,
        .grade-table td {
            border: 1px solid #000;
            text-align: center;
            padding: 2px;
        }

        .grade-table th {
            background-color: #d7e1ff;
            font-weight: bold;
        }

        .grade-table td.general-average {
            padding-right: 5px;
            height: 30px;
        }

        .subject-name {
            text-align: left;
            padding-left: 5px;
        }

        .signature-area {
            margin-top: 0.5cm;
            font-size: 17px;
            text-align: center;
        }

        .underlined-name {
            display: inline-block;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            min-width: 200px;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .adviser-title {
            font-size: 17px;
            font-style: italic;
            margin-top: 2px;
        }

        /* Date display styles */
        .date-generated {
            position: absolute;
            bottom: 0.2cm;
            left: 0.3cm;
            font-size: 12px;
            color: #333;
        }

        /* Red text for grades below 75 and failed remarks */
        .low-grade {
            color: #ff0000;
        }

        .failed-remark {
            color: #ff0000;
        }
    </style>
</head>

<body>
    @php
        $studentsPerPage = 4;
        $totalStudents = count($students);
        $totalPages = ceil($totalStudents / $studentsPerPage);
        $currentDate = now()->format('F d, Y');
    @endphp

    @for ($page = 0; $page < $totalPages; $page++)
        @php
            $pageStudents = $students->slice($page * $studentsPerPage, $studentsPerPage)->values();
            $studentCount = count($pageStudents);
        @endphp

        @if ($studentCount > 0)
            <div class="page">
                <table class="grid-table">
                    @for ($row = 0; $row < 2; $row++)
                        <tr>
                            @for ($col = 0; $col < 2; $col++)
                                @php
                                    $index = $row * 2 + $col;
                                @endphp
                                @if ($index < $studentCount)
                                    <td class="grade-card">
                                        <!-- Header -->
                                        <div class="header">
                                            <img src="{{ public_path('assetsDashboard/img/report_card_img/seal_of_deped.png') }}"
                                                alt="DepEd" class="left-logo">
                                            <img src="{{ public_path('assets/img/logo.png') }}" alt="School"
                                                class="right-logo">
                                            <div class="title">
                                                <div>Republic of the Philippines</div>
                                                <div>Department of Education</div>
                                                <div>Region V, Bicol</div>
                                                <div>Division of Camarines Sur - Nabua East District</div>
                                                <div class="bold">STA. BARBARA ELEMENTARY SCHOOL</div>
                                                <div class="school-year">School Year: {{ $schoolYear->school_year }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grade-slip-title">GRADE SLIP</div>

                                        <!-- Student Info -->
                                        <div class="student-info">
                                            <table>
                                                <tr>
                                                    <td style="width: 10%;">Name:</td>
                                                    <td colspan="5" style="border-bottom: 1px solid #000;">
                                                        {{ $pageStudents[$index]->student_fName }}
                                                        {{ $pageStudents[$index]->student_mName }}
                                                        {{ $pageStudents[$index]->student_lName }}
                                                        {{ $pageStudents[$index]->student_extName }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Age:</td>
                                                    <td style="border-bottom: 1px solid #000;">
                                                        @if ($pageStudents[$index]->student_dob)
                                                            {{ \Carbon\Carbon::parse($pageStudents[$index]->student_dob)->age }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>Sex:</td>
                                                    <td style="border-bottom: 1px solid #000;">
                                                        {{ ucfirst($pageStudents[$index]->student_sex) ?? '' }}</td>
                                                    <td>LRN:</td>
                                                    <td style="border-bottom: 1px solid #000;">
                                                        {{ $pageStudents[$index]->student_lrn }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Grade:</td>
                                                    <td style="border-bottom: 1px solid #000;">
                                                        {{ $class->formatted_grade_level }}</td>
                                                    <td>Section:</td>
                                                    <td style="border-bottom: 1px solid #000;">{{ $class->section }}
                                                    </td>
                                                    <td colspan="2"></td>
                                                </tr>
                                            </table>
                                        </div>

                                        <!-- Grades Table -->
                                        <table class="grade-table">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="width: 40%;">Learning Areas</th>
                                                    <th colspan="4">Quarter</th>
                                                    <th rowspan="2">Final Grade</th>
                                                    <th rowspan="2">Remarks</th>
                                                </tr>
                                                <tr>
                                                    <th>1</th>
                                                    <th>2</th>
                                                    <th>3</th>
                                                    <th>4</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $currentStudent = $pageStudents[$index];
                                                    $allSubjectsComplete = true;
                                                @endphp

                                                @foreach ($currentStudent->subjects_with_grades as $subjectGrade)
                                                    @php
                                                        $quarters = $subjectGrade['quarters'];
                                                        $finalGrade = $subjectGrade['final'];
                                                        $remarks = $subjectGrade['remarks'];

                                                        // Check if all quarterly grades are complete
                                                        $hasAllQuarterlyGrades = $quarters->every(
                                                            fn($q) => $q['grade'] !== null,
                                                        );

                                                        // Apply color coding
                                                        $quarterlyGradeClasses = [];
                                                        $finalGradeClass = '';
                                                        $remarksClass = '';

                                                        foreach ([1, 2, 3, 4] as $q) {
                                                            $quarterGrade = $quarters->firstWhere('quarter', $q);
                                                            $gradeValue = $quarterGrade ? $quarterGrade['grade'] : null;
                                                            $quarterlyGradeClasses[$q] = '';

                                                            if (
                                                                $gradeValue !== null &&
                                                                is_numeric($gradeValue) &&
                                                                round($gradeValue) < 75 &&
                                                                $gradeValue != 0
                                                            ) {
                                                                $quarterlyGradeClasses[$q] = 'low-grade';
                                                            }
                                                        }

                                                        if ($finalGrade !== null && $finalGrade != 0) {
                                                            if (round($finalGrade) < 75) {
                                                                $finalGradeClass = 'low-grade';
                                                            }
                                                            if ($remarks === 'Failed') {
                                                                $remarksClass = 'failed-remark';
                                                            }
                                                        }

                                                        if (!$hasAllQuarterlyGrades) {
                                                            $allSubjectsComplete = false;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td class="subject-name">
                                                            {{ ucfirst($subjectGrade['subject']) }}</td>

                                                        <!-- Quarter 1 -->
                                                        <td class="{{ $quarterlyGradeClasses[1] }}">
                                                            @if (
                                                                $quarters->firstWhere('quarter', 1) &&
                                                                    $quarters->firstWhere('quarter', 1)['grade'] !== null &&
                                                                    $quarters->firstWhere('quarter', 1)['grade'] != 0)
                                                                {{ round($quarters->firstWhere('quarter', 1)['grade']) }}
                                                            @endif
                                                        </td>

                                                        <!-- Quarter 2 -->
                                                        <td class="{{ $quarterlyGradeClasses[2] }}">
                                                            @if (
                                                                $quarters->firstWhere('quarter', 2) &&
                                                                    $quarters->firstWhere('quarter', 2)['grade'] !== null &&
                                                                    $quarters->firstWhere('quarter', 2)['grade'] != 0)
                                                                {{ round($quarters->firstWhere('quarter', 2)['grade']) }}
                                                            @endif
                                                        </td>

                                                        <!-- Quarter 3 -->
                                                        <td class="{{ $quarterlyGradeClasses[3] }}">
                                                            @if (
                                                                $quarters->firstWhere('quarter', 3) &&
                                                                    $quarters->firstWhere('quarter', 3)['grade'] !== null &&
                                                                    $quarters->firstWhere('quarter', 3)['grade'] != 0)
                                                                {{ round($quarters->firstWhere('quarter', 3)['grade']) }}
                                                            @endif
                                                        </td>

                                                        <!-- Quarter 4 -->
                                                        <td class="{{ $quarterlyGradeClasses[4] }}">
                                                            @if (
                                                                $quarters->firstWhere('quarter', 4) &&
                                                                    $quarters->firstWhere('quarter', 4)['grade'] !== null &&
                                                                    $quarters->firstWhere('quarter', 4)['grade'] != 0)
                                                                {{ round($quarters->firstWhere('quarter', 4)['grade']) }}
                                                            @endif
                                                        </td>

                                                        <!-- Final Grade -->
                                                        <td class="{{ $finalGradeClass }}">
                                                            @if ($finalGrade !== null && $finalGrade != 0)
                                                                {{ round($finalGrade) }}
                                                            @endif
                                                        </td>

                                                        <!-- Remarks -->
                                                        <td class="{{ $remarksClass }}">
                                                            {{ $remarks ?? '' }}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                @php
                                                    // General Average
                                                    $showGeneralAverage =
                                                        $allSubjectsComplete &&
                                                        $currentStudent->dynamic_general_average !== null;
                                                    $dynamicGeneralAverage = $currentStudent->dynamic_general_average;

                                                    $generalAverageRemarks = '';
                                                    $generalAverageClass = '';
                                                    $generalAverageRemarksClass = '';

                                                    if (
                                                        $showGeneralAverage &&
                                                        $dynamicGeneralAverage !== null &&
                                                        $dynamicGeneralAverage != 0
                                                    ) {
                                                        if ($dynamicGeneralAverage >= 75) {
                                                            $generalAverageRemarks = 'Promoted';
                                                        } else {
                                                            $generalAverageRemarks = 'Retained';
                                                        }
                                                    }
                                                @endphp

                                                <tr>
                                                    <td class="general-average" colspan="5"><strong>General
                                                            Average</strong></td>
                                                    <td><strong class="{{ $generalAverageClass }}">
                                                            @if ($showGeneralAverage && $dynamicGeneralAverage != 0)
                                                                {{ $dynamicGeneralAverage }}
                                                            @endif
                                                        </strong></td>
                                                    <td><strong class="{{ $generalAverageRemarksClass }}">
                                                            {{ $generalAverageRemarks }}
                                                        </strong></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="signature-area">
                                            <div class="underlined-name">
                                                @if ($adviser)
                                                    {{ $adviser->firstName }} {{ $adviser->lastName }}
                                                @else
                                                    [Adviser's Name]
                                                @endif
                                            </div>
                                            <div class="adviser-title">Class Adviser</div>
                                        </div>

                                        <!-- Date Generated -->
                                        <div class="date-generated">
                                            Generated: {{ $currentDate }}
                                        </div>
                                    </td>
                                @else
                                    <td class="grade-card empty"></td>
                                @endif
                            @endfor
                        </tr>
                    @endfor
                </table>
            </div>
        @endif
    @endfor
</body>

</html>
