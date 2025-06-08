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

        // For each class, fetch teachers with pivot role 'adviser' or 'both'
        foreach ($classes as $class) {
            $class->adviser = $class->teachers()->wherePivotIn('role', ['adviser', 'both'])->first();
        }


        return view('admin.classes.allClasses', compact('sections', 'gradeLevels', 'section', 'classes'));
    }


    public function showClass($grade_level, $section)
    {
        $class = $this->getClass($grade_level, $section);

        $studentCount = $class->students()->count();
        // $presentToday = $class->students()->whereHas('attendances', function ($query) {
        //     $query->whereDate('date', now())->where('status', 'present');
        // })->count();

        // For each class, fetch teachers with pivot role 'adviser' or 'both'
        $class->adviser = $class->teachers()->wherePivotIn('role', ['adviser', 'both'])->first();

        return view('admin.classes.showClass', compact('class', 'studentCount'));
    }

    public function masterList($grade_level, $section)
    {
        $class = $this->getClass($grade_level, $section);

        // Fetch students in the class
        $students = Student::where('class_id', $class->id)->get();
        // For each class, fetch teachers with pivot role 'adviser' or 'both'
        $class->adviser = $class->teachers()->wherePivotIn('role', ['adviser', 'both'])->first();

        return view('admin.classes.masterList.index', compact('class', 'students'));
    }

    // Helper function to get class by grade level and section
    private function getClass($grade_level, $section)
    {
        return Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();
    }

}
