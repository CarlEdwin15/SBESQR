<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        $class->adviser = $class->teachers()->wherePivotIn('role', ['adviser', 'both'])->first();

        $today = Carbon::now()->format('Y-m-d');
        $todayDayName = Carbon::now()->format('l');

        // Get all schedules for today
        $schedulesToday = $class->schedules()->where('day', $todayDayName)->get();

        // Total possible attendance entries = number of schedules × number of students
        $scheduleCount = $schedulesToday->count();
        $totalPossibleAttendance = $studentCount * $scheduleCount;

        // Get the count of all present or late attendance entries for all schedules today
        $presentCount = Attendance::whereIn('schedule_id', $schedulesToday->pluck('id'))
            ->whereDate('date', $today)
            ->whereIn('status', ['present', 'late'])
            ->count();

        // Cap at 100%
        $attendanceToday = $totalPossibleAttendance > 0
            ? min(100, round(($presentCount / $totalPossibleAttendance) * 100))
            : 0;

        return view('teacher.classes.myClass', compact(
            'class',
            'studentCount',
            'presentCount',
            'totalPossibleAttendance',
            'attendanceToday'
        ));
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
        $schedules = Schedule::where('class_id', $class->id)
            ->where('teacher_id', $teacherId)
            ->orderBy('day')
            ->orderBy('start_time')
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
            ->orderBy('student_sex')
            ->orderBy('student_lName')
            ->orderBy('student_fName')
            ->orderBy('student_mName')
            ->get();

        $monthParam = request('month', now()->format('Y-m'));
        $dateObj = \Carbon\Carbon::createFromFormat('Y-m', $monthParam);
        $year = $dateObj->year;
        $month = $dateObj->month;

        $scheduleDays = \App\Models\Schedule::where('class_id', $class->id)
            ->where('teacher_id', Auth::id())
            ->pluck('day')
            ->map(fn($day) => \Carbon\Carbon::parse($day)->format('D'))
            ->toArray();

        // Order schedules by start_time (AM to PM)
        $schedules = \App\Models\Schedule::where('class_id', $class->id)
            ->where('teacher_id', Auth::id())
            ->orderBy('start_time')
            ->get();

        $schedulesById = $schedules->keyBy('id');

        $attendances = \App\Models\Attendance::where('class_id', $class->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $calendarDates = [];
        $startOfMonth = \Carbon\Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            // Keep only weekdays (Mon to Fri)
            if (!in_array($date->format('D'), ['Sat', 'Sun'])) {
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
                'late' => 'L',
                'excused' => 'E',
                default => '-',
            };

            $schedule = $schedulesById->get($attendance->schedule_id);
            if (!$schedule) {
                continue;
            }

            // ✅ Ensure student entry exists
            if (!isset($attendanceData[$attendance->student_id])) {
                $attendanceData[$attendance->student_id] = [
                    'present' => 0,
                    'absent' => 0,
                    'by_date' => [],
                ];
            }

            $attendanceData[$attendance->student_id]['by_date'][$date][] = [
                'status' => $symbol,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'subject_name' => $schedule->subject_name ?? 'N/A',
                'schedule_order' => $schedule->start_time, // for sorting
            ];

            if (in_array($attendance->status, ['present', 'late'])) {
                $attendanceData[$attendance->student_id]['present']++;
            } else {
                $attendanceData[$attendance->student_id]['absent']++;
            }

            $student = $students->firstWhere('id', $attendance->student_id);
            if ($student && in_array($symbol, ['✓', 'L'])) {
                $combinedTotals[$date]++;
                if ($student->gender === 'Male') {
                    $maleTotals[$date]++;
                } else {
                    $femaleTotals[$date]++;
                }
            }
        }

        // Sort attendance by schedule (AM to PM) for each student/date
        foreach ($attendanceData as &$studentAttendance) {
            foreach ($studentAttendance['by_date'] as &$entries) {
                usort($entries, function ($a, $b) {
                    return strcmp($a['schedule_order'], $b['schedule_order']);
                });
            }
        }
        unset($studentAttendance);

        $maleTotalPresent = $femaleTotalPresent = $maleTotalAbsent = $femaleTotalAbsent = 0;
        foreach ($students as $student) {
            if (!isset($attendanceData[$student->id])) {
                continue;
            }

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
            'scheduleDays',
            'schedules'
        ));
    }

    public function attendanceHistory($grade_level, $section, $date = null)
    {
        $class = $this->getClass($grade_level, $section);
        $targetDate = $date ?? request('date') ?? now()->toDateString();
        $targetDate = \Carbon\Carbon::parse($targetDate)->format('Y-m-d');
        $dayName = \Carbon\Carbon::parse($targetDate)->format('l');

        $schedules = Schedule::where('teacher_id', Auth::id())
            ->where('class_id', $class->id)
            ->where('day', $dayName)
            ->get();

        if (!$schedules || $schedules->isEmpty()) {
            return back()->with('error', 'No schedule set for today ' . $dayName . '.');
        }

        $students = Student::where('class_id', $class->id)->get();

        // Reload grouped attendance
        $attendancesGrouped = Attendance::whereIn('schedule_id', $schedules->pluck('id'))
            ->where('date', $targetDate)
            ->get()
            ->groupBy('schedule_id')
            ->map(function ($items) {
                return $items->keyBy('student_id');
            });

        return view('teacher.classes.attendanceHistory', compact('class', 'students', 'schedules', 'attendancesGrouped', 'targetDate'));
    }

    public function submitAttendance(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $timeNow = now()->format('H:i:s');

        // Fetch the schedule to get its end_time
        $schedule = Schedule::findOrFail($request->schedule_id);
        $timeOutFixed = $schedule->end_time;

        foreach ($request->attendance as $studentId => $data) {
            $status = $data['status'];

            // Check if an attendance record already exists for this student/schedule/date
            $attendance = Attendance::where('student_id', $studentId)
                ->where('schedule_id', $request->schedule_id)
                ->where('date', $date)
                ->first();

            if (
                !$attendance ||
                $attendance->status !== $status
            ) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'schedule_id' => $request->schedule_id,
                        'date' => $date,
                    ],
                    [
                        'teacher_id' => $request->teacher_id,
                        'class_id' => $request->class_id,
                        'status' => $status,
                        'time_in' => !in_array($status, ['absent', 'excused']) ? $timeNow : null,
                        'time_out' => !in_array($status, ['absent', 'excused']) ? $timeOutFixed : null,
                    ]
                );
            }
        }

        return back()->with('success', 'Attendance submitted successfully!');
    }

    public function showScanner($grade, $section, $date = null, $schedule_id = null)
    {
        $date = $date ?? now()->toDateString();
        $class = Classes::where('grade_level', $grade)->where('section', $section)->firstOrFail();
        $dayName = \Carbon\Carbon::parse($date)->format('l');

        $schedules = Schedule::where('class_id', $class->id)
            ->where('teacher_id', Auth::id())
            ->where('day', $dayName)
            ->get();

        $schedule = $schedule_id ? Schedule::find($schedule_id) : $schedules->first();
        if (!$schedule) return back()->with('error', 'Schedule not found.');

        $gracePeriod = (int) request()->query('grace', 60); // default 60 minutes

        $students = Student::where('class_id', $class->id)->get();

        // ✅ Mark students who haven't scanned and class has ended as Absent
        $now = now();
        if ($now->gt(Carbon::parse($schedule->end_time))) {
            foreach ($students as $student) {
                $existing = Attendance::where([
                    'student_id' => $student->id,
                    'schedule_id' => $schedule->id,
                    'date' => $date
                ])->first();

                if (!$existing) {
                    Attendance::create([
                        'student_id' => $student->id,
                        'schedule_id' => $schedule->id,
                        'date' => $date,
                        'status' => 'absent',
                        'teacher_id' => Auth::id(),
                        'class_id' => $class->id,
                        'time_in' => null,
                        'time_out' => null,
                    ]);
                }
            }
        }

        // Reload students with updated attendance
        $students = Student::where('class_id', $class->id)
            ->with(['attendances' => function ($q) use ($date, $schedule_id) {
                $q->where('date', $date);
                if ($schedule_id) $q->where('schedule_id', $schedule_id);
            }])->get();

        return view('teacher.classes.qr_scan', compact(
            'grade',
            'section',
            'date',
            'students',
            'schedule_id',
            'schedule',
            'schedules',
            'class',
            'gracePeriod'
        ));
    }

    public function markAttendanceFromQR(Request $request)
    {
        $studentId = $request->input('student_id');
        $grade = $request->input('grade');
        $section = $request->input('section');
        $date = $request->input('date') ?? now()->toDateString();
        $scheduleId = $request->input('schedule_id');
        $graceMinutes = (int) $request->input('grace', 60); // Set default grace period to 60 minutes
        $customTimeout = $request->input('custom_timeout');

        // ✅ Validate custom timeout format (HH:MM)
        if ($customTimeout && !preg_match('/^\d{2}:\d{2}$/', $customTimeout)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid timeout format. Use HH:MM.'
            ]);
        }

        $student = Student::find($studentId);
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found']);
        }

        $class = Classes::where('grade_level', $grade)
            ->where('section', $section)
            ->first();

        if (!$class) {
            return response()->json(['success' => false, 'message' => 'Class not found.']);
        }

        if ($student->class_id !== $class->id) {
            Log::warning('Unauthorized QR scan attempt', [
                'student_id' => $student->id,
                'student_name' => $student->full_name,
                'scanned_class' => $grade . ' - ' . $section,
                'actual_class_id' => $student->class_id,
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'success' => false,
                'message' => '⛔ QR Code does not belong to this class (' . $grade . ' - ' . $section . ').'
            ]);
        }

        $schedule = Schedule::find($scheduleId);
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Schedule not found.']);
        }

        // ✅ Check if attendance already exists
        $existing = Attendance::where([
            'student_id' => $student->id,
            'schedule_id' => $schedule->id,
            'date' => $date
        ])->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ Student QR already scanned for this schedule.'
            ]);
        }

        $now = now();
        $startTime = Carbon::parse($schedule->start_time);

        if ($graceMinutes === -1) {
            $graceLimit = Carbon::parse($schedule->end_time);
        } else {
            $graceLimit = Carbon::parse($schedule->start_time)->addMinutes($graceMinutes);
        }

        $endTime = Carbon::parse($schedule->end_time);
        if ($now->lte($graceLimit)) {
            $status = 'present';
        } elseif ($now->gt($endTime)) {
            $status = 'absent';
        } else {
            $status = 'late';
        }

        // 🔧 Debugging line
        Log::debug("Now: $now | Start: $startTime | End: $endTime | Grace: $graceLimit | Status: $status");

        // ✅ Mark new attendance
        Attendance::create([
            'student_id' => $student->id,
            'schedule_id' => $schedule->id,
            'date' => $date,
            'status' => $status,
            'teacher_id' => Auth::id(),
            'class_id' => $class->id,
            'time_in' => now()->format('H:i:s'),
            'time_out' => $customTimeout ?? $schedule->end_time,
        ]);

        return response()->json([
            'success' => true,
            'student' => $student->full_name,
            'student_id' => $student->id,
            'status' => $status,
        ]);
    }

    public function markManualAttendance(Request $request)
    {
        $studentId = $request->input('student_id');
        $status = $request->input('status');
        $date = $request->input('date') ?? now()->toDateString();
        $scheduleId = $request->input('schedule_id');
        $customTimeout = $request->input('custom_timeout');

        // ✅ Validate custom timeout format (HH:MM)
        if ($customTimeout && !preg_match('/^\d{2}:\d{2}$/', $customTimeout)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid timeout format. Use HH:MM.'
            ]);
        }

        $student = Student::find($studentId);
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found']);
        }

        $schedule = Schedule::find($scheduleId);
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Schedule not found']);
        }

        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $student->id,
                'schedule_id' => $schedule->id,
                'date' => $date
            ],
            [
                'status' => $status,
                'teacher_id' => Auth::id(),
                'class_id' => $student->class_id,
                'time_in' => now()->format('H:i:s'),
                'time_out' => $customTimeout ?? $schedule->end_time,
            ]
        );

        return response()->json([
            'success' => true,
            'student_id' => $student->id,
            'student' => $student->full_name,
            'status' => $status
        ]);
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
