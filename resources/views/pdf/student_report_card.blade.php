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

        .page {
            width: 100%;
            height: 100%;
            display: table;
            table-layout: fixed;
            page-break-after: always;
            justify-content: space-between;
        }

        .column {
            display: table-cell;
            width: 49%;
            vertical-align: top;
            padding-left: 2mm;
            /* normal outer space */
            padding-right: 12mm;
            /* ✅ more space toward the middle */
            box-sizing: border-box;
        }

        .column:last-child {
            padding-left: 15mm;
            /* ✅ more space toward the middle */
            padding-right: 5mm;
            /* normal outer space */
        }

        h3,
        h4,
        h5,
        p {
            margin: 2px 0;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
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

        .no-border th,
        .no-border td {
            border: none;
        }

        .attendance-table {
            text-align: center;
            margin-bottom: 15px;
        }

        .attendance-table th {
            background-color: #b3c6ff;
            color: #000;
            font-weight: bold;
        }

        .signature-block {
            margin-top: 20px;
            font-size: 11px;
        }

        .signature-line {
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .spacing {
            margin-top: 15px;
        }

        .small {
            font-size: 9px;
        }

        .right {
            text-align: right;
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

        table.no-border td,
        table.no-border th {
            border: none;
        }

        .attendance-table .attendance-row td {
            height: 28px;
        }

        .signature-block .line {
            margin-left: 30px;
            /* adjust spacing: try 8–15px */
            display: inline-block;
        }

        .signature-block p {
            margin-bottom: 15px;
            /* Adjust spacing between each signature line */
        }

        .signature-block p.bold {
            margin-bottom: 6px;
            /* Slightly smaller margin after the title line */
        }

        /* Adjust spacing below "Attendance Record" label */
        .attendance-title {
            margin-bottom: 13px;
            /* Increase or decrease as needed */
        }

        /* Adjust spacing below "PARENT/GUARDIAN’S SIGNATURE" label */
        .signature-block .bold.center {
            margin-bottom: 18px !important;
            /* Make spacing more distinct */
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
                <table class="attendance-table">

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

                <!-- Row 2: Age and Sex (underline extends fully to the same right edge as Name row) -->
                <tr>
                    <td style="width: 8%;">Age:</td>
                    <td style="border-bottom: 1px solid #000; width: 15%;"></td>
                    <td style="width: 8%;">Sex:</td>
                    <td colspan="3" style="border-bottom: 1px solid #000; width: 69%;"></td>
                </tr>

                <!-- Row 3: Grade, Section, and LRN -->
                <tr>
                    <td style="width: 8%;">Grade:</td>
                    <td style="border-bottom: 1px solid #000; width: 10%;"></td>
                    <td style="width: 8%;">Section:</td>
                    <td style="border-bottom: 1px solid #000; width: 15%;"></td>
                    <td style="width: 6%;">LRN:</td>
                    <td style="border-bottom: 1px solid #000; width: 20%;"></td>
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
            <h3 class="center">Grade {{ optional($class)->formatted_grade_level ?? '' }}</h3>
            <h4 class="center">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</h4>

            <table>
                <tr>
                    <th rowspan="2">Learning Areas</th>
                    <th colspan="4" class="center">Quarter</th>
                    <th rowspan="2">Final Rating</th>
                    <th rowspan="2">Remarks</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
                @foreach ($subjects as $s)
                    <tr>
                        <td>{{ $s['subject'] }}</td>
                        <td class="center">{{ $s['quarters']->firstWhere('quarter', 1)['grade'] ?? '' }}</td>
                        <td class="center">{{ $s['quarters']->firstWhere('quarter', 2)['grade'] ?? '' }}</td>
                        <td class="center">{{ $s['quarters']->firstWhere('quarter', 3)['grade'] ?? '' }}</td>
                        <td class="center">{{ $s['quarters']->firstWhere('quarter', 4)['grade'] ?? '' }}</td>
                        <td class="center">{{ $s['final'] ?? '' }}</td>
                        <td class="center">{{ $s['remarks'] ?? '' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="bold">General Average</td>
                    <td colspan="4"></td>
                    <td class="center bold">{{ $generalAverage ?? '' }}</td>
                    <td class="center">{{ $generalRemarks ?? '' }}</td>
                </tr>
            </table>

            <div class="spacing">
                <table class="no-border small">
                    <tr>
                        <th class="center">Descriptors</th>
                        <th class="center">Grading Scale</th>
                        <th class="center">Remarks</th>
                    </tr>
                    <tr>
                        <td>Outstanding</td>
                        <td class="center">90–100</td>
                        <td>Passed</td>
                    </tr>
                    <tr>
                        <td>Very Satisfactory</td>
                        <td class="center">85–89</td>
                        <td>Passed</td>
                    </tr>
                    <tr>
                        <td>Satisfactory</td>
                        <td class="center">80–84</td>
                        <td>Passed</td>
                    </tr>
                    <tr>
                        <td>Fairly Satisfactory</td>
                        <td class="center">75–79</td>
                        <td>Passed</td>
                    </tr>
                    <tr>
                        <td>Did Not Meet Expectations</td>
                        <td class="center">Below 75</td>
                        <td>Failed</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- RIGHT SIDE: Report on Learner’s Observed Values --}}
        <div class="column">
            <h4 class="center">REPORT ON LEARNER’S OBSERVED VALUES</h4>
            <table>
                <tr>
                    <th>Core Values</th>
                    <th>Behavior Statements</th>
                    <th colspan="4" class="center">Quarter</th>
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

            <div class="spacing small markings-table">
                <table class="no-border small">
                    <tr>
                        <th class="center" style="width: 40%;">Marking</th>
                        <th class="center">Non-Numerical Rating</th>
                    </tr>
                    <tr>
                        <td class="center">AO</td>
                        <td>Always Observed</td>
                    </tr>
                    <tr>
                        <td class="center">SO</td>
                        <td>Sometimes Observed</td>
                    </tr>
                    <tr>
                        <td class="center">RO</td>
                        <td>Rarely Observed</td>
                    </tr>
                    <tr>
                        <td class="center">NO</td>
                        <td>Not Observed</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
