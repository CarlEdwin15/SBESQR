<?php

namespace App\Http\Controllers;

use App\Models\Student;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\school_id;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class IdController extends Controller
{
    public function previewID($id)
    {
        $student = Student::findOrFail($id);

        // Use the SAME data structure as in student_tabs
        $qrData = json_encode(['student_id' => $student->id]);

        $qrCode = base64_encode(
            QrCode::format('svg')
                ->size(150)
                ->generate($qrData)
        );

        $pdf = Pdf::loadView('pdf.id_card', compact('student', 'qrCode'))
            ->setPaper('A4', 'portrait');

        $filename = "{$student->student_fName}_{$student->student_lName}_ID.pdf";
        return $pdf->stream($filename);
    }

    public function bulkPrintIDs(Request $request)
    {
        $studentIds = $request->input('student_ids', []);

        if (empty($studentIds)) {
            return redirect()->back()->with('error', 'No students selected.');
        }

        // Fetch students in the EXACT ORDER they were selected (same as grade slip)
        $students = Student::whereIn('id', $studentIds)->get();

        // IMPORTANT: Maintain the original selection order (same as grade slip)
        $students = $students->sortBy(function ($student) use ($studentIds) {
            return array_search($student->id, $studentIds);
        });

        // Generate QR codes for all students
        $studentsWithQr = [];
        foreach ($students as $student) {
            $qrData = json_encode(['student_id' => $student->id]);
            $qrCode = base64_encode(
                QrCode::format('svg')
                    ->size(150)
                    ->generate($qrData)
            );

            $studentsWithQr[] = [
                'student' => $student,
                'qrCode' => $qrCode
            ];
        }

        $pdf = Pdf::loadView('pdf.bulk_id_cards', [
            'studentsWithQr' => $studentsWithQr
        ])
            ->setPaper('A4', 'landscape');

        $filename = "bulk_student_ids_" . date('Y-m-d') . ".pdf";
        return $pdf->stream($filename);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
}
