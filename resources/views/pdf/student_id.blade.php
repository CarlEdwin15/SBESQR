<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Student ID</title>
    <style>
        @page {
            size: 2.125in 3.375in;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card {
            width: 2.125in;
            height: 3.375in;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            box-sizing: border-box;
            position: relative;
            padding: 6px;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .school-logo {
            width: 28px;
            margin: 0 auto 3px;
        }

        .id-img {
            width: 1in;
            height: 1.1in;
            object-fit: cover;
            border: 1px solid #000;
            border-radius: 2px;
            margin: 4px auto;
        }

        .qr {
            width: 1.1in;
            height: 1.1in;
            margin: 4px auto;
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

        .title {
            font-size: 9px;
            margin-bottom: 2px;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- FRONT SIDE --}}
        <div class="card text-center"
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
                <p><strong>DOB:</strong> {{ \Carbon\Carbon::parse($student->student_dob)->format('F j, Y') }}</p>
                <p><strong>Address:</strong> Zone {{ $student->address->barangay }}, Sta. Barbara, Nabua, Cam. Sur</p>
            </div>
        </div>

        {{-- BACK SIDE --}}
        <div class="card"
            style="background-image: url('{{ public_path('assetsDashboard/img/id_layout/back_bg_id.png') }}');">

            <div class="text-center fw-bold small-text">IN CASE OF EMERGENCY PLEASE NOTIFY</div>

            <div class="small-text">
                <p>Name: <strong>{{ $student->parentInfo->emergCont_fName ?? 'N/A' }}
                        {{ $student->parentInfo->emergCont_lName ?? '' }}</strong></p>
                <p>Address: <strong>{{ $student->address->barangay ?? 'N/A' }},
                        {{ $student->address->municipality_city ?? 'N/A' }},
                        {{ $student->address->province ?? 'N/A' }}</strong></p>
                <p>Contact No.: <strong>{{ $student->parentInfo->emergCont_phone ?? 'N/A' }}</strong></p>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>School Year</th>
                        <th>Signature</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 6; $i++)
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            <div class="qr">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" style="width: 100%; height: auto;"
                    alt="QR Code">
            </div>

            <p class="text-center med-text"><strong>LRN:</strong> {{ $student->student_lrn }}</p>
        </div>
    </div>
</body>

</html>
