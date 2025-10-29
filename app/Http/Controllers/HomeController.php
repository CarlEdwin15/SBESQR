<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\SchoolYear;
use App\Models\Classes;
use App\Models\Announcement;

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

        // Priority 1: Check URL parameter (direct from push notification)
        if ($request->has('announcement_id')) {
            $announcementId = $request->get('announcement_id');
        }
        // Priority 2: Check session (from login redirect)
        elseif (session()->has('login_redirect_announcement')) {
            $announcementId = session('login_redirect_announcement');
            session()->forget('login_redirect_announcement');
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
}
