<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Classes;
use App\Models\ClassStudent;
use App\Models\PaymentHistory;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function indexAdmin(Request $request)
    {
        $now = now();
        $year = $now->year;

        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;

        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
        $nextSchoolYear    = ($currentYear + 1) . '-' . ($currentYear + 2);

        $savedYears = SchoolYear::pluck('school_year')->toArray();
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

        $selectedYear = $request->query('school_year', $currentSchoolYear);
        $selectedClass = $request->query('class_id');

        $schoolYear = SchoolYear::where('school_year', $selectedYear)->first();

        // Count enrolled students per class
        $allClasses = Classes::withCount(['classStudents as enrolled_count' => function ($q) use ($schoolYear) {
            $q->where('school_year_id', $schoolYear->id)
                ->where('enrollment_status', 'enrolled');
        }])->get();

        // Count total enrolled for "All Classes"
        $totalEnrolled = ClassStudent::where('school_year_id', $schoolYear->id)
            ->where('enrollment_status', 'enrolled')
            ->count();

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
            ->get();

        session()->flash(
            'school_year_notice',
            $selectedClass
                ? "Displaying Payment Records for selected class in SY {$selectedYear}"
                : "Displaying Payment Records for All Classes in SY {$selectedYear}"
        );

        return view('admin.payments.index', compact(
            'payments',
            'schoolYears',
            'selectedYear',
            'currentYear',
            'allClasses',
            'selectedClass',
            'totalEnrolled'
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
            'class_ids'          => 'nullable|array',
            'class_ids.*'        => 'string', // can be "all" or a class ID
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
                            'due_date'     => $request->due_date,
                        ]
                    );
                }
            }
        }
        // Case 2: Payments for multiple classes OR "All Classes"
        else {
            $classIds = $request->class_ids ?? [];

            if (in_array('all', $classIds)) {
                // ✅ Only classes with enrolled students
                $classIds = Classes::whereHas('classStudents', function ($q) use ($schoolYear) {
                    $q->where('school_year_id', $schoolYear->id)
                        ->where('enrollment_status', 'enrolled');
                })->pluck('id')->toArray();
            }

            foreach ($classIds as $classId) {
                $classStudents = ClassStudent::where('class_id', $classId)
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
                            'due_date'     => $request->due_date,
                        ]
                    );
                }
            }
        }

        return redirect()->route('admin.payments.index', [
            'school_year' => $request->school_year,
        ])->with('success', 'Payment(s) created successfully.');
    }


    public function showAdmin(Request $request, $paymentName)
    {
        $selectedYear = $request->input('school_year');
        $selectedClass = $request->input('class_id');

        $baseQuery = Payment::with([
            'student',
            'classStudent.class',
            'schoolYear',
            'paymentHistories' => function ($q) {
                $q->orderBy('payment_date'); // 👈 latest first
            },
            'paymentHistories.addedBy'
        ])->where('payment_name', $paymentName);

        $paidCount = (clone $baseQuery)->where('status', 'paid')->count();
        $partialCount = (clone $baseQuery)->where('status', 'partial')->count();
        $unpaidCount = (clone $baseQuery)->where('status', 'unpaid')->count();
        $totalCount = (clone $baseQuery)->count();

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


    public function addStudents(Request $request, $paymentName)
    {
        $validated = $request->validate([
            'class_student_ids'   => 'required|array|min:1',
            'class_student_ids.*' => 'exists:class_student,id',
            'school_year'         => 'required|string',
            'amount_due'          => 'nullable|numeric|min:0',
            'due_date'            => 'nullable|date',
        ]);

        $schoolYear = SchoolYear::where('school_year', $validated['school_year'])->firstOrFail();

        // Determine amount and due date
        $amountDue = $validated['amount_due'];
        $dueDate   = $validated['due_date'];

        if (!$amountDue || !$dueDate) {
            $template = Payment::where('payment_name', $paymentName)
                ->whereHas('classStudent', fn($q) => $q->where('school_year_id', $schoolYear->id))
                ->first();

            $amountDue ??= $template?->amount_due;
            $dueDate   ??= $template?->due_date;
        }

        if (!$amountDue || !$dueDate) {
            return back()->with('error', "Missing payment details. Please ensure a reference payment exists for '{$paymentName}' in {$validated['school_year']}.");
        }

        $added = 0;
        $skipped = 0;

        DB::transaction(function () use ($validated, $paymentName, $schoolYear, $amountDue, $dueDate, &$added, &$skipped) {
            foreach ($validated['class_student_ids'] as $csId) {
                $classStudent = ClassStudent::where('id', $csId)
                    ->where('school_year_id', $schoolYear->id)
                    ->where('enrollment_status', 'enrolled')
                    ->first();

                if (!$classStudent) {
                    $skipped++;
                    continue;
                }

                $exists = Payment::withTrashed()
                    ->where('payment_name', $paymentName)
                    ->where('class_student_id', $classStudent->id)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                Payment::create([
                    'payment_name'     => $paymentName,
                    'class_student_id' => $classStudent->id,
                    'amount_due'       => $amountDue,
                    'due_date'         => $dueDate,
                    'status'           => 'unpaid',
                    'created_by'       => Auth::id(),
                ]);

                $added++;
            }
        });

        $message = collect([
            $added   ? "{$added} student(s) added" : null,
            $skipped ? "{$skipped} student(s) skipped" : null,
        ])->filter()->implode(' — ');

        return redirect()->route('admin.payments.show', [
            'paymentName' => $paymentName,
            'school_year' => $validated['school_year'],
            'class_id'    => $request->input('class_id'),
        ])->with('success', $message ?: 'No students were added.');
    }


    public function addPayment(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
        ]);

        $amountPaid = $request->amount_paid;

        // Prevent overpayment
        $remainingBalance = $payment->amount_due - $payment->paymentHistories()->sum('amount_paid');
        if ($amountPaid > $remainingBalance) {
            return back()->withErrors(['amount_paid' => 'Payment exceeds remaining balance of ₱' . number_format($remainingBalance, 2)]);
        }

        // Record payment
        PaymentHistory::create([
            'payment_id'   => $payment->id,
            'amount_paid'  => $amountPaid,
            'payment_date' => now(),
            'added_by'     => Auth::id(),
        ]);

        // Recalculate total paid
        $totalPaid = $payment->paymentHistories()->sum('amount_paid');

        // Determine status automatically
        if ($totalPaid <= 0) {
            $status = 'unpaid';
        } elseif ($totalPaid < $payment->amount_due) {
            $status = 'partial';
        } else {
            $status = 'paid';
        }

        // Update summary
        $payment->update([
            'total_paid' => $totalPaid,
            'status'     => $status,
        ]);

        return redirect()
            ->route('admin.payments.show', ['paymentName' => $payment->payment_name] + request()->query())
            ->with('success', 'Payment added successfully.');
    }

    public function history($paymentName)
    {
        $payments = Payment::with('student', 'paymentHistories.addedBy')
            ->where('payment_name', $paymentName)
            ->orderBy('created_at') // Order by latest payment first
            ->get();

        return response()->json($payments);
    }

    public function deleteHistory($id)
    {
        $history = PaymentHistory::findOrFail($id);
        $payment = $history->payment; // parent payment

        // Delete history entry
        $history->delete();

        // Recalculate total paid
        $totalPaid = $payment->paymentHistories()->sum('amount_paid');

        // Update status
        if ($totalPaid <= 0) {
            $status = 'unpaid';
        } elseif ($totalPaid < $payment->amount_due) {
            $status = 'partial';
        } else {
            $status = 'paid';
        }

        $payment->update([
            'status' => $status,
        ]);

        return back()->with('success', 'Payment record deleted successfully.');
    }


    public function bulkAddPayment(Request $request)
    {
        $request->validate([
            'payment_ids'   => 'required|array',
            'payment_ids.*' => 'exists:payments,id',
            'amount_paid'   => 'required|numeric|min:0.01',
            'payment_date'  => 'required|date',
        ]);

        $invalidStudents = [];

        foreach ($request->payment_ids as $id) {
            $payment = Payment::find($id);
            if (!$payment) continue;

            $remainingBalance = $payment->amount_due - $payment->paymentHistories()->sum('amount_paid');

            if ($request->amount_paid > $remainingBalance) {
                $invalidStudents[] = $payment->student->full_name ?? "Student ID {$payment->id}";
            }
        }

        if (!empty($invalidStudents)) {
            return redirect()->back()->with(
                'bulk_error',
                'The following students exceed their balance: '
                    . implode(', ', $invalidStudents)
            );
        }

        // ✅ Continue if no invalid students
        foreach ($request->payment_ids as $id) {
            $payment = Payment::find($id);
            if (!$payment) continue;

            $payment->paymentHistories()->create([
                'amount_paid'  => $request->amount_paid,
                'payment_date' => $request->payment_date,
                'added_by'     => Auth::id(),
            ]);

            $totalPaid = $payment->paymentHistories()->sum('amount_paid');
            $status    = $totalPaid >= $payment->amount_due ? 'paid' : ($totalPaid > 0 ? 'partial' : 'unpaid');

            $payment->update([
                'total_paid' => $totalPaid,
                'status'     => $status,
            ]);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('success', 'Payments added successfully to selected students.');
    }

    public function bulkRemoveStudents(Request $request)
    {
        $request->validate([
            'payment_ids'   => 'required|array',
            'payment_ids.*' => 'exists:payments,id',
        ]);

        $removed = Payment::whereIn('id', $request->payment_ids)->forceDelete();

        return redirect()
            ->back()
            ->with('success', "{$removed} student(s) permanently removed from the payment list.");
    }

    public function destroyAdmin($paymentName)
    {
        $deleted = Payment::where('payment_name', $paymentName)->delete();

        if ($deleted) {
            return response()->json(['message' => 'Payments deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'No payments found.'], 404);
        }
    }

    public function getDefaultSchoolYear()
    {
        $now = now();
        $year = $now->year;
        $cutoff = now()->copy()->setMonth(6)->setDay(1);
        $start = $now->lt($cutoff) ? $year - 1 : $year;
        return $start . '-' . ($start + 1);
    }




    // public function index(Request $request, $grade_level, $section)
    // {
    //     $selectedYear = $request->input('school_year');

    //     $class = Classes::with('students')
    //         ->where('grade_level', $grade_level)
    //         ->where('section', $section)
    //         ->firstOrFail();

    //     // Fix: resolve the school year id properly
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
