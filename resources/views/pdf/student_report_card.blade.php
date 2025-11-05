<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SF9 - {{ $student->getFullNameAttribute() }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        body {
            font-family: 'Times New Roman', Times, sans-serif;
            font-size: 11px;
            color: #000;
        }

        h3,
        h4,
        h5,
        p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px 4px;
        }

        table.no-border td,
        table.no-border th {
            border: none;
        }

        /* ============================= */
        /* ====== CLASS STYLES A–Z ===== */
        /* ============================= */

        .attendance-table {
            text-align: center;
            margin-bottom: 15px;
        }

        .attendance-table .attendance-row td {
            height: 28px;
        }

        .attendance-table th {
            background-color: #d7e1ff;
            color: #000;
            font-weight: bold;
        }

        .attendance-title {
            margin-bottom: 13px;
            /* Increase or decrease as needed */
        }

        .bold {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .column {
            display: table-cell;
            width: 49%;
            vertical-align: top;
            padding-left: 2mm;
            padding-right: 12mm;
            box-sizing: border-box;
        }

        .column:last-child {
            padding-left: 15mm;
            padding-right: 5mm;
        }

        .descriptors-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 13px;
        }

        .descriptors-table td:first-child {
            width: 45%;
            text-align: left;
            padding-left: 8px;
        }

        .descriptors-table td:last-child {
            width: 30%;
            text-align: center;
        }

        .descriptors-table td:nth-child(2) {
            width: 25%;
            text-align: center;
        }

        .descriptors-table th {
            font-weight: bold;
            text-align: center;
            padding-bottom: 6px;
        }

        .descriptors-table th,
        .descriptors-table td {
            border: none;
            padding: 4px 0;
        }

        .descriptors-table th:first-child {
            text-align: left;
            padding-left: 8px;
        }

        .markings-table table {
            width: 80%;
            margin: 10px auto;
            border-collapse: collapse;
            font-size: 9px;
        }

        .markings-table th,
        .markings-table td {
            border: none;
            padding: 2px 4px;
        }

        .no-border th,
        .no-border td {
            border: none;
        }

        .non-numerical-rating {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            font-size: 13px;
        }

        .non-numerical-rating th,
        .non-numerical-rating td {
            border: none;
            padding: 3px 4px;
            vertical-align: middle;
        }

        .non-numerical-rating th:first-child,
        .non-numerical-rating td:first-child {
            width: 40%;
            text-align: center;
        }

        .non-numerical-rating th:last-child,
        .non-numerical-rating td:last-child {
            width: 60%;
            text-align: left;
            padding-left: 10px;
        }

        .non-numerical-rating th {
            font-weight: bold;
            text-align: center;
            padding-bottom: 5px;
        }

        .page {
            width: 100%;
            height: 100%;
            display: table;
            table-layout: fixed;
            page-break-after: always;
            justify-content: space-between;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .progress-table td {
            height: 20px;
            /* was 30px */
            padding: 2px 3px;
            /* less padding for tighter spacing */
            vertical-align: middle;
        }

        .progress-table th {
            background-color: #d7e1ff;
            color: #000;
            font-weight: bold;
            text-align: center;
            height: 22px;
            /* was 28px */
            padding: 2px 3px;
            /* reduce padding to lessen total height */
            vertical-align: middle;
        }

        .progress-table th:first-child,
        .values-table th:first-child {
            border-top-left-radius: 4px;
        }

        .progress-table th:last-child,
        .values-table th:last-child {
            border-top-right-radius: 4px;
        }

        .progress-table tr:first-child th {
            height: 18px;
            padding: 1px 3px;
        }

        .right {
            text-align: right;
        }

        .signature-block {
            margin-top: 20px;
            font-size: 11px;
        }

        .signature-block .bold.center {
            margin-bottom: 18px !important;
        }

        .signature-block .line {
            margin-left: 30px;
            display: inline-block;
        }

        .signature-block p {
            margin-bottom: 15px;
        }

        .signature-block p.bold {
            margin-bottom: 6px;
        }

        .signature-line {
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .small {
            font-size: 9px;
        }

        .spacing {
            margin-top: 15px;
        }

        .values-table td {
            height: 30px;
            vertical-align: top;
            padding: 6px 4px;
        }

        .values-table td:first-child {
            width: 22%;
        }

        .values-table td:nth-child(2) {
            width: 48%;
        }

        .values-table td:nth-child(n+3) {
            text-align: center;
            width: 7%;
        }

        .values-table th {
            background-color: #d7e1ff;
            color: #000;
            font-weight: bold;
            text-align: center;
            height: 28px;
            vertical-align: middle;
        }
    </style>
</head>

<body>

    {{-- ===================================================== --}}
    {{-- FRONT PAGE --}}
    {{-- ===================================================== --}}
    <div class="page">

        {{-- LEFT SIDE: Attendance + Parent Signatures --}}
        <div class="column">
            <div style="margin-bottom: 70px">
                <p class="bold center attendance-title" style="font-size: 16px;">Attendance Record</p>
                <table class="attendance-table" style="font-size: 12px">

                    <tr>
                        <th></th>
                        @foreach (['Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'Total'] as $m)
                            <th>{{ $m }}</th>
                        @endforeach
                    </tr>
                    <tr class="attendance-row">
                        <td style="width: 10%">No. of School Days</td>
                        @foreach (range(1, 12) as $i)
                            <td></td>
                        @endforeach
                    </tr>
                    <tr class="attendance-row">
                        <td style="width: 10%">No. of Days Present</td>
                        @foreach (range(1, 12) as $i)
                            <td></td>
                        @endforeach
                    </tr>
                    <tr class="attendance-row">
                        <td style="width: 10%">No. of Times Absent</td>
                        @foreach (range(1, 12) as $i)
                            <td></td>
                        @endforeach
                    </tr>
                </table>
            </div>

            <div class="signature-block">
                <p class="bold center" style="font-size: 17px;">PARENT/GUARDIAN’S SIGNATURE</p>
                <p style="font-size: 13px;">1<sup>st</sup> Quarter <span
                        class="line">__________________________________________</span></p>
                <p style="font-size: 13px;">2<sup>nd</sup> Quarter <span
                        class="line">__________________________________________</span></p>
                <p style="font-size: 13px;">3<sup>rd</sup> Quarter <span
                        class="line">__________________________________________</span></p>
                <p style="font-size: 13px;">4<sup>th</sup> Quarter <span
                        class="line">__________________________________________</span></p>
            </div>
        </div>

        {{-- RIGHT SIDE: Learner’s Progress Report Card --}}
        <div class="column">

            <!-- HEADER with logo and centered title -->
            <div style="position: relative; margin-bottom: 8px; text-align: center;">

                <!-- Logo properly aligned on top-left -->
                <img src="{{ public_path('assetsDashboard/img/report_card_img/seal_of_deped.png') }}" alt="DepEd Seal"
                    style="width: 57px; height: auto; position: absolute; top: 0; left: 0;">

                <!-- Centered Title -->
                <div
                    style="display: inline-block; text-align: center; font-size: 11px; line-height: 1.3; margin-top: 5px;">
                    <div class="bold" style="font-size: 15px">Republic of the Philippines</div>
                    <div class="bold" style="font-size: 15px">DEPARTMENT OF EDUCATION</div>
                </div>
            </div>

            {{-- SCHOOL INFO --}}
            <table class="no-border"
                style="width: 80%; margin-bottom: 15px; font-size: 12px; text-align: left; border-spacing: 0; line-height: 1.1; border-collapse: collapse;">
                <tr>
                    <td style="width: 18%; padding-right: 4px; padding-top: 7px">Region:</td>
                    <td style="border-bottom: 1px solid #000; width: 82%; text-align: center;">Region V
                    </td>
                </tr>
                <tr>
                    <td style="width: 18%; padding-right: 4px; padding-top: 7px">Division:</td>
                    <td style="border-bottom: 1px solid #000; text-align: center;">Schools Division
                        Office of Camarines Sur</td>
                </tr>
                <tr>
                    <td style="width: 18%; padding-right: 4px; padding-top: 7px">District:</td>
                    <td style="border-bottom: 1px solid #000; text-align: center;"></td>
                </tr>
                <tr>
                    <td style="width: 18%; padding-right: 4px; padding-top: 7px">School:</td>
                    <td style="border-bottom: 1px solid #000; text-align: center;">STA. BARBARA
                        ELEMENTARY SCHOOL</td>
                </tr>
            </table>

            <h3 class="center" style="margin-top: auto; font-size: 15px">LEARNER’S PROGRESS REPORT CARD</h3>
            <p class="center" style="margin-bottom: 10px; font-size: 13px">School Year
                {{ $schoolYear->school_year ?? '_____' }}</p>

            {{-- STUDENT INFO --}}
            <table class="no-border center" style="width: 100%; font-size: 13px; margin-top: 15px">
                <!-- Row 1: Name -->
                <tr>
                    <td style="width: 8%;">Name:</td>
                    <td colspan="5" style="border-bottom: 1px solid #000; width: 92%;">
                        {{ $student->getFullNameAttribute() }}
                    </td>
                </tr>

                <!-- Row 2: Age and Sex -->
                <tr>
                    <td style="width: 8%;">Age:</td>
                    <td style="border-bottom: 1px solid #000; width: 15%; text-align: center;">
                        {{ $student_info['age'] ?? '' }}
                    </td>
                    <td style="width: 8%;">Sex:</td>
                    <td colspan="3" style="border-bottom: 1px solid #000; width: 69%; text-align: center;">
                        {{ $student_info['sex'] ?? '' }}
                    </td>
                </tr>

                <!-- Row 3: Grade, Section, and LRN -->
                <tr>
                    <td style="width: 8%;">Grade:</td>
                    <td style="border-bottom: 1px solid #000; width: 10%; text-align: center;">
                        {{ $student_info['grade'] ?? '' }}
                    </td>
                    <td style="width: 8%;">Section:</td>
                    <td style="border-bottom: 1px solid #000; width: 15%; text-align: center;">
                        {{ $student_info['section'] ?? '' }}
                    </td>
                    <td style="width: 6%;">LRN:</td>
                    <td style="border-bottom: 1px solid #000; width: 20%; text-align: center;">
                        {{ $student_info['lrn'] ?? '' }}
                    </td>
                </tr>
            </table>

            {{-- LETTER TO PARENT --}}
            <p style="margin-top: 10px; font-size: 12px;">Dear Parent,</p>
            <div style="margin-top: 10px; text-align: justify; font-size: 12px;">
                <p style="font-size: 12px; text-align: justify; text-indent: 30px;">
                    This report card shows the ability and the progress your child has made in the different learning
                    areas as well
                    as his/her progress in core values. <br>
                </p>
                <p style="font-size: 12px; text-align: justify; text-indent: 30px;">
                    The school welcomes you should you desire to know more about
                    your child’s progress.
                </p>
            </div>

            {{-- SIGNATURES: Teacher (right) and Head Teacher/Principal (left) --}}
            <table class="no-border" style="width: 100%; margin-top: 15px; font-size: 11px;">
                <tr>
                    <td style="width: 50%; text-align: center;">
                        __________________________________<br>
                        Head Teacher / Principal
                    </td>
                    <td style="width: 50%; text-align: center;">
                        __________________________________<br>
                        Teacher
                    </td>
                </tr>
            </table>

            {{-- CERTIFICATE OF TRANSFER --}}
            <div style="margin-top: 10px; text-align: center;">
                <p class="bold" style="font-size: 13px;">Certificate of Transfer</p>
            </div>

            <!-- Use table-based layout for perfect alignment of underlines -->
            <table class="no-border" style="width: 100%; font-size: 11px; margin-top: 5px;">
                <!-- Row 1: Admitted to Grade / Section / Room -->
                <tr>
                    <td style="width: 18%;">Admitted to Grade:</td>
                    <td style="border-bottom: 1px solid #000; width: 15%;"></td>
                    <td style="width: 8%;">Section:</td>
                    <td style="border-bottom: 1px solid #000; width: 15%;"></td>
                    <td style="width: 6%;">Room:</td>
                    <td style="border-bottom: 1px solid #000; width: 20%;"></td>
                </tr>

                <!-- Row 2: Eligible for Admission to Grade -->
                <tr>
                    <td style="width: 37%;">Eligible for Admission to Grade:</td>
                    <td colspan="5" style="border-bottom: 1px solid #000; width: 50%;"></td>
                </tr>

                <tr>
                    <td colspan="6" style="padding-top: 5px;">Approved:</td>
                </tr>
            </table>

            <!-- Signatures -->
            <table class="no-border" style="width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 50%; text-align: center;">
                        __________________________________<br>
                        Head Teacher / Principal
                    </td>
                    <td style="width: 50%; text-align: center;">
                        __________________________________<br>
                        Teacher
                    </td>
                </tr>
            </table>

            {{-- CANCELLATION --}}
            <div style="margin-top: 20px; text-align: center;">
                <p class="bold" style="font-size: 13px;">Cancellation of Eligibility to Transfer</p>
            </div>

            <table class="no-border" style="width: 100%; font-size: 11px; margin-top: 5px;">
                <!-- Row 1: Admitted in -->
                <tr>
                    <td style="width: 18%;">Admitted in:</td>
                    <td style="border-bottom: 1px solid #000; width: 82%;"></td>
                </tr>

                <!-- Row 2: Date (shorter underline, adjusted height and spacing) -->
                <tr>
                    <td style="width: 5%;">Date:</td>
                    <td>
                        <div style="width: 40%; border-bottom: 1px solid #000; height: 14px; margin-top: 2px;"></div>
                    </td>
                </tr>

                <!-- Row 3: Signature aligned right -->
                <tr>
                    <td colspan="2" style="padding-top: 8px; text-align: right;">
                        <div style="display: inline-block; text-align: center;">
                            __________________________________<br>
                            Principal
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- BACK PAGE --}}
    {{-- ===================================================== --}}
    <div class="page">

        {{-- LEFT SIDE: Report on Learning Progress --}}
        <div class="column">
            <p style="font-size: 20px; font-weight: bold; margin-bottom: 15px;">
                {{ optional($class)->formatted_grade_level ?? '' }}</p>
            <p class="center" style="font-size: 14px; font-weight: bold; margin-bottom: 15px;">REPORT ON LEARNING
                PROGRESS AND ACHIEVEMENT</p>

            <table class="progress-table" style="margin-bottom: 20px">
                <tr style="font-size: 13px">
                    <th rowspan="2">Learning Areas</th>
                    <th colspan="4" class="center">Quarter</th>
                    <th rowspan="2">Final Rating</th>
                    <th rowspan="2">Remarks</th>
                </tr>
                <tr style="font-size: 13px">
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
                @foreach ($subjects as $s)
                    <tr style="font-size: 13px">
                        <td>{{ $s['subject'] }}</td>
                        <td class="center">
                            {{ isset($s['quarters']->firstWhere('quarter', 1)['grade']) ? round($s['quarters']->firstWhere('quarter', 1)['grade']) : '' }}
                        </td>
                        <td class="center">
                            {{ isset($s['quarters']->firstWhere('quarter', 2)['grade']) ? round($s['quarters']->firstWhere('quarter', 2)['grade']) : '' }}
                        </td>
                        <td class="center">
                            {{ isset($s['quarters']->firstWhere('quarter', 3)['grade']) ? round($s['quarters']->firstWhere('quarter', 3)['grade']) : '' }}
                        </td>
                        <td class="center">
                            {{ isset($s['quarters']->firstWhere('quarter', 4)['grade']) ? round($s['quarters']->firstWhere('quarter', 4)['grade']) : '' }}
                        </td>
                        <td class="center">
                            {{ isset($s['final']) ? round($s['final']) : '' }}
                        </td>
                        @php
                            $finalGrade = isset($s['final']) ? round($s['final']) : null;
                            $remarks = $finalGrade !== null ? ($finalGrade >= 75 ? 'Passed' : 'Failed') : '';
                        @endphp
                        <td class="center">{{ $remarks }}</td>
                    </tr>
                @endforeach
                <tr style="font-size: 13px; font-weight: bold;">
                    <td style="border: none;"></td>
                    <td class="center" colspan="4">General Average</td>
                    <td class="center">
                        {{ isset($generalAverage) ? $generalAverage : '' }}
                    </td>
                    <td class="center">
                        @if (isset($generalAverage))
                            {{ $generalAverage >= 75 ? 'Promoted' : 'Retained' }}
                        @endif
                    </td>
                </tr>
            </table>

            <div class="spacing">
                <table class="descriptors-table">
                    <tr>
                        <th>Descriptors</th>
                        <th>Grading Scale</th>
                        <th>Remarks</th>
                    </tr>
                    <tr>
                        <td>Outstanding</td>
                        <td>90–100</td>
                        <td>Passed</td>
                    </tr>
                    <tr>
                        <td>Very Satisfactory</td>
                        <td>85–89</td>
                        <td>Passed</td>
                    </tr>
                    <tr>
                        <td>Satisfactory</td>
                        <td>80–84</td>
                        <td>Passed</td>
                    </tr>
                    <tr>
                        <td>Fairly Satisfactory</td>
                        <td>75–79</td>
                        <td>Passed</td>
                    </tr>
                    <tr>
                        <td>Did Not Meet Expectations</td>
                        <td>Below 75</td>
                        <td>Failed</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- RIGHT SIDE: Report on Learner’s Observed Values --}}
        <div class="column">
            <p class="center" style="font-size: 14px; font-weight: bold; margin-top: 43px; margin-bottom: 15px;">
                REPORT ON LEARNER’S OBSERVED VALUES</p>
            <table class="values-table" style="font-size: 13px">
                <tr>
                    <th rowspan="2">Core Values</th>
                    <th rowspan="2">Behavior Statements</th>
                    <th colspan="4" class="center" style="height: 10px;">Quarter</th>
                </tr>
                <tr>
                    <th class="center">1</th>
                    <th class="center">2</th>
                    <th class="center">3</th>
                    <th class="center">4</th>
                </tr>
                <tr>
                    <td>1. Maka-Diyos</td>
                    <td>Expresses one’s spiritual beliefs while respecting the spiritual beliefs of others</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>2. Makatao</td>
                    <td>Shows adherence to ethical principles by upholding truth</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>3. Maka-kalikasan</td>
                    <td>Cares for the environment and utilizes resources wisely, judiciously, and economically</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>4. Makabansa</td>
                    <td>Demonstrates pride in being a Filipino; exercises the rights and responsibilities of a Filipino
                        citizen</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

            <div class="spacing markings-table">
                <table class="non-numerical-rating no-border" style="font-size: 13px">
                    <tr>
                        <th>Marking</th>
                        <th>Non-Numerical Rating</th>
                    </tr>
                    <tr>
                        <td>AO</td>
                        <td>Always Observed</td>
                    </tr>
                    <tr>
                        <td>SO</td>
                        <td>Sometimes Observed</td>
                    </tr>
                    <tr>
                        <td>RO</td>
                        <td>Rarely Observed</td>
                    </tr>
                    <tr>
                        <td>NO</td>
                        <td>Not Observed</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
