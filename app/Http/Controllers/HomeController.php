<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\SchoolYear;
use App\Models\Classes;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->role;

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

                return view('teacher.index', compact('class', 'currentSchoolYear'));
            }

            // Other roles
            if ($role == 'admin') {
                return view('admin.index');
            }

            if ($role == 'parent') {
                return view('parent.index');
            }
        }

        return redirect()->route('login');
    }
}
