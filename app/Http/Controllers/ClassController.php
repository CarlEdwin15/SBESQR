<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\User;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function allClasses(Request $request)
    {
        $now = now();
        $year = $now->year;
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;

        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
        $nextSchoolYear = ($currentYear + 1) . '-' . ($currentYear + 2);

        // Fetch all saved school years from DB
        $savedYears = SchoolYear::pluck('school_year')->toArray();

        // Extract numeric start years
        $savedStartYears = array_map(function ($sy) {
            return (int)substr($sy, 0, 4);
        }, $savedYears);

        $minYear = !empty($savedStartYears) ? min($savedStartYears) : $currentYear;

        // Build school years from minYear to currentYear
        $schoolYears = [];
        for ($y = $minYear; $y <= $currentYear; $y++) {
            $schoolYears[] = $y . '-' . ($y + 1);
        }

        // Add current and next school year if not already included
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

        // Get section and selected year
        $section = $request->input('section', 'A'); // default to A
        $selectedYear = $request->query('school_year', $currentSchoolYear);

        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        $sections = Classes::distinct()->pluck('section');
        $gradeLevels = Classes::where('section', $section)->distinct()->pluck('grade_level');

        $classes = Classes::where('section', $section)->get();

        foreach ($classes as $class) {
            $class->adviser = $class->teachers()
                ->wherePivot('school_year_id', $schoolYear->id)
                ->wherePivotIn('role', ['adviser'])
                ->first();
        }

        return view('admin.classes.allClasses', compact(
            'sections',
            'gradeLevels',
            'section',
            'classes',
            'selectedYear',
            'schoolYears',
            'currentYear',
        ));
    }

    // Show a specific class with adviser info and student count
    public function showClass(Request $request, $grade_level, $section)
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

        // Attendance Today (same as myClass)
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

        return view('admin.classes.showClass', compact(
            'class',
            'studentCount',
            'presentCount',
            'totalPossibleAttendance',
            'attendanceToday',
            'selectedYear'
        ));
    }

    // Master list of students in the class
    public function masterList(Request $request, $grade_level, $section)
    {
        // Determine current + next school years (same logic as teacher side)
        $now = now();
        $year = $now->year;
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;

        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
        $nextSchoolYear = ($currentYear + 1) . '-' . ($currentYear + 2);

        // Fetch all available school years
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

        // Selected school year
        $selectedYear = $request->query('school_year', $currentSchoolYear);

        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();
        $schoolYearId = $schoolYear->id;

        // Get class info
        $class = $this->getClass($grade_level, $section);

        // Students via pivot with class student data (updated to match teacher side)
        $students = $class->students()
            ->wherePivot('school_year_id', $schoolYearId)
            ->with(['classStudents' => function ($query) use ($class, $schoolYearId) {
                $query->where('class_id', $class->id)
                    ->where('school_year_id', $schoolYearId);
            }])
            ->get();

        // Adviser
        $class->adviser = $class->teachers()
            ->wherePivot('school_year_id', $schoolYearId)
            ->wherePivot('role', 'adviser')
            ->first();

        return view('admin.classes.masterList.index', compact(
            'class',
            'students',
            'schoolYears',
            'selectedYear',
            'schoolYearId',
            'currentYear'
        ));
    }

    public function subjects(Request $request, $grade_level, $section)
    {
        $selectedYear = $request->query('school_year', $this->getDefaultSchoolYear());

        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();
        $class = $this->getClass($grade_level, $section);

        $class->adviser = $class->teachers()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->wherePivotIn('role', ['adviser'])
            ->first();

        // Fetch class subjects for this class and school year
        $classSubjects = $class->classSubjects()
            ->where('school_year_id', $schoolYear->id)
            ->with(['subject', 'teacher'])
            ->get();

        return view('admin.classes.subjects_grades.subjects', compact(
            'class',
            'selectedYear',
            'classSubjects'
        ));
    }

    public function grades(Request $request, $grade_level, $section, $subject)
    {
        $selectedYear = $request->query('school_year', $this->getDefaultSchoolYear());

        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();
        $class = $this->getClass($grade_level, $section);

        $class->adviser = $class->teachers()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->wherePivotIn('role', ['adviser'])
            ->first();

        // Fetch class subject for this class, subject, and school year
        $classSubject = $class->classSubjects()
            ->where('school_year_id', $schoolYear->id)
            ->where('subject_id', $subject)
            ->with(['subject', 'teacher'])
            ->firstOrFail();

        // Fetch students with their grades for this subject
        $students = $class->students()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->with([
                'quarterlyGrades.quarter' => function ($query) use ($classSubject) {
                    $query->where('class_subject_id', $classSubject->id);
                },
                'finalSubjectGrades' => function ($query) use ($classSubject) {
                    $query->where('class_subject_id', $classSubject->id);
                }
            ])
            ->orderBy('student_lName')
            ->get();

        return view('admin.classes.subjects_grades.grades', compact(
            'class',
            'selectedYear',
            'classSubject',
            'students'
        ));
    }

    // Helper function to retrieve a class
    private function getClass($grade_level, $section)
    {
        return Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();
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
}
