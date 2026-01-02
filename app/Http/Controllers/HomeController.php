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
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            // Don't clear it here - let the JavaScript handle it
        }

        // Also check for announcement ID from login redirect
        if (session()->has('login_redirect_announcement')) {
            $announcementId = session('login_redirect_announcement');
            session()->forget('login_redirect_announcement');
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

        // Get user-specific announcements for notifications
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

        // Fetch active announcements based on date range - sorted by effective_date DESC (newest first)
        $activeAnnouncements = Announcement::where(function ($q) use ($user, $role) {
            // // Admins see all active announcements
            // if ($role === 'admin') {
            //     return;
            // }

            // Non-admins: announcements sent to them OR general (no recipients)
            $q->whereHas('recipients', function ($r) use ($user) {
                $r->where('users.id', $user->id);
            })->orWhereDoesntHave('recipients');
        })
            ->where(function ($q) {
                $q->whereDate('effective_date', '<=', now())
                    ->whereDate('end_date', '>=', now());
            })
            ->orderByDesc('effective_date') // Sort by effective_date DESC (newest first)
            ->get()
            ->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'body' => $announcement->body,
                    'date_published' => $announcement->date_published
                        ? \Carbon\Carbon::parse($announcement->date_published)->format('M d, Y h:i A')
                        : 'Draft',
                    'author_name' => $announcement->user?->firstName ?? 'Unknown',
                    'effective_date' => $announcement->effective_date,
                    'end_date' => $announcement->end_date,
                ];
            })
            ->toArray();

        // Get unread announcements count
        $unreadAnnouncementsCount = $user->receivedAnnouncements()
            ->wherePivot('read_at', null)
            ->where(function ($query) {
                $query->where('status', 'active')
                    ->orWhere('status', 'archive');
            })
            ->where('date_published', '>=', now()->subWeek())
            ->count();

        // Get recent notifications (both read and unread) for display
        $recentNotifications = $user->receivedAnnouncements()
            ->with('user')
            ->where(function ($query) {
                $query->where('status', 'active')
                    ->orWhere('status', 'archive');
            })
            ->where('date_published', '>=', now()->subWeek())
            ->orderByDesc('date_published')
            ->get();

        // Add is_unread flag to each notification
        $recentNotifications->each(function ($notification) use ($user) {
            $notification->is_unread = is_null($notification->recipients->find($user->id)->pivot->read_at ?? null);
        });

        // Set flag to show announcements on login
        $cameFromLogin = $request->session()->get('login_success_shown', false);
        if ($cameFromLogin && count($activeAnnouncements) > 0) {
            session(['show_announcements_on_login' => true]);
        }

        // Handle different user roles
        if ($role == 'teacher') {
            $currentSchoolYear = SchoolYear::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            $class = null;
            $students = collect();
            $sections = [];
            $assignedGrades = [];

            // Get all school years and filter to include only from first to current
            $allSchoolYears = SchoolYear::orderBy('start_date', 'asc')->get();

            if ($allSchoolYears->isNotEmpty()) {
                $firstSchoolYear = $allSchoolYears->first();

                if ($currentSchoolYear) {
                    // Filter school years from first to current (inclusive)
                    $schoolYears = $allSchoolYears
                        ->filter(function ($schoolYear) use ($firstSchoolYear, $currentSchoolYear) {
                            return $schoolYear->start_date >= $firstSchoolYear->start_date &&
                                $schoolYear->start_date <= $currentSchoolYear->start_date;
                        })
                        ->sortByDesc('start_date')
                        ->values();
                } else {
                    // If no current school year, just use all available school years
                    $schoolYears = $allSchoolYears->sortByDesc('start_date')->values();
                }
            } else {
                $schoolYears = collect();
            }

            $schoolYearId = $currentSchoolYear ? $currentSchoolYear->id : null;
            $schoolYearText = $currentSchoolYear ? $currentSchoolYear->school_year : 'N/A';

            // Get teacher's assigned classes for filtering
            $teacherClasses = $user->classes()
                ->with('schoolYear')
                ->get()
                ->groupBy('pivot.school_year_id');

            // Get all students across all school years for the teacher
            $allStudents = collect();
            $teacherClassIds = $user->classes()->pluck('classes.id')->toArray();

            // Fetch students from all school years
            foreach ($schoolYears as $schoolYear) {
                $studentsForYear = Student::whereHas('class', function ($query) use ($teacherClassIds, $schoolYear) {
                    $query->whereIn('classes.id', $teacherClassIds)
                        ->where('class_student.school_year_id', $schoolYear->id);
                })->with([
                    'address',
                    'parents',
                    'classStudents' => function ($query) use ($teacherClassIds, $schoolYear) {
                        $query->whereIn('class_id', $teacherClassIds)
                            ->where('school_year_id', $schoolYear->id)
                            ->with(['class', 'schoolYear']);
                    }
                ])->get();

                $allStudents = $allStudents->merge($studentsForYear);
            }

            // For current school year stats (existing logic)
            $students = collect();
            $groupedStudents = collect();

            if ($currentSchoolYear) {
                $currentYearTeacherClasses = $user->classes()
                    ->wherePivot('school_year_id', $currentSchoolYear->id)
                    ->get();

                $teacherClassIds = $currentYearTeacherClasses->pluck('id');
                $assignedGrades = $currentYearTeacherClasses->pluck('formatted_grade_level')->unique()->values()->all();

                // Get sections
                $sections = Classes::whereIn('id', $teacherClassIds)
                    ->pluck('section')->unique()->sort()->values()->all();

                // Fetch students enrolled in teacher's classes for current year
                $students = Student::whereHas('class', function ($query) use ($teacherClassIds, $currentSchoolYear) {
                    $query->whereIn('classes.id', $teacherClassIds)
                        ->where('class_student.school_year_id', $currentSchoolYear->id);
                })->with([
                    'address',
                    'parents',
                    'class' => function ($query) use ($currentSchoolYear) {
                        $query->where('class_student.school_year_id', $currentSchoolYear->id);
                    }
                ])->get();

                // Group students by grade level for current year
                foreach ($assignedGrades as $grade) {
                    $groupedStudents[$grade] = $students->filter(function ($student) use ($grade, $currentSchoolYear) {
                        $classForYear = $student->class->firstWhere('pivot.school_year_id', $currentSchoolYear->id);
                        return optional($classForYear)->formatted_grade_level === $grade;
                    });
                }
            }

            // Gender stats for current school year
            $genderStats = [
                'total' => 0,
                'female_count' => 0,
                'male_count' => 0,
                'female_percentage' => 0,
                'male_percentage' => 0,
            ];

            if ($currentSchoolYear) {
                // Get gender stats for current year
                $genderData = $this->getGenderData(new Request(['school_year_id' => $currentSchoolYear->id]));
                $genderStats = json_decode($genderData->getContent(), true);
            }

            $schoolYearText = $currentSchoolYear ? $currentSchoolYear->school_year : 'Current SY';

            // Student type stats for current school year
            $studentTypeStats = [
                'total' => 0,
                'regular_count' => 0,
                'returnee_count' => 0,
                'transferee_count' => 0,
                'regular_percentage' => 0,
                'returnee_percentage' => 0,
                'transferee_percentage' => 0,
            ];

            if ($currentSchoolYear) {
                // Get student type stats for current year
                $studentTypeData = $this->getStudentTypeData(new Request(['school_year_id' => $currentSchoolYear->id]));
                $studentTypeStats = json_decode($studentTypeData->getContent(), true);
            }

            $attendanceDate = now()->format('Y-m-d'); // Always use today

            return view('teacher.index', compact(
                'class',
                'currentSchoolYear',
                'notifications',
                'activeAnnouncements',
                'announcementId',
                'unreadAnnouncementsCount',
                'recentNotifications',
                'students',
                'groupedStudents',
                'sections',
                'assignedGrades',
                'schoolYears',
                'schoolYearId',
                'schoolYearText',
                'allStudents',
                'genderStats',
                'studentTypeStats'
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
                'activeAnnouncements',
                'unreadAnnouncementsCount',
                'recentNotifications'
            ));
        }

        if ($role == 'parent') {
            return view('parent.index', compact(
                'notifications',
                'activeAnnouncements',
                'announcementId',
                'unreadAnnouncementsCount',
                'recentNotifications'
            ));
        }

        return redirect()->route('welcome');
    }

    public function getStudentTypeData(Request $request)
    {
        $schoolYearId = $request->get('school_year_id');
        $gradeLevel = $request->get('grade_level');
        $section = $request->get('section');

        $query = ClassStudent::where('school_year_id', $schoolYearId)
            ->whereIn('enrollment_status', ['enrolled', 'archived'])
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

        $studentTypeData = $query->selectRaw('
        COUNT(DISTINCT students.id) as total,
        SUM(CASE WHEN class_student.enrollment_type = "regular" THEN 1 ELSE 0 END) as regular_count,
        SUM(CASE WHEN class_student.enrollment_type = "returnee" THEN 1 ELSE 0 END) as returnee_count,
        SUM(CASE WHEN class_student.enrollment_type = "transferee" THEN 1 ELSE 0 END) as transferee_count
        ')->first();

        $total = $studentTypeData->total ?? 0;
        $regularCount = $studentTypeData->regular_count ?? 0;
        $returneeCount = $studentTypeData->returnee_count ?? 0;
        $transfereeCount = $studentTypeData->transferee_count ?? 0;

        $response = [
            'total' => $total,
            'regular_count' => $regularCount,
            'returnee_count' => $returneeCount,
            'transferee_count' => $transfereeCount,
            'regular_percentage' => $total > 0 ? round(($regularCount / $total) * 100, 1) : 0,
            'returnee_percentage' => $total > 0 ? round(($returneeCount / $total) * 100, 1) : 0,
            'transferee_percentage' => $total > 0 ? round(($transfereeCount / $total) * 100, 1) : 0,
        ];

        return response()->json($response);
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

    public function getActiveUsersForTeacher(Request $request)
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

    public function getUserInfoForTeacher(User $user)
    {
        // Check if the requesting user is a teacher
        if (Auth::user()->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get user data
        $userData = [
            'id' => $user->id,
            'name' => $user->full_name,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'middleName' => $user->middleName,
            'extName' => $user->extName,
            'email' => $user->email,
            'role' => $user->role,
            'phone' => $user->phone,
            'gender' => $user->gender,
            'dob' => $user->dob ? Carbon::parse($user->dob)->format('F j, Y') : null,
            'age' => $user->dob ? Carbon::parse($user->dob)->age : null,
            // FIXED: Properly get the profile photo URL
            'profile_photo' => $this->getProfilePhotoUrl($user),
            'status' => $user->status,
            'created_at' => $user->created_at->format('F j, Y'),
            'last_seen' => $user->last_seen,
            'is_online' => $user->is_online,
            'parent_type' => $user->parent_type,
            'address' => [
                'house_no' => $user->house_no,
                'street_name' => $user->street_name,
                'barangay' => $user->barangay,
                'municipality_city' => $user->municipality_city,
                'province' => $user->province,
                'zip_code' => $user->zip_code,
            ],
        ];

        // Add role-specific data
        if ($user->role === 'teacher') {
            $currentSchoolYear = SchoolYear::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            $classesByYear = [];
            if ($currentSchoolYear) {
                $classes = $user->classes()
                    ->wherePivot('school_year_id', $currentSchoolYear->id)
                    ->get()
                    ->groupBy(fn($class) => $currentSchoolYear->school_year);

                $classesByYear = $classes->map(function ($classGroup) {
                    return $classGroup->map(function ($class) {
                        return [
                            'id' => $class->id,
                            'grade_level' => $class->formatted_grade_level ?? $class->grade_level,
                            'section' => $class->section,
                            'role' => $class->pivot->role,
                        ];
                    });
                });
            }
            $userData['classes'] = $classesByYear;
        } elseif ($user->role === 'parent') {
            $children = $user->children()->get()->map(function ($child) {
                return [
                    'id' => $child->id,
                    'name' => $child->student_fName . ' ' . $child->student_lName,
                    'lrn' => $child->student_lrn,
                    'profile_photo' => $this->getStudentProfilePhotoUrl($child),
                ];
            });
            $userData['children'] = $children;
        }

        return response()->json($userData);
    }

    // Add this helper method to the HomeController class
    private function getProfilePhotoUrl($user)
    {
        if (!$user->profile_photo) {
            return match ($user->role) {
                'admin' => asset('assetsDashboard/img/profile_pictures/admin_default_profile.jpg'),
                'teacher' => asset('assetsDashboard/img/profile_pictures/teacher_default_profile.jpg'),
                'parent' => asset('assetsDashboard/img/profile_pictures/parent_default_profile.jpg'),
                default => 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name),
            };
        }

        if (Str::startsWith($user->profile_photo, ['http://', 'https://'])) {
            return $user->profile_photo;
        }

        return Storage::url($user->profile_photo);
    }

    // Add this method for student profile photos
    private function getStudentProfilePhotoUrl($student)
    {
        if (!$student->student_photo) {
            return asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg');
        }

        if (Str::startsWith($student->student_photo, ['http://', 'https://'])) {
            return $student->student_photo;
        }

        return asset('public/uploads/' . $student->student_photo);
    }

    // Add this method to HomeController.php
    public function getTeacherStudents(Request $request)
    {
        $teacher = Auth::user();

        if ($teacher->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $schoolYearId = $request->get('school_year_id');
        $classId = $request->get('class_id');
        $searchTerm = $request->get('search', '');

        $query = Student::query();

        // Apply school year filter
        if ($schoolYearId && $schoolYearId !== 'all') {
            $query->whereHas('class', function ($q) use ($schoolYearId) {
                $q->where('class_student.school_year_id', $schoolYearId);
            });
        }

        // Apply class filter
        if ($classId && $classId !== 'all') {
            $query->whereHas('class', function ($q) use ($classId) {
                $q->where('classes.id', $classId);
            });
        }

        // Apply search filter
        if (!empty($searchTerm)) {
            $searchTerm = strtolower($searchTerm);
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(CONCAT(student_lName, " ", student_fName, " ", student_mName, " ", student_extName)) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(student_lrn) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        // Only get students from teacher's classes
        $teacherClassIds = $teacher->classes()->pluck('classes.id')->toArray();
        $query->whereHas('class', function ($q) use ($teacherClassIds) {
            $q->whereIn('classes.id', $teacherClassIds);
        });

        $students = $query->with([
            'class' => function ($query) {
                $query->with('schoolYear');
            }
        ])->get()->map(function ($student) {
            $currentClass = $student->class->first();
            $schoolYearId = $currentClass ? $currentClass->pivot->school_year_id : null;

            return [
                'id' => $student->id,
                'name' => $student->student_lName . ', ' . $student->student_fName . ' ' .
                    $student->student_mName . ' ' . $student->student_extName,
                'lrn' => $student->student_lrn,
                'photo' => $student->student_photo ?
                    asset('public/uploads/' . $student->student_photo) :
                    asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg'),
                'grade_section' => $currentClass ?
                    $currentClass->formatted_grade_level . ' - ' . $currentClass->section : 'N/A',
                'school_year' => $currentClass && $currentClass->schoolYear ?
                    $currentClass->schoolYear->school_year : 'N/A',
                'enrollment_status' => $currentClass ? $currentClass->pivot->enrollment_status : 'N/A',
                'enrollment_type' => $currentClass ? $currentClass->pivot->enrollment_type : 'N/A',
                'school_year_id' => $schoolYearId,
                'class_id' => $currentClass ? $currentClass->id : null,
                'grade_section_display' => $currentClass ?
                    $currentClass->formatted_grade_level . ' - ' . $currentClass->section : 'N/A',
                'badge_class_status' => match ($currentClass ? $currentClass->pivot->enrollment_status : 'N/A') {
                    'enrolled' => 'bg-label-success',
                    'not_enrolled' => 'bg-label-secondary',
                    'archived' => 'bg-label-warning',
                    'graduated' => 'bg-label-info',
                    default => 'bg-label-dark',
                },
                'badge_icon_status' => match ($currentClass ? $currentClass->pivot->enrollment_status : 'N/A') {
                    'enrolled' => 'bx bx-check-circle',
                    'not_enrolled' => 'bx bx-x-circle',
                    'archived' => 'bx bx-archive',
                    'graduated' => 'bx bx-graduation',
                    default => 'bx bx-minus-circle',
                },
                'badge_class_type' => match ($currentClass ? $currentClass->pivot->enrollment_type : 'N/A') {
                    'regular' => 'bg-label-primary',
                    'transferee' => 'bg-label-info',
                    'returnee' => 'bg-label-warning',
                    default => 'bg-label-dark',
                },
                'badge_icon_type' => match ($currentClass ? $currentClass->pivot->enrollment_type : 'N/A') {
                    'regular' => 'bx bx-user',
                    'transferee' => 'bx bx-transfer',
                    'returnee' => 'bx bx-undo',
                    default => 'bx bx-question-mark',
                },
                'student_info_url' => route('teacher.student.info', [
                    'id' => $student->id,
                    'school_year' => $schoolYearId
                ]),
            ];
        });

        return response()->json([
            'students' => $students,
            'total' => $students->count(),
            'filters' => [
                'school_year_id' => $schoolYearId,
                'class_id' => $classId,
                'search' => $searchTerm,
            ]
        ]);
    }

    // Add this method to HomeController.php
    public function getTeacherClasses(Request $request)
    {
        try {
            $teacher = Auth::user();

            if ($teacher->role !== 'teacher') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $schoolYear = $request->get('school_year');

            // Find the school year record
            $schoolYearRecord = SchoolYear::where('school_year', $schoolYear)->first();

            if (!$schoolYearRecord) {
                return response()->json([
                    'success' => true,
                    'classes' => []
                ]);
            }

            // Get teacher's classes for this school year
            $classes = $teacher->classes()
                ->where('school_year_id', $schoolYearRecord->id)
                ->get()
                ->map(function ($class) {
                    return [
                        'id' => $class->id,
                        'name' => $class->formatted_grade_level . ' - ' . $class->section,
                        'grade_level' => $class->formatted_grade_level,
                        'section' => $class->section
                    ];
                });

            return response()->json([
                'success' => true,
                'classes' => $classes
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading teacher classes: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading classes',
                'classes' => []
            ], 500);
        }
    }

    // Update the getTeacherDashboardStudents method to include class filter
    public function getTeacherDashboardStudents(Request $request)
    {
        try {
            $teacher = Auth::user();

            if ($teacher->role !== 'teacher') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            // Get current school year logic
            $now = now();
            $year = $now->year;
            $cutoff = $now->copy()->setMonth(6)->setDay(1);
            $currentYear = $now->lt($cutoff) ? $year - 1 : $year;

            $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);

            // Get selected year from request or use current
            $selectedYear = $request->query('school_year', $currentSchoolYear);
            $selectedClass = $request->query('class', 'all');
            $page = $request->query('page', 1);
            $perPage = $request->query('per_page', 10);
            $search = $request->query('search', '');

            // Find the school year record
            $schoolYear = SchoolYear::where('school_year', $selectedYear)->first();

            if (!$schoolYear) {
                return response()->json([
                    'success' => false,
                    'message' => 'School year not found',
                    'students' => [],
                    'pagination' => [
                        'total' => 0,
                        'per_page' => $perPage,
                        'current_page' => $page,
                        'last_page' => 1,
                        'from' => 0,
                        'to' => 0
                    ],
                    'schoolYears' => [],
                    'selectedYear' => $selectedYear,
                    'selectedClass' => $selectedClass,
                    'schoolYearId' => null,
                    'currentYear' => $currentYear
                ]);
            }

            $schoolYearId = $schoolYear->id;

            // Get teacher's classes for this school year
            $teacherClasses = $teacher->classes()
                ->where('school_year_id', $schoolYearId)
                ->get();

            $teacherClassIds = $teacherClasses->pluck('id');

            // Build query
            $query = Student::whereHas('class', function ($query) use ($teacherClassIds, $schoolYearId, $selectedClass) {
                $query->whereIn('classes.id', $teacherClassIds)
                    ->where('class_student.school_year_id', $schoolYearId);

                if ($selectedClass !== 'all') {
                    $query->where('classes.id', $selectedClass);
                }
            });

            // Apply search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('student_lName', 'LIKE', "%{$search}%")
                        ->orWhere('student_fName', 'LIKE', "%{$search}%")
                        ->orWhere('student_mName', 'LIKE', "%{$search}%")
                        ->orWhere('student_extName', 'LIKE', "%{$search}%")
                        ->orWhere('student_lrn', 'LIKE', "%{$search}%");
                });
            }

            // Get total count for pagination
            $total = $query->count();

            // Get paginated results
            $students = $query->with([
                'class' => function ($query) use ($schoolYearId) {
                    $query->where('class_student.school_year_id', $schoolYearId);
                }
            ])
                ->orderBy('student_lName')
                ->orderBy('student_fName')
                ->orderBy('student_mName')
                ->orderBy('student_extName')
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();

            // Format students data
            $formattedStudents = $students->map(function ($student) use ($schoolYearId) {
                $classForYear = $student->class->firstWhere('pivot.school_year_id', $schoolYearId);

                return [
                    'id' => $student->id,
                    'student_lName' => $student->student_lName,
                    'student_fName' => $student->student_fName,
                    'student_mName' => $student->student_mName,
                    'student_extName' => $student->student_extName,
                    'student_lrn' => $student->student_lrn,
                    'student_photo' => $student->student_photo,
                    'gender' => $student->student_sex,
                    'class' => $classForYear ? [
                        'id' => $classForYear->id,
                        'formatted_grade_level' => $classForYear->formatted_grade_level,
                        'grade_level' => $classForYear->grade_level,
                        'section' => $classForYear->section,
                        'pivot' => [
                            'enrollment_status' => $classForYear->pivot->enrollment_status,
                            'enrollment_type' => $classForYear->pivot->enrollment_type
                        ]
                    ] : null
                ];
            });

            // Get available school years for dropdown
            $schoolYears = SchoolYear::orderBy('start_date', 'desc')
                ->pluck('school_year')
                ->unique()
                ->values()
                ->toArray();

            // Get classes for filter
            $classes = $teacherClasses->map(function ($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->formatted_grade_level . ' - ' . $class->section,
                    'grade_level' => $class->formatted_grade_level,
                    'section' => $class->section
                ];
            });

            return response()->json([
                'success' => true,
                'students' => $formattedStudents,
                'pagination' => [
                    'total' => $total,
                    'per_page' => $perPage,
                    'current_page' => (int)$page,
                    'last_page' => ceil($total / $perPage),
                    'from' => ($page - 1) * $perPage + 1,
                    'to' => min($page * $perPage, $total)
                ],
                'schoolYears' => $schoolYears,
                'classes' => $classes,
                'selectedYear' => $selectedYear,
                'selectedClass' => $selectedClass,
                'schoolYearId' => $schoolYearId,
                'currentYear' => $currentYear
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading dashboard students: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading students: ' . $e->getMessage(),
                'students' => [],
                'pagination' => [
                    'total' => 0,
                    'per_page' => 10,
                    'current_page' => 1,
                    'last_page' => 1,
                    'from' => 0,
                    'to' => 0
                ],
                'schoolYears' => [],
                'classes' => [],
                'selectedYear' => '',
                'selectedClass' => 'all',
                'schoolYearId' => null,
                'currentYear' => now()->year
            ], 500);
        }
    }

    // Add these methods to your HomeController

    /**
     * Get school year ID from school year text
     */
    public function getSchoolYearId(Request $request)
    {
        $schoolYearText = $request->get('school_year');
        $schoolYear = SchoolYear::where('school_year', $schoolYearText)->first();

        if (!$schoolYear) {
            return response()->json([
                'success' => false,
                'message' => 'School year not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'school_year_id' => $schoolYear->id,
            'school_year_text' => $schoolYear->school_year
        ]);
    }

    /**
     * Get enrollment data with filters (for teacher dashboard)
     */
    public function getTeacherEnrollmentData(Request $request)
    {
        $teacher = Auth::user();

        if ($teacher->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $schoolYearId = $request->get('school_year_id');
        $gradeLevel = $request->get('grade_level');
        $section = $request->get('section');

        // Get teacher's class IDs for this school year
        $teacherClassIds = $teacher->classes()
            ->wherePivot('school_year_id', $schoolYearId)
            ->pluck('classes.id')
            ->toArray();

        if (empty($teacherClassIds)) {
            return response()->json([
                'enrollment_data' => [0, 0, 0, 0, 0, 0, 0],
                'total_enrolled' => 0,
                'grade_labels' => ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']
            ]);
        }

        $query = ClassStudent::where('class_student.school_year_id', $schoolYearId)
            ->whereIn('class_student.enrollment_status', ['enrolled', 'archived'])
            ->whereIn('class_student.class_id', $teacherClassIds)
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

        // Format data for all grade levels
        $gradeLevels = ['kindergarten', 'grade1', 'grade2', 'grade3', 'grade4', 'grade5', 'grade6'];
        $formattedData = [];

        foreach ($gradeLevels as $grade) {
            $found = $enrollmentData->firstWhere('grade_level', $grade);
            $formattedData[] = $found ? $found->student_count : 0;
        }

        $totalEnrolled = array_sum($formattedData);

        return response()->json([
            'enrollment_data' => $formattedData,
            'total_enrolled' => $totalEnrolled,
            'grade_labels' => ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']
        ]);
    }

    /**
     * Get grade sections for teacher dashboard
     */
    public function getTeacherGradeSections(Request $request)
    {
        $teacher = Auth::user();

        if ($teacher->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $schoolYearId = $request->get('school_year_id');
        $gradeLevel = $request->get('grade_level');

        // Get teacher's classes for this school year and grade level
        $teacherClasses = $teacher->classes()
            ->wherePivot('school_year_id', $schoolYearId)
            ->where('grade_level', $gradeLevel)
            ->get();

        $sections = $teacherClasses->pluck('section')->unique()->sort()->values();

        return response()->json($sections);
    }

    /**
     * Get gender data with filters for teacher dashboard
     */
    public function getTeacherGenderDataFiltered(Request $request)
    {
        $teacher = Auth::user();

        if ($teacher->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $schoolYearId = $request->get('school_year_id');
        $gradeLevel = $request->get('grade_level');
        $section = $request->get('section');

        // Get teacher's class IDs for this school year
        $teacherClassIds = $teacher->classes()
            ->wherePivot('school_year_id', $schoolYearId)
            ->pluck('classes.id')
            ->toArray();

        if (empty($teacherClassIds)) {
            return response()->json([
                'total' => 0,
                'female_count' => 0,
                'male_count' => 0,
                'female_percentage' => 0,
                'male_percentage' => 0,
            ]);
        }

        $query = ClassStudent::where('school_year_id', $schoolYearId)
            ->whereIn('enrollment_status', ['enrolled', 'archived'])
            ->whereIn('class_id', $teacherClassIds)
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

        return response()->json([
            'total' => $total,
            'female_count' => $femaleCount,
            'male_count' => $maleCount,
            'female_percentage' => $total > 0 ? round(($femaleCount / $total) * 100, 1) : 0,
            'male_percentage' => $total > 0 ? round(($maleCount / $total) * 100, 1) : 0,
        ]);
    }

    /**
     * Get attendance overview for specific class
     */
    public function getAttendanceOverview(Request $request)
    {
        $teacher = Auth::user();

        if ($teacher->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $classId = $request->input('class_id');
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $currentSchoolYear = SchoolYear::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        // Initialize attendance stats
        $attendanceStats = [
            'present' => 0,
            'late' => 0,
            'absent' => 0,
            'excused' => 0,
            'total' => 0
        ];

        if ($currentSchoolYear) {
            // Get teacher's classes
            $advisoryClass = $teacher
                ->advisoryClasses()
                ->wherePivot('school_year_id', $currentSchoolYear->id)
                ->first();

            $subjectClasses = $teacher->subjectClasses()->wherePivot('school_year_id', $currentSchoolYear->id)->get();

            $allTeacherClasses = collect();

            if ($advisoryClass) {
                $allTeacherClasses->push($advisoryClass);
            }

            if ($subjectClasses->isNotEmpty()) {
                $allTeacherClasses = $allTeacherClasses->merge($subjectClasses);
            }

            $allTeacherClasses = $allTeacherClasses->unique('id');

            // Filter classes if specific class is selected
            if ($classId && $classId !== 'all') {
                $allTeacherClasses = $allTeacherClasses->where('id', $classId);
            }

            // Get today's schedules
            $todayDayName = Carbon::now()->format('l');

            foreach ($allTeacherClasses as $classItem) {
                // Get student count for this class
                $studentCount = $classItem
                    ->students()
                    ->wherePivot('school_year_id', $currentSchoolYear->id)
                    ->count();

                $attendanceStats['total'] += $studentCount;

                // Get today's schedules for this class
                $todaySchedules = $classItem
                    ->schedules()
                    ->where('day', $todayDayName)
                    ->where('school_year_id', $currentSchoolYear->id)
                    ->get();

                // Get attendance for each schedule
                foreach ($todaySchedules as $schedule) {
                    $attendances = $schedule
                        ->attendances()
                        ->whereDate('date', $date)
                        ->get();

                    foreach ($attendances as $attendance) {
                        switch ($attendance->status) {
                            case 'present':
                                $attendanceStats['present']++;
                                break;
                            case 'late':
                                $attendanceStats['late']++;
                                break;
                            case 'absent':
                                $attendanceStats['absent']++;
                                break;
                            case 'excused':
                                $attendanceStats['excused']++;
                                break;
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'attendance' => $attendanceStats,
            'date' => $date,
            'class_id' => $classId
        ]);
    }
}
