<?php

namespace App\Http\Controllers;

use App\Models\Student;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\school_id;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class IdController extends Controller
{

    public function generateID(Request $request, $id)
    {
        // Store the previous URL, but avoid self-loop
        $previous = url()->previous();
        if ($previous !== $request->fullUrl()) {
            session(['back_url' => $previous]);
        }

        $student = Student::findOrFail($id);
        return view('admin.students.generate_id', compact('student'));
    }

    public function previewID($id)
    {
        $student = Student::findOrFail($id);

        $qrCode = base64_encode(
            QrCode::format('svg')
                ->size(150)
                ->generate(json_encode(['route' => 'student.info', 'id' => $student->id]))
        );

        $pdf = Pdf::loadView('pdf.id_card', compact('student', 'qrCode'))
            ->setPaper('A4', 'portrait');

        $filename = "{$student->student_fName}_{$student->student_lName}_ID.pdf";
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
