<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Learner's Permanent Academic Record (SF10-ES)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0 !important;
        }

        .center {
            text-align: center;
        }

        .header {
            margin-bottom: 10px;
        }

        .header img {
            height: 60px;
            vertical-align: middle;
        }

        .header-title {
            text-align: center;
            line-height: 1.2;
        }

        .header-title h3 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }

        .header-title h4 {
            margin: 0;
            font-size: 11px;
        }

        .section-title {
            background-color: #e6e6e6;
            border: 1px solid #000;
            text-align: center;
            font-weight: bold;
            padding: 3px;
            margin-top: 5px;
        }

        .personal-info-table {
            margin-bottom: 2px !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 3px;
            font-size: 9px;
        }

        table td {
            vertical-align: middle;
        }

        input[type="checkbox"] {
            transform: scale(0.9);
            margin-right: 2px;
            vertical-align: middle;
            position: relative;
            top: 0;
            /* leveled alignment */
        }

        /* Remove borders */
        .no-border td,
        .no-border th {
            border: none !important;
        }

        /* Add spacing for cleaner layout */
        .no-border td {
            padding: 4px 6px;
        }

        /* Underline only the values */
        .underline-values .value {
            text-align: center;
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 85px;
            padding: 0 2px;
        }

        .label {
            font-weight: bold;
            margin-right: 4px;
        }

        .learning-table th {
            background-color: #f0f0f0;
        }

        .remarks {
            text-transform: uppercase;
        }

        .footer {
            font-size: 9px;
            margin-top: 20px;
            text-align: center;
        }

        /* Two-column layout for grades */
        .grade-pair {
            width: 100%;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .grade-box {
            width: 49%;
            border: 1px solid #000;
            padding: 3px;
            margin-bottom: 5px;
        }

        .page-break {
            page-break-after: always;
        }

        /* Eligibility section styling */
        .eligibility-header td {
            vertical-align: top;
            height: 30px;
            padding-top: 2px;
            padding-bottom: 1px;
            position: relative;
        }

        .eligibility-top {
            position: absolute;
            top: 1px;
            left: 3px;
            right: 3px;
            font-style: italic;
        }

        .eligibility-box {
            border: 1px solid #000;
            padding: 1px 3px 2px 3px;
            margin-top: 2px;
        }

        .eligibility-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin: 0;
        }

        .eligibility-table td {
            border: none !important;
            padding-top: 0px;
            padding-bottom: 0px;
            vertical-align: top;
        }

        .eligibility-table .top-line {
            font-style: italic;
            padding-top: 0px;
            padding-bottom: 2px;
            margin-top: 0;
        }

        /* ✅ Make label close to top border */
        .eligibility-table tr:first-child td {
            padding-top: 0px;
            padding-bottom: 2px;
            vertical-align: top;
        }

        /* ✅ Tighten spacing and lift label */
        .eligibility-table em {
            display: inline-block;
            margin-top: 0px;
            font-style: italic;
            position: relative;
            top: -1px;
        }

        .checkbox-group {
            display: inline-flex;
            align-items: center;
            gap: 30px;
            margin-left: 20px;
            position: relative;
            top: -1px;
        }

        .checkbox-group label {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            white-space: nowrap;
        }

        .school-value {
            font-weight: normal;
            text-transform: uppercase;
        }
    </style>

</head>

<body>
    {{-- Header --}}
    <div class="header">
        <table class="no-border">
            <tr>
                <td width="20%" class="center">
                    <img src="{{ public_path('assetsDashboard/img/report_card_img/seal_of_deped.png') }}"
                        alt="DepEd Seal">
                </td>
                <td width="60%" class="header-title">
                    <h4>Republic of the Philippines</h4>
                    <h4>Department of Education</h4>
                    <h3>Learner’s Permanent Academic Record for Elementary School</h3>
                    <h4>(SF10-ES) Formerly Form 137</h4>
                </td>
                <td width="20%" class="center">
                    <img src="{{ public_path('assetsDashboard/img/report_card_img/deped_logo.png') }}" alt="DepEd Seal">
                </td>
            </tr>
        </table>
    </div>

    {{-- Learner’s Personal Info --}}
    <div class="section-title">LEARNER’S PERSONAL INFORMATION</div>
    <table class="no-border underline-values personal-info-table">
        <tr>
            <td>
                <span class="label">LAST NAME:</span>
                <span class="value">{{ strtoupper($student->student_lName) }}</span>
            </td>
            <td>
                <span class="label">FIRST NAME:</span>
                <span class="value">{{ strtoupper($student->student_fName) }}</span>
            </td>
            <td>
                <span class="label">NAME EXTN. (Jr,I,II):</span>
                <span class="value">{{ strtoupper($student->student_extName ?? '') }}</span>
            </td>
            <td>
                <span class="label">MIDDLE NAME:</span>
                <span class="value">{{ strtoupper($student->student_mName) }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="label">Learner Reference Number (LRN):</span>
                <span class="value">{{ $student->student_lrn }}</span>
            </td>
            <td>
                <span class="label">Birthdate (mm/dd/yyyy):</span>
                <span class="value">{{ \Carbon\Carbon::parse($student->student_dob)->format('m/d/Y') }}</span>
            </td>
            <td>
                <span class="label">Sex:</span>
                <span class="value">{{ ucfirst($student->student_sex) }}</span>
            </td>
        </tr>
    </table>

    {{-- Eligibility --}}
    <div class="section-title">ELIGIBILITY FOR ELEMENTARY SCHOOL ENROLMENT</div>

    <!-- Outer box -->
    <div class="eligibility-box">
        <table class="eligibility-table no-border">
            <tr>
                <td colspan="3">
                    <em>Credential Presented for Grade 1:</em>
                    <div class="checkbox-wrapper" style="margin-top: 20px;">
                        <span class="checkbox-group">
                            <label><input type="checkbox"> Kinder Progress Report</label>
                            <label><input type="checkbox"> ECCD Checklist</label>
                            <label><input type="checkbox"> Kindergarten Certificate of Completion</label>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 45%;">
                    Name of School: <span
                        class="school-value">{{ $school['name'] ?? 'STA. BARBARA ELEMENTARY SCHOOL' }}</span>
                </td>
                <td style="width: 15%;">
                    School ID: <span class="school-value">112828</span>
                </td>
                <td style="width: 40%;">
                    Address of School: <span
                        class="school-value">{{ $school['address'] ?? 'STA. BARBARA, NABUA, CAMARINES SUR' }}</span>
                </td>
            </tr>
        </table>
    </div>

    {{-- Scholastic Record --}}
    <div class="section-title">SCHOLASTIC RECORD</div>

    @php
        // Group grades into pairs (Grade 1-2, 3-4, etc.)
        $pairs = $classHistory->chunk(2);
        $counter = 0;
    @endphp

    @foreach ($pairs as $pair)
        <div class="grade-pair">
            @foreach ($pair as $classItem)
                @php
                    $schoolYear = $schoolYears[$classItem->pivot->school_year_id] ?? 'N/A';
                    $gradeLevel = strtoupper($classItem->formatted_grade_level);
                    $subjects = $gradesByClass[$classItem->id] ?? [];
                    $ga = $generalAverages[$classItem->id];
                @endphp

                <div class="grade-box">
                    <table>
                        <tr>
                            <td colspan="2">School: {{ $school['name'] ?? 'Sample Elementary School' }}</td>
                            <td>School ID: {{ $school['school_id'] ?? '000001' }}</td>
                        </tr>
                        <tr>
                            <td>Classified as Grade: {{ $gradeLevel }}</td>
                            <td>School Year: {{ $schoolYear }}</td>
                            <td>Adviser: __________________</td>
                        </tr>
                    </table>

                    <table class="learning-table">
                        <thead>
                            <tr class="center">
                                <th rowspan="2">Learning Areas</th>
                                <th colspan="4">Quarterly Rating</th>
                                <th rowspan="2">Final</th>
                                <th rowspan="2">Remarks</th>
                            </tr>
                            <tr class="center">
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subjects as $subject)
                                <tr>
                                    <td>{{ $subject['subject'] }}</td>
                                    <td class="center">
                                        {{ $subject['quarters']->firstWhere('quarter', 1)['grade'] ?? '' }}</td>
                                    <td class="center">
                                        {{ $subject['quarters']->firstWhere('quarter', 2)['grade'] ?? '' }}</td>
                                    <td class="center">
                                        {{ $subject['quarters']->firstWhere('quarter', 3)['grade'] ?? '' }}</td>
                                    <td class="center">
                                        {{ $subject['quarters']->firstWhere('quarter', 4)['grade'] ?? '' }}</td>
                                    <td class="center">
                                        {{ $subject['final_average'] ? round($subject['final_average']) : '' }}</td>
                                    <td class="remarks center">{{ strtoupper($subject['remarks'] ?? '') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="center">No grades recorded.</td>
                                </tr>
                            @endforelse

                            @if ($ga)
                                <tr>
                                    <td colspan="5" style="text-align:right;">General Average</td>
                                    <td class="center"><strong>{{ round($ga['general_average']) }}</strong></td>
                                    <td class="remarks center"><strong>{{ strtoupper($ga['remarks']) }}</strong></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

        @php $counter++; @endphp
        {{-- Insert a page break after 4 grade levels (2 pairs) --}}
        @if ($counter % 2 == 0)
            <div class="page-break"></div>
        @endif
    @endforeach

    <div class="footer">
        <p><em>This record is confidential and should be handled according to DepEd policy.</em></p>
    </div>
</body>

</html>
