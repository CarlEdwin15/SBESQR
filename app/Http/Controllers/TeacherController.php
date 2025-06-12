<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TeacherController extends Controller
{

    public function myClasses(Request $request)
    {
        $teacher = User::find(Auth::id());

        $sections = $teacher->classes->pluck('section')->unique()->sort();

        $selectedSection = $request->input('section', $sections->first());

        $classes = $teacher->classes()->where('section', $selectedSection)->get();

        // For each class, fetch teachers with pivot role 'adviser' or 'both'
        foreach ($classes as $class) {
            $class->adviser = $class->teachers()->wherePivotIn('role', ['adviser', 'both'])->first();
        }

        return view('teacher.classes.index', compact('classes', 'sections', 'selectedSection'));
    }

    public function myClass($grade_level, $section)
    {
        $class = $this->getClass($grade_level, $section);

        $studentCount = $class->students()->count();
        // $presentToday = $class->students()->whereHas('attendances', function ($query) {
        //     $query->whereDate('date', now())->where('status', 'present');
        // })->count();

        // For each class, fetch teachers with pivot role 'adviser' or 'both'
        $class->adviser = $class->teachers()->wherePivotIn('role', ['adviser', 'both'])->first();

        return view('teacher.classes.myClass', compact('class', 'studentCount'));
    }

    public function myClassMasterList($grade_level, $section)
    {
        $class = $this->getClass($grade_level, $section);

        // Fetch students in the class
        $students = Student::where('class_id', $class->id)->get();
        // For each class, fetch teachers with pivot role 'adviser' or 'both'
        $class->adviser = $class->teachers()->wherePivotIn('role', ['adviser', 'both'])->first();

        return view('teacher.classes.myMasterList', compact('class', 'students'));
    }

    public function mySchedule($grade_level, $section)
    {
        $class = $this->getClass($grade_level, $section);

        // Get the authenticated teacher's ID
        $teacherId = Auth::id();

        // Filter schedules only for the logged-in teacher
        $schedules = \App\Models\Schedule::where('class_id', $class->id)
            ->where('teacher_id', $teacherId)
            ->get();

        // Fetch only this teacher's pivot role in this class
        $teachers = $class->teachers()
            ->where('users.id', $teacherId)
            ->wherePivotIn('role', ['adviser', 'subject_teacher', 'both'])
            ->get();

        return view('teacher.classes.mySchedule', compact('class', 'schedules', 'teachers'));
    }

    public function myAttendanceRecord($grade_level, $section)
    {
        $class = $this->getClass($grade_level, $section);
        $students = Student::where('class_id', $class->id)
            ->orderBy('student_sex') // or 'gender'
            ->orderBy('student_lName')
            ->orderBy('student_fName')
            ->orderBy('student_mName')
            ->get();

        $monthParam = request('month', now()->format('Y-m'));
        $dateObj = \Carbon\Carbon::createFromFormat('Y-m', $monthParam);
        $year = $dateObj->year;
        $month = $dateObj->month;

        // Get teacher's schedule days (e.g., Mon, Tue)
        $scheduleDays = \App\Models\Schedule::where('class_id', $class->id)
            ->where('teacher_id', Auth::id())
            ->pluck('day') // Assuming stored as 'Monday', 'Tuesday', etc.
            ->map(function ($day) {
                return \Carbon\Carbon::parse($day)->format('D'); // Mon, Tue, etc.
            })
            ->toArray();

        // Fetch attendance records
        $attendances = \App\Models\Attendance::where('class_id', $class->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $calendarDates = [];
        $startOfMonth = \Carbon\Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            if ($date->isWeekday()) {
                $calendarDates[] = $date->format('Y-m-d');
            }
        }

        $attendanceData = [];
        foreach ($students as $student) {
            $attendanceData[$student->id] = [
                'present' => 0,
                'absent' => 0,
                'by_date' => [],
            ];
        }

        $combinedTotals = $maleTotals = $femaleTotals = [];
        foreach ($calendarDates as $date) {
            $combinedTotals[$date] = 0;
            $maleTotals[$date] = 0;
            $femaleTotals[$date] = 0;
        }

        foreach ($attendances as $attendance) {
            $date = $attendance->date;
            $symbol = match ($attendance->status) {
                'present' => '✓',
                'absent' => 'X',
                'late' => '/',
                default => '-',
            };

            $attendanceData[$attendance->student_id]['by_date'][$date] = $symbol;

            if (in_array($attendance->status, ['present', 'late'])) {
                $attendanceData[$attendance->student_id]['present']++;
            } else {
                $attendanceData[$attendance->student_id]['absent']++;
            }

            $student = $students->firstWhere('id', $attendance->student_id);
            if ($symbol === '✓' || $symbol === '/') {
                $combinedTotals[$date]++;
                if ($student->gender === 'Male') {
                    $maleTotals[$date]++;
                } else {
                    $femaleTotals[$date]++;
                }
            }
        }

        $maleTotalPresent = $femaleTotalPresent = $maleTotalAbsent = $femaleTotalAbsent = 0;
        foreach ($students as $student) {
            if ($student->gender === 'Male') {
                $maleTotalPresent += $attendanceData[$student->id]['present'];
                $maleTotalAbsent += $attendanceData[$student->id]['absent'];
            } else {
                $femaleTotalPresent += $attendanceData[$student->id]['present'];
                $femaleTotalAbsent += $attendanceData[$student->id]['absent'];
            }
        }

        $totalPresent = $maleTotalPresent + $femaleTotalPresent;
        $totalAbsent = $maleTotalAbsent + $femaleTotalAbsent;

        return view('teacher.classes.myAttendanceRecord', compact(
            'class',
            'students',
            'attendanceData',
            'calendarDates',
            'combinedTotals',
            'maleTotals',
            'femaleTotals',
            'monthParam',
            'maleTotalPresent',
            'femaleTotalPresent',
            'maleTotalAbsent',
            'femaleTotalAbsent',
            'totalAbsent',
            'totalPresent',
            'scheduleDays' // ✅ Add this to view
        ));
    }

    public function myStudents($grade_level, $section)
    {
        $teacher = Auth::user();

        $class = $this->getClass($grade_level, $section);

        // Fetch students in the class
        $students = Student::where('class_id', $class->id)->get();
        // Fetch teachers in the class
        $teachers = User::where('class_id', $class->id)->get();

        return view('teacher.students.index', compact('class', 'students'));
    }

    public function studentInfo($student_id)
    {
        $student = Student::findOrFail($student_id);
        return view('teacher.students.studentInfo', compact('student'));
    }

    public function editStudentInfo($student_id)
    {
        $student = Student::findOrFail($student_id);
        return view('teacher.students.editStudent', compact('student'));
    }

    public function updateStudentInfo(Request $request, $student_id)
    {
        $student = Student::findOrFail($student_id);

        $messages = [
            'student_lrn.regex' => 'The LRN must start with "112828" and be exactly 12 digits long.',
        ];

        $validatedData = $request->validate([
            'student_lrn' => [
                'required',
                'string',
                'max:12',
                'regex:/^112828[0-9]{6}$/',
                'unique:students,student_lrn,' . $student->student_id . ',student_id',
            ],
            'student_grade_level' => 'required|in:kindergarten,grade1,grade2,grade3,grade4,grade5,grade6',
            'student_section' => 'required|in:A,B,C,D,E,F',
            'student_fName' => 'required|string|max:255',
            'student_mName' => 'nullable|string|max:255',
            'student_lName' => 'required|string|max:255',
            'student_extName' => 'nullable|string|max:45',
            'student_dob' => 'nullable|date',
            'student_sex' => 'required|in:male,female',
            'student_age' => 'nullable|integer',
            'student_pob' => 'required|string|max:255',
            'address' => 'required|string|max:255',

            'student_fatherFName' => 'nullable|string|max:255',
            'student_fatherMName' => 'nullable|string|max:255',
            'student_fatherLName' => 'nullable|string|max:255',

            'student_motherFName' => 'nullable|string|max:255',
            'student_motherMName' => 'nullable|string|max:255',
            'student_motherLName' => 'nullable|string|max:255',

            'student_parentPhone' => 'nullable|string|max:255',
            'student_profile_photo' => 'nullable|image|mimes:jpeg,png|max:2048',
        ], $messages);

        // Handle photo upload
        if ($request->hasFile('student_profile_photo')) {
            $profilePhotoPath = $request->file('student_profile_photo')->store('student_profile_photos', 'public');
            $student->student_photo = $profilePhotoPath;
        }

        // Update fields
        $student->update([
            'student_lrn' => $validatedData['student_lrn'],
            'student_grade_level' => $validatedData['student_grade_level'],
            'student_section' => $validatedData['student_section'],
            'student_fName' => $validatedData['student_fName'],
            'student_mName' => $validatedData['student_mName'] ?? null,
            'student_lName' => $validatedData['student_lName'],
            'student_extName' => $validatedData['student_extName'] ?? null,
            'student_dob' => $validatedData['student_dob'] ?? null,
            'student_sex' => ucfirst($validatedData['student_sex']),
            'student_age' => $validatedData['student_age'] ?? null,
            'student_pob' => $validatedData['student_pob'],
            'student_address' => $validatedData['address'],
            'student_fatherFName' => $validatedData['student_fatherFName'] ?? null,
            'student_fatherMName' => $validatedData['student_fatherMName'] ?? null,
            'student_fatherLName' => $validatedData['student_fatherLName'] ?? null,
            'student_motherFName' => $validatedData['student_motherFName'] ?? null,
            'student_motherMName' => $validatedData['student_motherMName'] ?? null,
            'student_motherLName' => $validatedData['student_motherLName'] ?? null,
            'student_parentPhone' => $validatedData['student_parentPhone'] ?? null,
        ]);

        $student->save();

        return redirect()->route('teacher.my.students')->with('success', 'Student updated successfully!');
    }

    private function getClass($grade_level, $section)
    {
        return Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();
    }
}
