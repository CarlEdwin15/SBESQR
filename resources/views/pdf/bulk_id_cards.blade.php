<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bulk Student IDs</title>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" />

    <style>
        /* Page */
        @page {
            size: A4 landscape;
            margin: 0.5cm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            -webkit-print-color-adjust: exact;
        }

        /* Container for each printed page */
        .page {
            width: 100%;
            margin: 0 auto;
            padding-top: 0.2cm;
            padding-left: 0.5cm;
            padding-right: 0.5cm;
            box-sizing: border-box;
            page-break-after: auto !important;
            page-break-inside: avoid !important;
        }

        tr {
            page-break-inside: avoid !important;
        }

        /* Grid with 2 columns (each column holds one student pair = front+back) and 2 rows */
        .grid-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0.4cm 0.4cm;
            /* vertical horizontal spacing between cells */
            margin: 0 auto;
            text-align: center;
        }

        /* Each cell holds a front+back pair */
        .id-card-pair {
            width: 50%;
            vertical-align: top;
            page-break-inside: avoid;
            padding: 0;
        }

        .id-card-pair.empty {
            visibility: hidden;
        }

        /* container holding the two cards (front + back) horizontally */
        .card-container {
            display: flex;
            justify-content: center;
            /* center front+back within cell */
            gap: 0.5cm;
            /* gap between front and back card */
        }

        /*
         * IMPORTANT: Use exact original ID size converted to cm:
         * 2.125in  -> 2.125 * 2.54 = 5.3975 cm
         * 3.375in  -> 3.375 * 2.54 = 8.5725 cm
         */
        .card {
            width: 2.125in;
            height: 3.375in;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            box-sizing: border-box;
            padding: 6px;
            border: 1px solid #abd8fe;
            position: relative;
            display: inline-block;
            vertical-align: top;
            overflow: hidden;
        }

        /* images & QR adjusted in cm so proportions remain the same */
        .school-logo {
            height: 70px;
            width: 70px;
            display: block;
            margin: 0 auto 3px;
        }

        .id-img {
            width: 3.81cm;
            /* 1.5in */
            height: 3.81cm;
            object-fit: cover;
            border: 1px solid #0190d2;
            border-radius: 2px;
            display: block;
            margin: 0.12cm auto;
        }

        .qr img {
            width: 3cm;
            height: auto;
            display: block;
            margin: 0.15cm 0 0 0;
            border: 1px solid #000;
            padding: 5px;

        }

        .lrn {
            font-size: 11px;
            margin: 0.02cm auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6px;
            margin-top: 0.05cm;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            height: 0.30cm;
            text-align: center;
            padding: 2px;
        }

        /* text sizes tuned for print */
        .title {
            font-size: 12px;
            margin-bottom: 2px;
        }

        .small-text {
            font-size: 8px;
            line-height: 1.05;
        }

        .med-text {
            font-size: 9px;
        }

        .lrg-text {
            font-size: 10px;
        }

        .xl-text {
            font-size: 11px;
        }

        .fw-bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        .text-start {
            text-align: left;
        }

        .student-name {
            font-size: 12px;
            margin: 2px 0;
            min-height: 1.6em;
        }

        /* small tweaks to ensure consistent vertical spacing inside card */
        .card p {
            margin: 0.06cm 0;
            padding: 0;
        }

        .card .meta {
            margin-top: 0.08cm;
        }
    </style>
</head>

<body>
    @php
        $studentsPerPage = 4;
        $totalStudents = count($studentsWithQr);
        $totalPages = ceil($totalStudents / $studentsPerPage);
    @endphp

    @for ($page = 0; $page < $totalPages; $page++)
        @php
            $pageStudents = array_slice($studentsWithQr, $page * $studentsPerPage, $studentsPerPage);
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
                                    @php
                                        $studentData = $pageStudents[$index];
                                        $student = $studentData['student'];
                                        $qrCode = $studentData['qrCode'];

                                        $photoPath =
                                            $student->student_photo &&
                                            file_exists(public_path('uploads/' . $student->student_photo))
                                                ? public_path('uploads/' . $student->student_photo)
                                                : public_path(
                                                    'assetsDashboard/img/student_profile_pictures/student_default_profile.jpg',
                                                );

                                        $parent = $student->parents->first();
                                    @endphp

                                    <td class="id-card-pair">
                                        <div class="card-container">
                                            {{-- FRONT SIDE --}}
                                            <div class="card text-center"
                                                style="background-image: url('{{ public_path('assetsDashboard/img/id_layout/front_bg_id.png') }}');">

                                                <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo"
                                                    class="school-logo">

                                                <div class="fw-bold uppercase title">Sta. Barbara Elementary School
                                                </div>
                                                <div class="med-text">Sta. Barbara, Nabua, Camarines Sur</div>

                                                <img src="{{ $photoPath }}" alt="Student Photo" class="id-img">

                                                <div class="fw-bold uppercase student-name">
                                                    {{ $student->student_fName }}
                                                    {{ $student->student_mName ? ' ' . $student->student_mName : '' }}
                                                    {{ $student->student_lName }}
                                                    {{ $student->student_extName ? ' ' . $student->student_extName : '' }}
                                                </div>

                                                <div class="small-text meta">
                                                    <p><strong>Date of Birth:</strong>
                                                        {{ \Carbon\Carbon::parse($student->student_dob)->format('F j, Y') }}
                                                    </p>
                                                    <p>
                                                        <strong>Address:</strong>
                                                        {{ $student->address->house_no ?? 'N/A' }},
                                                        {{ $student->address->street_name ?? 'N/A' }},
                                                        {{ $student->address->barangay ?? 'N/A' }},
                                                        {{ $student->address->municipality_city ?? 'N/A' }},
                                                        {{ $student->address->province ?? 'N/A' }},
                                                        {{ $student->address->zip_code ?? 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- BACK SIDE --}}
                                            <div class="card text-start"
                                                style="background-image: url('{{ public_path('assetsDashboard/img/id_layout/back_bg_id.png') }}');">

                                                <div class="fw-bold text-center med-text">IN CASE OF EMERGENCY PLEASE
                                                    NOTIFY</div>

                                                <div class="small-text parent-info" style="margin-top:0.08cm;">
                                                    <p>Name:
                                                        <span class="fw-bold">
                                                            {{ $parent ? $parent->firstName . ' ' . ($parent->middleName ? $parent->middleName . ' ' : '') . $parent->lastName : 'N/A' }}
                                                        </span>
                                                    </p>
                                                    <p>Address:
                                                        <span class="fw-bold">
                                                            {{ $parent && $parent->barangay ? $parent->barangay . ', ' . $parent->municipality_city . ', ' . $parent->province : 'N/A' }}
                                                        </span>
                                                    </p>
                                                    <p>Contact No.:
                                                        <span class="fw-bold">
                                                            {{ $parent->phone ?? 'N/A' }}
                                                        </span>
                                                    </p>
                                                </div>

                                                <table class="table text-center">
                                                    <thead>
                                                        <tr>
                                                            <th class="small-text">School Year</th>
                                                            <th class="small-text">Signature</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @for ($i = 0; $i < 7; $i++)
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        @endfor
                                                    </tbody>
                                                </table>

                                                <div class="qr text-center">
                                                    <img src="data:image/svg+xml;base64,{{ $qrCode }}"
                                                        alt="QR Code">
                                                </div>

                                                <p class="lrn text-center">LRN:
                                                    <strong>{{ $student->student_lrn }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td class="id-card-pair empty"></td>
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
