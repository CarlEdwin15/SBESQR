<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Student ID</title>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" />

    <style>
        @page {
            size: A4 portrait;
            margin: 1cm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .a4-page {
            width: 100%;
            height: 100%;
            padding: 1cm;
            box-sizing: border-box;
        }

        .id-row {
            display: flex;
            flex-wrap: nowrap;
            justify-content: center;
            gap: 5cm;
            margin-top: 1rem;
        }

        /* FIXED: These were missing dots (.) */
        .d-flex {
            display: flex;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .justify-content-center {
            justify-content: center;
        }


        .card {
            width: 2.125in;
            height: 3.375in;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            justify-content: center;
            box-sizing: border-box;
            padding: 6px;
            position: relative;
            display: inline-block;
            /* flex-direction: column; */
            align-items: center;
            text-align: center;
            margin: auto;
            margin-bottom: 10px;
            border: 1px solid #abd8fe;
        }

        .card-name {
            text-align: start;
        }

        .school-logo {
            height: 70px;
            width: 70px;
            margin: 0 auto 3px;
        }

        .id-img {
            width: 1.5in;
            height: 1.5in;
            object-fit: cover;
            border: 1px solid #0190d2;
            border-radius: 2px;
            margin: 4px auto;
        }

        .qr {
            width: 1.1in;
            height: 1.1in;
            margin: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.5px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            height: 10px;
            text-align: center;
            padding: 1px;
        }

        .small-text {
            font-size: 7px;
            margin: 1px 0;
        }

        .med-text {
            font-size: 8px;
        }

        .lrg-text {
            font-size: 10px;
        }

        .title {
            font-size: 12px;
            margin-bottom: 2px;
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
            text-align: start;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .gap-4 {
            gap: 1rem;
        }

        .mt-1 {
            margin-top: 0.5rem;
        }
    </style>

</head>

<body>
    <div class="a4-page">
        <div class="id-row">
            {{-- FRONT SIDE --}}
            <div class="card"
                style="background-image: url('{{ public_path('assetsDashboard/img/id_layout/front_bg_id.png') }}');">

                <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo" class="school-logo">

                <div class="fw-bold uppercase title">Sta. Barbara Elementary School</div>
                <div class="med-text">Sta. Barbara, Nabua, Camarines Sur</div>

                @php
                    $photoPath =
                        $student->student_photo && file_exists(public_path('storage/' . $student->student_photo))
                            ? public_path('storage/' . $student->student_photo)
                            : public_path('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg');
                @endphp
                <img src="{{ $photoPath }}" alt="Student Photo" class="id-img">

                <div class="fw-bold uppercase med-text">
                    {{ $student->student_fName }} {{ $student->student_mName }} {{ $student->student_lName }}
                </div>

                <div class="small-text">
                    <p><strong>Date of Birth:</strong>
                        {{ \Carbon\Carbon::parse($student->student_dob)->format('F j, Y') }}</p>
                    <p><strong>Address:</strong> {{ $student->address->barangay }}</p>
                </div>
            </div>

            {{-- BACK SIDE --}}
            <div class="card"
                style="background-image: url('{{ public_path('assetsDashboard/img/id_layout/back_bg_id.png') }}');">

                <div class="fw-bold" style="font-size: 9px">IN CASE OF EMERGENCY PLEASE NOTIFY</div>

                <div class="card-name med-text text-start">
                    <p>Name: <strong>{{ $student->parentInfo->emergcont_fName ?? 'N/A' }}
                            {{ $student->parentInfo->emergcont_lName ?? '' }}</strong></p>
                    <p>Address: <strong>{{ $student->parentInfo->barangay ?? 'N/A' }},
                            {{ $student->parentInfo->municipality_city ?? 'N/A' }},
                            {{ $student->parentInfo->province ?? 'N/A' }}</strong></p>
                    <p>Contact No.: <strong>{{ $student->parentInfo->emergcontPhone ?? 'N/A' }}</strong></p>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>School Year</th>
                            <th>Signature</th>
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

                <div class="qr mt-1 mb-3">
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" style="width: 100%; height: auto; border: 1px solid #000; padding: 5px"
                        alt="QR Code">
                </div>

                <p class="text-center med-text mt-1">LRN:<strong> {{ $student->student_lrn }}</strong></p>
            </div>
        </div>
    </div>
</body>


</html>
