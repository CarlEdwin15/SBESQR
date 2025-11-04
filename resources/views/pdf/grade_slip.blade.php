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
        }

        .header img.right-logo {
            right: 0;
        }

        .header .title {
            text-align: center;
            font-size: 10px;
            line-height: 1.3;
        }

        .header .bold {
            font-weight: bold;
        }

        .school-year {
            font-size: 13px;
            margin-top: 2px;
            margin-bottom: 5px;
            text-align: center;
        }

        .grade-slip-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 6px;
        }

        .student-info {
            font-size: 13px;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-info td {
            padding: 1px 2px;
        }

        .grade-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
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

        .subject-name {
            text-align: left;
            padding-left: 5px;
        }

        .signature-area {
            margin-top: 0.4cm;
            font-size: 11px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 0.2cm auto 0;
            padding-top: 0.1cm;
        }
    </style>
</head>

<body>
    @php
        $studentsPerPage = 4;
        $totalStudents = count($students);
        $totalPages = ceil($totalStudents / $studentsPerPage);
    @endphp

    @for ($page = 0; $page < $totalPages; $page++)
        @php
            $pageStudents = $students->slice($page * $studentsPerPage, $studentsPerPage)->values();
            $studentCount = count($pageStudents);
        @endphp

        @if ($studentCount > 0) {{-- Only create page if there are students --}}
            <div class="page">
                <table class="grid-table">
                    @for ($row = 0; $row < 2; $row++)
                        {{-- Always 2 rows max --}}
                        <tr>
                            @for ($col = 0; $col < 2; $col++)
                                {{-- Always 2 columns --}}
                                @php
                                    $index = $row * 2 + $col;
                                @endphp
                                @if ($index < $studentCount)
                                    <td class="grade-card">
                                        <!-- Your existing card content here -->
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
                                                <div>Sta. Barbara, Nabua, Camarines Sur</div>
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
                                                @foreach ($subjects as $subject)
                                                    @php
                                                        $quarterlyGrades = [];
                                                        for ($q = 1; $q <= 4; $q++) {
                                                            $grade = $pageStudents[$index]->quarterlyGrades
                                                                ->where('quarter.quarter', $q)
                                                                ->where('quarter.classSubject.subject_id', $subject->id)
                                                                ->first();
                                                            $quarterlyGrades[$q] = $grade ? $grade->final_grade : '';
                                                        }
                                                        $finalGrade = $pageStudents[$index]->finalSubjectGrades
                                                            ->where('classSubject.subject_id', $subject->id)
                                                            ->first();
                                                    @endphp
                                                    <tr>
                                                        <td class="subject-name">{{ ucfirst($subject->name) }}</td>
                                                        <td>{{ $quarterlyGrades[1] }}</td>
                                                        <td>{{ $quarterlyGrades[2] }}</td>
                                                        <td>{{ $quarterlyGrades[3] }}</td>
                                                        <td>{{ $quarterlyGrades[4] }}</td>
                                                        <td>{{ $finalGrade->final_grade ?? '' }}</td>
                                                        <td>{{ ucfirst($finalGrade->remarks ?? '') }}</td>
                                                    </tr>
                                                @endforeach
                                                @php $generalAverage = $pageStudents[$index]->generalAverages->first(); @endphp
                                                <tr>
                                                    <td colspan="5"><strong>General Average</strong></td>
                                                    <td><strong>{{ $generalAverage->general_average ?? '' }}</strong>
                                                    </td>
                                                    <td><strong>{{ $generalAverage->remarks ?? '' }}</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="signature-area">
                                            <div class="signature-line">
                                                @if ($class->adviser)
                                                    {{ $class->adviser->firstName }} {{ $class->adviser->lastName }}
                                                @else
                                                    [Adviser's Name]
                                                @endif
                                                <br><em>Class Adviser</em>
                                            </div>
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
