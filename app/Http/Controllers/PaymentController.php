<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Classes;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['student', 'class', 'schoolYear'])->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $classes = Classes::all();
        $students = Student::all();
        $schoolYears = SchoolYear::all();
        $defaultSchoolYear = $this->getDefaultSchoolYear(); // Use default school year string

        // Try to fetch the actual SchoolYear model for that string (if your DB has a 'school_year' column)
        $defaultSchoolYearModel = SchoolYear::where('school_year', $defaultSchoolYear)->first();

        return view('admin.payments.create', compact(
            'classes',
            'students',
            'schoolYears',
            'defaultSchoolYearModel'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'student_id' => 'required|exists:students,id',
            'payment_name' => 'required|string|max:255',
            'amount_due' => 'required|numeric|min:0',
            'date_created' => 'required|date',
            'due_date' => 'required|date',
        ]);

        // Use school_year_id from request or fallback to default school year
        $schoolYearId = $request->school_year_id;

        if (!$schoolYearId) {
            $defaultSchoolYear = $this->getDefaultSchoolYear();
            $schoolYear = SchoolYear::where('school_year', $defaultSchoolYear)->first();

            if ($schoolYear) {
                $schoolYearId = $schoolYear->id;
            } else {
                return redirect()->back()->withErrors(['school_year_id' => 'Default school year not found.']);
            }
        }

        Payment::create([
            'created_by' => Auth::id(),
            'class_id' => $request->class_id,
            'school_year_id' => $schoolYearId,
            'student_id' => $request->student_id,
            'payment_name' => $request->payment_name,
            'amount_due' => $request->amount_due,
            'date_created' => $request->date_created,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
    }

    public function show(Payment $payment)
    {
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $classes = Classes::all();
        $students = Student::all();
        $schoolYears = SchoolYear::all();

        return view('admin.payments.edit', compact('payment', 'classes', 'students', 'schoolYears'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'school_year_id' => 'required|exists:school_years,id',
            'student_id' => 'required|exists:students,id',
            'payment_name' => 'required|string|max:255',
            'amount_due' => 'required|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'date_paid' => 'nullable|date',
            'status' => 'required|in:unpaid,partial,paid',
            'date_created' => 'required|date',
            'due_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $payment->update($request->all());

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    /**
     * Helper: Get default school year string (e.g. "2024-2025")
     */
    private function getDefaultSchoolYear(): string
    {
        $now = now();
        $year = $now->year;
        $cutoff = now()->copy()->month(6)->day(1);
        $start = $now->lt($cutoff) ? $year - 1 : $year;

        return $start . '-' . ($start + 1);
    }
}
