<table>
    <tr>
        <td colspan="9" style="font-weight: bold;">School Form 2 (SF2) – Daily Attendance Report of Learners</td>
    </tr>
    <tr>
        <td colspan="5">School: {{ $school_name }}</td>
        <td colspan="4">School Year: {{ $school_year }}</td>
    </tr>
    <tr>
        <td colspan="5">Grade Level: {{ $grade_level }}</td>
        <td colspan="4">Section: {{ $section }}</td>
    </tr>
    <tr><td colspan="9"></td></tr>

    <thead>
        <tr>
            <th>No.</th>
            <th>NAME<br>Last Name, First Name, Middle Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Grade Level</th>
            <th>Phone</th>
            <th>Address</th>
        </tr>
    </thead>
    <tbody>
        @foreach($teachers as $index => $teacher)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $teacher->lastName }}, {{ $teacher->firstName }}, {{ $teacher->middleName }}</td>
            <td>{{ $teacher->email }}</td>
            <td>{{ $teacher->gender }}</td>
            <td>{{ $teacher->grade_level_assigned }}</td>
            <td>{{ $teacher->phone }}</td>
            <td>{{ $teacher->address }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
