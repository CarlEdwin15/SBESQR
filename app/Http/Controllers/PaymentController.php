<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request, $grade_level, $section)
    {
        $selectedYear = $request->input('school_year');

        $class = Classes::with('students')
            ->where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        // ✅ Fix: resolve the school year id properly
        $payments = Payment::where('class_id', $class->id)
            ->when($selectedYear, function ($query) use ($selectedYear) {
                $query->whereHas('schoolYear', function ($q) use ($selectedYear) {
                    $q->where('school_year', $selectedYear); // adjust column name (e.g., "year" or "name")
                });
            })
            ->get();

        return view('teacher.classes.payments.index', compact('class', 'payments', 'selectedYear'));
    }

    public function show(Request $request, $grade_level, $section, $paymentName)
    {
        $selectedYear = $request->input('school_year');

        // find class
        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        // decode just in case (Laravel usually decodes already)
        $paymentName = urldecode($paymentName);

        // fetch payments that match this payment name, for the class, and optionally the school year
        $payments = Payment::with('student')
            ->where('class_id', $class->id)
            ->where('payment_name', $paymentName)
            ->when($selectedYear, function ($query) use ($selectedYear) {
                $query->whereHas('schoolYear', function ($q) use ($selectedYear) {
                    // adjust the column name in the SchoolYear model/table as needed
                    $q->where('school_year', $selectedYear);
                });
            })
            ->get();

        // if desired, fail if no payments found for given name
        // abort(404) OR just show an empty table. I'll allow empty view and pass data.
        $first = $payments->first();

        $totalStudents = $payments->count();
        $paidCount     = $payments->where('status', 'paid')->count();
        $partialCount  = $payments->where('status', 'partial')->count();
        $unpaidCount   = $payments->where('status', 'unpaid')->count();

        return view('teacher.classes.payments.show', compact(
            'class',
            'selectedYear',
            'payments',
            'first',
            'paymentName',
            'totalStudents',
            'paidCount',
            'partialCount',
            'unpaidCount'
        ));
    }

    public function create(Request $request, $grade_level, $section)
    {
        $request->validate([
            'payment_name' => 'required|string|max:255',
            'amount_due'   => 'required|numeric|min:0',
            'due_date'     => 'required|date',
            'remarks'      => 'nullable|string|max:500',
        ]);

        $class = Classes::with('students')
            ->where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        foreach ($class->students as $student) {
            Payment::firstOrCreate(
                [
                    'class_id'       => $class->id,
                    'school_year_id' => $student->pivot->school_year_id,
                    'student_id'     => $student->id,
                    'payment_name'   => $request->payment_name,
                ],
                [
                    'created_by'   => Auth::id(),
                    'amount_due'   => $request->amount_due,
                    'date_created' => now()->toDateString(), // match migration type
                    'due_date'     => $request->due_date,
                    'status'       => 'unpaid',
                    'remarks'      => $request->remarks,
                ]
            );
        }

        return redirect()->route('teacher.payments.index', [
            'grade_level' => $grade_level,
            'section'     => $section,
            'school_year' => $request->input('school_year'),
        ])->with('success', 'Payment created successfully for all students.');
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'amount_paid' => 'nullable|numeric|min:0',
            'remarks'     => 'nullable|string|max:500',
        ]);

        $amountPaid = $request->amount_paid ?? 0;
        $amountDue  = $payment->amount_due;

        // 🔹 Determine status automatically
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
            'remarks'     => $request->remarks,
        ]);

        return back()->with('success', 'Payment updated successfully.');
    }
}
