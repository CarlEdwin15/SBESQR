<?php

namespace App\Http\Controllers;

use App\Models\Student;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\school_id;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class IdController extends Controller
{

    public function generateID($id)
    {
        $student = Student::findOrFail($id);
        return view('admin.students.generate_id', compact('student'));
    }

    public function downloadID($id)
    {
        $student = Student::findOrFail($id);

        // Create QR Code as base64 SVG for PDF
        $qrCode = base64_encode(
            QrCode::format('svg')
                ->size(150)
                ->generate(json_encode(['route' => 'student.info', 'id' => $student->id]))
        );

        // 2.125in = 153.75pt, 3.375in = 243.75pt (width x height in portrait)
        $customPaper = [0, 0, 153.75, 243.75];

        $pdf = Pdf::loadView('pdf.id_card', compact('student', 'qrCode'))
            ->setPaper($customPaper, 'portrait');

        return $pdf->download($student->student_lName . '_ID.pdf');
    }

    // public function downloadID($id)
    // {
    //     $student = Student::findOrFail($id);

    //     // Create QR Code as base64 PNG to avoid Imagick and render it in PDF
    //     $qrCode = base64_encode(
    //         QrCode::format('svg')
    //             ->size(150)
    //             ->generate(route('student.info', ['id' => $student->id]))
    //     );

    //     // $pdf = Pdf::loadView('pdf.student_id', compact('student', 'qrCode'))
    //     //     ->setPaper('3.375in', '2.125in', 'portrait'); // Set paper size to ID card size

    //     $pdf = Pdf::loadView('pdf.student_id', compact('student', 'qrCode'))
    //         ->setPaper('a4', 'portrait');



    //     return $pdf->download($student->last_name . '_ID.pdf');
    // }



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

    /**
     * Display the specified resource.
     */
    public function show(school_id $school_id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(school_id $school_id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, school_id $school_id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(school_id $school_id)
    {
        //
    }
}
