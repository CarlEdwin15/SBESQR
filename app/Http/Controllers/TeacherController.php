<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\ClassStudent;
use App\Models\ClassSubject;
use App\Models\FinalSubjectGrade;
use App\Models\Schedule;
use App\Models\SchoolYear;
use App\Models\QuarterlyGrade;
use App\Models\Subject;
use App\Services\TwilioService;
use App\Services\ItexmoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // make sure barryvdh/laravel-dompdf is installed
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        $now = now();
        $year = $now->year;
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;

        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
        $nextSchoolYear = ($currentYear + 1) . '-' . ($currentYear + 2);

        // Fetch school years
        $savedYears = SchoolYear::pluck('school_year')->toArray();
        $savedStartYears = array_map(fn($sy) => (int)substr($sy, 0, 4), $savedYears);
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

        // Get selected year from query, default to current
        $selectedYear = $request->query('school_year', $currentSchoolYear);

        // Find the SchoolYear model + ID
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->first();
        $schoolYearId = optional($schoolYear)->id;

        if (!$schoolYear) {
            abort(404, 'School year not found');
        }

        // Fetch the class
        $class = $this->getClass($grade_level, $section);

        // Check if the authenticated teacher is the adviser of this class for the selected school year
        $isAdviser = auth()->user()->classes()
            ->where('classes.id', $class->id)
            ->wherePivot('role', 'adviser')
            ->wherePivot('school_year_id', $schoolYearId)
            ->exists();

        // Fetch students with their class student data
        $students = $class->students()
            ->wherePivot('school_year_id', $schoolYearId)
            ->with(['classStudents' => function ($query) use ($class, $schoolYearId) {
                $query->where('class_id', $class->id)
                    ->where('school_year_id', $schoolYearId);
            }])
            ->get();

        // Fetch adviser for that class in the given school year
        $class->adviser = $class->teachers()
            ->wherePivot('school_year_id', $schoolYearId)
            ->wherePivot('role', 'adviser')
            ->first();

        return view('teacher.classes.masterlists.myMasterList', compact(
            'class',
            'students',
            'schoolYears',
            'selectedYear',
            'schoolYearId',
            'currentYear',
            'isAdviser' // Add this variable
        ));
    }

    public function updateGradePermission(Request $request)
    {
        try {
            Log::info('Update Grade Permission Request:', $request->all());

            $request->validate([
                'student_id' => 'required|exists:students,id',
                'class_id' => 'required|exists:classes,id',
                'school_year_id' => 'required|exists:school_years,id',
                'quarter' => 'required|in:q1,q2,q3,q4',
                'allow_view' => 'required|boolean'
            ]);

            // Additional security check: verify the teacher is the adviser for this class
            $isAdviser = Auth::user()->classes()
                ->where('classes.id', $request->class_id)
                ->wherePivot('role', 'adviser')
                ->wherePivot('school_year_id', $request->school_year_id)
                ->exists();

            if (!$isAdviser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: Only class advisers can update grade permissions.'
                ], 403);
            }

            $classStudent = ClassStudent::where('student_id', $request->student_id)
                ->where('class_id', $request->class_id)
                ->where('school_year_id', $request->school_year_id)
                ->first();

            Log::info('ClassStudent found:', [$classStudent ? $classStudent->toArray() : 'Not found']);

            if (!$classStudent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found in this class for the selected school year.'
                ], 404);
            }

            // Update the specific quarter permission
            $column = $request->quarter . '_allow_view';

            Log::info('Updating column:', [
                'column' => $column,
                'value' => $request->allow_view
            ]);

            $classStudent->update([
                $column => $request->allow_view
            ]);

            Log::info('Update successful');

            return response()->json([
                'success' => true,
                'message' => 'Grade viewing permission updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating grade permission:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating permission: ' . $e->getMessage()
            ], 500);
        }
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

        return view('teacher.classes.schedules.mySchedule', compact('class', 'schedules', 'teachers', 'selectedYear'));
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
            ->unique()
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

        return view('teacher.classes.attendances.myAttendanceRecord', compact(
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

        return view('teacher.classes.attendances.attendanceHistory', compact(
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

        // Retrieve schedules for the class within the selected school year
        $schedules = Schedule::where('class_id', $class->id)
            ->where('teacher_id', Auth::id())
            ->where('school_year_id', $schoolYear->id)
            ->where('day', $dayName)
            ->orderBy('start_time')
            ->get();

        // If schedule_id is passed, validate it and ensure it's within the same class & year
        $schedule = $schedule_id ? Schedule::where('id', $schedule_id)
            ->where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->first() : $schedules->first();

        if (!$schedule) return back()->with('error', 'Schedule not found.');

        $gracePeriod = (int) $request->query('grace', 60); // default to 60 minutes

        // Filter students enrolled in this class and school year
        $students = $class->students()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->orderBy('student_sex')
            ->orderBy('student_lName')
            ->orderBy('student_fName')
            ->orderBy('student_mName')
            ->get();

        $now = now();

        // Mark absent only if the time passed AND the teacher explicitly triggered it
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

        // Reload students with attendance on that date and schedule for this school year
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

        return view('teacher.classes.attendances.qr_scan', compact(
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

        // Fetch student
        $student = Student::find($studentId);
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found.']);
        }

        // Verify class
        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->first();

        if (!$class) {
            return response()->json(['success' => false, 'message' => 'Class not found.']);
        }

        // Check if student is enrolled this school year
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

        // Check if student belongs to the scanned class
        $studentInClass = $class->students()
            ->where('students.id', $student->id)
            ->wherePivot('school_year_id', $schoolYear->id)
            ->exists();

        if (!$studentInClass) {
            Log::warning('Unauthorized QR scan attempt (Wrong class)', [
                'student_id' => $student->id,
                'student_name' => $student->full_name,
                'scanned_class' => $grade_level . ' - ' . $section,
                'actual_class_id' => $student->class_id,
                'timestamp' => now()->toDateTimeString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => '⛔ QR Code does not belong to this class (' . $grade_level . ' - ' . $section . ').'
            ]);
        }

        // Verify schedule
        $schedule = Schedule::find($scheduleId);
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Schedule not found.']);
        }

        // Reconfirm school year
        $schoolYear = SchoolYear::where('school_year', $request->input('school_year', $this->getDefaultSchoolYear()))
            ->orWhere('id', $request->input('school_year'))
            ->first();

        if (!$schoolYear) {
            return response()->json(['success' => false, 'message' => 'School year not found.']);
        }

        // Check for duplicate attendance record
        $existing = Attendance::where([
            'student_id' => $student->id,
            'schedule_id' => $schedule->id,
            'school_year_id' => $schoolYear->id,
            'date' => $date,
        ])->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ Student QR already scanned for this schedule.'
            ]);
        }

        // Determine attendance status
        $now = now();
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);
        $graceLimit = ($graceMinutes === -1) ? $endTime : $startTime->copy()->addMinutes($graceMinutes);

        $status = $now->lte($graceLimit)
            ? 'present'
            : ($now->gt($endTime) ? 'absent' : 'late');

        Log::debug("Now: $now | Start: $startTime | End: $endTime | Grace: $graceLimit | Status: $status");

        // Record attendance
        $attendance = Attendance::create([
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

        // $parents = $student->parents()->whereNotNull('phone')->get();
        // $timeIn = Carbon::parse($attendance->time_in)->format('h:i A');
        // $timeOut = Carbon::parse($attendance->time_out)->format('h:i A');
        // $formattedDate = Carbon::parse($attendance->date)->format('M d, Y');
        // $currentClass = "{$class->grade_level} - {$class->section}";

        // if ($parents->count() > 0) {
        //     $semaphore = new \App\Services\SemaphoreService();

        //     // Collect all parent numbers
        //     $numbers = [];
        //     foreach ($parents as $parent) {
        //         $phone = $parent->phone;

        //         $phone = preg_replace('/[^0-9]/', '', $phone); // Remove non-numeric
        //         if (str_starts_with($phone, '0')) {
        //             $phone = '+63' . substr($phone, 1);
        //         } elseif (str_starts_with($phone, '63')) {
        //             $phone = '+' . $phone;
        //         } elseif (!str_starts_with($phone, '+63')) {
        //             $phone = '+63' . $phone; // fallback
        //         }

        //         $numbers[] = $phone;
        //     }

        //     // Build and send message depending on status
        //     foreach ($parents as $parent) {
        //         $studentFullName = "{$student->student_fName} {$student->student_lName}";

        //         switch ($status) {
        //             case 'present':
        //                 $message = "Dear {$parent->firstName}, "
        //                     . "We are pleased to inform you that your child, {$studentFullName}, "
        //                     . "enrolled in {$currentClass}, has attended the ({$formattedDate}) {$schedule->subject_name} class. "
        //                     . "The recorded Time In is {$timeIn}, and the scheduled Time Out is {$timeOut}. "
        //                     . "Thank you for your continued support.";
        //                 break;

        //             case 'late':
        //                 $message = "Dear {$parent->firstName}, "
        //                     . "Please be informed that your child, {$studentFullName}, "
        //                     . "enrolled in {$currentClass}, arrived late for today's ({$formattedDate}) {$schedule->subject_name} class. "
        //                     . "The recorded Time In is {$timeIn}, and the scheduled Time Out is {$timeOut}. "
        //                     . "Kindly remind your child of the importance of punctuality. Thank you.";
        //                 break;

        //             case 'absent':
        //                 $message = "Dear {$parent->firstName}, "
        //                     . "This is to inform you that your child, {$studentFullName}, "
        //                     . "enrolled in {$currentClass}, was marked as ABSENT for today's ({$formattedDate}) {$schedule->subject_name} class. "
        //                     . "If your child was unable to attend due to a valid reason, please notify the school adviser. Thank you.";
        //                 break;

        //             case 'excused':
        //                 $message = "Dear {$parent->firstName}, "
        //                     . "Please be advised that your child, {$studentFullName}, "
        //                     . "enrolled in {$currentClass}, has been marked as EXCUSED for today's ({$formattedDate}) {$schedule->subject_name} class. "
        //                     . "The school acknowledges the reason provided for the absence. Thank you for keeping us informed.";
        //                 break;

        //             default:
        //                 $message = "Dear {$parent->firstName}, "
        //                     . "This is to inform you that your child, {$studentFullName}, "
        //                     . "enrolled in {$currentClass}, has been marked as '{$status}' for today's ({$formattedDate}) {$schedule->subject_name} class.";
        //                 break;
        //         }

        //         // Send via queued SMS job
        //         \App\Jobs\SendAttendanceSMS::dispatchAfterResponse($student, $status, $schedule, $attendance);
        //         $sent = true;

        //         if (!$sent) {
        //             Log::warning("Failed to send SMS to parent {$parent->id} ({$parent->phone})");
        //         }
        //     }
        // } else {
        //     Log::warning("No parent phone number found for student ID {$student->id}");
        // }

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

        // Validate custom timeout format (HH:MM)
        if ($customTimeout && !preg_match('/^\d{2}:\d{2}$/', $customTimeout)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid timeout format. Use HH:MM.'
            ]);
        }

        // Fetch student
        $student = Student::find($studentId);
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found.']);
        }

        // Fetch schedule
        $schedule = Schedule::find($scheduleId);
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Schedule not found.']);
        }

        // Fetch the class linked to this schedule
        $class = Classes::find($schedule->class_id);
        if (!$class) {
            return response()->json(['success' => false, 'message' => 'Class not found.']);
        }

        // Check if student is enrolled in this class for the current school year
        $enrolledThisYear = DB::table('class_student')
            ->where('student_id', $student->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('class_id', $class->id)
            ->where('enrollment_status', 'enrolled')
            ->exists();

        if (!$enrolledThisYear) {
            return response()->json([
                'success' => false,
                'message' => '⛔ Student is not enrolled in this class for the selected school year.'
            ]);
        }

        // Prevent duplicate attendance entries for same schedule/date
        $existing = Attendance::where([
            'student_id' => $student->id,
            'schedule_id' => $schedule->id,
            'school_year_id' => $schoolYear->id,
            'date' => $date,
        ])->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ Attendance already recorded for this student and schedule.'
            ]);
        }

        // Record attendance
        $attendance = Attendance::create([
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

        // Dispatch the same SMS job as the QR scanner does
        // \App\Jobs\SendAttendanceSMS::dispatchAfterResponse($student, $status, $schedule, $attendance);

        return response()->json([
            'success' => true,
            'student_id' => $student->id,
            'student' => $student->full_name,
            'status' => $status
        ]);
    }

    public function myClassSubject(Request $request, $grade_level, $section)
    {
        $selectedYear = $request->query('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        $class = $this->getClass($grade_level, $section);
        $teacherId = Auth::id();

        // Check if the current teacher is the adviser of this class in this school year
        $adviser = $class->teachers()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->wherePivot('role', 'adviser')
            ->first();

        if ($adviser && $adviser->id == $teacherId) {
            // Adviser: fetch ALL subjects in this class for this school year
            $subjects = ClassSubject::with(['subject', 'quarters', 'teacher'])
                ->where('class_id', $class->id)
                ->where('school_year_id', $schoolYear->id)
                ->get();
        } else {
            // Subject Teacher: fetch ONLY their assigned subjects
            $subjects = ClassSubject::with(['subject', 'quarters', 'teacher'])
                ->where('class_id', $class->id)
                ->where('school_year_id', $schoolYear->id)
                ->where('teacher_id', $teacherId)
                ->get();
        }

        // Attach adviser info
        $class->adviser = $adviser;

        return view('teacher.classes.subject_grades.myClassSubject', [
            'class'         => $class,
            'classSubjects' => $subjects,
            'selectedYear'  => $selectedYear,
            'schoolYear'    => $schoolYear
        ]);
    }

    public function createSubject(Request $request, $grade_level, $section)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'custom_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $subjectName = $request->name === "Others" ? $request->custom_name : $request->name;

        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        $schoolYear = SchoolYear::where('school_year', $request->selected_school_year)
            ->firstOrFail();

        // Ensure subject exists in master list (no description here)
        $subject = Subject::firstOrCreate(['name' => $subjectName]);

        // Prevent duplicate class_subject entry
        $exists = ClassSubject::where('class_id', $class->id)
            ->where('subject_id', $subject->id)
            ->where('school_year_id', $schoolYear->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'name' => 'This subject already exists for this class and school year.'
            ])->withInput();
        }

        // Create pivot with teacher’s custom description
        ClassSubject::create([
            'class_id'       => $class->id,
            'subject_id'     => $subject->id,
            'school_year_id' => $schoolYear->id,
            'teacher_id'     => Auth::id(),
            'description'    => $request->description, // 🔑 moved here
        ]);

        return redirect()->back()->with('success', 'Subject created successfully!');
    }

    public function deleteSubject(Request $request, $grade_level, $section, $subject_id)
    {
        $schoolYear = SchoolYear::where('school_year', $request->query('school_year', $this->getDefaultSchoolYear()))
            ->firstOrFail();

        $class = $this->getClass($grade_level, $section);
        $teacherId = Auth::id();

        // Check if the classSubject exists
        $classSubject = ClassSubject::where('id', $subject_id)
            ->where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->firstOrFail();

        // Only the assigned teacher or the adviser can delete it
        $adviser = $class->teachers()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->wherePivot('role', 'adviser')
            ->first();

        if ($classSubject->teacher_id !== $teacherId && (!$adviser || $adviser->id !== $teacherId)) {
            return back()->with('error', 'You are not authorized to delete this subject.');
        }

        // Delete subject
        $classSubject->delete();

        return redirect()
            ->route('teacher.myClassSubject', [
                'grade_level' => $grade_level,
                'section' => $section,
                'school_year' => $schoolYear->school_year,
            ])
            ->with('success', 'Subject deleted successfully.');
    }

    public function viewSubject(Request $request, $grade_level, $section, $subject_id)
    {
        $now = now();
        $year = $now->year;
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;

        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
        $nextSchoolYear = ($currentYear + 1) . '-' . ($currentYear + 2);

        // Fetch school years from DB
        $savedYears = SchoolYear::pluck('school_year')->toArray();
        $savedStartYears = array_map(fn($sy) => (int)substr($sy, 0, 4), $savedYears);
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

        // Selected year
        $selectedYear = $request->query('school_year', $currentSchoolYear);

        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();
        $schoolYearId = $schoolYear->id;

        // Fetch class
        $class = $this->getClass($grade_level, $section);
        $teacherId = Auth::id();

        // Get adviser
        $adviser = $class->teachers()
            ->wherePivot('school_year_id', $schoolYearId)
            ->wherePivot('role', 'adviser')
            ->first();

        // Get classSubject
        if ($adviser && $adviser->id == $teacherId) {
            // Adviser can open ANY subject of this class/year
            $classSubject = ClassSubject::with(['subject', 'quarters', 'teacher'])
                ->where('class_id', $class->id)
                ->where('school_year_id', $schoolYearId)
                ->where('id', $subject_id)
                ->firstOrFail();
        } else {
            // Subject teacher can only open their own subject
            $classSubject = ClassSubject::with(['subject', 'quarters', 'teacher'])
                ->where('class_id', $class->id)
                ->where('school_year_id', $schoolYearId)
                ->where('teacher_id', $teacherId)
                ->where('id', $subject_id)
                ->firstOrFail();
        }

        $class->adviser = $adviser;

        // Fetch students
        $students = $class->students()
            ->wherePivot('school_year_id', $schoolYearId)
            ->wherePivot('enrollment_status', 'enrolled')
            ->with([
                'quarterlyGrades.quarter' => function ($q) use ($classSubject) {
                    $q->where('class_subject_id', $classSubject->id);
                },
                'finalSubjectGrades' => function ($q) use ($classSubject) {
                    $q->where('class_subject_id', $classSubject->id);
                }
            ])
            ->get();

        // Flag: is this teacher allowed to edit grades?
        $canEdit = $classSubject->teacher_id == $teacherId;

        return view('teacher.classes.subject_grades.viewSubject', [
            'class'        => $class,
            'classSubject' => $classSubject,
            'selectedYear' => $selectedYear,
            'schoolYear'   => $schoolYear,
            'schoolYearId' => $schoolYearId,
            'students'     => $students,
            'schoolYears'  => $schoolYears,
            'currentYear'  => $currentYear,
            'canEdit'      => $canEdit,
        ]);
    }

    public function saveGrades(Request $request, $grade_level, $section, $subject_id)
    {
        $selectedYear = $request->query('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        $class = $this->getClass($grade_level, $section);
        $teacherId = Auth::id();

        $classSubject = ClassSubject::where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('teacher_id', $teacherId)
            ->where('id', $subject_id)
            ->firstOrFail();

        // [quarterNumber => quarterId]
        $quarters = $classSubject->quarters()->pluck('id', 'quarter')->toArray();

        foreach ($request->input('grades', []) as $studentId => $gradeData) {

            // Make sure $gradeData is an array (defensive)
            if (!is_array($gradeData)) {
                continue;
            }

            foreach ([1, 2, 3, 4] as $q) {
                $quarterId = $quarters[$q] ?? null;
                if (!$quarterId) continue;

                if (!array_key_exists("q{$q}", $gradeData)) {
                    continue;
                }

                $grade = $gradeData["q{$q}"];

                if ($grade === null || $grade === '') {
                    QuarterlyGrade::where('student_id', $studentId)
                        ->where('quarter_id', $quarterId)
                        ->delete();
                    continue;
                }

                if (!is_numeric($grade)) continue;

                $gradeValue = round(floatval($grade), 2);
                if ($gradeValue < 60 || $gradeValue > 100) {
                    return back()
                        ->with('error', "Grade for student ID {$studentId} must be between 60 and 100.")
                        ->withInput();
                }

                // ✅ NEW: check if previous quarters exist for this student
                if ($q > 1) {
                    $previousQuarterIds = array_values(array_slice($quarters, 0, $q - 1));
                    $missingPrev = QuarterlyGrade::where('student_id', $studentId)
                        ->whereIn('quarter_id', $previousQuarterIds)
                        ->where(function ($query) {
                            $query->whereNull('final_grade')
                                ->orWhere('final_grade', '');
                        })
                        ->count();

                    if ($missingPrev > 0) {
                        return back()
                            ->with('error', "Cannot enter Quarter {$q} grade for student ID {$studentId}. Please complete previous quarters first.")
                            ->withInput();
                    }
                }

                // ✅ Save valid grade
                QuarterlyGrade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'quarter_id' => $quarterId,
                    ],
                    [
                        'final_grade' => $gradeValue,
                    ]
                );
            }

            // Recalculate final average based on remaining quarterly grades in DB
            $quarterIds = array_values($quarters); // ensure values-only for whereIn
            $existingGrades = QuarterlyGrade::whereIn('quarter_id', $quarterIds)
                ->where('student_id', $studentId)
                ->pluck('final_grade')
                ->filter(fn($g) => $g !== null && $g !== '')
                ->map(fn($g) => floatval($g))
                ->toArray();

            if (count($existingGrades) > 0) {
                $finalAverage = round(array_sum($existingGrades) / count($existingGrades), 2);

                FinalSubjectGrade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_subject_id' => $classSubject->id,
                    ],
                    [
                        'final_grade' => $finalAverage,
                        'remarks'     => $finalAverage >= 75 ? 'passed' : 'failed',
                    ]
                );
            } else {
                // No quarterly grades remain — delete final subject grade
                FinalSubjectGrade::where('student_id', $studentId)
                    ->where('class_subject_id', $classSubject->id)
                    ->delete();
            }
        }

        return back()->with('success');
    }

    public function deleteGrade(Request $request, $grade_level, $section, $subject_id, $student_id, $quarter)
    {
        $selectedYear = $request->query('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        $class = $this->getClass($grade_level, $section);
        $teacherId = Auth::id();

        $classSubject = ClassSubject::where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('teacher_id', $teacherId)
            ->where('id', $subject_id)
            ->firstOrFail();

        $quarterId = $classSubject->quarters()->where('quarter', $quarter)->value('id');

        if ($quarterId) {
            QuarterlyGrade::where('student_id', $student_id)
                ->where('quarter_id', $quarterId)
                ->delete();
        }

        // Recompute final grade after deletion
        $quarterGrades = QuarterlyGrade::where('student_id', $student_id)
            ->whereIn('quarter_id', $classSubject->quarters()->pluck('id'))
            ->pluck('final_grade')
            ->filter()
            ->toArray();

        if (count($quarterGrades) > 0) {
            $finalAverage = round(array_sum($quarterGrades) / count($quarterGrades), 2);
            FinalSubjectGrade::updateOrCreate(
                [
                    'student_id' => $student_id,
                    'class_subject_id' => $classSubject->id,
                ],
                [
                    'final_grade' => $finalAverage,
                    'remarks' => $finalAverage >= 75 ? 'passed' : 'failed',
                ]
            );
        } else {
            FinalSubjectGrade::where('student_id', $student_id)
                ->where('class_subject_id', $classSubject->id)
                ->delete();
        }

        return back()->with('success', 'Grade deleted successfully!');
    }

    public function exportQuarterlyGrades(Request $request, $grade_level, $section, $subject_id)
    {
        $now = now();
        $year = $now->year;
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;

        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);

        // Fetch school year
        $schoolYear = SchoolYear::where('school_year', $currentSchoolYear)->firstOrFail();
        $schoolYearId = $schoolYear->id;

        // Fetch class
        $class = $this->getClass($grade_level, $section);
        $teacherId = Auth::id();

        // Class subject
        $classSubject = ClassSubject::with(['subject', 'quarters', 'teacher'])
            ->where('class_id', $class->id)
            ->where('school_year_id', $schoolYearId)
            ->where('teacher_id', $teacherId)
            ->where('id', $subject_id)
            ->firstOrFail();

        // Adviser
        $class->adviser = $class->teachers()
            ->wherePivot('school_year_id', $schoolYearId)
            ->wherePivot('role', 'adviser')
            ->first();

        // Students
        $students = $class->students()
            ->wherePivot('school_year_id', $schoolYearId)
            ->wherePivot('enrollment_status', 'enrolled')
            ->with([
                'quarterlyGrades.quarter' => function ($q) use ($classSubject) {
                    $q->where('class_subject_id', $classSubject->id);
                },
                'finalSubjectGrades' => function ($q) use ($classSubject) {
                    $q->where('class_subject_id', $classSubject->id);
                }
            ])
            ->get();

        $pdf = Pdf::loadView('pdf.quarterly_grade', [
            'class'        => $class,
            'classSubject' => $classSubject,
            'schoolYear'   => $schoolYear,
            'students'     => $students,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('Quarterly_Grades.pdf');
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




    public function myStudents(Request $request)
    {
        $teacher = Auth::user();

        $now = now();
        $year = $now->year;
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;

        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
        $nextSchoolYear = ($currentYear + 1) . '-' . ($currentYear + 2);

        // Fetch school years
        $savedYears = SchoolYear::pluck('school_year')->toArray();
        $savedStartYears = array_map(fn($sy) => (int)substr($sy, 0, 4), $savedYears);
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
        $selectedSection = $request->query('section');

        $schoolYear = SchoolYear::where('school_year', $selectedYear)->first();
        $schoolYearId = optional($schoolYear)->id;

        $students = collect();
        $sections = [];
        $assignedGrades = [];

        if ($schoolYear) {
            // Get class IDs and grade levels assigned to this teacher in this school year
            $teacherClasses = $teacher->classes()
                ->where('school_year_id', $schoolYearId)
                ->get();

            $teacherClassIds = $teacherClasses->pluck('id');
            $assignedGrades = $teacherClasses->pluck('formatted_grade_level')->unique()->values()->all();

            $sections = Classes::whereIn('id', $teacherClassIds)
                ->pluck('section')->unique()->sort()->values()->all();

            // Fetch students enrolled in teacher's classes
            $students = Student::whereHas('class', function ($query) use ($teacherClassIds, $schoolYearId, $selectedSection) {
                $query->whereIn('classes.id', $teacherClassIds)
                    ->where('class_student.school_year_id', $schoolYearId);

                if (!empty($selectedSection)) {
                    $query->where('section', $selectedSection);
                }
            })->with([
                'address',
                'parents',
                'class' => function ($query) use ($schoolYearId) {
                    $query->where('class_student.school_year_id', $schoolYearId);
                }
            ])->get();
        }

        // Filter only assigned grades
        $groupedStudents = collect();
        foreach ($assignedGrades as $grade) {
            $groupedStudents[$grade] = $students->filter(function ($student) use ($grade, $schoolYearId) {
                $classForYear = $student->class->firstWhere('pivot.school_year_id', $schoolYearId);
                return optional($classForYear)->formatted_grade_level === $grade;
            });
        }

        return view('teacher.students.myStudents', compact(
            'groupedStudents',
            'schoolYears',
            'selectedYear',
            'sections',
            'selectedSection',
            'currentYear',
            'schoolYearId'
        ));
    }

    public function studentInfo($student_id, Request $request)
    {
        $schoolYearId = $request->query('school_year');

        if (!$schoolYearId) {
            $schoolYearId = SchoolYear::latest('start_date')->value('id');
        }

        $student = Student::with([
            'address',
            'parents',
            'class' => function ($query) {
                $query->with('schoolYear');
            },
            'schoolYears'
        ])->findOrFail($student_id);

        $class = $student->class()
            ->wherePivot('school_year_id', $schoolYearId)
            ->first();

        $schoolYear = SchoolYear::find($schoolYearId);

        // === ADD THIS STATUS LOGIC (same as admin) ===
        // Determine status
        $latestEnrollment = $student->classStudents()->latest()->first();
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
            $lastEnrollment = $student->classStudents()
                ->where('enrollment_status', 'enrolled')
                ->latest()
                ->first();

            $lastSY = $lastEnrollment?->schoolYear?->school_year;
            $statusInfo = $lastSY ? "Last Enrolled: {$lastSY}" : 'No recent enrollment';
        } elseif ($studentStatus === 'graduated') {
            $graduatedRecord = $student->classStudents()
                ->where('enrollment_status', 'graduated')
                ->latest()
                ->first();

            $gradSY = $graduatedRecord?->schoolYear?->school_year;
            $statusInfo = $gradSY ? "Graduated: {$gradSY}" : 'Graduation year not recorded';
        }
        // === END STATUS LOGIC ===

        // Fetch and reorder classes (latest/current first)
        $classHistory = $student->class()
            ->with([
                'schoolYear',
                'advisers' => function ($q) {
                    $q->wherePivot('role', 'adviser');
                }
            ])
            ->get();

        // Sort: enrolled first, then by latest school_year_id
        $classHistory = $classHistory
            ->sortByDesc(function ($classItem) {
                return $classItem->pivot->enrollment_status === 'enrolled' ? 2 : 1;
            })
            ->sortByDesc(function ($classItem) {
                return $classItem->pivot->school_year_id;
            })
            ->values();

        // Prepare storage
        $gradesByClass = [];
        $generalAverages = [];

        foreach ($classHistory as $classItem) {
            $classSubjects = $classItem->classSubjects()
                ->with(['subject', 'quarters.quarterlyGrades' => function ($q) use ($student) {
                    $q->where('student_id', $student->id);
                }])
                ->where('school_year_id', $classItem->pivot->school_year_id)
                ->get();

            $subjectsWithGrades = [];
            $finalGrades = [];

            foreach ($classSubjects as $classSubject) {
                // Collect quarters
                $quarters = $classSubject->quarters->map(function ($quarter) use ($student) {
                    return [
                        'quarter' => $quarter->quarter,
                        'grade' => optional($quarter->quarterlyGrades->first())->final_grade,
                    ];
                });

                // Check if all 4 quarters are present
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

            // General Average: only if ALL subjects have final grades
            $totalSubjects = count($classSubjects);
            $completedSubjects = count($finalGrades);

            if ($totalSubjects > 0 && $completedSubjects === $totalSubjects) {
                $generalAverage = round(array_sum($finalGrades) / $completedSubjects, 2);
                $remarks = $generalAverage >= 75 ? 'passed' : 'failed';

                \App\Models\GeneralAverage::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'school_year_id' => $classItem->pivot->school_year_id,
                    ],
                    [
                        'general_average' => $generalAverage,
                        'remarks' => $remarks,
                    ]
                );

                $generalAverages[$classItem->id] = [
                    'general_average' => $generalAverage,
                    'remarks' => $remarks,
                ];
            } else {
                $generalAverages[$classItem->id] = null;
            }
        }

        return view('teacher.students.myStudentInfo', compact(
            'student',
            'class',
            'schoolYear',
            'schoolYearId',
            'classHistory',
            'gradesByClass',
            'generalAverages',
            'studentStatus',
            'statusInfo'
        ));
    }

    public function studentReportCard($student_id, Request $request)
    {
        // Determine school_year_id (from query or latest)
        $schoolYearId = $request->query('school_year');
        if (!$schoolYearId) {
            $schoolYearId = SchoolYear::latest('start_date')->value('id');
        }

        $student = \App\Models\Student::with(['address', 'parents', 'class' => function ($q) {
            $q->with('schoolYear');
        }])->findOrFail($student_id);

        // get the class for this student in the selected school year
        $class = $student->class()->wherePivot('school_year_id', $schoolYearId)->first();

        // If no class found for that year, try to find class history and pick the matching pivot entry
        if (!$class) {
            $class = $student->class()->wherePivot('school_year_id', $schoolYearId)->first();
        }

        // Collect subjects & grades for this class + school year
        $subjectsWithGrades = collect();

        if ($class) {
            // load classSubjects for this class + school year
            $classSubjects = ClassSubject::where('class_id', $class->id)
                ->where('school_year_id', $schoolYearId)
                ->with(['subject', 'quarters.quarterlyGrades' => function ($q) use ($student) {
                    $q->where('student_id', $student->id);
                }])->get();

            foreach ($classSubjects as $cs) {
                $quarters = collect();
                foreach ($cs->quarters as $q) {
                    // find grade record for this student & quarter
                    $qgrade = $q->quarterlyGrades->first();
                    $quarters->push([
                        'quarter' => $q->quarter,
                        'grade' => $qgrade ? $qgrade->final_grade : null,
                    ]);
                }

                // compute final average if all 4 quarters present
                $allPresent = $quarters->every(fn($x) => $x['grade'] !== null);
                $final = null;
                $remarks = null;
                if ($allPresent) {
                    $arr = $quarters->pluck('grade')->all();
                    $final = round(array_sum($arr) / count($arr), 2);
                    $roundedFinal = round($final);
                    $remarks = $roundedFinal >= 75 ? 'Passed' : 'Failed';
                }

                $subjectsWithGrades->push([
                    'subject' => $cs->subject->name,
                    'quarters' => $quarters,
                    'final' => $final,
                    'remarks' => $remarks,
                ]);
            }
        }

        // General average calculation (only if all subject finals available)
        $finals = $subjectsWithGrades->pluck('final')->filter();
        $generalAverage = null;
        $generalRemarks = null;
        if ($subjectsWithGrades->count() > 0 && $finals->count() === $subjectsWithGrades->count()) {
            $generalAverage = round($finals->sum() / $finals->count(), 2);
            $roundedGA = round($generalAverage);
            $generalRemarks = $roundedGA >= 75 ? 'Passed' : 'Failed';
        }

        // Attendance counts - placeholder logic (you can replace with your Attendance model)
        $attendance = [
            'days_school' => 0,
            'days_present' => 0,
            'days_absent' => 0,
            // If you have monthly attendance, compute arrays here
        ];

        // Compute Age based on DOB and end of school year (March 31 default)
        $age = null;
        if (!empty($student->student_dob)) {
            $dob = \Carbon\Carbon::parse($student->student_dob);
            $schoolYear = SchoolYear::find($schoolYearId);
            $endOfSchoolYear = $schoolYear
                ? \Carbon\Carbon::parse($schoolYear->end_date ?? now())
                : now();

            // Ensure whole number only
            $age = (int) floor($dob->diffInYears($endOfSchoolYear));
        }

        // Extract other key info
        $sex = ucfirst($student->gender ?? $student->student_sex ?? '');
        $gradeLevel = $class ? $class->formatted_grade_level : '';
        $section = $class ? $class->section : '';
        $lrn = $student->student_lrn ?? '';

        // Prepare data for blade
        $data = [
            'student' => $student,
            'class' => $class,
            'schoolYear' => SchoolYear::find($schoolYearId),
            'subjects' => $subjectsWithGrades,
            'generalAverage' => $generalAverage,
            'generalRemarks' => $generalRemarks,
            'attendance' => $attendance,
            'school' => [
                'name' => 'STA. BARBARA ELEMENTARY SCHOOL',
                'division' => 'Schools Division Office of Camarines Sur',
                'region' => 'Region V',
                'department' => 'Republic of the Philippines, Department of Education',
            ],
            // New derived student info
            'student_info' => [
                'age' => $age,
                'sex' => $sex,
                'grade' => $gradeLevel,
                'section' => $section,
                'lrn' => $lrn,
            ],
        ];

        // Render PDF: A4 landscape and show two pages side-by-side
        $pdf = Pdf::loadView('pdf.student_report_card', $data)
            ->setPaper('A4', 'landscape');

        // stream download
        $filename = "Report_Card(SF9){$student->student_lName}_{$student->student_fName}_{$data['schoolYear']->school_year}.pdf";

        return $pdf->stream($filename);
    }

    public function studentForm10($student_id)
    {
        // Load student with necessary relationships
        $student = Student::with(['address', 'parents'])->findOrFail($student_id);

        // Fetch class history (all enrolled classes, sorted by school year descending)
        $classHistory = $student->class()
            ->with([
                'advisers' => function ($q) {
                    $q->wherePivot('role', 'adviser');
                }
            ])
            ->get()
            ->sortByDesc(function ($classItem) {
                return $classItem->pivot->school_year_id;
            })
            ->values();

        // Preload school years for efficiency
        $schoolYears = SchoolYear::whereIn('id', $classHistory->pluck('pivot.school_year_id'))->pluck('school_year', 'id');

        // Prepare grades data (similar to studentInfo method)
        $gradesByClass = [];
        $generalAverages = [];

        foreach ($classHistory as $classItem) {
            $classSubjects = $classItem->classSubjects()
                ->with(['subject', 'quarters.quarterlyGrades' => function ($q) use ($student) {
                    $q->where('student_id', $student->id);
                }])
                ->where('school_year_id', $classItem->pivot->school_year_id)
                ->get();

            $subjectsWithGrades = [];
            $finalGrades = [];

            foreach ($classSubjects as $classSubject) {
                $quarters = $classSubject->quarters->map(function ($quarter) use ($student) {
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

            // General Average
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

        // Prepare data for PDF
        $data = [
            'student' => $student,
            'classHistory' => $classHistory,
            'gradesByClass' => $gradesByClass,
            'generalAverages' => $generalAverages,
            'schoolYears' => $schoolYears, // Add this
            'school' => [
                'name' => 'STA. BARBARA ELEMENTARY SCHOOL',
                'division' => 'Schools Division Office of Camarines Sur',
                'region' => 'Region V',
                'department' => 'Republic of the Philippines, Department of Education',
            ],
        ];

        // Generate PDF with custom paper size (8.5 x 13 inches in points)
        $pdf = Pdf::loadView('pdf.student_form10', $data)
            ->setPaper([0, 0, 612, 936], 'portrait'); // 8.5" width, 13" height

        // Stream the PDF
        $filename = 'Form10_' . str_replace(' ', '_', $student->full_name) . '.pdf';
        return $pdf->stream($filename);
    }

    public function bulkPrintGrades(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'school_year_id' => 'required|exists:school_years,id',
        ]);

        $studentIds = $request->input('student_ids');
        $classId = $request->input('class_id');
        $schoolYearId = $request->input('school_year_id');

        // Fetch the class and school year
        $class = Classes::findOrFail($classId);
        $schoolYear = SchoolYear::findOrFail($schoolYearId);

        // Fetch students in the EXACT ORDER they were selected
        $students = Student::whereIn('id', $studentIds)
            ->with([
                'classStudents' => function ($query) use ($classId, $schoolYearId) {
                    $query->where('class_id', $classId)
                        ->where('school_year_id', $schoolYearId);
                },
                'quarterlyGrades.quarter.classSubject.subject',
                'finalSubjectGrades.classSubject.subject',
                'generalAverages' => function ($query) use ($schoolYearId) {
                    $query->where('school_year_id', $schoolYearId);
                }
            ])
            ->get();

        // IMPORTANT: Maintain the original selection order
        $students = $students->sortBy(function ($student) use ($studentIds) {
            return array_search($student->id, $studentIds);
        });

        // Get all subjects for this class
        $subjects = $class->subjects()
            ->wherePivot('school_year_id', $schoolYearId)
            ->get();

        // Prepare data for PDF
        $data = [
            'students' => $students,
            'class' => $class,
            'schoolYear' => $schoolYear,
            'subjects' => $subjects,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.grade_slip', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('dpi', 150)
            ->setOption('defaultFont', 'Times New Roman');

        return $pdf->stream('grade_slips_' . $class->grade_level . '_' . $class->section . '.pdf');
    }

    public function editStudentInfo($student_id)
    {
        $student = Student::findOrFail($student_id);
        return view('teacher.students.editStudent', compact('student'));
    }

    public function updateStudentInfo(Request $request, $student_id)
    {
        $student = Student::with(['address', 'parents'])->findOrFail($student_id);

        $messages = [
            'student_lrn.regex' => 'The LRN must start with "112828" and be exactly 12 digits long.',
        ];

        $validatedData = $request->validate([
            'student_lrn' => [
                'required',
                'string',
                'max:12',
                'regex:/^112828[0-9]{6}$/',
                'unique:students,student_lrn,' . $student->id . ',id',
            ],
            'student_grade_level' => 'required|in:kindergarten,grade1,grade2,grade3,grade4,grade5,grade6',
            'student_section' => 'required|in:A,B,C,D,E,F',
            'student_fName' => 'required|string|max:255',
            'student_mName' => 'nullable|string|max:255',
            'student_lName' => 'required|string|max:255',
            'student_extName' => 'nullable|string|max:45',
            'student_dob' => 'nullable|date',
            'student_sex' => 'required|in:male,female',
            'student_pob' => 'required|string|max:255',

            // Address
            'house_no' => 'nullable|string|max:255',
            'street_name' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',

            // Parents
            'student_fatherFName' => 'nullable|string|max:255',
            'student_fatherMName' => 'nullable|string|max:255',
            'student_fatherLName' => 'nullable|string|max:255',
            'student_fatherPhone' => 'nullable|string|max:255',
            'student_motherFName' => 'nullable|string|max:255',
            'student_motherMName' => 'nullable|string|max:255',
            'student_motherLName' => 'nullable|string|max:255',
            'student_motherPhone' => 'nullable|string|max:255',
            'student_emergcontFName' => 'nullable|string|max:255',
            'student_emergcontMName' => 'nullable|string|max:255',
            'student_emergcontLName' => 'nullable|string|max:255',
            'student_emergcontPhone' => 'nullable|string|max:255',
            'student_parentEmail' => 'nullable|string|max:255',

            // Profile
            'student_profile_photo' => 'nullable|image|mimes:jpeg,png|max:2048',
        ], $messages);

        // Profile photo
        if ($request->hasFile('student_profile_photo')) {
            if ($student->student_photo && Storage::disk('public')->exists($student->student_photo)) {
                Storage::disk('public')->delete($student->student_photo);
            }
            $profilePhotoPath = $request->file('student_profile_photo')->store('student_profile_photos', 'public');
            $student->student_photo = $profilePhotoPath;
        }

        // Update address relation
        $student->address()->updateOrCreate([], [
            'house_no' => $request->house_no,
            'street_name' => $request->street_name,
            'barangay' => $request->barangay,
            'municipality_city' => $request->municipality_city,
            'province' => $request->province,
            'zip_code' => $request->zip_code,
            'country' => 'Philippines',
            'pob' => $request->student_pob,
        ]);

        // Update parent info
        $student->parents()->updateOrCreate([], [
            'father_fName' => $request->student_fatherFName,
            'father_mName' => $request->student_fatherMName,
            'father_lName' => $request->student_fatherLName,
            'father_phone' => $request->student_fatherPhone,
            'mother_fName' => $request->student_motherFName,
            'mother_mName' => $request->student_motherMName,
            'mother_lName' => $request->student_motherLName,
            'mother_phone' => $request->student_motherPhone,
            'emergcont_fName' => $request->student_emergcontFName,
            'emergcont_mName' => $request->student_emergcontMName,
            'emergcont_lName' => $request->student_emergcontLName,
            'emergcont_phone' => $request->student_emergcontPhone,
            'parent_email' => $request->student_parentEmail,
        ]);

        // Update main student fields
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
        ]);

        $student->save();

        return redirect()->route('teacher.my.students')
            ->with('success', 'Student updated successfully!');
    }

    private function getClass($grade_level, $section)
    {
        return Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();
    }

    public function accountSettings()
    {
        $user = Auth::user();
        return view('teacher.accountSettings', compact('user'));
    }

    public function updateTeacher($id)
    {
        $user = User::findOrFail($id);

        // Check if the logged-in user is updating their own account
        if (Auth::id() != $user->id) {
            abort(403, 'Unauthorized');
        }

        $data = request()->all();

        $validated = Validator::make($data, [
            'firstName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|same:confirm_password',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        // Update profile photo if provided
        if (request()->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::delete('public/' . $user->profile_photo);
            }
            $path = request()->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        // Update basic fields
        $user->firstName = $data['firstName'];
        $user->email = $data['email'];

        // Handle password update
        if (!empty($data['current_password']) && !empty($data['new_password'])) {
            if (Hash::check($data['current_password'], $user->password)) {
                $user->password = Hash::make($data['new_password']);
            } else {
                return back()->withErrors(['current_password' => 'Current password is incorrect'])->withInput();
            }
        }

        $user->save();

        return back()->with('success', 'Account updated successfully');
    }
}
