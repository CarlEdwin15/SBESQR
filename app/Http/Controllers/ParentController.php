<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            // Compute General Average
            $totalSubjects = count($classSubjects);
            $completedSubjects = count($finalGrades);

            if ($totalSubjects > 0 && $completedSubjects === $totalSubjects) {
                $generalAverage = round(array_sum($finalGrades) / $completedSubjects, 2);
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
            'generalAverages'
        ));
    }

    public function schoolFees()
    {
        $parent = Auth::user();

        // Get all children of this parent
        $children = $parent->children()->with('classStudents.payments')->get();

        // Collect all payments via class_student
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
            ->orderBy('due_date', 'asc')
            ->get();

        return view('parent.school_fees.index', compact('payments', 'children'));
    }

    public function smsLogs()
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Unauthorized');
        }

        $children = $user->children()->with(['classStudents.class', 'schoolYears'])->get();

        // Collect SMS logs from all children
        $smsLogs = $children->flatMap(function ($child) {
            return $child->smsLogs; // Assuming 'smsLogs' is the relationship defined in the Student model
        })->sortByDesc('created_at'); // Sort by most recent

        return view('parent.sms_logs.index', compact('smsLogs'));
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
}
