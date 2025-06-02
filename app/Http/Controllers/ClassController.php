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
            $section = 'A'; // default
            return redirect()->route('all.classes', ['section' => $section]);
        }

        $sections = Classes::distinct()->pluck('section');
        $gradeLevels = Classes::where('section', $section)->distinct()->pluck('grade_level');

        // Get all classes for the section
        $classes = Classes::where('section', $section)->get();

        // For each class, fetch teachers using class_id
        foreach ($classes as $class) {
            $class->teachers = User::where('class_id', $class->id)->get();
        }

        return view('admin.classes.allClasses', compact('sections', 'gradeLevels', 'section', 'classes'));
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

    public function masterList($grade_level, $section)
    {
        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        // Fetch students in the class
        $students = Student::where('class_id', $class->id)->get();
        // Fetch teachers in the class
        $teachers = User::where('class_id', $class->id)->get();

        return view('admin.classes.masterList', compact('class', 'students', 'teachers'));
    }
}
