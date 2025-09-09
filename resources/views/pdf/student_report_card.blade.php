<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <title>SF9 - {{ $student->getFullNameAttribute() }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #111;
        }

        /* container holds two pages side-by-side */
        .container {
            width: 100%;
            display: flex;
            gap: 10px;
        }

        /* each page is half the page width */
        .page {
            width: 50%;
            box-sizing: border-box;
            border: 1px solid transparent;
            padding: 8px 10px;
        }

        /* header */
        .school-header {
            text-align: center;
        }

        .school-header h3 {
            margin: 0;
            font-size: 14px;
        }

        .school-header p {
            margin: 0;
            font-size: 10px;
        }

        /* student info row */
        .student-info {
            margin-top: 6px;
            margin-bottom: 6px;
            font-size: 11px;
        }

        .student-info .left {
            width: 65%;
            display: inline-block;
            vertical-align: top;
        }

        .student-info .right {
            width: 34%;
            display: inline-block;
            text-align: right;
            vertical-align: top;
        }

        /* table for learning areas */
        .subjects-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .subjects-table th,
        .subjects-table td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 10.5px;
        }

        .subjects-table thead th {
            background: #eee;
            text-align: center;
        }

        .subjects-table td.subject-name {
            text-align: left;
            padding-left: 6px;
            width: 45%;
        }

        /* smaller helper tables */
        .attend-table,
        .desc-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 10px;
        }

        .attend-table td,
        .desc-table td {
            padding: 3px;
            vertical-align: top;
        }

        /* right page styles */
        .right-content {
            padding-left: 8px;
        }

        .core-values {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .core-values td,
        .core-values th {
            border: 1px solid #000;
            padding: 4px;
            font-size: 10px;
        }

        /* signatures */
        .sign-area {
            margin-top: 18px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }

        .sign-area .col {
            width: 48%;
            text-align: center;
        }

        /* small helpers */
        .center {
            text-align: center;
        }

        .bold {
            font-weight: 700;
        }

        .muted {
            color: #666;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="container">

        {{-- LEFT PAGE --}}
        <div class="page">
            <div class="school-header">
                <p style="font-size:9px; margin-bottom:2px;">{{ $school['department'] }}</p>
                <p style="font-size:9px; margin-bottom:2px;">{{ $school['region'] }}</p>
                <h3>{{ $school['name'] }}</h3>
            </div>

            <div class="student-info">
                <div class="left">
                    <div><strong>Name:</strong> {{ $student->getFullNameAttribute() }}</div>
                    <div><strong>Age:</strong> {{ \Carbon\Carbon::parse($student->student_dob)->age ?? 'N/A' }}
                        &nbsp;&nbsp; <strong>Sex:</strong>
                        {{ $student->student_sex ? ucfirst($student->student_sex) : 'N/A' }}</div>
                    <div><strong>Grade:</strong> {{ optional($class)->formatted_grade_level ?? 'N/A' }} &nbsp;&nbsp;
                        <strong>Section:</strong> {{ optional($class)->section ?? 'N/A' }}</div>
                    <div><strong>School Year:</strong> {{ $schoolYear->school_year ?? 'N/A' }} &nbsp;&nbsp;
                        <strong>LRN:</strong> {{ $student->student_lrn ?? '' }}</div>
                </div>
                <div class="right">
                    {{-- optionally student photo --}}
                    @if ($student->student_photo)
                        <img src="{{ public_path('storage/' . $student->student_photo) }}"
                            style="width:120px; height:120px; object-fit:cover; border:1px solid #000" alt="photo" />
                    @else
                        <div
                            style="width:120px;height:120px;border:1px solid #000; display:flex; align-items:center; justify-content:center; font-size:10px;">
                            NO PHOTO</div>
                    @endif
                </div>
            </div>

            {{-- Subjects table --}}
            <table class="subjects-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:50%;">LEARNING AREAS</th>
                        <th colspan="4">QUARTER</th>
                        <th rowspan="2">FINAL GRADE</th>
                        <th rowspan="2">REMARKS</th>
                    </tr>
                    <tr>
                        <th style="width:7%;">1</th>
                        <th style="width:7%;">2</th>
                        <th style="width:7%;">3</th>
                        <th style="width:7%;">4</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $s)
                        <tr>
                            <td class="subject-name">{{ $s['subject'] }}</td>
                            <td class="center">{{ $s['quarters']->firstWhere('quarter', 1)['grade'] ?? '' }}</td>
                            <td class="center">{{ $s['quarters']->firstWhere('quarter', 2)['grade'] ?? '' }}</td>
                            <td class="center">{{ $s['quarters']->firstWhere('quarter', 3)['grade'] ?? '' }}</td>
                            <td class="center">{{ $s['quarters']->firstWhere('quarter', 4)['grade'] ?? '' }}</td>
                            <td class="center">
                                @if ($s['final'] !== null)
                                    <strong>{{ $s['final'] }}</strong>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="center">{{ $s['remarks'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="center muted">No subjects/grades available</td>
                        </tr>
                    @endforelse

                    {{-- General Average row --}}
                    <tr>
                        <td class="bold center">General Average</td>
                        <td colspan="4"></td>
                        <td class="center bold">{{ $generalAverage ?? '-' }}</td>
                        <td class="center">{{ $generalRemarks ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Descriptors & Grading Scales --}}
            <table class="desc-table" style="margin-top:8px;">
                <tr>
                    <td style="width:55%; vertical-align: top;">
                        <table style="width:100%; border-collapse: collapse;">
                            <tr>
                                <td class="bold">Descriptors</td>
                                <td class="bold">Grading Scales</td>
                                <td class="bold">Remarks</td>
                            </tr>
                            <tr>
                                <td>Outstanding</td>
                                <td>90-100</td>
                                <td>Passed</td>
                            </tr>
                            <tr>
                                <td>Very Satisfactory</td>
                                <td>85-89</td>
                                <td>Passed</td>
                            </tr>
                            <tr>
                                <td>Satisfactory</td>
                                <td>80-84</td>
                                <td>Passed</td>
                            </tr>
                            <tr>
                                <td>Fairly Satisfactory</td>
                                <td>75-79</td>
                                <td>Passed</td>
                            </tr>
                            <tr>
                                <td>Did Not Meet Expectations</td>
                                <td>Below 75</td>
                                <td>Failed</td>
                            </tr>
                        </table>
                    </td>

                    <td style="width:45%; vertical-align: top;">
                        <div class="bold">REPORT ON ATTENDANCE</div>
                        <table class="attend-table" border="1">
                            <tr>
                                <td>No. of School Days</td>
                                <td class="center">{{ $attendance['days_school'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>No. of Days Present</td>
                                <td class="center">{{ $attendance['days_present'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>No. of Days Absent</td>
                                <td class="center">{{ $attendance['days_absent'] ?? '' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </div>

        {{-- RIGHT PAGE --}}
        <div class="page right-content">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <p style="font-size:11px; margin:0;">Dear Parent:</p>
                    <p class="muted" style="margin-top:6px; margin-bottom:6px;">
                        This report card shows the ability and progress of your child has made in the different learning
                        areas as well as his/her core values.
                        The school welcomes you should you desire to know more about your child's progress.
                    </p>

                    <div class="muted" style="margin-top:8px;">
                        <div><strong>Teacher:</strong> JOHANN ALEX R. DAZA</div>
                        <div><strong>Head Teacher II:</strong> ALMERA S. PAJA</div>
                    </div>
                </div>

                <div style="width:35%; text-align:right;">
                    <div style="font-size:12px; font-weight:700;">{{ $student->student_fName }}
                        {{ $student->student_lName }}</div>
                    <div class="muted">Teacher</div>
                </div>
            </div>

            {{-- Certificate of Transfer / cancellation placeholders --}}
            <div style="margin-top:10px;">
                <div style="border-top:1px solid #000; padding-top:6px;">
                    <div style="margin-bottom:8px;"><strong>CERTIFICATE OF TRANSFER</strong></div>
                    <div>Admitted to Grade: _____________ &nbsp; Section: _____________</div>
                    <div>Eligibility for Admission to Grade: ________________________</div>
                </div>

                <div style="margin-top:12px; border-top:1px solid #000; padding-top:6px;">
                    <div style="margin-bottom:8px;"><strong>CANCELLATION OF ELIGIBILITY TO TRANSFER</strong></div>
                    <div>Admitted in: _________________________________</div>
                    <div>Date: ___________________________</div>
                </div>
            </div>

            {{-- Report on learner's observed values --}}
            <div style="margin-top:12px;">
                <div class="bold">REPORT ON LEARNER'S OBSERVED VALUES</div>
                <table class="core-values" style="margin-top:6px;">
                    <thead>
                        <tr>
                            <th style="width:40%;">CORE VALUES</th>
                            <th style="width:40%;">BEHAVIOR STATEMENTS</th>
                            <th style="width:20%;">QUARTER</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1. MAKA-DIYOS</td>
                            <td>Expresses one’s spiritual beliefs while respecting the spiritual beliefs of others.</td>
                            <td style="min-height:80px;"></td>
                        </tr>
                        <tr>
                            <td>2. MAKATAO</td>
                            <td>Is sensitive to individual, social, and cultural differences.</td>
                            <td style="min-height:80px;"></td>
                        </tr>
                        <tr>
                            <td>3. MAKA-KALIKASAN</td>
                            <td>Cares for the environment and utilizes resources wisely, judiciously, and economically.
                            </td>
                            <td style="min-height:80px;"></td>
                        </tr>
                        <tr>
                            <td>4. MAKABANSA</td>
                            <td>Demonstrates pride in being a Filipino; exercises the rights and responsibilities of a
                                Filipino citizen.</td>
                            <td style="min-height:80px;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="sign-area">
                <div class="col">
                    <div style="border-top:1px solid #000; padding-top:6px;">PARENT/GUARDIAN'S SIGNATURE</div>
                </div>
                <div class="col">
                    <div style="border-top:1px solid #000; padding-top:6px;">TEACHER'S SIGNATURE</div>
                </div>
            </div>

            <div style="margin-top:8px;">
                <div class="muted">FIRST QUARTER &nbsp;&nbsp;&nbsp; SECOND QUARTER &nbsp;&nbsp;&nbsp; THIRD QUARTER
                    &nbsp;&nbsp;&nbsp; FOURTH QUARTER</div>
            </div>
        </div>
    </div>
</body>

</html>
