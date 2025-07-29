<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        td, th {
            border: 1px solid #000;
            padding: 3px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <h3>SF2 Export for {{ $class->formatted_grade_level }} - {{ $class->section }}</h3>
    <p>School Year: {{ $selectedYear }}</p>
    <p>Month: {{ \Carbon\Carbon::createFromFormat('Y-m', $monthParam)->format('F Y') }}</p>

    {{-- Paste the attendance table here from your original view --}}
    {{-- Example placeholder: --}}

</body>
</html>
