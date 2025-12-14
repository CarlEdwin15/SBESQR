<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\SchoolYear;
use App\Models\Classes;
use App\Models\Announcement;
use App\Models\ClassStudent;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $role = $user->role ?? 'parent';

        // Handle announcement redirect from push notification
        $announcementId = null;

        // Check for announcement ID from notification redirect
        if (session()->has('notification_announcement_id')) {
            $announcementId = session('notification_announcement_id');
            session()->forget('notification_announcement_id'); // Clear it after use
        }

        // Also check for announcement ID from login redirect
        if (session()->has('login_redirect_announcement')) {
            $announcementId = session('login_redirect_announcement');
            session()->forget('login_redirect_announcement'); // Clear it after use
        }

        // Check URL parameter as fallback
        if ($request->has('announcement_id')) {
            $announcementId = $request->get('announcement_id');
        }

        // Flash login success message (only once per session)
        if (!session()->has('login_success_shown')) {
            session()->flash('success', 'Login successful!');
            session(['login_success_shown' => true]);
        }

        // Get user-specific announcements
        $notifications = Announcement::where(function ($q) use ($user, $role) {
            // Admins see all announcements
            if ($role === 'admin') {
                return;
            }

            // For non-admins: announcements specifically assigned to them
            $q->whereHas('recipients', function ($r) use ($user) {
                $r->where('users.id', $user->id);
            });

            // OR general (no recipients, meaning sent to everyone)
            $q->orWhereDoesntHave('recipients');
        })
            ->orderByDesc('date_published')
            ->take(99)
            ->get();

        // Fetch active announcements based on date range
        $activeAnnouncements = Announcement::where(function ($q) use ($user, $role) {
            // Admins see all active announcements
            if ($role === 'admin') {
                return;
            }

            // Non-admins: announcements sent to them OR general (no recipients)
            $q->whereHas('recipients', function ($r) use ($user) {
                $r->where('users.id', $user->id);
            })->orWhereDoesntHave('recipients');
        })
            ->where(function ($q) {
                $q->whereDate('effective_date', '<=', now())
                    ->whereDate('end_date', '>=', now());
            })
            ->orderByDesc('effective_date')
            ->get();

        // Handle different user roles
        if ($role == 'teacher') {
            $currentSchoolYear = SchoolYear::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            $class = null;

            if ($currentSchoolYear) {
                $class = $user->classes()
                    ->wherePivot('school_year_id', $currentSchoolYear->id)
                    ->wherePivot('role', 'adviser')
                    ->first();
            }

            return view('teacher.index', compact(
                'class',
                'currentSchoolYear',
                'notifications',
                'activeAnnouncements',
                'announcementId'
            ));
        }

        if ($role == 'admin') {
            // All school years for dropdown
            $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();

            // Selected school year (via ?school_year_id= or current active)
            $selectedSchoolYear = request()->get('school_year_id')
                ? SchoolYear::find(request()->get('school_year_id'))
                : SchoolYear::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            // Default empty collection
            $enrolleesByGrade = collect();

            if ($selectedSchoolYear) {
                $enrolleesByGrade = Classes::withCount([
                    'students as total_enrolled' => function ($q) use ($selectedSchoolYear) {
                        $q->where('class_student.school_year_id', $selectedSchoolYear->id)
                            ->where('class_student.enrollment_status', 'enrolled');
                    }
                ])
                    ->orderBy('grade_level')
                    ->get()
                    ->map(function ($class) {
                        return [
                            'grade_level' => $class->formatted_grade_level,
                            'total' => $class->total_enrolled,
                        ];
                    });
            }

            return view('admin.index', compact(
                'notifications',
                'schoolYears',
                'selectedSchoolYear',
                'enrolleesByGrade',
                'announcementId',
                'activeAnnouncements'
            ));
        }

        if ($role == 'parent') {
            return view('parent.index', compact(
                'notifications',
                'activeAnnouncements',
                'announcementId'
            ));
        }

        return redirect()->route('welcome');
    }

    public function getEnrollmentData(Request $request)
    {
        $schoolYearId = $request->get('school_year_id');
        $gradeLevel = $request->get('grade_level');
        $section = $request->get('section');

        $query = ClassStudent::where('class_student.school_year_id', $schoolYearId)
            ->whereIn('class_student.enrollment_status', ['enrolled', 'archived']) // Only active students
            ->join('classes', 'class_student.class_id', '=', 'classes.id');

        // Apply grade level filter if provided
        if ($gradeLevel) {
            $query->where('classes.grade_level', $gradeLevel);
        }

        // Apply section filter if provided
        if ($section) {
            $query->where('classes.section', $section);
        }

        $enrollmentData = $query->selectRaw('classes.grade_level, COUNT(DISTINCT class_student.student_id) as student_count')
            ->groupBy('classes.grade_level')
            ->get();

        // Get all possible grade levels to ensure consistent ordering
        $gradeLevels = ['kindergarten', 'grade1', 'grade2', 'grade3', 'grade4', 'grade5', 'grade6'];
        $formattedData = [];

        foreach ($gradeLevels as $grade) {
            $found = $enrollmentData->firstWhere('grade_level', $grade);
            $formattedData[] = $found ? $found->student_count : 0;
        }

        // Calculate total for this filtered view
        $totalEnrolled = array_sum($formattedData);

        return response()->json([
            'enrollment_data' => $formattedData,
            'total_enrolled' => $totalEnrolled,
            'grade_labels' => ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']
        ]);
    }

    public function getGenderData(Request $request)
    {
        $schoolYearId = $request->get('school_year_id');

        $genderData = ClassStudent::where('school_year_id', $schoolYearId)
            ->whereIn('enrollment_status', ['enrolled', 'archived']) // Consistent with enrollment data
            ->join('students', 'class_student.student_id', '=', 'students.id')
            ->selectRaw('
            COUNT(DISTINCT students.id) as total,
            SUM(CASE WHEN LOWER(students.student_sex) IN ("f", "female") THEN 1 ELSE 0 END) as female_count,
            SUM(CASE WHEN LOWER(students.student_sex) IN ("m", "male") THEN 1 ELSE 0 END) as male_count
        ')
            ->first();

        $total = $genderData->total ?? 0;
        $femaleCount = $genderData->female_count ?? 0;
        $maleCount = $genderData->male_count ?? 0;

        $response = [
            'total' => $total,
            'female_count' => $femaleCount,
            'male_count' => $maleCount,
            'female_percentage' => $total > 0 ? round(($femaleCount / $total) * 100, 1) : 0,
            'male_percentage' => $total > 0 ? round(($maleCount / $total) * 100, 1) : 0,
        ];

        return response()->json($response);
    }

    public function getGenderDataFiltered(Request $request)
    {
        $schoolYearId = $request->get('school_year_id');
        $gradeLevel = $request->get('grade_level');
        $section = $request->get('section');

        $query = ClassStudent::where('school_year_id', $schoolYearId)
            ->whereIn('enrollment_status', ['enrolled', 'archived']) // Consistent with main gender data
            ->join('students', 'class_student.student_id', '=', 'students.id')
            ->join('classes', 'class_student.class_id', '=', 'classes.id');

        // Apply grade level filter if provided
        if ($gradeLevel) {
            $query->where('classes.grade_level', $gradeLevel);
        }

        // Apply section filter if provided
        if ($section) {
            $query->where('classes.section', $section);
        }

        $genderData = $query->selectRaw('
        COUNT(DISTINCT students.id) as total,
        SUM(CASE WHEN LOWER(students.student_sex) IN ("f", "female") THEN 1 ELSE 0 END) as female_count,
        SUM(CASE WHEN LOWER(students.student_sex) IN ("m", "male") THEN 1 ELSE 0 END) as male_count
    ')->first();

        $total = $genderData->total ?? 0;
        $femaleCount = $genderData->female_count ?? 0;
        $maleCount = $genderData->male_count ?? 0;

        $response = [
            'total' => $total,
            'female_count' => $femaleCount,
            'male_count' => $maleCount,
            'female_percentage' => $total > 0 ? round(($femaleCount / $total) * 100, 1) : 0,
            'male_percentage' => $total > 0 ? round(($maleCount / $total) * 100, 1) : 0,
        ];

        return response()->json($response);
    }

    public function getGradeSections(Request $request)
    {
        $schoolYearId = $request->get('school_year_id');
        $gradeLevel = $request->get('grade_level');

        $sections = Classes::where('grade_level', $gradeLevel)
            ->whereHas('classStudents', function ($query) use ($schoolYearId) {
                $query->where('school_year_id', $schoolYearId)
                    ->whereIn('enrollment_status', ['enrolled', 'archived', 'graduated']);
            })
            ->pluck('section')
            ->unique()
            ->values();

        return response()->json($sections);
    }

    //Get school year info for AJAX
    public function getSchoolYearInfo(Request $request)
    {
        $schoolYearId = $request->get('school_year_id');
        $schoolYear = SchoolYear::find($schoolYearId);

        if (!$schoolYear) {
            return response()->json(['error' => 'School year not found'], 404);
        }

        return response()->json([
            'school_year_text' => $schoolYear->school_year
        ]);
    }

    public function getActiveUsers()
    {
        $users = User::whereNotNull('last_sign_in_at') // Only users who have logged in at least once
            ->get()
            ->sortBy(fn($user) => sprintf('%d-%s', $user->is_online ? 0 : 1, strtolower($user->full_name)))
            ->values()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->full_name,
                    'role' => $user->role,
                    'profile_photo' => $user->profile_photo,
                    'is_online' => $user->is_online,
                    'last_seen' => $user->last_seen,
                    'last_sign_in_at' => $user->last_sign_in_at ? $user->last_sign_in_at->toISOString() : null,
                    'sign_in_at' => $user->sign_in_at ? $user->sign_in_at->toISOString() : null,
                ];
            });

        return response()->json($users);
    }
}
