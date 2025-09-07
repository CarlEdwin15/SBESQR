<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary of Quarterly Grades</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
        }

        .meta {
            width: 100%;
            margin-bottom: 15px;
        }

        .meta td {
            padding: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
            font-size: 11px;
        }

        th {
            background: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Summary of Quarterly Grades</h2>
    </div>

    <table class="meta">
        <tr>
            <td><strong>Region:</strong> V</td>
            <td><strong>Division:</strong> Camarines Sur</td>
            <td><strong>District:</strong> Nabua East</td>
        </tr>
        <tr>
            <td><strong>School Name:</strong> Sta. Barbara Elementary School</td>
            <td><strong>School Year:</strong> {{ $schoolYear->school_year }}</td>
            <td><strong>Subject:</strong> {{ $classSubject->subject->name }}</td>
        </tr>
        <tr>
            <td><strong>Grade & Section:</strong> {{ $class->formatted_grade_level }} - {{ $class->section }}</td>
            <td colspan="2"><strong>Teacher:</strong> {{ $classSubject->teacher->name }}</td>
        </tr>
    </table>

    <h4>Male</h4>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Learner’s Name</th>
                <th>1st Quarter</th>
                <th>2nd Quarter</th>
                <th>3rd Quarter</th>
                <th>4th Quarter</th>
                <th>Final Grade</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php $maleIndex = 1; @endphp
            @foreach ($students->where('gender', 'Male')->sortBy('student_lName') as $student)
                @php
                    $grades = [];
                    foreach ([1, 2, 3, 4] as $q) {
                        $grades[$q] = $student->quarterlyGrades->firstWhere('quarter.quarter', $q)->final_grade ?? '';
                    }
                    $valid = array_filter($grades);
                    $final = count($valid) ? round(array_sum($valid) / count($valid)) : '';
                    $remarks = $final ? ($final >= 75 ? 'PASSED' : 'FAILED') : '';
                @endphp
                <tr>
                    <td>{{ $maleIndex++ }}</td>
                    <td>{{ $student->student_lName }}, {{ $student->student_fName }} {{ $student->student_mName }}</td>
                    <td>{{ $grades[1] }}</td>
                    <td>{{ $grades[2] }}</td>
                    <td>{{ $grades[3] }}</td>
                    <td>{{ $grades[4] }}</td>
                    <td>{{ $final }}</td>
                    <td>{{ $remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Female</h4>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Learner’s Name</th>
                <th>1st Quarter</th>
                <th>2nd Quarter</th>
                <th>3rd Quarter</th>
                <th>4th Quarter</th>
                <th>Final Grade</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php $femaleIndex = 1; @endphp
            @foreach ($students->where('gender', 'Female')->sortBy('student_lName') as $student)
                @php
                    $grades = [];
                    foreach ([1, 2, 3, 4] as $q) {
                        $grades[$q] = $student->quarterlyGrades->firstWhere('quarter.quarter', $q)->final_grade ?? '';
                    }
                    $valid = array_filter($grades);
                    $final = count($valid) ? round(array_sum($valid) / count($valid)) : '';
                    $remarks = $final ? ($final >= 75 ? 'PASSED' : 'FAILED') : '';
                @endphp
                <tr>
                    <td>{{ $femaleIndex++ }}</td>
                    <td>{{ $student->student_lName }}, {{ $student->student_fName }} {{ $student->student_mName }}
                    </td>
                    <td>{{ $grades[1] }}</td>
                    <td>{{ $grades[2] }}</td>
                    <td>{{ $grades[3] }}</td>
                    <td>{{ $grades[4] }}</td>
                    <td>{{ $final }}</td>
                    <td>{{ $remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
