<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Schedule;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function attendanceRecords(Request $request, $grade_level, $section)
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
            $defaultMonth = $today->copy();
            if ($defaultMonth->lt(Carbon::parse($schoolYear->start_date))) {
                $defaultMonth = Carbon::parse($schoolYear->start_date);
            } elseif ($defaultMonth->gt(Carbon::parse($schoolYear->end_date))) {
                $defaultMonth = Carbon::parse($schoolYear->end_date);
            }
        } else {
            $defaultMonth = Carbon::parse($schoolYear->start_date);
        }

        $monthParam = $request->input('month', $defaultMonth->format('Y-m'));
        $dateObj = Carbon::createFromFormat('Y-m', $monthParam);

        $schoolStart = Carbon::parse($schoolYear->start_date)->startOfMonth();
        $schoolEnd = Carbon::parse($schoolYear->end_date)->endOfMonth();

        $dateObj = $dateObj->lt($schoolStart) ? $schoolStart : ($dateObj->gt($schoolEnd) ? $schoolEnd : $dateObj);

        $year = $dateObj->year;
        $month = $dateObj->month;

        $scheduleDays = Schedule::where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->pluck('day')
            ->map(fn($day) => Carbon::parse($day)->format('D'))
            ->unique()
            ->toArray();

        $schedules = Schedule::where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
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
            if (!$schedule) continue;

            // Ensure student's slot exists
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

        // <-- NEW: support returning array (used by SF2Export) just like teacher method
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

        return view('admin.classes.attendances.index', compact(
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
        ))->with('selectedYearObj', $schoolYear);
    }

    public function attendanceHistory(Request $request, $grade_level, $section, $date = null)
    {
        $selectedYear = $request->input('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        $class = $this->getClass($grade_level, $section);

        $defaultDate = Carbon::parse($schoolYear->start_date)->toDateString();
        $targetDate = $date ?? $request->input('date', $defaultDate);
        $dateObj = Carbon::parse($targetDate);

        // Clamp within school year
        $dateObj = $dateObj->lt($schoolYear->start_date) ? Carbon::parse($schoolYear->start_date)
            : ($dateObj->gt($schoolYear->end_date) ? Carbon::parse($schoolYear->end_date) : $dateObj);

        $targetDate = $dateObj->toDateString();

        $students = $class->students()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->orderBy('student_sex')
            ->orderBy('student_lName')
            ->orderBy('student_fName')
            ->orderBy('student_mName')
            ->get();

        $schedules = Schedule::where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
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

        return view('admin.classes.attendances.attendance_history', compact(
            'class',
            'students',
            'schedules',
            'attendancesGrouped',
            'targetDate',
            'selectedYear',
            'schoolYear'
        ));
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

    // Helper function to get the class (e.g., "Grade 1 - Section A")
    private function getClass($grade_level, $section)
    {
        return Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();
    }

    public function takeAttendance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'status' => 'required|in:present,absent,late',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
        ]);

        $schedule = Schedule::where('teacher_id', $request->teacher_id)
            ->where('class_id', $request->class_id)
            ->where('day', now()->format('l')) // e.g. "Monday"
            ->first();

        Attendance::create([
            'student_id' => $request->student_id,
            'teacher_id' => $request->teacher_id,
            'class_id' => $request->class_id,
            'schedule_id' => $schedule?->id,
            'date' => Carbon::now()->toDateString(),
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Attendance recorded successfully!');
    }

    public function getAttendanceExportData()
    {
        // Use current or request params if needed
        $request = request();

        $grade_level = $request->input('grade_level');
        $section = $request->input('section');
        $school_year = $request->input('school_year');
        $month = $request->input('month');

        // Mock Request object for function reuse
        $mockRequest = new \Illuminate\Http\Request([
            'school_year' => $school_year,
            'month' => $month,
            '__return_array__' => true,
        ]);

        return app()->call(
            [app(\App\Http\Controllers\TeacherController::class), 'myAttendanceRecord'],
            [
                'request' => $mockRequest,
                'grade_level' => $grade_level,
                'section' => $section,
            ]
        );
    }

    // public function fetchMonth($studentId, $schoolYearId, $classId, $year, $month)
    // {
    //     $attendances = \App\Models\Attendance::where('student_id', $studentId)
    //         ->where('school_year_id', $schoolYearId)
    //         ->where('class_id', $classId)
    //         ->whereYear('date', $year)
    //         ->whereMonth('date', $month)
    //         ->get();

    //     return view('partials.attendance_calendar', compact('attendances', 'year', 'month'));
    // }
}
