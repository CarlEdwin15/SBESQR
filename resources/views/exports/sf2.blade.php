<!DOCTYPE html>
<html>

{{-- <head>
    <meta charset="UTF-8">
    <style>
        * {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 2px;
            text-align: center;
        }

        .text-left {
            text-align: left;
            padding-left: 5px;
        }

        .bold {
            font-weight: bold;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head> --}}

<body>

    {{-- <table style="border: none; width: 100%; font-size: 10px;">
        <tr>
            <td colspan="{{ count($calendarDates) + 5 }}" class="bold text-center" style="font-size: 14px;">
                SCHOOL FORM 2 (SF2) DAILY ATTENDANCE REPORT OF LEARNERS
            </td>
        </tr>
        <tr>
            <td colspan="3">School ID: <b>112828</b></td>
            <td colspan="5">School Year: <b>{{ $selectedYear }}</b></td>
            <td colspan="3">Grade Level: <b>{{ $class->formatted_grade_level }}</b></td>
            <td colspan="4">Section: <b>{{ $class->section }}</b></td>
        </tr>
        <tr>
            <td colspan="{{ count($calendarDates) + 5 }}">Name of School: <b>Sta. Barbara ES</b></td>
        </tr>
        <tr>
            <td colspan="{{ count($calendarDates) + 5 }}">
                Report for the Month of: <b>{{ \Carbon\Carbon::createFromFormat('Y-m', $monthParam)->format('F') }}</b>
            </td>
        </tr>
    </table> --}}

    {{-- Attendance Table --}}
    <table>
        {{-- <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2">NAME<br><small>(Last Name, First Name, Middle Name)</small></th>
                @foreach ($calendarDates as $date)
                    <th>
                        {{ \Carbon\Carbon::parse($date)->format('D') }}<br>
                        {{ \Carbon\Carbon::parse($date)->format('j') }}
                    </th>
                @endforeach
                <th rowspan="2">ABSENT</th>
                <th rowspan="2">PRESENT</th>
                <th rowspan="2" style="width: 130px;">REMARKS</th>
            </tr>
        </thead> --}}
        <tbody>
            {{-- @php $no = 1; @endphp --}}

            {{-- @foreach ($students as $student)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td class="text-left">
                        {{ $student->student_lName }}, {{ $student->student_fName }}
                        {{ $student->student_mName ? strtoupper(substr($student->student_mName, 0, 1)) . '.' : '' }}
                    </td>

                    @foreach ($calendarDates as $date)
                        <td>
                            @php
                                $symbols = $attendanceData[$student->id]['by_date'][$date] ?? [];
                                $symbolStr = collect($symbols)->pluck('status')->implode(' ');
                            @endphp
                            {{ $symbolStr ?: '-' }}
                        </td>
                    @endforeach

                    <td>{{ $attendanceData[$student->id]['absent'] }}</td>
                    <td>{{ $attendanceData[$student->id]['present'] }}</td>
                    <td></td>
                </tr>
            @endforeach --}}

            {{-- Gender and Combined Totals --}}
            {{-- <tr class="bold">
                <td></td>
                <td class="text-left">MALE | TOTAL PER DAY</td>
                @foreach ($maleTotals as $val)
                    <td>{{ $val }}</td>
                @endforeach
                <td>{{ $maleTotalAbsent }}</td>
                <td>{{ $maleTotalPresent }}</td>
                <td></td>
            </tr>
            <tr class="bold">
                <td></td>
                <td class="text-left">FEMALE | TOTAL PER DAY</td>
                @foreach ($femaleTotals as $val)
                    <td>{{ $val }}</td>
                @endforeach
                <td>{{ $femaleTotalAbsent }}</td>
                <td>{{ $femaleTotalPresent }}</td>
                <td></td>
            </tr>
            <tr class="bold">
                <td></td>
                <td class="text-left">Combined TOTAL PER DAY</td>
                @foreach ($combinedTotals as $val)
                    <td>{{ $val }}</td>
                @endforeach
                <td>{{ $totalAbsent }}</td>
                <td>{{ $totalPresent }}</td>
                <td></td>
            </tr> --}}
        </tbody>
    </table>

    <br>

    {{-- Summary Footer --}}
    {{-- <table style="width: 100%; margin-top: 20px;" class="no-border">
        <tr>
            <td class="no-border" colspan="4">
                <b>I certify that this is a true and correct report.</b><br><br>
                {{ strtoupper(Auth::user()->name ?? 'Adviser Name') }}<br>
                (Signature of Adviser over Printed Name)
            </td>
            <td class="no-border" colspan="4">
                <b>Attested by:</b><br><br>
                MELANIE PRADES AGNAS<br>
                (Signature of School Head over Printed Name)
            </td>
        </tr>
    </table> --}}

</body>

</html>
