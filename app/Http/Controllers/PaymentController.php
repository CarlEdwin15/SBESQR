<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Classes;
use App\Models\ClassStudent;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function indexAdmin(Request $request)
    {
        $now = now();
        $year = $now->year;

        // Cutoff is June 1 — if before June, school year starts last year; otherwise, current year
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;

        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
        $nextSchoolYear    = ($currentYear + 1) . '-' . ($currentYear + 2);

        // Fetch all saved school years from DB
        $savedYears = \App\Models\SchoolYear::pluck('school_year')->toArray();
        $savedStartYears = array_map(fn($sy) => (int) substr($sy, 0, 4), $savedYears);
        $minYear = !empty($savedStartYears) ? min($savedStartYears) : $currentYear;

        $schoolYears = [];
        for ($y = $minYear; $y <= $currentYear; $y++) {
            $schoolYears[] = $y . '-' . ($y + 1);
        }

        if (!in_array($currentSchoolYear, $schoolYears)) {
            $schoolYears[] = $currentSchoolYear;
        }
        $schoolYears[] = $nextSchoolYear;

        usort($schoolYears, fn($a, $b) => intval(substr($a, 0, 4)) <=> intval(substr($b, 0, 4)));

        // Selected year (default to current school year)
        $selectedYear = $request->query('school_year', $currentSchoolYear);

        // Selected class (optional filter)
        $selectedClass = $request->query('class_id');

        // Get all classes (for dropdown)
        $allClasses = Classes::with('teachers')->get();

        // Query payments for that year and class if selected
        $payments = Payment::with(['classStudent.student', 'classStudent.class', 'classStudent.schoolYear'])
            ->when($selectedYear, function ($query) use ($selectedYear) {
                $query->whereHas('classStudent.schoolYear', function ($q) use ($selectedYear) {
                    $q->where('school_year', $selectedYear);
                });
            })
            ->when($selectedClass, function ($query) use ($selectedClass) {
                $query->whereHas('classStudent', function ($q) use ($selectedClass) {
                    $q->where('class_id', $selectedClass);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Build flash message with school year + optional class
        if ($selectedClass) {
            $class = Classes::find($selectedClass);
            if ($class) {
                $message = 'Displaying Payment Records for '
                    . strtoupper($class->formattedGradeLevel ?? $class->grade_level)
                    . ' - ' . $class->section
                    . ' for School Year ' . $selectedYear;
            } else {
                $message = 'Displaying Payment Records for All Classes for School Year ' . $selectedYear;
            }
        } else {
            $message = 'Displaying Payment Records for All Classes for School Year ' . $selectedYear;
        }

        session()->flash('school_year_notice', $message);


        return view('admin.payments.index', compact(
            'payments',
            'schoolYears',
            'selectedYear',
            'currentYear',
            'allClasses',
            'selectedClass'
        ));
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'school_year'        => 'required|string',
            'payment_name'       => 'required|string|max:255',
            'amount_due'         => 'required|numeric|min:0',
            'due_date'           => 'required|date',
            'class_student_ids'  => 'nullable|array',
            'class_student_ids.*' => 'exists:class_student,id',
            'class_id'           => $request->filled('class_student_ids')
                ? 'nullable|exists:classes,id'
                : 'required|exists:classes,id',
        ]);

        $schoolYear = SchoolYear::where('school_year', $request->school_year)->firstOrFail();

        // Case 1: Payments for specific students
        if ($request->filled('class_student_ids')) {
            foreach ($request->class_student_ids as $csId) {
                $classStudent = ClassStudent::where('id', $csId)
                    ->where('school_year_id', $schoolYear->id)
                    ->where('enrollment_status', 'enrolled')
                    ->first();

                if ($classStudent) {
                    Payment::firstOrCreate(
                        [
                            'class_student_id' => $classStudent->id,
                            'payment_name'     => $request->payment_name,
                        ],
                        [
                            'created_by'   => Auth::id(),
                            'amount_due'   => $request->amount_due,
                            'date_created' => now(),
                            'due_date'     => $request->due_date,
                        ]
                    );
                }
            }
        }
        // Case 2: Payments for entire class
        else {
            $classStudents = ClassStudent::where('class_id', $request->class_id)
                ->where('school_year_id', $schoolYear->id)
                ->where('enrollment_status', 'enrolled')
                ->get();

            foreach ($classStudents as $classStudent) {
                Payment::firstOrCreate(
                    [
                        'class_student_id' => $classStudent->id,
                        'payment_name'     => $request->payment_name,
                    ],
                    [
                        'created_by'   => Auth::id(),
                        'amount_due'   => $request->amount_due,
                        'date_created' => now(),
                        'due_date'     => $request->due_date,
                    ]
                );
            }
        }

        return redirect()->route('admin.payments.index', [
            'school_year' => $request->school_year,
            'class_id'    => $request->class_id,
        ])->with('success', 'Payment(s) created successfully.');
    }


    public function showAdmin(Request $request, $paymentName)
    {
        $selectedYear = $request->query('school_year');
        $selectedClass = $request->query('class_id');

        // Unfiltered query for summary cards
        $baseQuery = Payment::with(['student', 'classStudent.class', 'schoolYear'])
            ->where('payment_name', $paymentName);

        $paidCount = (clone $baseQuery)->where('status', 'paid')->count();
        $partialCount = (clone $baseQuery)->where('status', 'partial')->count();
        $unpaidCount = (clone $baseQuery)->where('status', 'unpaid')->count();
        $totalCount = (clone $baseQuery)->count();

        // Table data (no search/status filter here; JS handles that)
        $payments = (clone $baseQuery)
            ->join('class_student', 'payments.class_student_id', '=', 'class_student.id')
            ->join('students', 'class_student.student_id', '=', 'students.id')
            ->orderByRaw("LOWER(CONCAT(students.student_lName, ' ', students.student_fName, ' ', students.student_mName))")
            ->select('payments.*')
            ->get();

        $first = $payments->first();

        $classes = Classes::all();
        $schoolYears = SchoolYear::all();

        return view('admin.payments.show', compact(
            'payments',
            'first',
            'paidCount',
            'partialCount',
            'unpaidCount',
            'totalCount',
            'paymentName',
            'selectedYear',
            'selectedClass',
            'classes',
            'schoolYears'
        ));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'amount_paid' => 'nullable|numeric|min:0',
        ]);

        $amountPaid = $request->amount_paid ?? 0;
        $amountDue  = $payment->amount_due;

        // Determine status automatically
        if ($amountPaid <= 0) {
            $status   = 'unpaid';
            $datePaid = null;
        } elseif ($amountPaid < $amountDue) {
            $status   = 'partial';
            $datePaid = null; // not fully paid yet
        } else {
            $status   = 'paid';
            $datePaid = now()->toDateString(); // full payment today
        }

        $payment->update([
            'status'      => $status,
            'amount_paid' => $amountPaid,
            'date_paid'   => $datePaid,
        ]);

        return back()->with('success', 'Payment updated successfully.');
    }



    // public function index(Request $request, $grade_level, $section)
    // {
    //     $selectedYear = $request->input('school_year');

    //     $class = Classes::with('students')
    //         ->where('grade_level', $grade_level)
    //         ->where('section', $section)
    //         ->firstOrFail();

    //     // ✅ Fix: resolve the school year id properly
    //     $payments = Payment::where('class_id', $class->id)
    //         ->when($selectedYear, function ($query) use ($selectedYear) {
    //             $query->whereHas('schoolYear', function ($q) use ($selectedYear) {
    //                 $q->where('school_year', $selectedYear); // adjust column name (e.g., "year" or "name")
    //             });
    //         })
    //         ->get();

    //     return view('teacher.classes.payments.index', compact('class', 'payments', 'selectedYear'));
    // }

    // public function show(Request $request, $grade_level, $section, $paymentName)
    // {
    //     $selectedYear = $request->input('school_year');

    //     // find class
    //     $class = Classes::where('grade_level', $grade_level)
    //         ->where('section', $section)
    //         ->firstOrFail();

    //     // decode just in case (Laravel usually decodes already)
    //     $paymentName = urldecode($paymentName);

    //     // fetch payments that match this payment name, for the class, and optionally the school year
    //     $payments = Payment::with('student')
    //         ->where('class_id', $class->id)
    //         ->where('payment_name', $paymentName)
    //         ->when($selectedYear, function ($query) use ($selectedYear) {
    //             $query->whereHas('schoolYear', function ($q) use ($selectedYear) {
    //                 // adjust the column name in the SchoolYear model/table as needed
    //                 $q->where('school_year', $selectedYear);
    //             });
    //         })
    //         ->get();

    //     // if desired, fail if no payments found for given name
    //     // abort(404) OR just show an empty table. I'll allow empty view and pass data.
    //     $first = $payments->first();

    //     $totalStudents = $payments->count();
    //     $paidCount     = $payments->where('status', 'paid')->count();
    //     $partialCount  = $payments->where('status', 'partial')->count();
    //     $unpaidCount   = $payments->where('status', 'unpaid')->count();

    //     return view('teacher.classes.payments.show', compact(
    //         'class',
    //         'selectedYear',
    //         'payments',
    //         'first',
    //         'paymentName',
    //         'totalStudents',
    //         'paidCount',
    //         'partialCount',
    //         'unpaidCount'
    //     ));
    // }

    // public function create(Request $request, $grade_level, $section)
    // {
    //     $request->validate([
    //         'payment_name' => 'required|string|max:255',
    //         'amount_due'   => 'required|numeric|min:0',
    //         'due_date'     => 'required|date',
    //     ]);

    //     $class = Classes::with('students')
    //         ->where('grade_level', $grade_level)
    //         ->where('section', $section)
    //         ->firstOrFail();

    //     foreach ($class->students as $student) {
    //         Payment::firstOrCreate(
    //             [
    //                 'class_id'       => $class->id,
    //                 'school_year_id' => $student->pivot->school_year_id,
    //                 'student_id'     => $student->id,
    //                 'payment_name'   => $request->payment_name,
    //             ],
    //             [
    //                 'created_by'   => Auth::id(),
    //                 'amount_due'   => $request->amount_due,
    //                 'date_created' => now()->toDateString(), // match migration type
    //                 'due_date'     => $request->due_date,
    //                 'status'       => 'unpaid',
    //             ]
    //         );
    //     }

    //     return redirect()->route('teacher.payments.index', [
    //         'grade_level' => $grade_level,
    //         'section'     => $section,
    //         'school_year' => $request->input('school_year'),
    //     ])->with('success', 'Payment created successfully for all students.');
    // }


}
