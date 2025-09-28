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
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('welcome');
        }

        // 🔹 Flash success message only once after login
        if (!session()->has('login_success_shown')) {
            session()->flash('success', 'Login successful!');
            session(['login_success_shown' => true]);
        }

        $user = Auth::user();
        $role = $user->role ?? 'parent';

        // Get recent announcements for dropdown
        $notifications = Announcement::orderBy('date_published', 'desc')
            ->take(99)
            ->get();

        if ($role == 'teacher') {
            $currentSchoolYear = SchoolYear::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            $class = null;

            if ($currentSchoolYear) {
                $class = $user->classes()
                    ->wherePivot('school_year_id', $currentSchoolYear->id)
                    ->wherePivot('role', 'adviser') // or subject_teacher, depending
                    ->first();
            }

            return view('teacher.index', compact('class', 'currentSchoolYear', 'notifications'));
        }

        if ($role == 'admin') {
            // 🔹 All school years for dropdown
            $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();

            // 🔹 Selected school year (via ?school_year_id= or current active)
            $selectedSchoolYear = request()->get('school_year_id')
                ? SchoolYear::find(request()->get('school_year_id'))
                : SchoolYear::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            // 🔹 Default empty collection
            $enrolleesByGrade = collect();

            if ($selectedSchoolYear) {
                $enrolleesByGrade = Classes::withCount([
                    'students as total_enrolled' => function ($q) use ($selectedSchoolYear) {
                        $q->where('class_student.school_year_id', $selectedSchoolYear->id)
                            ->where('class_student.enrollment_status', 'enrolled'); // adjust if status differs
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
                'enrolleesByGrade'
            ));
        }

        if ($role == 'parent') {
            return view('parent.index', compact('notifications'));
        }

        return redirect()->route('welcome');
    }
}
