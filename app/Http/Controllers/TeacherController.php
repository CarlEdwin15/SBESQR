<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class TeacherController extends Controller
{
    public function myClasses(Request $request)
    {
        $teacher = User::find(Auth::id());

        $now = now();
        $year = $now->year;
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;

        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
        $nextSchoolYear = ($currentYear + 1) . '-' . ($currentYear + 2);

        // Fetch all saved school years
        $savedYears = SchoolYear::pluck('school_year')->toArray();

        // Extract numeric start years
        $savedStartYears = array_map(function ($sy) {
            return (int)substr($sy, 0, 4);
        }, $savedYears);

        $minYear = !empty($savedStartYears) ? min($savedStartYears) : $currentYear;

        // Build range of school years
        $schoolYears = [];
        for ($y = $minYear; $y <= $currentYear; $y++) {
            $schoolYears[] = $y . '-' . ($y + 1);
        }

        if (!in_array($currentSchoolYear, $schoolYears)) {
            $schoolYears[] = $currentSchoolYear;
        }
        if (!in_array($nextSchoolYear, $schoolYears)) {
            $schoolYears[] = $nextSchoolYear;
        }

        // Sort school years ascending
        usort($schoolYears, function ($a, $b) {
            return intval(substr($a, 0, 4)) <=> intval(substr($b, 0, 4));
        });

        // Selected school year
        $selectedYear = $request->input('school_year', $currentSchoolYear);
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        // Get all classes assigned to the teacher for that school year
        $classes = $teacher->classes()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->get();

        // Get distinct sections from those classes (no sorting)
        $sections = $classes->pluck('section')->unique()->values();

        // Section filter (optional)
        $selectedSection = $request->input('section');

        if ($selectedSection) {
            $classes = $classes->where('section', $selectedSection)->values();
        }

        // Attach adviser info
        foreach ($classes as $class) {
            $class->adviser = $class->teachers()
                ->wherePivot('school_year_id', $schoolYear->id)
                ->wherePivot('role', 'adviser')
                ->first();
        }

        return view('teacher.classes.index', [
            'classes' => $classes,
            'sections' => $sections,
            'selectedSection' => $selectedSection,
            'selectedYear' => $selectedYear,
            'schoolYears' => $schoolYears,
            'currentYear' => $currentYear,
            'section' => $selectedSection,
        ]);
    }

    public function myClass(Request $request, $grade_level, $section)
    {
        $selectedYear = $request->query('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        $class = $this->getClass($grade_level, $section);

        $studentCount = $class->students()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->count();

        $class->adviser = $class->teachers()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->wherePivot('role', 'adviser')
            ->first();

        $today = now()->format('Y-m-d');
        $dayName = now()->format('l');

        $schedulesToday = $class->schedules()
            ->where('day', $dayName)
            ->where('school_year_id', $schoolYear->id)
            ->get();

        $scheduleCount = $schedulesToday->count();
        $totalPossibleAttendance = $studentCount * $scheduleCount;

        $presentCount = Attendance::whereIn('schedule_id', $schedulesToday->pluck('id'))
            ->whereDate('date', $today)
            ->whereIn('status', ['present', 'late'])
            ->count();

        $attendanceToday = $totalPossibleAttendance > 0
            ? min(100, round(($presentCount / $totalPossibleAttendance) * 100))
            : 0;

        return view('teacher.classes.myClass', compact(
            'class',
            'studentCount',
            'presentCount',
            'totalPossibleAttendance',
            'attendanceToday',
            'selectedYear'
        ));
    }

    public function myClassMasterList(Request $request, $grade_level, $section)
    {
        $selectedYear = $request->input('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        $class = $this->getClass($grade_level, $section);

        $students = $class->students()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->get();

        $class->adviser = $class->teachers()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->wherePivot('role', 'adviser')
            ->first();

        return view('teacher.classes.myMasterList', compact('class', 'students', 'selectedYear'));
    }

    public function mySchedule(Request $request, $grade_level, $section)
    {
        $selectedYear = $request->input('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        $class = $this->getClass($grade_level, $section);
        $teacherId = Auth::id();

        $schedules = Schedule::where('class_id', $class->id)
            ->where('teacher_id', $teacherId)
            ->where('school_year_id', $schoolYear->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $teachers = $class->teachers()
            ->where('users.id', $teacherId)
            ->wherePivot('school_year_id', $schoolYear->id)
            ->wherePivotIn('role', ['adviser', 'subject_teacher'])
            ->get();

        return view('teacher.classes.mySchedule', compact('class', 'schedules', 'teachers', 'selectedYear'));
    }

    public function myAttendanceRecord(Request $request, $grade_level, $section)
    {
        $selectedYear = $request->input('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        $class = $this->getClass($grade_level, $section);

        $students = $class->students()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->orderBy('student_sex')
            ->orderBy('student_lName')
            ->orderBy('student_fName')
            ->orderBy('student_mName')
            ->get();

        $today = now();
        $isCurrentSchoolYear = $schoolYear->start_date <= $today && $today <= $schoolYear->end_date;

        if ($isCurrentSchoolYear) {
            // Use now() if within current school year
            $defaultMonth = $today->copy();
            if ($defaultMonth->lt(Carbon::parse($schoolYear->start_date))) {
                $defaultMonth = Carbon::parse($schoolYear->start_date);
            } elseif ($defaultMonth->gt(Carbon::parse($schoolYear->end_date))) {
                $defaultMonth = Carbon::parse($schoolYear->end_date);
            }
        } else {
            // For past/future school years, use the start date of the school year
            $defaultMonth = Carbon::parse($schoolYear->start_date);
        }

        $monthParam = request('month', $defaultMonth->format('Y-m'));
        $dateObj = Carbon::createFromFormat('Y-m', $monthParam);

        // Clamp date within school year bounds
        $schoolStart = Carbon::parse($schoolYear->start_date)->startOfMonth();
        $schoolEnd = Carbon::parse($schoolYear->end_date)->endOfMonth();

        if ($dateObj->lt($schoolStart)) {
            $dateObj = $schoolStart;
        } elseif ($dateObj->gt($schoolEnd)) {
            $dateObj = $schoolEnd;
        }

        $year = $dateObj->year;
        $month = $dateObj->month;

        $scheduleDays = Schedule::where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('teacher_id', Auth::id())
            ->pluck('day')
            ->map(fn($day) => Carbon::parse($day)->format('D'))
            ->toArray();

        $schedules = Schedule::where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('teacher_id', Auth::id())
            ->orderBy('start_time')
            ->get();

        $schedulesById = $schedules->keyBy('id');

        $attendances = Attendance::where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $calendarDates = [];
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
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
                'schedule_order' => $schedule->start_time,
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

        foreach ($attendanceData as &$studentAttendance) {
            foreach ($studentAttendance['by_date'] as &$entries) {
                usort($entries, fn($a, $b) => strcmp($a['schedule_order'], $b['schedule_order']));
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

        if ($request->has('__return_array__')) {
            return compact(
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
                'schedules',
                'selectedYear',
                'schoolYear'
            ) + ['selectedYearObj' => $schoolYear];
        }

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
            'schedules',
            'selectedYear',
            'schoolYear',
        ))->with('selectedYearObj', $schoolYear);
    }

    public function attendanceHistory(Request $request, $grade_level, $section, $date = null, $schedule_id = null)
    {
        $selectedYear = $request->input('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        $class = $this->getClass($grade_level, $section);

        // Use date from route (if present) or fallback to query or default
        $defaultDate = Carbon::parse($schoolYear->start_date)->toDateString();
        $targetDate = $date ?? $request->input('date', $defaultDate);
        $dateObj = Carbon::parse($targetDate);

        // Clamp targetDate within school year range
        $schoolStart = Carbon::parse($schoolYear->start_date);
        $schoolEnd = Carbon::parse($schoolYear->end_date);
        if ($dateObj->lt($schoolStart)) {
            $dateObj = $schoolStart;
        } elseif ($dateObj->gt($schoolEnd)) {
            $dateObj = $schoolEnd;
        }

        $targetDate = $dateObj->toDateString(); // Normalize date
        $year = $dateObj->year;
        $month = $dateObj->month;

        $students = $class->students()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->orderBy('student_sex')
            ->orderBy('student_lName')
            ->orderBy('student_fName')
            ->orderBy('student_mName')
            ->get();

        $schedules = Schedule::where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('teacher_id', Auth::id())
            ->where('day', ucfirst($dateObj->format('l')))
            ->orderBy('start_time')
            ->get();

        $attendanceData = Attendance::where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->whereDate('date', $targetDate)
            ->get();

        $attendancesGrouped = [];
        foreach ($attendanceData as $attendance) {
            $attendancesGrouped[$attendance->schedule_id][$attendance->student_id] = $attendance;
        }

        return view('teacher.classes.attendanceHistory', compact(
            'class',
            'students',
            'schedules',
            'attendancesGrouped',
            'targetDate',
            'selectedYear',
            'schoolYear'
        ));
    }

    public function submitAttendance(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $timeNow = now()->format('H:i:s');

        $schedule = Schedule::findOrFail($request->schedule_id);
        $timeOutFixed = $schedule->end_time;

        // Resolve the school year object (supports both ID or string format)
        $schoolYear = SchoolYear::where('school_year', $request->input('school_year'))
            ->orWhere('id', $request->input('school_year'))
            ->firstOrFail();

        foreach ($request->attendance as $studentId => $data) {
            $status = $data['status'];

            // Get the existing record for the day/schedule/student
            $attendance = Attendance::where('student_id', $studentId)
                ->where('schedule_id', $schedule->id)
                ->where('date', $date)
                ->where('school_year_id', $schoolYear->id)
                ->first();

            // Determine if we should write/update the record
            if (!$attendance || $attendance->status !== $status) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'schedule_id' => $schedule->id,
                        'date' => $date,
                        'school_year_id' => $schoolYear->id,
                    ],
                    [
                        'teacher_id' => $request->teacher_id,
                        'class_id' => $request->class_id,
                        'status' => $status,
                        'time_in' => in_array($status, ['present', 'late']) ? $timeNow : null,
                        'time_out' => in_array($status, ['present', 'late']) ? $timeOutFixed : null,
                    ]
                );
            }
        }

        return back()->with('success', 'Attendance submitted successfully!');
    }

    public function showScanner(Request $request, $grade_level, $section, $date = null, $schedule_id = null)
    {
        $selectedYear = $request->input('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        $date = $date ?? now()->toDateString();
        $class = $this->getClass($grade_level, $section);
        $dayName = Carbon::parse($date)->format('l');

        // ✅ Retrieve schedules for the class within the selected school year
        $schedules = Schedule::where('class_id', $class->id)
            ->where('teacher_id', Auth::id())
            ->where('school_year_id', $schoolYear->id)
            ->where('day', $dayName)
            ->orderBy('start_time')
            ->get();

        // ✅ If schedule_id is passed, validate it and ensure it's within the same class & year
        $schedule = $schedule_id ? Schedule::where('id', $schedule_id)
            ->where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->first() : $schedules->first();

        if (!$schedule) return back()->with('error', 'Schedule not found.');

        $gracePeriod = (int) $request->query('grace', 60); // default to 60 minutes

        // ✅ Filter students enrolled in this class and school year
        $students = $class->students()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->orderBy('student_sex')
            ->orderBy('student_lName')
            ->orderBy('student_fName')
            ->orderBy('student_mName')
            ->get();

        $now = now();

        // ✅ Mark absent only if the time passed AND the teacher explicitly triggered it
        if ($now->gt(Carbon::parse($schedule->end_time)) && $request->has('mark_absent')) {
            foreach ($students as $student) {
                $existing = Attendance::where([
                    'student_id' => $student->id,
                    'schedule_id' => $schedule->id,
                    'school_year_id' => $schoolYear->id,
                    'date' => $date
                ])->first();

                if (!$existing) {
                    Attendance::create([
                        'student_id' => $student->id,
                        'schedule_id' => $schedule->id,
                        'school_year_id' => $schoolYear->id,
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

        // ✅ Reload students with attendance on that date and schedule for this school year
        $students = $class->students()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->with(['attendances' => function ($q) use ($date, $schedule, $schoolYear) {
                $q->where('date', $date)
                    ->where('schedule_id', $schedule->id)
                    ->where('school_year_id', $schoolYear->id);
            }])
            ->orderBy('student_sex')
            ->orderBy('student_lName')
            ->orderBy('student_fName')
            ->orderBy('student_mName')
            ->get();

        return view('teacher.classes.qr_scan', compact(
            'grade_level',
            'section',
            'date',
            'students',
            'schedule_id',
            'schedule',
            'schedules',
            'class',
            'gracePeriod',
            'schoolYear',
            'selectedYear'
        ));
    }

    public function markAttendanceFromQR(Request $request)
    {
        $selectedYear = $request->input('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        // Validate required fields
        $studentId = $request->input('student_id');
        $grade_level = $request->input('grade_level');
        $section = $request->input('section');
        $date = $request->input('date') ?? now()->toDateString();
        $scheduleId = $request->input('schedule_id');
        $graceMinutes = (int) $request->input('grace', 60);
        $customTimeout = $request->input('custom_timeout');

        // Validate custom timeout format (HH:MM)
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

        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->first();

        if (!$class) {
            return response()->json(['success' => false, 'message' => 'Class not found.']);
        }

        // Check if student is enrolled in the current school year (in any class)
        $enrolledThisYear = DB::table('class_student')
            ->where('student_id', $student->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('enrollment_status', 'enrolled')
            ->exists();

        if (!$enrolledThisYear) {
            return response()->json([
                'success' => false,
                'message' => '⛔ Student is not yet enrolled for the current school year.'
            ]);
        }

        // Then check if student is in the scanned class
        $studentInClass = $class->students()
            ->where('students.id', $student->id)
            ->wherePivot('school_year_id', $schoolYear->id)
            ->exists();

        if (!$studentInClass) {
            // Log unauthorized scan attempt
            Log::warning('Unauthorized QR scan attempt (Wrong class)', [
                'student_id' => $student->id,
                'student_name' => $student->full_name,
                'scanned_class' => $grade_level . ' - ' . $section,
                'actual_class_id' => $student->class_id,
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'success' => false,
                'message' => '⛔ QR Code does not belong to this class (' . $grade_level . ' - ' . $section . ').'
            ]);
        }

        $schedule = Schedule::find($scheduleId);
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Schedule not found.']);
        }

        // Resolve school year
        $schoolYear = SchoolYear::where('school_year', $request->input('school_year', $this->getDefaultSchoolYear()))
            ->orWhere('id', $request->input('school_year'))
            ->first();

        if (!$schoolYear) {
            return response()->json(['success' => false, 'message' => 'School year not found.']);
        }

        // Check if attendance already exists
        $existing = Attendance::where([
            'student_id' => $student->id,
            'schedule_id' => $schedule->id,
            'school_year_id' => $schoolYear->id,
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
        $endTime = Carbon::parse($schedule->end_time);
        $graceLimit = ($graceMinutes === -1) ? $endTime : $startTime->copy()->addMinutes($graceMinutes);

        $status = $now->lte($graceLimit) ? 'present' : ($now->gt($endTime) ? 'absent' : 'late');

        // Debug
        Log::debug("Now: $now | Start: $startTime | End: $endTime | Grace: $graceLimit | Status: $status");

        // Mark new attendance
        Attendance::create([
            'student_id' => $student->id,
            'schedule_id' => $schedule->id,
            'school_year_id' => $schoolYear->id,
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
        $selectedYear = $request->input('school_year', $this->getDefaultSchoolYear());

        $schoolYear = SchoolYear::where('school_year', $selectedYear)
            ->orWhere('id', $selectedYear)
            ->first();

        if (!$schoolYear) {
            return response()->json(['success' => false, 'message' => 'School year not found.']);
        }

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

        // ✅ Ensure attendance is specific to school year
        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $student->id,
                'schedule_id' => $schedule->id,
                'school_year_id' => $schoolYear->id, // 🔄 Match QR attendance condition
                'date' => $date
            ],
            [
                'status' => $status,
                'teacher_id' => Auth::id(),
                'class_id' => $schedule->class_id,
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

    // Helper function to get the default school year (e.g., "2024-2025")
    private function getDefaultSchoolYear()
    {
        $now = now();
        $year = $now->year;
        $cutoff = now()->copy()->setMonth(6)->setDay(1);
        $start = $now->lt($cutoff) ? $year - 1 : $year;

        return $start . '-' . ($start + 1);
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
