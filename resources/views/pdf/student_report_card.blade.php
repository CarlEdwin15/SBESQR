<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <title>SF9 - {{ $student->getFullNameAttribute() }}</title>
    <style>
        @page {
            size: letter landscape;
            margin: 10mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #111;
        }

        .sheet {
            display: grid;
            grid-template-columns: 1fr 1fr;
            /* left + right portrait */
            gap: 10mm;
        }

        .column {
            padding: 5mm;
            box-sizing: border-box;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .no-border td,
        .no-border th {
            border: none;
        }

        .attendance-table {
            font-size: 9px;
            /* smaller text */
            table-layout: fixed;
            /* equal column widths */
        }

        .attendance-table th,
        .attendance-table td {
            padding: 2px;
            /* tighter spacing */
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="sheet">

        {{-- FRONT PAGE --}}
        <div class="sheet">

            {{-- LEFT SIDE (Attendance + Signatures) --}}
            <div class="column">
                <table class="attendance-table">
                    <tr>
                        <th colspan="13" class="center">Attendance Record</th>
                    </tr>
                    <tr>
                        <th></th>
                        @foreach (['Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'Total'] as $m)
                            <th>{{ $m }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <td>No. of School Days</td>
                        @foreach (range(1, 12) as $i)
                            <td></td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>No. of Days Present</td>
                        @foreach (range(1, 12) as $i)
                            <td></td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>No. of Times Absent</td>
                        @foreach (range(1, 12) as $i)
                            <td></td>
                        @endforeach
                    </tr>
                </table>

                <br><br>
                <div class="bold">PARENT/GUARDIAN’S SIGNATURE</div>
                <p>1st Quarter ____________________________</p>
                <p>2nd Quarter ____________________________</p>
                <p>3rd Quarter ____________________________</p>
                <p>4th Quarter ____________________________</p>
            </div>

            {{-- RIGHT SIDE (DepEd Info + Student Details) --}}
            <div class="column">
                <div class="center bold">
                    Republic of the Philippines<br>
                    DEPARTMENT OF EDUCATION
                </div>

                <p>Region: Region V</p>
                <p>Division: Schools Division Office of Camarines Sur</p>
                <p>School: STA. BARBARA ELEMENTARY SCHOOL</p>

                <h3 class="center">
                    LEARNER’S PROGRESS REPORT CARD<br>
                    School Year 2025-2026
                </h3>

                <p>
                    Name: Conde, Carl Edwin Vasquez &nbsp;
                    Age: 22 &nbsp;
                    Sex: Male
                </p>
                <p>
                    Grade: Kindergarten &nbsp;
                    Section: A &nbsp;
                    LRN: 112828090080
                </p>

                <p>Dear Parent,</p>
                <p>
                    This report card shows the ability and the progress your child has made in the different learning
                    areas
                    as well as his/her progress in core values.
                </p>

                <br><br>
                <p>_________________________ Teacher</p>
                <p>_________________________ Head Teacher/Principal</p>

                <br><br>
                <div class="bold">Certificate of Transfer</div>
                <p>Admitted to Grade ______ Section ______ Room ______</p>
                <p>Eligible for Admission to Grade ______</p>
                <p>Approved: __________________ Teacher</p>
                <p>_________________________ Head Teacher/Principal</p>

                <br>
                <div class="bold">Cancellation of Eligibility to Transfer</div>
                <p>Admitted in __________________</p>
                <p>Date: __________________</p>
                <p>_________________________ Principal</p>
            </div>

        </div>

        {{-- BACK PAGE --}}
        <div class="column">
            <h3 class="center">Grade {{ optional($class)->formatted_grade_level ?? '' }}</h3>
            <h4 class="center">Report on Learning Progress and Achievement</h4>

            {{-- Learning Areas --}}
            <table>
                <tr>
                    <th rowspan="2">Learning Areas</th>
                    <th colspan="4">Quarter</th>
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

            <br>
            {{-- Core Values --}}
            <h4 class="center">Report on Learner’s Observed Values</h4>
            <table>
                <tr>
                    <th>Core Values</th>
                    <th>Behavior Statements</th>
                    <th colspan="4">Quarter</th>
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
                    <td>Cares for the environment and utilizes resources wisely</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>4. Makabansa</td>
                    <td>Demonstrates pride in being a Filipino; exercises rights and responsibilities</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

            <br>
            <p class="bold">Marking:</p>
            <p>AO - Always Observed &nbsp;&nbsp; SO - Sometimes Observed &nbsp;&nbsp; RO - Rarely Observed &nbsp;&nbsp;
                NO - Not Observed</p>
        </div>

    </div>
</body>

</html>
