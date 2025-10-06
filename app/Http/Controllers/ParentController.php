<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Unauthorized');
        }

        return view('parent.index');
    }

    public function children()
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Unauthorized');
        }

        $children = $user->children()->with(['classStudents.class', 'schoolYears'])->get();

        return view('parent.children.index', compact('children'));
    }

    public function schoolFees()
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Unauthorized');
        }

        $children = $user->children()->with(['classStudents.class', 'schoolYears'])->get();

        return view('parent.school_fees.index', compact('children'));
    }

    public function smsLogs()
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Unauthorized');
        }

        $children = $user->children()->with(['classStudents.class', 'schoolYears'])->get();

        // Collect SMS logs from all children
        $smsLogs = $children->flatMap(function ($child) {
            return $child->smsLogs; // Assuming 'smsLogs' is the relationship defined in the Student model
        })->sortByDesc('created_at'); // Sort by most recent

        return view('parent.sms_logs.index', compact('smsLogs'));
    }

    public function announcements()
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Unauthorized');
        }

        $children = $user->children()->with(['classStudents.class', 'schoolYears'])->get();

        return view('parent.announcements.index', compact('children'));
    }
}
