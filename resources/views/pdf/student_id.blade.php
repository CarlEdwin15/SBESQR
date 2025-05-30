<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Student ID</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .gap {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .card {
            width: 320px;
            height: 510px;
            box-sizing: border-box;
            position: relative;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            padding: 16px;
        }

        .id-img {
            width: 2in;
            height: 2in;
            object-fit: cover;
            border: 6px solid #000;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
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

        .mb-1 {
            margin-bottom: 4px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .table th,
        .table td {
            border: 1px solid black;
            text-align: center;
            height: 20px;
        }

        .qr {
            display: inline-block;
            border: 2px solid #000;
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="gap">
        {{-- FRONT SIDE --}}
        <div class="card"
            style="background-image: url('{{ public_path('assetsDashboard/img/id_layout/front_bg_id.png') }}');">
            <div class="text-center mb-1">
                <img src="{{ public_path('assets/img/logo.png') }}" alt="School Logo" style="width: 75px;">
            </div>
            <div class="text-center mb-2">
                <h5 class="mb-1 fw-bold uppercase" style="font-size: 16px;">Sta. Barbara Elementary School</h5>
                <p style="font-size: 12px;">Sta. Barbara, Nabua, Camarines Sur</p>
            </div>

            <div class="text-center mb-2">
                @php
                    $photoPath =
                        $student->student_photo && file_exists(public_path('storage/' . $student->student_photo))
                            ? public_path('storage/' . $student->student_photo)
                            : public_path('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg');
                @endphp
                <img src="{{ $photoPath }}" alt="Student Photo" class="id-img">
            </div>

            <div class="text-center mb-2">
                <h6 class="mb-1 fw-bold uppercase" style="font-size: 14px;">
                    {{ $student->student_fName }} {{ $student->student_mName }} {{ $student->student_lName }}
                </h6>
            </div>

            <div class="text-center" style="font-size: 12px;">
                <p class="mb-1"><strong>Date of Birth:</strong>
                    {{ \Carbon\Carbon::parse($student->student_dob)->format('F j, Y') }}</p>
                <p class="mb-1"><strong>Address:</strong> Zone {{ $student->address->barangay }}, Sta. Barbara, Nabua,
                    Camarines Sur</p>
            </div>
        </div>

        {{-- BACK SIDE --}}
        <div class="card"
            style="background-image: url('{{ public_path('assetsDashboard/img/id_layout/back_bg_id.png') }}');">
            <h6 class="text-center fw-bold mb-2" style="font-size: 10px;">IN CASE OF EMERGENCY PLEASE NOTIFY</h6>

            <div style="font-size: 11px;">
                <p class="mb-1">Name:
                    <strong>{{ $student->parentInfo->emergCont_fName ?? 'N/A' }}
                        {{ $student->parentInfo->emergCont_lName ?? '' }}</strong>
                </p>
                <p class="mb-1">Address:
                    <strong>{{ $student->address->barangay ?? 'N/A' }},
                        {{ $student->address->municipality_city ?? 'N/A' }},
                        {{ $student->address->province ?? 'N/A' }}</strong>
                </p>
                <p class="mb-2">Contact No.:
                    <strong>{{ $student->parentInfo->emergCont_phone ?? 'N/A' }}</strong>
                </p>
            </div>

            {{-- Validation Table --}}
            <table class="table mb-1 mt-1">
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

            {{-- QR Code --}}
            <div class="text-center mt-1 mb-1">
                <div class="qr">
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code"
                        style="height: 190px; width: 190px;">
                </div>
            </div>

            <p class="text-center" style="font-size: 13px;"><strong>LRN:</strong> {{ $student->student_lrn }}</p>
        </div>
    </div>
</body>

</html>
