<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function allClasses(Request $request)
    {
        $section = $request->input('section');

        if (empty($section)) {
            $section = 'A'; // only the letter
            return redirect()->route('all.classes', ['section' => $section]);
        }

        $sections = Classes::distinct()->pluck('section');
        $gradeLevels = Classes::where('section', $section)->distinct()->pluck('grade_level');

        return view('admin.classes.allClasses', compact('sections', 'gradeLevels', 'section'));
    }



    public function showClass($grade_level, $section)
    {
        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        // Example stats
        $studentCount = $class->students()->count(); // assumes a `students()` relation
        // $presentToday = $class->students()->whereHas('attendances', function ($query) {
        //     $query->whereDate('date', now())->where('status', 'present');
        // })->count();

        return view('admin.classes.showClass', compact('class', 'studentCount'));
    }
}
