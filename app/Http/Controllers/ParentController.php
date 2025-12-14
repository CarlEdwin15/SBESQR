<?php

namespace App\Http\Controllers;

use App\Models\ClassStudent;
use App\Models\Payment;
use App\Models\PaymentHistory;
use App\Models\PaymentRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Unauthorized');
        }

        return view('parent.index');
    }

    public function children()
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Unauthorized');
        }

        $children = $user->children()->with(['classStudents.class', 'schoolYears'])->get();

        return view('parent.children.index', compact('children'));
    }

    public function showChild($id, Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Unauthorized');
        }

        // Ensure the child belongs to this parent
        $child = $user->children()
            ->with([
                'address',
                'class' => function ($q) {
                    $q->with('schoolYear');
                },
                'schoolYears',
                'parents'
            ])
            ->findOrFail($id);

        // Handle school year selection
        $schoolYearId = $request->query('school_year');
        if (!$schoolYearId) {
            $schoolYearId = \App\Models\SchoolYear::latest('start_date')->value('id');
        }

        $schoolYear = \App\Models\SchoolYear::find($schoolYearId);

        // Get the student's current class for that school year
        $class = $child->class()
            ->wherePivot('school_year_id', $schoolYearId)
            ->first();

        // === ADD THIS STATUS LOGIC (same as admin) ===
        // Determine status
        $latestEnrollment = $child->classStudents()->latest()->first();
        $studentStatus = $latestEnrollment->enrollment_status ?? 'not_enrolled';

        // Determine additional info
        $statusInfo = null;

        if ($studentStatus === 'enrolled' && $class) {
            $grade = $class->formatted_grade_level ?? null;
            $section = $class->section ?? null;
            $gradeSection = $grade ? $grade . ($section ? ' - ' . $section : '') : null;

            $statusInfo = $gradeSection
                ? "{$gradeSection} for SY {$schoolYear->school_year}"
                : "For SY {$schoolYear->school_year}";
        } elseif (in_array($studentStatus, ['archived', 'not_enrolled'])) {
            $lastEnrollment = $child->classStudents()
                ->where('enrollment_status', 'enrolled')
                ->latest()
                ->first();

            $lastSY = $lastEnrollment?->schoolYear?->school_year;
            $statusInfo = $lastSY ? "Last Enrolled: {$lastSY}" : 'No recent enrollment';
        } elseif ($studentStatus === 'graduated') {
            $graduatedRecord = $child->classStudents()
                ->where('enrollment_status', 'graduated')
                ->latest()
                ->first();

            $gradSY = $graduatedRecord?->schoolYear?->school_year;
            $statusInfo = $gradSY ? "Graduated: {$gradSY}" : 'Graduation year not recorded';
        }
        // === END STATUS LOGIC ===

        // === ADD SCHOOL FEES LOGIC ===
        // Handle school fees year filter (separate from main school year)
        $feesSchoolYear = $request->query('school_year_fees');
        if (!$feesSchoolYear) {
            $feesSchoolYear = \App\Models\SchoolYear::latest('start_date')->value('school_year');
        }

        $selectedFeesYear = $feesSchoolYear;

        // Get school years for dropdown
        $schoolYears = \App\Models\SchoolYear::orderBy('start_date', 'desc')
            ->pluck('school_year')
            ->unique()
            ->values()
            ->toArray();

        // Get school fees payments for the selected year
        $schoolFeesPayments = $child->payments()
            ->with([
                'classStudent.class',
                'classStudent.student',
                'histories.addedBy'
            ])
            ->whereHas('schoolYear', function ($query) use ($selectedFeesYear) {
                $query->where('school_year', $selectedFeesYear);
            })
            ->get();
        // === END SCHOOL FEES LOGIC ===

        // Fetch all classes the child has been in (class history)
        $classHistory = $child->class()
            ->with([
                'schoolYear',
                'advisers' => function ($q) {
                    $q->wherePivot('role', 'adviser');
                }
            ])
            ->get();

        // ✅ Reorder: latest school year first, and show "enrolled" first
        $classHistory = $classHistory
            ->sortByDesc(function ($classItem) {
                return $classItem->pivot->enrollment_status === 'enrolled' ? 2 : 1;
            })
            ->sortByDesc(function ($classItem) {
                return $classItem->pivot->school_year_id;
            })
            ->values();

        // 🔹 Prepare grade data
        $gradesByClass = [];
        $generalAverages = [];

        foreach ($classHistory as $classItem) {
            $classSubjects = $classItem->classSubjects()
                ->with(['subject', 'quarters.quarterlyGrades' => function ($q) use ($child) {
                    $q->where('student_id', $child->id);
                }])
                ->where('school_year_id', $classItem->pivot->school_year_id)
                ->get();

            $subjectsWithGrades = [];
            $finalGrades = [];

            foreach ($classSubjects as $classSubject) {
                $quarters = $classSubject->quarters->map(function ($quarter) use ($child) {
                    return [
                        'quarter' => $quarter->quarter,
                        'grade' => optional($quarter->quarterlyGrades->first())->final_grade,
                    ];
                });

                $allQuartersHaveGrades = $quarters->every(fn($q) => $q['grade'] !== null);

                $finalAverage = null;
                $remarks = null;

                if ($allQuartersHaveGrades) {
                    $grades = $quarters->pluck('grade')->all();
                    $finalAverage = round(array_sum($grades) / 4, 2);
                    $remarks = $finalAverage >= 75 ? 'passed' : 'failed';
                    $finalGrades[] = $finalAverage;
                }

                $subjectsWithGrades[] = [
                    'subject' => $classSubject->subject->name,
                    'quarters' => $quarters,
                    'final_average' => $finalAverage,
                    'remarks' => $remarks,
                ];
            }

            $gradesByClass[$classItem->id] = $subjectsWithGrades;

            // Compute General Average - MATCHING GRADE SLIP LOGIC
            $totalSubjects = count($classSubjects);
            $completedSubjects = count($finalGrades);

            if ($totalSubjects > 0 && $completedSubjects === $totalSubjects) {
                // Apply DepEd rounding to each subject's final average FIRST
                $roundedFinalGrades = array_map('round', $finalGrades);

                // Then calculate general average from rounded grades
                $generalAverage = round(array_sum($roundedFinalGrades) / $completedSubjects);
                $remarks = $generalAverage >= 75 ? 'passed' : 'failed';

                $generalAverages[$classItem->id] = [
                    'general_average' => $generalAverage,
                    'remarks' => $remarks,
                ];
            } else {
                $generalAverages[$classItem->id] = null;
            }
        }

        // 🔹 Return same kind of view as teacher, but under parent folder
        return view('parent.children.show', compact(
            'child',
            'class',
            'schoolYear',
            'schoolYearId',
            'classHistory',
            'gradesByClass',
            'generalAverages',
            'studentStatus',
            'statusInfo',
            'schoolFeesPayments',
            'schoolYears',
            'selectedFeesYear'
        ));
    }

    public function schoolFees(Request $request)
    {
        $parent = Auth::user();
        $now = now();
        $year = $now->year;

        // Determine current school year
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;
        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
        $nextSchoolYear    = ($currentYear + 1) . '-' . ($currentYear + 2);

        // Fetch available school years
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

        // Sort ascending by start year
        usort($schoolYears, fn($a, $b) => intval(substr($a, 0, 4)) <=> intval(substr($b, 0, 4)));

        // Selected school year (default = current)
        $selectedYear = $request->query('school_year', $currentSchoolYear);

        // Fetch payments only for the selected school year
        $payments = Payment::whereIn('class_student_id', function ($query) use ($parent) {
            $query->select('class_student.id')
                ->from('class_student')
                ->join('student_parent', 'student_parent.student_id', '=', 'class_student.student_id')
                ->where('student_parent.parent_id', $parent->id);
        })
            ->with([
                'classStudent.student',
                'classStudent.class',
                'classStudent.schoolYear',
                'histories',
            ])
            ->whereHas('classStudent.schoolYear', function ($q) use ($selectedYear) {
                $q->where('school_year', $selectedYear);
            })
            ->orderBy('due_date', 'asc')
            ->get();

        return view('parent.school_fees.index', compact('payments', 'schoolYears', 'selectedYear', 'currentYear'));
    }

    public function showSchoolFee($paymentName, Request $request)
    {
        $parent = Auth::user();

        // Selected school year (default = current)
        $now = now();
        $year = $now->year;
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;
        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
        $selectedYear = $request->query('school_year', $currentSchoolYear);

        // Selected class filter
        $selectedClass = $request->query('class_id', null);

        // Fetch payments matching the criteria
        $paymentsQuery = Payment::where('payment_name', $paymentName)
            ->whereIn('class_student_id', function ($query) use ($parent) {
                $query->select('class_student.id')
                    ->from('class_student')
                    ->join('student_parent', 'student_parent.student_id', '=', 'class_student.student_id')
                    ->where('student_parent.parent_id', $parent->id);
            })
            ->whereHas('classStudent.schoolYear', function ($q) use ($selectedYear) {
                $q->where('school_year', $selectedYear);
            });

        if ($selectedClass) {
            $paymentsQuery->whereHas('classStudent.class', function ($q) use ($selectedClass) {
                $q->where('id', $selectedClass);
            });
        }

        $payments = $paymentsQuery
            ->with([
                'classStudent.student',
                'classStudent.class',
                'classStudent.schoolYear',
                'histories',
            ])
            ->orderBy('due_date', 'asc')
            ->get();

        // Fetch classes for the selected school year that have payments
        $classes = ClassStudent::whereIn('id', function ($query) use ($parent, $selectedYear) {
            $query->select('class_student.class_id')
                ->from('class_student')
                ->join('student_parent', 'student_parent.student_id', '=', 'class_student.student_id')
                ->join('school_years', 'school_years.id', '=', 'class_student.school_year_id')
                ->where('student_parent.parent_id', $parent->id)
                ->where('school_years.school_year', $selectedYear);
        })->get();
        return view('parent.school_fees.show', compact('payments', 'paymentName', 'selectedYear', 'selectedClass', 'classes'));
    }

    public function addPayment(Request $request, $paymentId)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:1',
            'payment_method' => 'required|in:gcash,paymaya',
            'reference_number' => 'nullable|string',
            'receipt_image' => 'nullable|image|max:2048',
        ]);

        $parent = Auth::user();
        $payment = Payment::findOrFail($paymentId);

        // Ensure parent is linked to this student
        $isAuthorized = DB::table('student_parent')
            ->join('class_student', 'student_parent.student_id', '=', 'class_student.student_id')
            ->where('student_parent.parent_id', $parent->id)
            ->where('class_student.id', $payment->class_student_id)
            ->exists();

        if (!$isAuthorized) {
            abort(403, 'Unauthorized payment request.');
        }

        // CHECK MAX ATTEMPTS (3 attempts)
        $previousAttemptsCount = PaymentRequest::where('payment_id', $paymentId)
            ->where('parent_id', $parent->id)
            ->whereIn('status', ['pending', 'denied'])
            ->count();

        if ($previousAttemptsCount >= 3) {
            return redirect()->back()->with('error', 'You have reached the maximum of 3 payment request attempts for this fee. Please contact the administrator for assistance.');
        }

        // Determine attempt number
        $attemptNumber = $previousAttemptsCount + 1;

        // Handle receipt upload
        $receiptPath = null;
        if ($request->hasFile('receipt_image')) {
            $receiptPath = $request->file('receipt_image')->store('receipts', 'public');
        }

        // Create payment request
        PaymentRequest::create([
            'payment_id' => $payment->id,
            'parent_id' => $parent->id,
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number ?? null,
            'receipt_image' => $receiptPath,
            'attempt_number' => $attemptNumber,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Your payment request (Attempt #' . $attemptNumber . ') has been submitted and is pending review.');
    }

    public function reviewPaymentRequest($id, Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,deny',
            'remarks' => 'nullable|string',
        ]);

        $paymentRequest = PaymentRequest::findOrFail($id);
        $paymentRequest->status = $request->action === 'approve' ? 'approved' : 'denied';
        $paymentRequest->admin_remarks = $request->remarks;
        $paymentRequest->reviewed_at = now();
        $paymentRequest->save();

        if ($request->action === 'approve') {
            // Create official payment history
            PaymentHistory::create([
                'payment_id' => $paymentRequest->payment_id,
                'payment_method' => $paymentRequest->payment_method,
                'added_by' => Auth::id(),
                'amount_paid' => $paymentRequest->amount_paid,
                'payment_date' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Payment request has been ' . $paymentRequest->status . '.');
    }

    public function announcements()
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Unauthorized');
        }

        $children = $user->children()->with(['classStudents.class', 'schoolYears'])->get();

        return view('parent.announcements.index', compact('children'));
    }

    public function updateParent()
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Unauthorized');
        }

        return view('parent.accountSettings', compact('user'));
    }

    public function accountSettings()
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Unauthorized');
        }

        return view('parent.accountSettings', compact('user'));
    }

    public function checkAttempts($paymentId)
    {
        $parent = Auth::user();

        if ($parent->role !== 'parent') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $previousAttemptsCount = PaymentRequest::where('payment_id', $paymentId)
            ->where('parent_id', $parent->id)
            ->whereIn('status', ['pending', 'denied'])
            ->count();

        $canRequest = $previousAttemptsCount < 3;
        $remainingAttempts = 3 - $previousAttemptsCount;

        return response()->json([
            'can_request' => $canRequest,
            'previous_attempts' => $previousAttemptsCount,
            'remaining_attempts' => $remainingAttempts,
            'is_last_attempt' => $remainingAttempts === 1
        ]);
    }

    public function deletePaymentRequest($id)
{
    $parent = Auth::user();

    if ($parent->role !== 'parent') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    try {
        $paymentRequest = PaymentRequest::findOrFail($id);

        // Ensure parent can only delete their own pending requests
        if ($paymentRequest->parent_id !== $parent->id || $paymentRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized or cannot delete this request'
            ], 403);
        }

        // Get attempt number before deleting
        $attemptNumber = $paymentRequest->attempt_number;

        // Delete the payment request
        $paymentRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment request deleted successfully.',
            'attempt_number' => $attemptNumber
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'error' => 'Payment request not found'
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to delete request: ' . $e->getMessage()
        ], 500);
    }
}
}
